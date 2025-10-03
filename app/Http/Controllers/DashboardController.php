<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Bidang;
use App\Models\Subtask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil semua tasks dengan relasi
        $tasks = Task::with(['bidang', 'subtasks', 'assignedUsers'])
            ->orderBy('deadline', 'asc')
            ->get()
            ->map(function ($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'deadline' => $task->deadline,
                    'priority' => $task->priority,
                    'column' => $task->column,
                    'bidang' => [
                        'id' => $task->bidang->id,
                        'name' => $task->bidang->name,
                        'slug' => $task->bidang->slug,
                        'color' => $task->bidang->color,
                    ],
                    'subtasks' => $task->subtasks->map(function ($subtask) {
                        return [
                            'id' => $subtask->id,
                            'text' => $subtask->text,
                            'completed' => $subtask->completed,
                        ];
                    }),
                    'created_at' => $task->created_at,
                    'completed_at' => $task->completed_at,
                ];
            });

        // Hitung metrics
        $metrics = $this->calculateMetrics($tasks);

        return view('dashboard.dashboard', compact('tasks', 'metrics'));
    }

    private function calculateMetrics($tasks)
    {
        $total = $tasks->count();

        $statusCounts = [
            'product-backlog' => 0,
            'in-progress' => 0,
            'pending' => 0,
            'done' => 0,
            'overdue' => 0
        ];

        $priorityCounts = [
            'high' => 0,
            'medium' => 0,
            'low' => 0
        ];

        $totalSubtasks = 0;
        $completedSubtasks = 0;
        $today = now()->startOfDay();

        foreach ($tasks as $task) {
            // Count by status
            $statusCounts[$task['column']]++;

            // Count by priority
            $priorityCounts[$task['priority']]++;

            // Count subtasks
            if (isset($task['subtasks'])) {
                $totalSubtasks += count($task['subtasks']);
                $completedSubtasks += collect($task['subtasks'])->where('completed', true)->count();
            }

            // Check if overdue
            $deadline = \Carbon\Carbon::parse($task['deadline'])->startOfDay();
            if ($deadline->lt($today) && $task['column'] !== 'done' && $task['column'] !== 'overdue') {
                // Update task status to overdue in database
                Task::where('id', $task['id'])->update(['column' => 'overdue']);
                $statusCounts['overdue']++;
                $statusCounts[$task['column']]--;
            }
        }

        $completionRate = $total > 0 ? round(($statusCounts['done'] / $total) * 100) : 0;
        $subtaskCompletionRate = $totalSubtasks > 0 ? round(($completedSubtasks / $totalSubtasks) * 100) : 0;

        return [
            'total' => $total,
            'statusCounts' => $statusCounts,
            'priorityCounts' => $priorityCounts,
            'completionRate' => $completionRate,
            'subtaskCompletionRate' => $subtaskCompletionRate,
            'totalSubtasks' => $totalSubtasks,
            'completedSubtasks' => $completedSubtasks,
        ];
    }

    // API endpoint untuk data charts (optional)
    public function getChartData()
    {
        $last7Days = [];
        $completedTasks = [];
        $newTasks = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $last7Days[] = $date->format('d M');

            // Hitung task completed per hari
            $completedTasks[] = Task::whereDate('completed_at', $date->format('Y-m-d'))->count();

            // Hitung task baru per hari
            $newTasks[] = Task::whereDate('created_at', $date->format('Y-m-d'))->count();
        }

        return response()->json([
            'labels' => $last7Days,
            'completedTasks' => $completedTasks,
            'newTasks' => $newTasks
        ]);
    }
}
