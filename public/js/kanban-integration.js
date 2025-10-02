// kanban-integration.js - Frontend API Integration
// File ini harus di-include SETELAH kanban.js di master.blade.php

const API_BASE = "/api/kanban";

// Get CSRF token from meta tag
function getCSRFToken() {
    return (
        document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content") || ""
    );
}

// API Helper function
async function apiRequest(endpoint, options = {}) {
    const defaultOptions = {
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": getCSRFToken(),
            "X-Requested-With": "XMLHttpRequest",
            Accept: "application/json",
        },
        credentials: "same-origin",
    };

    // Don't set Content-Type for FormData
    if (options.body instanceof FormData) {
        delete defaultOptions.headers["Content-Type"];
    }

    const config = {
        ...defaultOptions,
        ...options,
        headers: {
            ...defaultOptions.headers,
            ...(options.headers || {}),
        },
    };

    try {
        const response = await fetch(`${API_BASE}${endpoint}`, config);
        const data = await response.json();

        if (!response.ok || !data.success) {
            throw new Error(data.message || "Request failed");
        }

        return data;
    } catch (error) {
        console.error("API Error:", error);
        showToast("error", "Error!", error.message || "Terjadi kesalahan");
        throw error;
    }
}

// ===== LOAD DATA FUNCTIONS =====

// Load all tasks from API
async function loadTasksFromAPI(filters = {}) {
    try {
        const params = new URLSearchParams(filters);
        const data = await apiRequest(`/tasks?${params.toString()}`);

        console.log("✅ Tasks loaded from API:", data.tasks);
        console.log("📊 Stats:", data.stats);

        // Update global tasks variable dengan format yang sesuai untuk frontend
        tasks = data.tasks;

        // Update stats
        if (data.stats) {
            updateOverdueStatusFromAPI(data.stats.overdue_count);
        }

        filteredTasks = [...tasks];
        renderTasks();

        return tasks;
    } catch (error) {
        console.error("Error loading tasks:", error);
        return [];
    }
}

// Load users from API
async function loadUsersFromAPI() {
    try {
        const data = await apiRequest("/users");
        users = normalizeUserIds(data.users);
        userNames = Object.keys(users);
        return users;
    } catch (error) {
        console.error("Error loading users:", error);
        return {};
    }
}

function normalizeUserIds(usersArray) {
    const normalized = {};
    usersArray.forEach((user) => {
        const userId = user.id.toString(); // Pastikan ID sebagai string
        normalized[userId] = {
            name: user.name,
            avatar: user.avatar || user.name.substring(0, 2).toUpperCase(),
            color: user.color || "#4299e1",
        };
    });
    return normalized;
}

// Load bidangs from API
let bidangsMap = {};
async function loadBidangsFromAPI() {
    try {
        const data = await apiRequest("/bidangs");

        // Create bidang map: slug -> id
        bidangsMap = {};
        data.bidangs.forEach((bidang) => {
            bidangsMap[bidang.slug] = bidang.id;
        });

        return data.bidangs;
    } catch (error) {
        console.error("Error loading bidangs:", error);
        return [];
    }
}

// New function to render bidang options
function renderBidangOptions(bidangs) {
    const container = document.getElementById("bidangContainer");
    if (!container) return;

    container.innerHTML = bidangs
        .map(
            (bidang) => `
        <input type="radio" id="bidang-${bidang.slug}" class="bidang-checkbox" name="bidang" value="${bidang.slug}" />
        <label for="bidang-${bidang.slug}" class="bidang-option ${bidang.slug}">${bidang.name}</label>
    `
        )
        .join("");
}

// Helper to get bidang ID by slug
function getBidangIdBySlug(slug) {
    return bidangsMap[slug] || null;
}

// ===== TASK CRUD FUNCTIONS =====

// Create new task via API
async function createTaskAPI(taskData) {
    console.log("📝 Creating task with data:", taskData);
    console.log("📋 Subtasks to create:", taskData.subtasks);

    const payload = {
        title: taskData.title,
        description: taskData.description,
        deadline: taskData.deadline,
        priority: taskData.priority,
        bidang_id: getBidangIdBySlug(taskData.bidang),
        assigned_users: taskData.assignedUsers.map((id) => parseInt(id)),
        subtasks: taskData.subtasks.map((st) => {
            console.log("Processing subtask:", st);
            return {
                text: st.text,
                completed: st.completed || false,
                created_by: st.createdBy ? parseInt(st.createdBy) : null,
            };
        }),
    };

    console.log("📤 Payload to send:", payload);

    const response = await apiRequest("/tasks", {
        method: "POST",
        body: JSON.stringify(payload),
    });

    showToast("success", "Berhasil!", response.message);
    await loadTasksFromAPI();
    return response.task;
}

// Update task via API
async function updateTaskAPI(taskId, taskData) {
    console.log("📝 Updating task with data:", taskData);
    console.log("📋 Subtasks to update:", taskData.subtasks);

    const payload = {
        title: taskData.title,
        description: taskData.description,
        deadline: taskData.deadline,
        priority: taskData.priority,
        bidang_id: getBidangIdBySlug(taskData.bidang),
        assigned_users: taskData.assignedUsers.map((id) => parseInt(id)),
        subtasks: taskData.subtasks.map((st) => {
            console.log("Processing subtask:", st);
            return {
                text: st.text,
                completed: st.completed || false,
                created_by: st.createdBy ? parseInt(st.createdBy) : null,
            };
        }),
    };

    console.log("📤 Payload to send:", payload);

    const response = await apiRequest(`/tasks/${taskId}`, {
        method: "PUT",
        body: JSON.stringify(payload),
    });

    showToast("success", "Berhasil!", response.message);
    await loadTasksFromAPI();
    return response.task;
}

// Move task to different column via API
async function moveTaskAPI(
    taskId,
    column,
    pendingReason = null,
    newDeadline = null
) {
    const payload = {
        column: column,
        pending_reason: pendingReason,
        new_deadline: newDeadline,
    };

    const response = await apiRequest(`/tasks/${taskId}/move`, {
        method: "POST",
        body: JSON.stringify(payload),
    });

    showToast("success", "Berhasil!", response.message);
    await loadTasksFromAPI();
    return response.task;
}

// Delete task via API
async function deleteTaskAPI(taskId) {
    const response = await apiRequest(`/tasks/${taskId}`, {
        method: "DELETE",
    });

    showToast("success", "Berhasil!", response.message);
    await loadTasksFromAPI();
}

// Toggle subtask completion via API
async function toggleSubtaskAPI(taskId, subtaskId) {
    const response = await apiRequest(
        `/tasks/${taskId}/subtasks/${subtaskId}/toggle`,
        {
            method: "POST",
        }
    );

    await loadTasksFromAPI();
    return response.subtask;
}

// Add comment via API
async function addCommentAPI(taskId, text, files = []) {
    const formData = new FormData();
    formData.append("text", text || "(File attached)");

    files.forEach((file, index) => {
        formData.append(`attachments[${index}]`, file);
    });

    const response = await apiRequest(`/tasks/${taskId}/comments`, {
        method: "POST",
        body: formData,
    });

    showToast("success", "Berhasil!", response.message);
    await loadTasksFromAPI();
    return response.comment;
}

// ===== OVERRIDE ORIGINAL FUNCTIONS =====

// Override handleTaskSubmit
const originalHandleTaskSubmit = window.handleTaskSubmit;
window.handleTaskSubmit = async function (event) {
    event.preventDefault();

    const title = document.getElementById("taskTitle").value.trim();
    const priority = document.getElementById("taskPriority").value;
    const deadlineISO = document.getElementById("taskDeadline").value;
    const description = document.getElementById("taskDescription").value.trim();
    const bidang =
        document.querySelector(".bidang-checkbox:checked")?.value || null;
    const assignedUsers = taskAssignedUsers;

    if (title && priority && deadlineISO && description && bidang) {
        const today = new Date().toISOString().split("T")[0];
        if (deadlineISO < today) {
            showToast(
                "error",
                "Error!",
                "Deadline tidak boleh tanggal yang sudah lewat."
            );
            return;
        }

        if (assignedUsers.length === 0) {
            showToast(
                "warning",
                "Peringatan!",
                "Mohon tetapkan setidaknya satu user pada task ini."
            );
            return;
        }

        const taskData = {
            title,
            priority,
            deadline: deadlineISO,
            description,
            bidang,
            assignedUsers,
            subtasks: [...currentSubtasks],
        };

        try {
            if (editingTaskId) {
                await updateTaskAPI(editingTaskId, taskData);
            } else {
                await createTaskAPI(taskData);
            }
            closeTaskModal();
        } catch (error) {
            console.error("Error saving task:", error);
        }
    } else {
        showToast("warning", "Peringatan!", "Semua field wajib diisi!");
    }
};

// Override deleteTask
const originalDeleteTask = window.deleteTask;
window.deleteTask = async function (taskId) {
    showConfirm(
        "Hapus Task",
        "Apakah Anda yakin ingin menghapus tugas ini? Tindakan ini tidak dapat dibatalkan.",
        async () => {
            try {
                await deleteTaskAPI(taskId);
            } catch (error) {
                console.error("Error deleting task:", error);
            }
        },
        "danger"
    );
};

// Override toggleSubtask
const originalToggleSubtask = window.toggleSubtask;
window.toggleSubtask = async function (taskId, subtaskId) {
    try {
        await toggleSubtaskAPI(taskId, subtaskId);
    } catch (error) {
        console.error("Error toggling subtask:", error);
    }
};

// Override handleDrop for drag and drop
const originalHandleDrop = window.handleDrop;
window.handleDrop = async function (event, columnId) {
    event.preventDefault();

    const columns = document.querySelectorAll(".column");
    columns.forEach((col) => col.classList.remove("drop-zone"));

    if (draggedTask) {
        const isReactivating =
            draggedTask.column === "overdue" &&
            ["product-backlog", "in-progress", "pending"].includes(columnId);

        if (isReactivating) {
            taskToReactivateId = draggedTask.id;
            targetColumnForReactivation = columnId;
            openReactivationModal(columnId === "pending");
            return;
        }

        if (columnId === "pending" && draggedTask.column !== "pending") {
            pendingTaskId = draggedTask.id;
            openPendingModal();
            return;
        }

        // Move task via API
        try {
            await moveTaskAPI(draggedTask.id, columnId);
        } catch (error) {
            console.error("Error moving task:", error);
        }
    }

    draggedTask = null;
    const draggingElements = document.querySelectorAll(".dragging");
    draggingElements.forEach((el) => el.classList.remove("dragging"));
};

// Override handlePendingSubmit
const originalHandlePendingSubmit = window.handlePendingSubmit;
window.handlePendingSubmit = async function (event) {
    event.preventDefault();

    const reason = document.getElementById("pendingReasonInput").value.trim();

    if (reason && pendingTaskId) {
        try {
            await moveTaskAPI(pendingTaskId, "pending", reason);
            closePendingModal();
        } catch (error) {
            console.error("Error moving to pending:", error);
        }
    }
};

// Override handleReactivationSubmit
const originalHandleReactivationSubmit = window.handleReactivationSubmit;
window.handleReactivationSubmit = async function (event) {
    event.preventDefault();

    const deadlineISO = document.getElementById("newDeadlineInput").value;
    const isPendingTarget = targetColumnForReactivation === "pending";
    const reason = document
        .getElementById("reactivationPendingReason")
        .value.trim();

    const today = new Date().toISOString().split("T")[0];

    if (!deadlineISO || deadlineISO < today) {
        alert(
            "Mohon masukkan deadline baru yang valid (hari ini atau setelahnya)."
        );
        return;
    }

    if (isPendingTarget && !reason) {
        alert("Mohon masukkan alasan pending.");
        return;
    }

    if (deadlineISO && taskToReactivateId && targetColumnForReactivation) {
        try {
            await moveTaskAPI(
                taskToReactivateId,
                targetColumnForReactivation,
                isPendingTarget ? reason : null,
                deadlineISO
            );
            closeReactivationModal();
        } catch (error) {
            console.error("Error reactivating task:", error);
        }
    }
};

// Override sendMessage for chat
const originalSendMessage = window.sendMessage;
window.sendMessage = async function () {
    const messageText = document.getElementById("messageInput").value.trim();

    if (!messageText && selectedFiles.length === 0) {
        showToast(
            "warning",
            "Peringatan!",
            "Masukkan pesan atau pilih file untuk dikirim"
        );
        return;
    }

    if (!currentChatTaskId) return;

    try {
        // Use default message if no text but has files
        const finalText = messageText || "(File uploaded)";
        await addCommentAPI(currentChatTaskId, finalText, selectedFiles);

        // Reset form
        document.getElementById("messageInput").value = "";
        selectedFiles = [];
        updateSelectedFiles();

        // Re-render chat messages
        const task = tasks.find((t) => t.id === currentChatTaskId);
        if (task) {
            renderChatMessages();
        }
    } catch (error) {
        console.error("Error sending message:", error);
    }
};

// Override filterTasks to work with API data
const originalFilterTasks = window.filterTasks;
window.filterTasks = function () {
    const searchTerm =
        document.getElementById("searchInput")?.value?.toLowerCase() || "";
    const priorityFilter =
        document.getElementById("priorityFilter")?.value || "";
    const bidangFilter = document.getElementById("bidangFilter")?.value || "";
    const userFilterKey = selectedUserFilter;

    filteredTasks = tasks.filter((task) => {
        // Filter by priority
        if (priorityFilter && task.priority !== priorityFilter) return false;

        // Filter by bidang
        if (bidangFilter && task.bidang !== bidangFilter) return false;

        // Filter by assigned user
        if (userFilterKey) {
            if (
                !task.assignedUsers ||
                !task.assignedUsers.includes(parseInt(userFilterKey))
            ) {
                return false;
            }
        }

        // Filter by search term
        if (searchTerm) {
            const inTitle = task.title?.toLowerCase().includes(searchTerm);
            const inDesc = task.description?.toLowerCase().includes(searchTerm);
            const inDeadline = task.deadline
                ?.toLowerCase()
                .includes(searchTerm);

            const inSubtask = (task.subtasks || []).some((st) =>
                st.text?.toLowerCase().includes(searchTerm)
            );

            const inUser = (task.assignedUsers || []).some((uid) => {
                const user = users[uid.toString()];
                if (!user) return false;
                return (
                    uid.toString().toLowerCase().includes(searchTerm) ||
                    user.name.toLowerCase().includes(searchTerm)
                );
            });

            if (!(inTitle || inDesc || inDeadline || inSubtask || inUser))
                return false;
        }

        return true;
    });

    renderTasks();
    updateOverdueStatus();
};

// Helper function to update overdue status from API
function updateOverdueStatusFromAPI(overdueCount) {
    const statusElement = document.getElementById("overdueStatus");

    if (overdueCount > 0) {
        statusElement.textContent = `${overdueCount} tugas overdue`;
        statusElement.className = "overdue-status danger";
    } else {
        statusElement.textContent = "No task overdue";
        statusElement.className = "overdue-status success";
    }
}

// ===== INITIALIZATION =====

// Initialize app with API data
async function initializeKanbanApp() {
    try {
        // Show loading state
        const loadingToast = showToast(
            "info",
            "Loading...",
            "Memuat data kanban..."
        );

        // Load master data first
        const [usersData, bidangsData] = await Promise.all([
            loadUsersFromAPI(),
            loadBidangsFromAPI(),
        ]);

        // Render bidang options
        renderBidangOptions(bidangsData);

        // Then load tasks
        await loadTasksFromAPI();

        // Remove loading toast
        if (loadingToast) {
            removeToast(loadingToast);
        }

        console.log("Kanban app initialized successfully");
    } catch (error) {
        console.error("Initialization error:", error);
        showToast(
            "error",
            "Error!",
            "Gagal memuat data. Silakan refresh halaman."
        );
    }
}

// Wait for DOM and original kanban.js to load
function waitForKanbanJS() {
    // Check if critical functions from kanban.js exist
    if (
        typeof showToast !== "undefined" &&
        typeof renderTasks !== "undefined"
    ) {
        initializeKanbanApp();
    } else {
        // Retry after a short delay
        setTimeout(waitForKanbanJS, 50);
    }
}

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", waitForKanbanJS);
} else {
    waitForKanbanJS();
}

// Auto-refresh every 5 minutes
setInterval(() => {
    loadTasksFromAPI();
}, 300000);

// Export API functions for debugging
window.kanbanAPI = {
    loadTasks: loadTasksFromAPI,
    createTask: createTaskAPI,
    updateTask: updateTaskAPI,
    moveTask: moveTaskAPI,
    deleteTask: deleteTaskAPI,
    toggleSubtask: toggleSubtaskAPI,
    addComment: addCommentAPI,
    loadUsers: loadUsersFromAPI,
    loadBidangs: loadBidangsFromAPI,
    initialize: initializeKanbanApp,
};

console.log("Kanban Integration loaded. API available at window.kanbanAPI");
