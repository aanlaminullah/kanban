<?php
// database/seeders/TaskSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\User;
use App\Models\Bidang;
use Carbon\Carbon;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing tasks (optional)
        // Task::truncate();

        // Get users and bidangs for reference
        $users = User::all();
        $bidangs = Bidang::all();

        // Sample Tasks Data - 15 tasks dengan tema sesuai bidang
        $sampleTasks = [
            // ==================== APTIKA (Pengembangan Aplikasi) ====================
            [
                'title' => 'Pengembangan Sistem E-Office',
                'description' => 'Membangun aplikasi e-office untuk digitalisasi surat menyurat internal',
                'deadline' => Carbon::now()->addDays(20),
                'priority' => 'high',
                'column' => 'in-progress',
                'bidang_id' => $bidangs->where('slug', 'aptika')->first()->id,
                'created_by' => $users->where('email', 'admin@example.com')->first()->id,
                'assigned_users' => [
                    $users->where('email', 'admin@example.com')->first()->id,
                    $users->where('email', 'john@example.com')->first()->id
                ],
                'subtasks' => [
                    ['text' => 'Analisis kebutuhan user', 'completed' => true],
                    ['text' => 'Design database structure', 'completed' => true],
                    ['text' => 'Develop modul surat masuk', 'completed' => false],
                    ['text' => 'Develop modul surat keluar', 'completed' => false],
                    ['text' => 'Implementasi workflow approval', 'completed' => false],
                    ['text' => 'Testing sistem', 'completed' => false],
                ],
            ],
            [
                'title' => 'Maintenance Aplikasi SIMPEG',
                'description' => 'Perbaikan bug dan update fitur pada Sistem Manajemen Kepegawaian',
                'deadline' => Carbon::now()->addDays(7),
                'priority' => 'high',
                'column' => 'in-progress',
                'bidang_id' => $bidangs->where('slug', 'aptika')->first()->id,
                'created_by' => $users->where('email', 'john@example.com')->first()->id,
                'assigned_users' => [
                    $users->where('email', 'john@example.com')->first()->id,
                    $users->where('email', 'alice@example.com')->first()->id
                ],
                'subtasks' => [
                    ['text' => 'Identifikasi bug yang dilaporkan', 'completed' => true],
                    ['text' => 'Perbaikan error laporan absensi', 'completed' => false],
                    ['text' => 'Update modul cuti tahunan', 'completed' => false],
                    ['text' => 'Optimasi query database', 'completed' => false],
                ],
            ],
            [
                'title' => 'Pengembangan Mobile App Layanan Publik',
                'description' => 'Membuat aplikasi mobile untuk layanan informasi publik',
                'deadline' => Carbon::now()->addDays(45),
                'priority' => 'medium',
                'column' => 'product-backlog',
                'bidang_id' => $bidangs->where('slug', 'aptika')->first()->id,
                'created_by' => $users->where('email', 'admin@example.com')->first()->id,
                'assigned_users' => [
                    $users->where('email', 'admin@example.com')->first()->id,
                    $users->where('email', 'alice@example.com')->first()->id
                ],
                'subtasks' => [
                    ['text' => 'UI/UX design untuk mobile', 'completed' => false],
                    ['text' => 'Develop frontend React Native', 'completed' => false],
                    ['text' => 'Integrasi API backend', 'completed' => false],
                    ['text' => 'Testing di berbagai device', 'completed' => false],
                ],
            ],
            [
                'title' => 'Backup dan Recovery System',
                'description' => 'Implementasi sistem backup otomatis untuk semua aplikasi',
                'deadline' => Carbon::now()->addDays(10),
                'priority' => 'high',
                'column' => 'product-backlog',
                'bidang_id' => $bidangs->where('slug', 'aptika')->first()->id,
                'created_by' => $users->where('email', 'john@example.com')->first()->id,
                'assigned_users' => [
                    $users->where('email', 'john@example.com')->first()->id
                ],
                'subtasks' => [
                    ['text' => 'Setup backup schedule', 'completed' => false],
                    ['text' => 'Konfigurasi cloud storage', 'completed' => false],
                    ['text' => 'Test recovery procedure', 'completed' => false],
                    ['text' => 'Dokumentasi proses backup', 'completed' => false],
                ],
            ],
            [
                'title' => 'Update Framework Laravel',
                'description' => 'Upgrade versi Laravel untuk keamanan dan performa yang lebih baik',
                'deadline' => Carbon::now()->addDays(15),
                'priority' => 'medium',
                'column' => 'product-backlog',
                'bidang_id' => $bidangs->where('slug', 'aptika')->first()->id,
                'created_by' => $users->where('email', 'admin@example.com')->first()->id,
                'assigned_users' => [
                    $users->where('email', 'admin@example.com')->first()->id
                ],
                'subtasks' => [
                    ['text' => 'Backup database dan code', 'completed' => false],
                    ['text' => 'Update composer dependencies', 'completed' => false],
                    ['text' => 'Test semua fungsi aplikasi', 'completed' => false],
                    ['text' => 'Fix breaking changes', 'completed' => false],
                ],
            ],

            // ==================== SARKOM (Media dan Penyebarluasan Informasi) ====================
            [
                'title' => 'Pengelolaan Media Sosial Dinas',
                'description' => 'Mengelola konten dan engagement di media sosial resmi dinas',
                'deadline' => Carbon::now()->addDays(5),
                'priority' => 'medium',
                'column' => 'in-progress',
                'bidang_id' => $bidangs->where('slug', 'sarkom')->first()->id,
                'created_by' => $users->where('email', 'alice@example.com')->first()->id,
                'assigned_users' => [
                    $users->where('email', 'alice@example.com')->first()->id,
                    $users->where('email', 'bob@example.com')->first()->id
                ],
                'subtasks' => [
                    ['text' => 'Buat content calendar mingguan', 'completed' => true],
                    ['text' => 'Desain grafis untuk Instagram', 'completed' => false],
                    ['text' => 'Jadwalkan posting Facebook', 'completed' => false],
                    ['text' => 'Monitor engagement dan comments', 'completed' => false],
                ],
            ],
            [
                'title' => 'Produksi Video Profil Dinas',
                'description' => 'Membuat video profil dinas untuk promosi dan informasi publik',
                'deadline' => Carbon::now()->addDays(30),
                'priority' => 'medium',
                'column' => 'product-backlog',
                'bidang_id' => $bidangs->where('slug', 'sarkom')->first()->id,
                'created_by' => $users->where('email', 'bob@example.com')->first()->id,
                'assigned_users' => [
                    $users->where('email', 'bob@example.com')->first()->id
                ],
                'subtasks' => [
                    ['text' => 'Script writing dan storyboard', 'completed' => false],
                    ['text' => 'Shooting video di lokasi', 'completed' => false],
                    ['text' => 'Video editing dan color grading', 'completed' => false],
                    ['text' => 'Add subtitle dan musik', 'completed' => false],
                ],
            ],
            [
                'title' => 'Press Release Kegiatan Dinas',
                'description' => 'Membuat dan menyebarluaskan press release kegiatan terbaru',
                'deadline' => Carbon::now()->addDays(2),
                'priority' => 'high',
                'column' => 'in-progress',
                'bidang_id' => $bidangs->where('slug', 'sarkom')->first()->id,
                'created_by' => $users->where('email', 'alice@example.com')->first()->id,
                'assigned_users' => [
                    $users->where('email', 'alice@example.com')->first()->id
                ],
                'subtasks' => [
                    ['text' => 'Kumpulkan data kegiatan', 'completed' => true],
                    ['text' => 'Tulis draft press release', 'completed' => false],
                    ['text' => 'Review dan revisi', 'completed' => false],
                    ['text' => 'Distribusi ke media', 'completed' => false],
                ],
            ],
            [
                'title' => 'Desain Banner Event Teknologi',
                'description' => 'Membuat desain banner untuk event teknologi yang akan datang',
                'deadline' => Carbon::now()->addDays(3),
                'priority' => 'medium',
                'column' => 'product-backlog',
                'bidang_id' => $bidangs->where('slug', 'sarkom')->first()->id,
                'created_by' => $users->where('email', 'bob@example.com')->first()->id,
                'assigned_users' => [
                    $users->where('email', 'bob@example.com')->first()->id
                ],
                'subtasks' => [
                    ['text' => 'Concept design 3 options', 'completed' => false],
                    ['text' => 'Presentasi ke pimpinan', 'completed' => false],
                    ['text' => 'Revisi berdasarkan feedback', 'completed' => false],
                    ['text' => 'Finalisasi file print-ready', 'completed' => false],
                ],
            ],
            [
                'title' => 'Update Website Berita Dinas',
                'description' => 'Memperbarui konten website dengan berita dan informasi terbaru',
                'deadline' => Carbon::now()->addDays(1),
                'priority' => 'high',
                'column' => 'in-progress',
                'bidang_id' => $bidangs->where('slug', 'sarkom')->first()->id,
                'created_by' => $users->where('email', 'alice@example.com')->first()->id,
                'assigned_users' => [
                    $users->where('email', 'alice@example.com')->first()->id
                ],
                'subtasks' => [
                    ['text' => 'Tulis artikel kegiatan terbaru', 'completed' => true],
                    ['text' => 'Upload foto pendukung', 'completed' => false],
                    ['text' => 'Optimasi SEO artikel', 'completed' => false],
                    ['text' => 'Publikasi dan share link', 'completed' => false],
                ],
            ],

            // ==================== SEKRETARIAT (Pekerjaan Kesektariatan) ====================
            [
                'title' => 'Penyusunan Laporan Triwulan',
                'description' => 'Menyusun laporan kinerja triwulan dinas untuk disampaikan ke pemerintah pusat',
                'deadline' => Carbon::now()->addDays(14),
                'priority' => 'high',
                'column' => 'in-progress',
                'bidang_id' => $bidangs->where('slug', 'sekretariat')->first()->id,
                'created_by' => $users->where('email', 'bob@example.com')->first()->id,
                'assigned_users' => [
                    $users->where('email', 'bob@example.com')->first()->id,
                    $users->where('email', 'admin@example.com')->first()->id
                ],
                'subtasks' => [
                    ['text' => 'Kumpulkan data dari semua bidang', 'completed' => true],
                    ['text' => 'Analisis capaian kinerja', 'completed' => false],
                    ['text' => 'Tulis draft laporan', 'completed' => false],
                    ['text' => 'Review oleh kepala dinas', 'completed' => false],
                    ['text' => 'Finalisasi dan pengiriman', 'completed' => false],
                ],
            ],
            [
                'title' => 'Pengarsipan Dokumen Bulanan',
                'description' => 'Melakukan pengarsipan dokumen surat menyurat bulan ini',
                'deadline' => Carbon::now()->addDays(3),
                'priority' => 'medium',
                'column' => 'product-backlog',
                'bidang_id' => $bidangs->where('slug', 'sekretariat')->first()->id,
                'created_by' => $users->where('email', 'admin@example.com')->first()->id,
                'assigned_users' => [
                    $users->where('email', 'admin@example.com')->first()->id
                ],
                'subtasks' => [
                    ['text' => 'Sortir dokumen by kategori', 'completed' => false],
                    ['text' => 'Scan dokumen penting', 'completed' => false],
                    ['text' => 'Input database arsip', 'completed' => false],
                    ['text' => 'Simpan fisik dokumen', 'completed' => false],
                ],
            ],
            [
                'title' => 'Persiapan Rapat Koordinasi',
                'description' => 'Mempersiapkan segala kebutuhan rapat koordinasi bulanan',
                'deadline' => Carbon::now()->addDays(2),
                'priority' => 'high',
                'column' => 'in-progress',
                'bidang_id' => $bidangs->where('slug', 'sekretariat')->first()->id,
                'created_by' => $users->where('email', 'bob@example.com')->first()->id,
                'assigned_users' => [
                    $users->where('email', 'bob@example.com')->first()->id
                ],
                'subtasks' => [
                    ['text' => 'Siapkan undangan peserta', 'completed' => true],
                    ['text' => 'Print materi rapat', 'completed' => false],
                    ['text' => 'Setup ruang rapat', 'completed' => false],
                    ['text' => 'Konfirmasi kehadiran', 'completed' => false],
                ],
            ],
            [
                'title' => 'Pengelolaan Inventaris Kantor',
                'description' => 'Update data inventaris dan pemeliharaan aset kantor',
                'deadline' => Carbon::now()->addDays(7),
                'priority' => 'medium',
                'column' => 'product-backlog',
                'bidang_id' => $bidangs->where('slug', 'sekretariat')->first()->id,
                'created_by' => $users->where('email', 'admin@example.com')->first()->id,
                'assigned_users' => [
                    $users->where('email', 'admin@example.com')->first()->id
                ],
                'subtasks' => [
                    ['text' => 'Inventory check semua barang', 'completed' => false],
                    ['text' => 'Update database inventaris', 'completed' => false],
                    ['text' => 'Servis peralatan elektronik', 'completed' => false],
                    ['text' => 'Buat laporan kondisi aset', 'completed' => false],
                ],
            ],
            [
                'title' => 'Pengadaan Barang Habis Pakai',
                'description' => 'Procurement barang habis pakai untuk kebutuhan 3 bulan ke depan',
                'deadline' => Carbon::now()->addDays(10),
                'priority' => 'medium',
                'column' => 'product-backlog',
                'bidang_id' => $bidangs->where('slug', 'sekretariat')->first()->id,
                'created_by' => $users->where('email', 'bob@example.com')->first()->id,
                'assigned_users' => [
                    $users->where('email', 'bob@example.com')->first()->id
                ],
                'subtasks' => [
                    ['text' => 'Survey harga di vendor', 'completed' => false],
                    ['text' => 'Buat Rencana Kebutuhan', 'completed' => false],
                    ['text' => 'Proses pengadaan sesuai prosedur', 'completed' => false],
                    ['text' => 'Penerimaan dan distribusi', 'completed' => false],
                ],
            ],
            // Contoh task yang sudah selesai
            [
                'title' => 'Training Penggunaan Aplikasi E-Office',
                'description' => 'Melatih pegawai dalam menggunakan aplikasi e-office yang baru',
                'deadline' => Carbon::now()->subDays(2),
                'priority' => 'medium',
                'column' => 'done',
                'bidang_id' => $bidangs->where('slug', 'aptika')->first()->id,
                'created_by' => $users->where('email', 'admin@example.com')->first()->id,
                'assigned_users' => [
                    $users->where('email', 'admin@example.com')->first()->id,
                    $users->where('email', 'john@example.com')->first()->id
                ],
                'subtasks' => [
                    ['text' => 'Siapkan materi training', 'completed' => true],
                    ['text' => 'Jadwalkan sesi training', 'completed' => true],
                    ['text' => 'Training untuk 3 divisi', 'completed' => true],
                    ['text' => 'Evaluasi hasil training', 'completed' => true],
                ],
                'completed_at' => Carbon::now()->subDays(1),
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

        $this->command->info('✅ TaskSeeder completed successfully!');
        $this->command->info('Tasks Created: ' . count($sampleTasks));
        $this->command->info('Breakdown by Bidang:');
        $this->command->info('- Aptika: 6 tasks');
        $this->command->info('- Sarkom: 5 tasks');
        $this->command->info('- Sekretariat: 5 tasks');
    }
}
