<?php
// app/Http/Controllers/KanbanController.php
namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Bidang;
use App\Models\User;
use App\Models\Subtask;
use App\Models\TaskComment;
use App\Models\TaskAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class KanbanController extends Controller
{
    /**
     * Display kanban board page
     */
    public function index()
    {
        $currentUser = auth()->user();
        return view('kanban.index2', compact('currentUser'));
    }

    /**
     * Get all tasks with relations for kanban board
     */
    public function getTasks(Request $request)
    {
        try {
            // Update overdue tasks first
            $this->updateOverdueTasks();

            $query = Task::with([
                'bidang:id,name,slug,color',
                'assignedUsers:id,name,avatar,color',
                'subtasks' => function ($q) {
                    $q->orderBy('order');
                },
                'subtasks.creator:id,name',
                'attachments.uploader:id,name,avatar',
                'comments' => function ($q) {
                    $q->latest();
                },
                'comments.author:id,name,avatar,color',
                'comments.attachments'
            ])->orderBy('order');

            // Apply filters
            if ($request->filled('bidang')) {
                $query->whereHas('bidang', function ($q) use ($request) {
                    $q->where('slug', $request->bidang);
                });
            }

            if ($request->filled('priority')) {
                $query->where('priority', $request->priority);
            }

            if ($request->filled('user')) {
                $query->whereHas('assignedUsers', function ($q) use ($request) {
                    $q->where('users.id', $request->user);
                });
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            }

            $tasks = $query->get();

            // Transform data for frontend
            $tasksFormatted = $tasks->map(function ($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'deadline' => $task->deadline->format('Y-m-d'),
                    'priority' => $task->priority,
                    'column' => $task->column,
                    'bidang' => $task->bidang ? $task->bidang->slug : null,
                    'bidang_data' => $task->bidang,
                    'assignedUsers' => $task->assignedUsers->pluck('id')->toArray(),
                    'assigned_users' => $task->assignedUsers,
                    'createdAt' => $task->created_at ? $task->created_at->toISOString() : now()->toISOString(),
                    'completedAt' => $task->completed_at ? $task->completed_at->toISOString() : null,
                    'pendingReason' => $task->pending_reason,
                    'subtasks' => $task->subtasks->map(function ($st) {
                        return [
                            'id' => $st->id,
                            'text' => $st->text,
                            'completed' => (bool)$st->completed,
                            'createdBy' => (string)$st->created_by,  // ID sebagai string
                            'creatorName' => $st->creator ? $st->creator->name : 'Unknown'  // Nama untuk backup
                        ];
                    }),
                    'attachments' => $task->attachments->map(function ($att) {
                        return [
                            'id' => $att->id,
                            'name' => $att->name,
                            'size' => $this->formatFileSize($att->file_size),
                            'type' => $att->mime_type,
                            'uploadedBy' => $att->uploader ? $att->uploader->name : 'Unknown',
                            'uploadedAt' => $att->created_at->toISOString(),
                            'url' => Storage::url($att->file_path)
                        ];
                    }),
                    'comments' => $task->comments->map(function ($comment) {
                        return [
                            'id' => $comment->id,
                            'text' => $comment->text,
                            'author' => $comment->author ? $comment->author->name : 'Unknown',
                            'authorData' => $comment->author,
                            'createdAt' => $comment->created_at->toISOString(),
                            'attachments' => $comment->attachments->map(function ($att) {
                                return [
                                    'id' => $att->id,
                                    'name' => $att->name,
                                    'size' => $this->formatFileSize($att->file_size),
                                    'type' => $att->mime_type,
                                    'url' => Storage::url($att->file_path)
                                ];
                            })
                        ];
                    }),
                    // Total attachments (task attachments + all comment attachments)
                    'totalAttachments' => $task->attachments->count() +
                        $task->comments->sum(function ($comment) {
                            return $comment->attachments->count();
                        })
                ];
            });

            return response()->json([
                'success' => true,
                'tasks' => $tasksFormatted,
                'stats' => $this->getStats()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat tasks: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store new task
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'required|date|after_or_equal:today',
            'priority' => 'required|in:high,medium,low',
            'bidang_id' => 'required|exists:bidangs,id',
            'assigned_users' => 'required|array|min:1',
            'assigned_users.*' => 'exists:users,id',
            'subtasks' => 'nullable|array',
            'subtasks.*.text' => 'required|string',
            'subtasks.*.completed' => 'sometimes|boolean',
            'subtasks.*.created_by' => 'nullable|exists:users,id'  // TAMBAH INI
        ]);

        DB::beginTransaction();
        try {
            // Get max order for product-backlog column
            $maxOrder = Task::where('column', 'product-backlog')->max('order') ?? 0;

            $task = Task::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'deadline' => $validated['deadline'],
                'priority' => $validated['priority'],
                'column' => 'product-backlog',
                'bidang_id' => $validated['bidang_id'],
                'created_by' => auth()->id(),
                'order' => $maxOrder + 1
            ]);

            // Assign users
            $task->assignedUsers()->attach($validated['assigned_users']);

            // Create subtasks
            if (!empty($validated['subtasks'])) {
                foreach ($validated['subtasks'] as $index => $subtaskData) {
                    $createdBy = isset($subtaskData['created_by'])
                        ? $subtaskData['created_by']
                        : auth()->id();

                    $task->subtasks()->create([
                        'text' => $subtaskData['text'],
                        'completed' => $subtaskData['completed'] ?? false,
                        'created_by' => $createdBy,
                        'order' => $index
                    ]);
                }
            }

            DB::commit();

            $task->load(['bidang', 'assignedUsers', 'subtasks']);

            return response()->json([
                'success' => true,
                'message' => 'Task berhasil ditambahkan',
                'task' => $task
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan task: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update task
     */
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'deadline' => 'sometimes|required|date|after_or_equal:today',
            'priority' => 'sometimes|required|in:high,medium,low',
            'bidang_id' => 'sometimes|required|exists:bidangs,id',
            'assigned_users' => 'sometimes|array',
            'assigned_users.*' => 'exists:users,id',
            'subtasks' => 'sometimes|array',
            'subtasks.*.text' => 'required|string',
            'subtasks.*.completed' => 'sometimes|boolean',
            'subtasks.*.created_by' => 'sometimes|exists:users,id'
        ]);

        DB::beginTransaction();
        try {
            // Update task basic info
            $task->update(array_filter([
                'title' => $validated['title'] ?? $task->title,
                'description' => $validated['description'] ?? $task->description,
                'deadline' => $validated['deadline'] ?? $task->deadline,
                'priority' => $validated['priority'] ?? $task->priority,
                'bidang_id' => $validated['bidang_id'] ?? $task->bidang_id,
            ]));

            // Update assigned users if provided
            if (isset($validated['assigned_users'])) {
                $task->assignedUsers()->sync($validated['assigned_users']);
            }

            // Update subtasks if provided
            if (isset($validated['subtasks'])) {
                // Delete existing subtasks
                $task->subtasks()->delete();

                // Create new subtasks with preserved creator
                foreach ($validated['subtasks'] as $index => $subtaskData) {
                    // Use provided created_by if exists, otherwise current user
                    $createdBy = isset($subtaskData['created_by']) && $subtaskData['created_by']
                        ? $subtaskData['created_by']
                        : auth()->id();

                    $task->subtasks()->create([
                        'text' => $subtaskData['text'],
                        'completed' => $subtaskData['completed'] ?? false,
                        'created_by' => $createdBy,
                        'order' => $index
                    ]);
                }
            }

            DB::commit();

            $task->load(['bidang', 'assignedUsers', 'subtasks']);

            return response()->json([
                'success' => true,
                'message' => 'Task berhasil diupdate',
                'task' => $task
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate task: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Move task to different column
     */
    public function move(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $validated = $request->validate([
            'column' => 'required|in:product-backlog,in-progress,pending,done,overdue',
            'pending_reason' => 'required_if:column,pending|nullable|string',
            'new_deadline' => 'nullable|date|after_or_equal:today'
        ]);

        DB::beginTransaction();
        try {
            $updateData = ['column' => $validated['column']];

            if ($validated['column'] === 'done') {
                $updateData['completed_at'] = now();
                $updateData['pending_reason'] = null;
            } elseif ($validated['column'] === 'pending') {
                $updateData['pending_reason'] = $validated['pending_reason'];
                if (isset($validated['new_deadline'])) {
                    $updateData['deadline'] = $validated['new_deadline'];
                }
            } else {
                $updateData['pending_reason'] = null;
                $updateData['completed_at'] = null;
                if (isset($validated['new_deadline'])) {
                    $updateData['deadline'] = $validated['new_deadline'];
                }
            }

            $task->update($updateData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Task berhasil dipindahkan',
                'task' => $task
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memindahkan task: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete task
     */
    public function destroy($id)
    {
        try {
            $task = Task::findOrFail($id);

            // Delete associated files
            foreach ($task->attachments as $attachment) {
                Storage::delete($attachment->file_path);
            }

            foreach ($task->comments as $comment) {
                foreach ($comment->attachments as $attachment) {
                    Storage::delete($attachment->file_path);
                }
            }

            $task->delete();

            return response()->json([
                'success' => true,
                'message' => 'Task berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus task: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle subtask completion
     */
    public function toggleSubtask($taskId, $subtaskId)
    {
        try {
            $task = Task::findOrFail($taskId);
            $subtask = $task->subtasks()->findOrFail($subtaskId);

            $subtask->update([
                'completed' => !$subtask->completed
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Subtask berhasil diupdate',
                'subtask' => $subtask
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate subtask: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add comment to task
     */
    public function addComment(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $validated = $request->validate([
            'text' => 'required|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240' // 10MB max
        ]);

        DB::beginTransaction();
        try {
            $comment = $task->comments()->create([
                'text' => $validated['text'],
                'author_id' => auth()->id()
            ]);

            // Handle attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('comment-attachments', 'public');

                    $comment->attachments()->create([
                        'name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'file_size' => $file->getSize(),
                        'mime_type' => $file->getMimeType()
                    ]);
                }
            }

            DB::commit();

            $comment->load(['author', 'attachments']);

            return response()->json([
                'success' => true,
                'message' => 'Komentar berhasil ditambahkan',
                'comment' => $comment
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan komentar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload task attachment
     */
    public function uploadAttachment(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $validated = $request->validate([
            'file' => 'required|file|max:20480' // 20MB max
        ]);

        try {
            $file = $request->file('file');
            $path = $file->store('task-attachments', 'public');

            $attachment = $task->attachments()->create([
                'name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'uploaded_by' => auth()->id()
            ]);

            $attachment->load('uploader');

            return response()->json([
                'success' => true,
                'message' => 'File berhasil diupload',
                'attachment' => $attachment
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload file: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all users for assignment dropdown
     */
    public function getUsers()
    {
        $users = User::select('id', 'name', 'avatar', 'color', 'role')->get();
        return response()->json([
            'success' => true,
            'users' => $users
        ]);
    }

    /**
     * Get all bidangs
     */
    public function getBidangs()
    {
        $bidangs = Bidang::select('id', 'name', 'slug', 'color', 'description')->get();
        return response()->json([
            'success' => true,
            'bidangs' => $bidangs
        ]);
    }

    /**
     * Update overdue tasks (private helper)
     */
    private function updateOverdueTasks()
    {
        Task::where('deadline', '<', Carbon::today())
            ->whereNotIn('column', ['done', 'overdue'])
            ->update([
                'column' => 'overdue',
                'pending_reason' => null
            ]);
    }

    /**
     * Get kanban statistics (private helper)
     */
    private function getStats()
    {
        return [
            'overdue_count' => Task::where('column', 'overdue')->count(),
            'total_tasks' => Task::count(),
            'completed_tasks' => Task::where('column', 'done')->count(),
            'in_progress_tasks' => Task::where('column', 'in-progress')->count(),
            'pending_tasks' => Task::where('column', 'pending')->count(),
            'backlog_tasks' => Task::where('column', 'product-backlog')->count(),
        ];
    }

    /**
     * Format file size helper
     */
    private function formatFileSize($bytes)
    {
        if ($bytes === 0) return '0 B';
        $k = 1024;
        $sizes = ['B', 'KB', 'MB', 'GB'];
        $i = floor(log($bytes) / log($k));
        return round($bytes / pow($k, $i), 1) . ' ' . $sizes[$i];
    }
}
