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
