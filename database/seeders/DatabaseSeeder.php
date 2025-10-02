<?php
// database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Bidang;
use App\Models\Task;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data (optional)
        // Task::truncate();
        // Bidang::truncate();
        // User::truncate();

        // Create Users
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'avatar' => 'AU',
                'color' => '#4299e1',
                'role' => 'admin'
            ],
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => Hash::make('password'),
                'avatar' => 'JD',
                'color' => '#48bb78',
                'role' => 'user'
            ],
            [
                'name' => 'Alice Smith',
                'email' => 'alice@example.com',
                'password' => Hash::make('password'),
                'avatar' => 'AS',
                'color' => '#ed8936',
                'role' => 'user'
            ],
            [
                'name' => 'Bob Johnson',
                'email' => 'bob@example.com',
                'password' => Hash::make('password'),
                'avatar' => 'BJ',
                'color' => '#e53e3e',
                'role' => 'manager'
            ],
        ];

        $createdUsers = [];
        foreach ($users as $userData) {
            $createdUsers[] = User::create($userData);
        }

        // Create Bidangs
        $bidangs = [
            [
                'name' => 'Aptika',
                'slug' => 'aptika',
                'color' => '#4299e1',
                'description' => 'Aplikasi dan Informatika'
            ],
            [
                'name' => 'Sarkom',
                'slug' => 'sarkom',
                'color' => '#48bb78',
                'description' => 'Sarana Komunikasi'
            ],
            [
                'name' => 'Sekretariat',
                'slug' => 'sekretariat',
                'color' => '#ed8936',
                'description' => 'Sekretariat dan Administrasi'
            ],
        ];

        $createdBidangs = [];
        foreach ($bidangs as $bidangData) {
            $createdBidangs[] = Bidang::create($bidangData);
        }

        // Create Sample Tasks
        $sampleTasks = [
            [
                'title' => 'Implementasi Sistem Login',
                'description' => 'Membuat sistem autentikasi pengguna dengan JWT token dan validasi form',
                'deadline' => Carbon::now()->addDays(10),
                'priority' => 'high',
                'column' => 'product-backlog',
                'bidang_id' => $createdBidangs[0]->id, // Aptika
                'created_by' => $createdUsers[0]->id,
                'assigned_users' => [$createdUsers[0]->id, $createdUsers[1]->id],
                'subtasks' => [
                    ['text' => 'Setup JWT library', 'completed' => false],
                    ['text' => 'Buat login form', 'completed' => true],
                    ['text' => 'Validasi input', 'completed' => false],
                ],
            ],
            [
                'title' => 'Design UI Dashboard',
                'description' => 'Merancang interface dashboard utama dengan komponen yang responsif',
                'deadline' => Carbon::now()->addDays(20),
                'priority' => 'medium',
                'column' => 'in-progress',
                'bidang_id' => $createdBidangs[1]->id, // Sarkom
                'created_by' => $createdUsers[1]->id,
                'assigned_users' => [$createdUsers[1]->id, $createdUsers[2]->id],
                'subtasks' => [
                    ['text' => 'Wireframe', 'completed' => true],
                    ['text' => 'Mockup design', 'completed' => false],
                ],
            ],
            [
                'title' => 'Fix Bug Authentication',
                'description' => 'Mengatasi masalah token expiry yang tidak ter-handle dengan baik',
                'deadline' => Carbon::now()->addDays(5),
                'priority' => 'high',
                'column' => 'in-progress',
                'bidang_id' => $createdBidangs[0]->id, // Aptika
                'created_by' => $createdUsers[0]->id,
                'assigned_users' => [$createdUsers[0]->id],
                'subtasks' => [
                    ['text' => 'Reproduce bug', 'completed' => true],
                    ['text' => 'Fix implementation', 'completed' => false],
                    ['text' => 'Testing', 'completed' => false],
                ],
            ],
            [
                'title' => 'Database Optimization',
                'description' => 'Optimasi query database untuk meningkatkan performa aplikasi',
                'deadline' => Carbon::now()->addDays(15),
                'priority' => 'medium',
                'column' => 'product-backlog',
                'bidang_id' => $createdBidangs[2]->id, // Sekretariat
                'created_by' => $createdUsers[2]->id,
                'assigned_users' => [$createdUsers[2]->id, $createdUsers[3]->id],
                'subtasks' => [
                    ['text' => 'Analyze slow queries', 'completed' => false],
                    ['text' => 'Add indexes', 'completed' => false],
                ],
            ],
            [
                'title' => 'Update Documentation',
                'description' => 'Memperbarui dokumentasi API dan user guide',
                'deadline' => Carbon::now()->addDays(30),
                'priority' => 'low',
                'column' => 'product-backlog',
                'bidang_id' => $createdBidangs[1]->id, // Sarkom
                'created_by' => $createdUsers[1]->id,
                'assigned_users' => [$createdUsers[1]->id],
                'subtasks' => [],
            ],
            [
                'title' => 'Completed Task Example',
                'description' => 'Contoh task yang sudah selesai',
                'deadline' => Carbon::now()->subDays(2),
                'priority' => 'medium',
                'column' => 'done',
                'bidang_id' => $createdBidangs[0]->id,
                'created_by' => $createdUsers[0]->id,
                'assigned_users' => [$createdUsers[0]->id],
                'subtasks' => [
                    ['text' => 'Task completed', 'completed' => true],
                ],
                'completed_at' => Carbon::now()->subDays(1),
            ],
            [
                'title' => 'Overdue Task Example',
                'description' => 'Contoh task yang overdue',
                'deadline' => Carbon::now()->subDays(5),
                'priority' => 'high',
                'column' => 'overdue',
                'bidang_id' => $createdBidangs[2]->id,
                'created_by' => $createdUsers[2]->id,
                'assigned_users' => [$createdUsers[2]->id, $createdUsers[3]->id],
                'subtasks' => [
                    ['text' => 'Pending subtask', 'completed' => false],
                ],
            ],
        ];

        foreach ($sampleTasks as $taskData) {
            $assignedUsers = $taskData['assigned_users'];
            $subtasks = $taskData['subtasks'];
            unset($taskData['assigned_users'], $taskData['subtasks']);

            // Create task
            $task = Task::create($taskData);

            // Assign users
            $task->assignedUsers()->attach($assignedUsers);

            // Create subtasks
            foreach ($subtasks as $index => $subtaskData) {
                $task->subtasks()->create([
                    'text' => $subtaskData['text'],
                    'completed' => $subtaskData['completed'],
                    'created_by' => $task->created_by,
                    'order' => $index
                ]);
            }
        }

        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('');
        $this->command->info('Test Users Created:');
        $this->command->info('Email: admin@example.com | Password: password');
        $this->command->info('Email: john@example.com | Password: password');
        $this->command->info('Email: alice@example.com | Password: password');
        $this->command->info('Email: bob@example.com | Password: password');
        $this->command->info('');
        $this->command->info('Bidangs Created: ' . count($createdBidangs));
        $this->command->info('Tasks Created: ' . count($sampleTasks));
    }
}
