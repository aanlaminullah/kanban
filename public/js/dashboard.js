let charts = {};

// Calculate metrics dari data PHP

function calculateMetrics() {
    const total = tasks.length;
    const statusCounts = {
        "product-backlog": 0,
        "in-progress": 0,
        pending: 0,
        done: 0,
        overdue: 0,
    };

    const priorityCounts = {
        high: 0,
        medium: 0,
        low: 0,
    };

    let totalSubtasks = 0;
    let completedSubtasks = 0;
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    tasks.forEach((task) => {
        // Count by status
        statusCounts[task.column]++;

        // Count by priority
        priorityCounts[task.priority]++;

        // Count subtasks
        if (task.subtasks) {
            totalSubtasks += task.subtasks.length;
            completedSubtasks += task.subtasks.filter(
                (st) => st.completed
            ).length;
        }

        // Check if overdue
        const deadline = new Date(task.deadline);
        deadline.setHours(0, 0, 0, 0);
        if (
            deadline < today &&
            task.column !== "done" &&
            task.column !== "overdue"
        ) {
            task.column = "overdue";
            statusCounts["overdue"]++;
            statusCounts[
                task.column === "overdue" ? "product-backlog" : task.column
            ]--;
        }
    });

    const completionRate =
        total > 0 ? Math.round((statusCounts.done / total) * 100) : 0;
    const subtaskCompletionRate =
        totalSubtasks > 0
            ? Math.round((completedSubtasks / totalSubtasks) * 100)
            : 0;

    return {
        total,
        statusCounts,
        priorityCounts,
        completionRate,
        subtaskCompletionRate,
        totalSubtasks,
        completedSubtasks,
    };
}

// Generate KPI cards
function generateKPICards() {
    const metrics = calculateMetrics();
    const kpiGrid = document.getElementById("kpiGrid");

    const kpis = [
        {
            title: "Total Tasks",
            value: metrics.total,
            subtitle: "Semua task dalam sistem",
            icon: "📋",
            type: "primary",
        },
        {
            title: "Task Selesai",
            value: `${metrics.completionRate}%`,
            subtitle: `${metrics.statusCounts.done} dari ${metrics.total} task`,
            icon: "✅",
            type: "success",
            progress: metrics.completionRate,
        },
        {
            title: "Task Overdue",
            value: metrics.statusCounts.overdue,
            subtitle: "Perlu perhatian segera",
            icon: "🚨",
            type: "danger",
        },
        {
            title: "In Progress",
            value: metrics.statusCounts["in-progress"],
            subtitle: "Task sedang dikerjakan",
            icon: "🔄",
            type: "info",
        },
        {
            title: "Progress Subtask",
            value: `${metrics.subtaskCompletionRate}%`,
            subtitle: `${metrics.completedSubtasks} dari ${metrics.totalSubtasks} subtask`,
            icon: "📝",
            type: "warning",
            progress: metrics.subtaskCompletionRate,
        },
    ];

    kpiGrid.innerHTML = kpis
        .map(
            (kpi) => `
                <div class="kpi-card ${kpi.type}">
                    <div class="kpi-header">
                        <div class="kpi-title">${kpi.title}</div>
                        <div class="kpi-icon">${kpi.icon}</div>
                    </div>
                    <div class="kpi-value">${kpi.value}</div>
                    <div class="kpi-subtitle">${kpi.subtitle}</div>
                    ${
                        kpi.progress !== undefined
                            ? `
                                                                                <div class="progress-bar">
                                                                                    <div class="progress-fill ${kpi.type}" style="width: ${kpi.progress}%"></div>
                                                                                </div>
                                                                            `
                            : ""
                    }
                </div>
            `
        )
        .join("");
}

// Initialize charts
function initCharts() {
    const metrics = calculateMetrics();

    // Priority Chart (Bar Chart)
    const priorityCtx = document
        .getElementById("priorityChart")
        .getContext("2d");
    charts.priority = new Chart(priorityCtx, {
        type: "bar",
        data: {
            labels: ["High 🔥", "Medium ⬆️", "Low ➡️"],
            datasets: [
                {
                    label: "Jumlah Task",
                    data: [
                        metrics.priorityCounts.high,
                        metrics.priorityCounts.medium,
                        metrics.priorityCounts.low,
                    ],
                    backgroundColor: ["#fed7d7", "#fef5e7", "#c6f6d5"],
                    borderColor: ["#e53e3e", "#d69e2e", "#48bb78"],
                    borderWidth: 2,
                    borderRadius: 6,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                    },
                },
            },
        },
    });

    // Status Chart (Pie Chart)
    const statusCtx = document.getElementById("statusChart").getContext("2d");
    charts.status = new Chart(statusCtx, {
        type: "doughnut",
        data: {
            labels: ["Backlog", "In Progress", "Pending", "Done", "Overdue"],
            datasets: [
                {
                    data: [
                        metrics.statusCounts["product-backlog"],
                        metrics.statusCounts["in-progress"],
                        metrics.statusCounts["pending"],
                        metrics.statusCounts["done"],
                        metrics.statusCounts["overdue"],
                    ],
                    backgroundColor: [
                        "#e2e8f0",
                        "#bee3f8",
                        "#faf089",
                        "#c6f6d5",
                        "#fed7d7",
                    ],
                    borderColor: [
                        "#a0aec0",
                        "#4299e1",
                        "#d69e2e",
                        "#48bb78",
                        "#e53e3e",
                    ],
                    borderWidth: 2,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: "bottom",
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                    },
                },
            },
        },
    });

    // Trend Chart (Line Chart) - Sample data
    const trendCtx = document.getElementById("trendChart").getContext("2d");
    const last7Days = [];
    const completedTasks = [1, 0, 2, 1, 0, 1, 1]; // Sample data - bisa diganti dengan data real
    const newTasks = [2, 1, 1, 2, 1, 0, 1]; // Sample data - bisa diganti dengan data real

    for (let i = 6; i >= 0; i--) {
        const date = new Date();
        date.setDate(date.getDate() - i);
        last7Days.push(
            date.toLocaleDateString("id-ID", {
                day: "2-digit",
                month: "short",
            })
        );
    }

    charts.trend = new Chart(trendCtx, {
        type: "line",
        data: {
            labels: last7Days,
            datasets: [
                {
                    label: "Task Diselesaikan",
                    data: completedTasks,
                    borderColor: "#48bb78",
                    backgroundColor: "rgba(72, 187, 120, 0.1)",
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                },
                {
                    label: "Task Baru",
                    data: newTasks,
                    borderColor: "#4299e1",
                    backgroundColor: "rgba(66, 153, 225, 0.1)",
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: "index",
            },
            plugins: {
                legend: {
                    position: "top",
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                    },
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                    },
                },
            },
        },
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
    if (daysLeft < 0) return "danger";
    if (daysLeft <= 2) return "danger";
    if (daysLeft <= 7) return "warning";
    return "safe";
}

// Format deadline text
function formatDeadlineText(daysLeft) {
    if (daysLeft < 0) return `${Math.abs(daysLeft)} hari terlambat`;
    if (daysLeft === 0) return "Hari ini";
    if (daysLeft === 1) return "Besok";
    return `${daysLeft} hari lagi`;
}

// Generate upcoming deadlines
function generateUpcomingDeadlines() {
    const container = document.getElementById("upcomingDeadlines");

    // Get tasks approaching deadline (within 7 days or overdue)
    const upcomingTasks = tasks
        .filter((task) => {
            const daysLeft = getDaysUntilDeadline(task.deadline);
            return daysLeft <= 7 && task.column !== "done";
        })
        .sort(
            (a, b) =>
                getDaysUntilDeadline(a.deadline) -
                getDaysUntilDeadline(b.deadline)
        );

    if (upcomingTasks.length === 0) {
        container.innerHTML =
            '<div class="empty-state">Tidak ada task yang mendekati deadline 🎉</div>';
        return;
    }

    container.innerHTML = upcomingTasks
        .map((task) => {
            const daysLeft = getDaysUntilDeadline(task.deadline);
            const deadlineStatus = getDeadlineStatus(daysLeft);
            const deadlineText = formatDeadlineText(daysLeft);

            return `
                    <div class="task-item">
                        <div class="task-info">
                            <div class="task-name">${task.title}</div>
                            <div class="task-meta">
                                <div class="task-priority priority-${
                                    task.priority
                                }">
                                    ${
                                        task.priority === "high"
                                            ? "🔥"
                                            : task.priority === "medium"
                                            ? "⬆️"
                                            : "➡️"
                                    }
                                    ${task.priority}
                                </div>
                                <div class="task-labels">
                                    <span class="task-label label-${
                                        task.bidang?.slug || "general"
                                    }">
                                        ${task.bidang?.name || "General"}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="days-left ${deadlineStatus}">
                            ${deadlineText}
                        </div>
                    </div>
                `;
        })
        .join("");
}

// Generate high priority tasks
function generateHighPriorityTasks() {
    const container = document.getElementById("highPriorityTasks");

    const highPriorityTasks = tasks
        .filter((task) => task.priority === "high" && task.column !== "done")
        .sort(
            (a, b) =>
                getDaysUntilDeadline(a.deadline) -
                getDaysUntilDeadline(b.deadline)
        );

    if (highPriorityTasks.length === 0) {
        container.innerHTML =
            '<div class="empty-state">Tidak ada task prioritas tinggi yang belum selesai 🎯</div>';
        return;
    }

    container.innerHTML = highPriorityTasks
        .map((task) => {
            const completedSubtasks =
                task.subtasks?.filter((st) => st.completed).length || 0;
            const totalSubtasks = task.subtasks?.length || 0;
            const subtaskProgress =
                totalSubtasks > 0
                    ? Math.round((completedSubtasks / totalSubtasks) * 100)
                    : 0;

            return `
                    <div class="task-item">
                        <div class="task-info">
                            <div class="task-name">${task.title}</div>
                            <div class="task-meta">
                                <div class="task-priority priority-high">
                                    🔥 Status: ${task.column.replace("-", " ")}
                                </div>
                                <div class="task-labels">
                                    <span class="task-label label-${
                                        task.bidang?.slug || "general"
                                    }">
                                        ${task.bidang?.name || "General"}
                                    </span>
                                </div>
                            </div>
                            ${
                                totalSubtasks > 0
                                    ? `
                                                                                        <div class="subtask-progress">
                                                                                            <div class="progress-text">${completedSubtasks}/${totalSubtasks}</div>
                                                                                            <div class="mini-progress-bar">
                                                                                                <div class="mini-progress-fill" style="width: ${subtaskProgress}%"></div>
                                                                                            </div>
                                                                                        </div>
                                                                                    `
                                    : ""
                            }
                        </div>
                        <div class="days-left ${getDeadlineStatus(
                            getDaysUntilDeadline(task.deadline)
                        )}">
                            ${formatDeadlineText(
                                getDaysUntilDeadline(task.deadline)
                            )}
                        </div>
                    </div>
                `;
        })
        .join("");
}

// Refresh all data
function refreshData() {
    const refreshBtn = document.querySelector(".refresh-btn");
    refreshBtn.innerHTML = '<div class="spinner"></div>Updating...';
    refreshBtn.disabled = true;

    // Reload page untuk refresh data dari server
    setTimeout(() => {
        location.reload();
    }, 1000);
}

// Initialize dashboard
document.addEventListener("DOMContentLoaded", function () {
    generateKPICards();
    initCharts();
    generateUpcomingDeadlines();
    generateHighPriorityTasks();

    // Auto-refresh every 5 minutes
    setInterval(() => {
        refreshData();
    }, 300000); // 5 minutes

    // Keyboard shortcuts for modal
    document.addEventListener("keydown", function (e) {
        if (
            e.key === "Escape" &&
            document.getElementById("confirmModal").classList.contains("active")
        ) {
            document.getElementById("confirmModal").classList.remove("active");
            document.body.style.overflow = "auto";
        }
    });
});

// Toast Notification System
let toastCounter = 0;

container.appendChild(toast);

// Trigger animation
setTimeout(() => toast.classList.add("show"), 10);

// Auto remove after duration
setTimeout(() => removeToast(toastId), duration);

// Click to dismiss
toast.addEventListener("click", () => removeToast(toastId));
