<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        .label-aptika {
            background: #fed7d7;
            color: #c53030;
        }

        .label-sarkom {
            background: #fbb6ce;
            color: #b83280;
        }

        .label-sekretariat {
            background: #c6f6d5;
            color: #2f855a;
        }

        .label-general {
            background: #bee3f8;
            color: #2b6cb0;
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

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            padding: 20px;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-container {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            max-width: 400px;
            width: 100%;
            animation: modalSlideIn 0.2s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Confirm Modal Styles */
        .confirm-modal {
            text-align: center;
        }

        .confirm-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 24px;
        }

        .confirm-icon.warning {
            background: #fef5e7;
            color: #d69e2e;
        }

        .confirm-icon.danger {
            background: #fed7d7;
            color: #e53e3e;
        }

        .confirm-icon.success {
            background: #c6f6d5;
            color: #38a169;
        }

        .confirm-icon.info {
            background: #bee3f8;
            color: #3182ce;
        }

        .confirm-modal h3 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #2d3748;
        }

        .confirm-modal p {
            color: #718096;
            margin-bottom: 24px;
            line-height: 1.5;
        }

        .confirm-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        .btn-primary,
        .btn-secondary {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            min-width: 100px;
        }

        .btn-primary {
            background: #4299e1;
            color: white;
        }

        .btn-primary:hover {
            background: #3182ce;
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #4a5568;
        }

        .btn-secondary:hover {
            background: #cbd5e0;
        }

        /* Toast Styles */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1001;
            display: flex;
            flex-direction: column;
            gap: 10px;
            max-width: 400px;
        }

        .toast {
            background: white;
            border-radius: 8px;
            padding: 16px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            border-left: 4px solid;
            transform: translateX(400px);
            opacity: 0;
            transition: all 0.3s ease-out;
            cursor: pointer;
            position: relative;
        }

        .toast.show {
            transform: translateX(0);
            opacity: 1;
        }

        .toast.success {
            border-left-color: #48bb78;
        }

        .toast.error {
            border-left-color: #e53e3e;
        }

        .toast.warning {
            border-left-color: #ed8936;
        }

        .toast.info {
            border-left-color: #4299e1;
        }

        .toast-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .toast-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            font-size: 14px;
        }

        .toast-icon {
            width: 16px;
            height: 16px;
        }

        .toast-close {
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            color: #a0aec0;
            padding: 0;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toast-close:hover {
            color: #718096;
        }

        .toast-message {
            font-size: 14px;
            color: #4a5568;
            line-height: 1.4;
        }

        .toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background: currentColor;
            opacity: 0.3;
            width: 100%;
            transform-origin: left;
            animation: toastProgress linear forwards;
        }

        @keyframes toastProgress {
            from {
                transform: scaleX(1);
            }

            to {
                transform: scaleX(0);
            }
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        @include('partials.sidebar')

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
                    <a href="/kanban" class="kanban-link">
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
                    <h2 class="section-title">📈 Key Performance Indicators (KPIs)</h2>
                    <div class="kpi-grid" id="kpiGrid">
                        <!-- KPI cards akan di-generate oleh JavaScript -->
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="charts-section">
                    <h2 class="section-title">📊 Visualisasi Data</h2>
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
                    <h2 class="section-title">📋 Detail Task</h2>
                    <div class="tables-grid">
                        <div class="table-card">
                            <h3 class="table-title">⚠️ Task Mendekati Deadline</h3>
                            <div class="task-list" id="upcomingDeadlines">
                                <!-- Task list akan di-generate oleh JavaScript -->
                            </div>
                        </div>

                        <div class="table-card">
                            <h3 class="table-title">🔥 Task Prioritas Tinggi</h3>
                            <div class="task-list" id="highPriorityTasks">
                                <!-- Task list akan di-generate oleh JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toastContainer" class="toast-container"></div>

    <script>
        // Data akan diambil dari PHP/backend
        let tasks = <?php echo json_encode($tasks); ?>;
    </script>

    <!-- Load app.js FIRST (contains showToast, showConfirm, logout, etc.) -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Load dashboard.js AFTER app.js -->
    <script src="{{ asset('js/dashboard.js') }}"></script>
</body>

</html>
