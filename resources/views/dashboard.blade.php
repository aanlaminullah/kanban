<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Dashboard - Kanban Board</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: #333;
        }

        .dashboard-container {
            display: flex;
            height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            background: linear-gradient(145deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .user-profile {
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            text-align: center;
        }

        .user-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-size: 24px;
            font-weight: bold;
        }

        .user-name {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .user-role {
            font-size: 12px;
            opacity: 0.8;
        }

        .sidebar-menu {
            flex: 1;
            padding: 20px 0;
        }

        .menu-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            color: white;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .menu-item.active {
            background: rgba(255, 255, 255, 0.2);
            border-right: 3px solid white;
        }

        .menu-icon {
            width: 20px;
            height: 20px;
            margin-right: 12px;
        }

        .logout-btn {
            margin: 20px;
            padding: 12px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Main Content Styles */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .header {
            background: white;
            padding: 20px 30px;
            border-bottom: 1px solid #e1e5e9;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 24px;
            font-weight: 600;
            color: #2d3748;
        }

        .header-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .refresh-btn {
            background: #4299e1;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s;
            font-size: 14px;
        }

        .refresh-btn:hover {
            background: #3182ce;
        }

        .kanban-link {
            background: #805ad5;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s;
            font-size: 14px;
        }

        .kanban-link:hover {
            background: #6b46c1;
        }

        /* Dashboard Content */
        .dashboard-content {
            flex: 1;
            padding: 20px 30px;
            overflow-y: auto;
        }

        /* KPI Cards */
        .kpi-section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 16px;
            color: #2d3748;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .kpi-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border-left: 4px solid;
            transition: transform 0.2s;
        }

        .kpi-card:hover {
            transform: translateY(-2px);
        }

        .kpi-card.primary {
            border-left-color: #4299e1;
        }

        .kpi-card.success {
            border-left-color: #48bb78;
        }

        .kpi-card.warning {
            border-left-color: #ed8936;
        }

        .kpi-card.danger {
            border-left-color: #e53e3e;
        }

        .kpi-card.info {
            border-left-color: #805ad5;
        }

        .kpi-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8px;
        }

        .kpi-title {
            font-size: 14px;
            font-weight: 500;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .kpi-icon {
            width: 24px;
            height: 24px;
            opacity: 0.6;
        }

        .kpi-value {
            font-size: 28px;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 4px;
        }

        .kpi-subtitle {
            font-size: 12px;
            color: #a0aec0;
        }

        .progress-bar {
            width: 100%;
            height: 6px;
            background: #e2e8f0;
            border-radius: 3px;
            margin-top: 8px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 3px;
            transition: width 0.3s;
        }

        .progress-fill.success {
            background: #48bb78;
        }

        .progress-fill.warning {
            background: #ed8936;
        }

        .progress-fill.danger {
            background: #e53e3e;
        }

        /* Charts Section */
        .charts-section {
            margin-bottom: 30px;
        }

        .charts-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .chart-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .chart-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 16px;
            color: #2d3748;
        }

        .chart-container {
            position: relative;
            height: 300px;
        }

        .chart-container.small {
            height: 250px;
        }

        .line-chart-card {
            grid-column: 1 / -1;
        }

        /* Tables Section */
        .tables-section {
            margin-bottom: 30px;
        }

        .tables-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .table-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .table-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 16px;
            color: #2d3748;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .task-list {
            max-height: 300px;
            overflow-y: auto;
        }

        .task-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            margin-bottom: 8px;
            transition: all 0.2s;
        }

        .task-item:hover {
            background: #f7fafc;
            border-color: #cbd5e0;
        }

        .task-info {
            flex: 1;
        }

        .task-name {
            font-weight: 500;
            color: #2d3748;
            margin-bottom: 4px;
        }

        .task-meta {
            display: flex;
            gap: 12px;
            font-size: 12px;
            color: #718096;
        }

        .task-priority {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .priority-high {
            color: #e53e3e;
        }

        .priority-medium {
            color: #ed8936;
        }

        .priority-low {
            color: #48bb78;
        }

        .task-labels {
            display: flex;
            gap: 4px;
        }

        .task-label {
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: 500;
            text-transform: uppercase;
        }

        .label-urgent {
            background: #fed7d7;
            color: #c53030;
        }

        .label-bug {
            background: #fbb6ce;
            color: #b83280;
        }

        .label-improvement {
            background: #c6f6d5;
            color: #2f855a;
        }

        .label-personal {
            background: #bee3f8;
            color: #2b6cb0;
        }

        .label-feature {
            background: #e9d8fd;
            color: #805ad5;
        }

        .days-left {
            font-size: 12px;
            padding: 4px 8px;
            border-radius: 12px;
            font-weight: 500;
        }

        .days-left.danger {
            background: #fed7d7;
            color: #c53030;
        }

        .days-left.warning {
            background: #fef5e7;
            color: #d69e2e;
        }

        .days-left.safe {
            background: #c6f6d5;
            color: #2f855a;
        }

        .subtask-progress {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 4px;
        }

        .progress-text {
            font-size: 11px;
            color: #718096;
        }

        .mini-progress-bar {
            width: 60px;
            height: 4px;
            background: #e2e8f0;
            border-radius: 2px;
            overflow: hidden;
        }

        .mini-progress-fill {
            height: 100%;
            background: #48bb78;
            border-radius: 2px;
            transition: width 0.3s;
        }

        .empty-state {
            text-align: center;
            color: #a0aec0;
            font-style: italic;
            padding: 40px 20px;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .charts-grid {
                grid-template-columns: 1fr;
            }

            .tables-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
            }

            .kpi-grid {
                grid-template-columns: 1fr;
            }

            .dashboard-content {
                padding: 16px;
            }
        }

        /* Loading Animation */
        .loading {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 200px;
            color: #a0aec0;
        }

        .spinner {
            width: 32px;
            height: 32px;
            border: 3px solid #e2e8f0;
            border-top: 3px solid #4299e1;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 12px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="user-profile">
                <div class="user-avatar">JD</div>
                <div class="user-name">John Doe</div>
                <div class="user-role">Project Manager</div>
            </div>

            <div class="sidebar-menu">
                <a href="/" class="menu-item active">
                    <svg class="menu-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                    Dashboard
                </a>

                <a href="/kanban" class="menu-item">
                    <svg class="menu-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                    </svg>
                    Kanban Board
                </a>

                <a href="#" class="menu-item">
                    <svg class="menu-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                            clip-rule="evenodd" />
                    </svg>
                    Calendar
                </a>

                <a href="#" class="menu-item">
                    <svg class="menu-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2L3 7v11a1 1 0 001 1h3v-5a1 1 0 011-1h4a1 1 0 011 1v5h3a1 1 0 001-1V7l-7-5z" />
                    </svg>
                    Projects
                </a>

                <a href="#" class="menu-item">
                    <svg class="menu-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z"
                            clip-rule="evenodd" />
                    </svg>
                    Team
                </a>

                <a href="#" class="menu-item">
                    <svg class="menu-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                            clip-rule="evenodd" />
                    </svg>
                    Settings
                </a>
            </div>

            <button class="logout-btn" onclick="logout()">
                <svg style="width: 16px; height: 16px; margin-right: 8px;" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z"
                        clip-rule="evenodd" />
                </svg>
                Logout
            </button>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <div class="header">
                <h1>📊 Analytics Dashboard</h1>
                <div class="header-actions">
                    <button class="refresh-btn" onclick="refreshData()">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                                clip-rule="evenodd" />
                        </svg>
                        Refresh Data
                    </button>
                    <a href="#" class="kanban-link" onclick="goToKanban()">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                        </svg>
                        Ke Kanban Board
                    </a>
                </div>
            </div>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <!-- KPI Section -->
                <div class="kpi-section">
                    <h2 class="section-title">
                        📈 Key Performance Indicators (KPIs)
                    </h2>
                    <div class="kpi-grid" id="kpiGrid">
                        <!-- KPI cards will be generated here -->
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="charts-section">
                    <h2 class="section-title">
                        📊 Visualisasi Data
                    </h2>
                    <div class="charts-grid">
                        <div class="chart-card">
                            <h3 class="chart-title">Task Berdasarkan Prioritas</h3>
                            <div class="chart-container small">
                                <canvas id="priorityChart"></canvas>
                            </div>
                        </div>

                        <div class="chart-card">
                            <h3 class="chart-title">Status Task</h3>
                            <div class="chart-container small">
                                <canvas id="statusChart"></canvas>
                            </div>
                        </div>

                        <div class="chart-card line-chart-card">
                            <h3 class="chart-title">Tren Progress Task (7 Hari Terakhir)</h3>
                            <div class="chart-container">
                                <canvas id="trendChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tables Section -->
                <div class="tables-section">
                    <h2 class="section-title">
                        📋 Detail Task
                    </h2>
                    <div class="tables-grid">
                        <div class="table-card">
                            <h3 class="table-title">
                                ⚠️ Task Mendekati Deadline
                            </h3>
                            <div class="task-list" id="upcomingDeadlines">
                                <!-- Task list will be generated here -->
                            </div>
                        </div>

                        <div class="table-card">
                            <h3 class="table-title">
                                🔥 Task Prioritas Tinggi
                            </h3>
                            <div class="task-list" id="highPriorityTasks">
                                <!-- Task list will be generated here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Sample data - same as kanban board
        const tasks = [{
                id: 1,
                title: 'Implementasi Sistem Login',
                deadline: '2024-09-20',
                description: 'Membuat sistem autentikasi pengguna dengan JWT token dan validasi form',
                column: 'overdue',
                priority: 'high',
                labels: ['urgent', 'feature'],
                subtasks: [{
                        id: 1,
                        text: 'Setup JWT library',
                        completed: false
                    },
                    {
                        id: 2,
                        text: 'Buat login form',
                        completed: true
                    },
                    {
                        id: 3,
                        text: 'Validasi input',
                        completed: false
                    }
                ]
            },
            {
                id: 2,
                title: 'Design UI Dashboard',
                deadline: '2024-10-20',
                description: 'Merancang interface dashboard utama dengan komponen yang responsif',
                column: 'product-backlog',
                priority: 'medium',
                labels: ['improvement'],
                subtasks: [{
                        id: 1,
                        text: 'Wireframe',
                        completed: true
                    },
                    {
                        id: 2,
                        text: 'Mockup design',
                        completed: false
                    }
                ]
            },
            {
                id: 3,
                title: 'Fix Bug Authentication',
                deadline: '2024-09-15',
                description: 'Mengatasi masalah token expiry yang tidak ter-handle dengan baik',
                column: 'overdue',
                priority: 'high',
                labels: ['urgent', 'bug'],
                subtasks: [{
                        id: 1,
                        text: 'Reproduce bug',
                        completed: true
                    },
                    {
                        id: 2,
                        text: 'Fix implementation',
                        completed: false
                    },
                    {
                        id: 3,
                        text: 'Testing',
                        completed: false
                    }
                ]
            },
            {
                id: 4,
                title: 'Database Schema',
                deadline: '2024-10-12',
                description: 'Membuat struktur database untuk aplikasi dengan relasi yang tepat',
                column: 'in-progress',
                priority: 'medium',
                labels: ['feature'],
                subtasks: [{
                        id: 1,
                        text: 'ERD Design',
                        completed: true
                    },
                    {
                        id: 2,
                        text: 'Migration scripts',
                        completed: false
                    }
                ]
            },
            {
                id: 5,
                title: 'Personal Learning: React Hooks',
                deadline: '2024-10-25',
                description: 'Mempelajari advanced React Hooks untuk project mendatang',
                column: 'product-backlog',
                priority: 'low',
                labels: ['personal', 'improvement'],
                subtasks: [{
                        id: 1,
                        text: 'useCallback & useMemo',
                        completed: false
                    },
                    {
                        id: 2,
                        text: 'Custom hooks',
                        completed: false
                    },
                    {
                        id: 3,
                        text: 'Practice project',
                        completed: false
                    }
                ]
            },
            {
                id: 6,
                title: 'Unit Testing Setup',
                deadline: '2024-09-28',
                description: 'Implementasi framework testing dan setup CI/CD',
                column: 'in-progress',
                priority: 'high',
                labels: ['feature', 'improvement'],
                subtasks: [{
                        id: 1,
                        text: 'Jest configuration',
                        completed: true
                    },
                    {
                        id: 2,
                        text: 'Write test cases',
                        completed: true
                    },
                    {
                        id: 3,
                        text: 'CI/CD integration',
                        completed: false
                    }
                ]
            },
            {
                id: 7,
                title: 'API Documentation',
                deadline: '2024-09-30',
                description: 'Membuat dokumentasi lengkap untuk semua API endpoint',
                column: 'pending',
                priority: 'medium',
                labels: ['improvement'],
                subtasks: [{
                        id: 1,
                        text: 'Endpoint listing',
                        completed: false
                    },
                    {
                        id: 2,
                        text: 'Request/Response examples',
                        completed: false
                    }
                ]
            },
            {
                id: 8,
                title: 'User Profile Feature',
                deadline: '2024-10-05',
                description: 'Implementasi halaman profil pengguna dengan edit functionality',
                column: 'done',
                priority: 'medium',
                labels: ['feature'],
                subtasks: [{
                        id: 1,
                        text: 'UI Design',
                        completed: true
                    },
                    {
                        id: 2,
                        text: 'Backend API',
                        completed: true
                    },
                    {
                        id: 3,
                        text: 'Frontend Integration',
                        completed: true
                    }
                ]
            }
        ];

        let charts = {};

        // Calculate metrics
        function calculateMetrics() {
            const total = tasks.length;
            const statusCounts = {
                'product-backlog': 0,
                'in-progress': 0,
                'pending': 0,
                'done': 0,
                'overdue': 0
            };

            const priorityCounts = {
                'high': 0,
                'medium': 0,
                'low': 0
            };

            let totalSubtasks = 0;
            let completedSubtasks = 0;
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            tasks.forEach(task => {
                // Count by status
                statusCounts[task.column]++;

                // Count by priority
                priorityCounts[task.priority]++;

                // Count subtasks
                if (task.subtasks) {
                    totalSubtasks += task.subtasks.length;
                    completedSubtasks += task.subtasks.filter(st => st.completed).length;
                }

                // Check if overdue
                const deadline = new Date(task.deadline);
                deadline.setHours(0, 0, 0, 0);
                if (deadline < today && task.column !== 'done' && task.column !== 'overdue') {
                    task.column = 'overdue';
                    statusCounts['overdue']++;
                    statusCounts[task.column === 'overdue' ? 'product-backlog' : task.column]--;
                }
            });

            const completionRate = total > 0 ? Math.round((statusCounts.done / total) * 100) : 0;
            const subtaskCompletionRate = totalSubtasks > 0 ? Math.round((completedSubtasks / totalSubtasks) * 100) : 0;

            return {
                total,
                statusCounts,
                priorityCounts,
                completionRate,
                subtaskCompletionRate,
                totalSubtasks,
                completedSubtasks
            };
        }

        // Generate KPI cards
        function generateKPICards() {
            const metrics = calculateMetrics();
            const kpiGrid = document.getElementById('kpiGrid');

            const kpis = [{
                    title: 'Total Tasks',
                    value: metrics.total,
                    subtitle: 'Semua task dalam sistem',
                    icon: '📋',
                    type: 'primary'
                },
                {
                    title: 'Task Selesai',
                    value: `${metrics.completionRate}%`,
                    subtitle: `${metrics.statusCounts.done} dari ${metrics.total} task`,
                    icon: '✅',
                    type: 'success',
                    progress: metrics.completionRate
                },
                {
                    title: 'Task Overdue',
                    value: metrics.statusCounts.overdue,
                    subtitle: 'Perlu perhatian segera',
                    icon: '🚨',
                    type: 'danger'
                },
                {
                    title: 'In Progress',
                    value: metrics.statusCounts['in-progress'],
                    subtitle: 'Task sedang dikerjakan',
                    icon: '🔄',
                    type: 'info'
                },
                {
                    title: 'Progress Subtask',
                    value: `${metrics.subtaskCompletionRate}%`,
                    subtitle: `${metrics.completedSubtasks} dari ${metrics.totalSubtasks} subtask`,
                    icon: '📝',
                    type: 'warning',
                    progress: metrics.subtaskCompletionRate
                }
            ];

            kpiGrid.innerHTML = kpis.map(kpi => `
                <div class="kpi-card ${kpi.type}">
                    <div class="kpi-header">
                        <div class="kpi-title">${kpi.title}</div>
                        <div class="kpi-icon">${kpi.icon}</div>
                    </div>
                    <div class="kpi-value">${kpi.value}</div>
                    <div class="kpi-subtitle">${kpi.subtitle}</div>
                    ${kpi.progress !== undefined ? `
                                    <div class="progress-bar">
                                        <div class="progress-fill ${kpi.type}" style="width: ${kpi.progress}%"></div>
                                    </div>
                                ` : ''}
                </div>
            `).join('');
        }

        // Initialize charts
        function initCharts() {
            const metrics = calculateMetrics();

            // Priority Chart (Bar Chart)
            const priorityCtx = document.getElementById('priorityChart').getContext('2d');
            charts.priority = new Chart(priorityCtx, {
                type: 'bar',
                data: {
                    labels: ['High 🔥', 'Medium ⬆️', 'Low ➡️'],
                    datasets: [{
                        label: 'Jumlah Task',
                        data: [
                            metrics.priorityCounts.high,
                            metrics.priorityCounts.medium,
                            metrics.priorityCounts.low
                        ],
                        backgroundColor: [
                            '#fed7d7',
                            '#fef5e7',
                            '#c6f6d5'
                        ],
                        borderColor: [
                            '#e53e3e',
                            '#d69e2e',
                            '#48bb78'
                        ],
                        borderWidth: 2,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            // Status Chart (Pie Chart)
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            charts.status = new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Backlog', 'In Progress', 'Pending', 'Done', 'Overdue'],
                    datasets: [{
                        data: [
                            metrics.statusCounts['product-backlog'],
                            metrics.statusCounts['in-progress'],
                            metrics.statusCounts['pending'],
                            metrics.statusCounts['done'],
                            metrics.statusCounts['overdue']
                        ],
                        backgroundColor: [
                            '#e2e8f0',
                            '#bee3f8',
                            '#faf089',
                            '#c6f6d5',
                            '#fed7d7'
                        ],
                        borderColor: [
                            '#a0aec0',
                            '#4299e1',
                            '#d69e2e',
                            '#48bb78',
                            '#e53e3e'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        }
                    }
                }
            });

            // Trend Chart (Line Chart) - Sample data
            const trendCtx = document.getElementById('trendChart').getContext('2d');
            const last7Days = [];
            const completedTasks = [1, 0, 2, 1, 0, 1, 1]; // Sample data
            const newTasks = [2, 1, 1, 2, 1, 0, 1]; // Sample data

            for (let i = 6; i >= 0; i--) {
                const date = new Date();
                date.setDate(date.getDate() - i);
                last7Days.push(date.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'short'
                }));
            }

            charts.trend = new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: last7Days,
                    datasets: [{
                            label: 'Task Diselesaikan',
                            data: completedTasks,
                            borderColor: '#48bb78',
                            backgroundColor: 'rgba(72, 187, 120, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Task Baru',
                            data: newTasks,
                            borderColor: '#4299e1',
                            backgroundColor: 'rgba(66, 153, 225, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }

        // Calculate days until deadline
        function getDaysUntilDeadline(deadline) {
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const deadlineDate = new Date(deadline);
            deadlineDate.setHours(0, 0, 0, 0);

            const diffTime = deadlineDate - today;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            return diffDays;
        }

        // Get deadline status class
        function getDeadlineStatus(daysLeft) {
            if (daysLeft < 0) return 'danger';
            if (daysLeft <= 2) return 'danger';
            if (daysLeft <= 7) return 'warning';
            return 'safe';
        }

        // Format deadline text
        function formatDeadlineText(daysLeft) {
            if (daysLeft < 0) return `${Math.abs(daysLeft)} hari terlambat`;
            if (daysLeft === 0) return 'Hari ini';
            if (daysLeft === 1) return 'Besok';
            return `${daysLeft} hari lagi`;
        }

        // Generate upcoming deadlines
        function generateUpcomingDeadlines() {
            const container = document.getElementById('upcomingDeadlines');

            // Get tasks approaching deadline (within 7 days or overdue)
            const upcomingTasks = tasks
                .filter(task => {
                    const daysLeft = getDaysUntilDeadline(task.deadline);
                    return (daysLeft <= 7 && task.column !== 'done');
                })
                .sort((a, b) => getDaysUntilDeadline(a.deadline) - getDaysUntilDeadline(b.deadline));

            if (upcomingTasks.length === 0) {
                container.innerHTML = '<div class="empty-state">Tidak ada task yang mendekati deadline 🎉</div>';
                return;
            }

            container.innerHTML = upcomingTasks.map(task => {
                const daysLeft = getDaysUntilDeadline(task.deadline);
                const deadlineStatus = getDeadlineStatus(daysLeft);
                const deadlineText = formatDeadlineText(daysLeft);

                const labelsHTML = task.labels?.map(label =>
                    `<span class="task-label label-${label}">${label}</span>`
                ).join('') || '';

                return `
                    <div class="task-item">
                        <div class="task-info">
                            <div class="task-name">${task.title}</div>
                            <div class="task-meta">
                                <div class="task-priority priority-${task.priority}">
                                    ${task.priority === 'high' ? '🔥' : task.priority === 'medium' ? '⬆️' : '➡️'}
                                    ${task.priority}
                                </div>
                                <div class="task-labels">${labelsHTML}</div>
                            </div>
                        </div>
                        <div class="days-left ${deadlineStatus}">
                            ${deadlineText}
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Generate high priority tasks
        function generateHighPriorityTasks() {
            const container = document.getElementById('highPriorityTasks');

            const highPriorityTasks = tasks
                .filter(task => task.priority === 'high' && task.column !== 'done')
                .sort((a, b) => getDaysUntilDeadline(a.deadline) - getDaysUntilDeadline(b.deadline));

            if (highPriorityTasks.length === 0) {
                container.innerHTML =
                    '<div class="empty-state">Tidak ada task prioritas tinggi yang belum selesai 🎯</div>';
                return;
            }

            container.innerHTML = highPriorityTasks.map(task => {
                const completedSubtasks = task.subtasks?.filter(st => st.completed).length || 0;
                const totalSubtasks = task.subtasks?.length || 0;
                const subtaskProgress = totalSubtasks > 0 ? Math.round((completedSubtasks / totalSubtasks) * 100) :
                    0;

                const labelsHTML = task.labels?.map(label =>
                    `<span class="task-label label-${label}">${label}</span>`
                ).join('') || '';

                return `
                    <div class="task-item">
                        <div class="task-info">
                            <div class="task-name">${task.title}</div>
                            <div class="task-meta">
                                <div class="task-priority priority-high">
                                    🔥 Status: ${task.column.replace('-', ' ')}
                                </div>
                                <div class="task-labels">${labelsHTML}</div>
                            </div>
                            ${totalSubtasks > 0 ? `
                                            <div class="subtask-progress">
                                                <div class="progress-text">${completedSubtasks}/${totalSubtasks}</div>
                                                <div class="mini-progress-bar">
                                                    <div class="mini-progress-fill" style="width: ${subtaskProgress}%"></div>
                                                </div>
                                            </div>
                                        ` : ''}
                        </div>
                        <div class="days-left ${getDeadlineStatus(getDaysUntilDeadline(task.deadline))}">
                            ${formatDeadlineText(getDaysUntilDeadline(task.deadline))}
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Refresh all data
        function refreshData() {
            const refreshBtn = document.querySelector('.refresh-btn');
            refreshBtn.innerHTML = '<div class="spinner"></div>Updating...';
            refreshBtn.disabled = true;

            setTimeout(() => {
                generateKPICards();

                // Destroy existing charts before recreating
                Object.values(charts).forEach(chart => {
                    if (chart) chart.destroy();
                });
                charts = {};

                initCharts();
                generateUpcomingDeadlines();
                generateHighPriorityTasks();

                refreshBtn.innerHTML = `
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                    </svg>
                    Refresh Data
                `;
                refreshBtn.disabled = false;
            }, 1500);
        }

        // Navigation functions
        function goToKanban() {
            alert('Navigasi ke Kanban Board akan dibuat disini');
        }

        function logout() {
            if (confirm('Apakah Anda yakin ingin keluar?')) {
                alert('Anda telah berhasil logout!');
            }
        }

        // Initialize dashboard
        document.addEventListener('DOMContentLoaded', function() {
            generateKPICards();
            initCharts();
            generateUpcomingDeadlines();
            generateHighPriorityTasks();

            // Auto-refresh every 5 minutes
            setInterval(() => {
                refreshData();
            }, 300000); // 5 minutes
        });
    </script>
</body>

</html>
