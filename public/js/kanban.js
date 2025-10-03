// Global users object (will be populated from API)
let users = {};

// Enhanced tasks data with new fields
let tasks = [];

// --- NEW GLOBAL STATE FOR USER FILTER ---
let selectedUserFilter = "";
let userNames = []; // Array of user keys for filtering (will be populated from API)
// ----------------------------------------

// Helper function to get initials/avatar (if not already present)
function getAvatarHtml(userKey) {
    const user = users[userKey];
    if (!user)
        return '<div class="user-avatar-small" style="background: #a0aec0;">?</div>';
    return `<div class="user-avatar-small" style="background: ${user.color};">${user.avatar}</div>`;
}

// ... (lanjutkan dengan kode kanban.js yang sudah ada)

let draggedTask = null;
let nextTaskId = 6;
let nextSubtaskId = 10;
let editingTaskId = null;
let currentSubtasks = [];
let filteredTasks = [...tasks];
let pendingTaskId = null; // For Pending Modal (non-overdue source)

// --- NEW GLOBAL STATE FOR ASSIGNED USERS WITH SEARCH ---
let taskAssignedUsers = [];
// ------------------------------------------------------

let taskToReactivateId = null;
let targetColumnForReactivation = null;

// Priority icons
const priorityIcons = {
    high: "🔥",
    medium: "⬆️",
    low: "➡️",
};

// Bidang colors (for display in task card)
const bidangClasses = {
    aptika: "bidang-aptika",
    sarkom: "bidang-sarkom",
    sekretariat: "bidang-sekretariat",
};

// Toast Notification System
let toastCounter = 0;

// Format date function (For Display: 20 Sep 2024)
function formatDate(dateString) {
    const options = {
        day: "2-digit",
        month: "short",
        year: "numeric",
    };
    // Use local date string to avoid timezone issues for display
    return new Date(dateString).toLocaleDateString("id-ID", options);
}

// Check and update overdue tasks
function updateOverdueTasks() {
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    tasks.forEach((task) => {
        const taskDeadline = new Date(task.deadline);
        taskDeadline.setHours(0, 0, 0, 0);

        if (
            taskDeadline < today &&
            task.column !== "done" &&
            task.column !== "overdue"
        ) {
            task.column = "overdue";
            task.pendingReason = null; // Clear pending reason if it was set
        }
        // Jika deadline diperbarui melalui modal baru, ini akan diatasi di handleReactivationSubmit
    });
}

// Update Overdue Status in header
function updateOverdueStatus() {
    const overdueCount = tasks.filter(
        (task) => task.column === "overdue"
    ).length;
    const statusElement = document.getElementById("overdueStatus");

    if (overdueCount > 0) {
        statusElement.textContent = `${overdueCount} tugas overdue`;
        statusElement.className = "overdue-status danger";
    } else {
        statusElement.textContent = "No task overdue";
        statusElement.className = "overdue-status success";
    }
}

// Filter tasks based on search and filters
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

        // Filter by assigned user - PERBAIKAN DI SINI
        if (userFilterKey) {
            // Normalize assignedUsers to string for comparison
            const assignedUserIds = (task.assignedUsers || []).map((id) =>
                id.toString()
            );
            if (!assignedUserIds.includes(userFilterKey)) {
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

            // PERBAIKAN: Normalize user IDs for search
            const inUser = (task.assignedUsers || []).some((uid) => {
                const userKey = uid.toString();
                const user = users[userKey];
                if (!user) return false;
                return (
                    userKey.toLowerCase().includes(searchTerm) ||
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

// Create task card HTML
function createTaskCard(task) {
    console.log(
        "Creating card for task:",
        task.id,
        "Attachments:",
        task.attachments
    );
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    const taskDeadline = new Date(task.deadline);
    taskDeadline.setHours(0, 0, 0, 0);

    const isOverdue = taskDeadline < today && task.column !== "done";
    const overdueClass = isOverdue ? "overdue-task" : "";

    // Calculate subtask progress
    const completedSubtasks =
        task.subtasks?.filter((st) => st.completed).length || 0;
    const totalSubtasks = task.subtasks?.length || 0;

    // Generate bidang HTML
    const bidangHTML = task.bidang
        ? `<span class="task-bidang-label ${bidangClasses[task.bidang]}">${
              task.bidang
          }</span>`
        : "";

    // Generate assigned users HTML (max 3 avatars + counter)
    const maxVisibleUsers = 3;
    const assignedUsers = task.assignedUsers || [];
    const visibleUsers = assignedUsers.slice(0, maxVisibleUsers);
    const hiddenUsersCount = assignedUsers.length - maxVisibleUsers;

    const assignedUsersHTML =
        assignedUsers.length > 0
            ? visibleUsers
                  .map((userId) => {
                      const user = users[userId];
                      if (user) {
                          return `<div class="assigned-user-avatar" style="background: ${user.color}" title="${user.name}">${user.avatar}</div>`;
                      }
                      return "";
                  })
                  .join("") +
              (hiddenUsersCount > 0
                  ? `<div class="user-counter" title="${hiddenUsersCount} user lainnya">+${hiddenUsersCount}</div>`
                  : "")
            : "";

    // Generate subtasks HTML
    const subtasksHTML =
        task.subtasks
            ?.slice(0, 3)
            .map(
                (subtask) =>
                    `<div class="subtask-item">
                    <input type="checkbox" class="subtask-checkbox" ${
                        subtask.completed ? "checked" : ""
                    } 
                           onchange="toggleSubtask(${task.id}, ${subtask.id})">
                    <span class="subtask-text ${
                        subtask.completed ? "completed" : ""
                    }">${subtask.text}</span>
                </div>`
            )
            .join("") || "";

    // Pending reason display
    const pendingReasonHTML =
        task.column === "pending" && task.pendingReason
            ? `<div style="font-size: 11px; color: #b7791f; margin-bottom: 8px;">
                    <span style="font-weight: bold;">Alasan Pending:</span> ${task.pendingReason}
                 </div>`
            : "";
    const attachmentsHTML =
        task.attachments && task.attachments.length > 0
            ? `<div class="task-attachments">
        ${task.attachments
            .slice(0, 3)
            .map(
                (attachment) =>
                    `<div class="attachment-item" title="${attachment.name} (${attachment.size})">
                <svg class="attachment-icon" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                </svg>
                ${attachment.name}
            </div>`
            )
            .join("")}
        ${
            task.attachments.length > 3
                ? `<span class="attachment-item">+${
                      task.attachments.length - 3
                  } lagi</span>`
                : ""
        }
       </div>`
            : "";

    // Generate recent comments HTML - FIXED: Show latest comment at the bottom
    const commentsHTML =
        task.comments && task.comments.length > 0
            ? `<div class="chat-comments">
    ${task.comments
        .slice()
        .sort((a, b) => new Date(a.createdAt) - new Date(b.createdAt))
        .slice(-2)
        .map(
            (comment) =>
                `<div class="chat-comment">
            <span class="chat-author">${
                users[comment.author]?.name || comment.author
            }:</span>
            ${comment.text.substring(0, 50)}${
                    comment.text.length > 50 ? "..." : ""
                }
        </div>`
        )
        .join("")}
   </div>`
            : "";

    // Calculate total attachments from task attachments and comment attachment
    const totalAttachments =
        (task.attachments?.length || 0) +
        (task.comments?.reduce(
            (sum, comment) => sum + (comment.attachments?.length || 0),
            0
        ) || 0);

    // Chat section
    const chatSectionHTML =
        totalAttachments > 0 || (task.comments && task.comments.length > 0)
            ? `<div class="task-chat-section">
        ${attachmentsHTML}
        ${commentsHTML}
        <button class="chat-toggle" onclick="openChatModal(${task.id})">
            ${task.comments?.length || 0} komentar • ${totalAttachments} file
        </button>
       </div>`
            : `<div class="task-chat-section">
        <button class="chat-toggle" onclick="openChatModal(${task.id})">Tambah komentar atau file</button>
       </div>`;

    return `
                <div class="task-card ${overdueClass}" draggable="true" data-task-id="${
        task.id
    }" 
                     ondragstart="handleDragStart(event, ${task.id})">
                    <div class="task-header">
                        <div class="task-title">${task.title}</div>
                        <div class="task-priority priority-${task.priority}">
                            ${priorityIcons[task.priority] || ""}
                        </div>
                    </div>
                    
                    <div class="task-meta">
                        <div class="task-deadline">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            ${formatDate(task.deadline)}
                            ${
                                isOverdue && task.column !== "overdue"
                                    ? '<span style="color: #c53030; font-weight: bold; margin-left: 4px;">OVERDUE!</span>'
                                    : ""
                            }
                        </div>
                        
                        <div class="task-bidang">
                            ${bidangHTML}
                        </div>
                    </div>
                    
                    <div class="task-description">${task.description}</div>
                    
                    ${chatSectionHTML}

                    ${pendingReasonHTML}

                    ${
                        totalSubtasks > 0
                            ? `
                                    <div class="subtasks-container">
                                        ${subtasksHTML}
                                        ${
                                            totalSubtasks > 3
                                                ? `<div class="subtask-item" style="font-style: italic;">...dan ${
                                                      totalSubtasks - 3
                                                  } lainnya</div>`
                                                : ""
                                        }
                                    </div>
                                `
                            : ""
                    }
                    
                    <div class="task-actions">
                        <div class="task-assigned-users">
                            ${assignedUsersHTML}
                        </div>
                        <div class="subtask-progress">
                            ${
                                totalSubtasks > 0
                                    ? `${completedSubtasks}/${totalSubtasks} subtasks`
                                    : ""
                            }
                        </div>
                        <div class="task-buttons">
                            <button class="edit-btn" onclick="openEditTaskModal(${
                                task.id
                            })" title="Edit Task">
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                </svg>
                            </button>
                            <button class="delete-btn" onclick="deleteTask(${
                                task.id
                            })" title="Hapus Task">
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd"/>
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            `;
}

// Render tasks in columns
function renderTasks() {
    updateOverdueTasks();

    const columns = [
        "product-backlog",
        "in-progress",
        "pending",
        "done",
        "overdue",
    ];

    columns.forEach((columnId) => {
        const container = document.getElementById(columnId + "-tasks");
        const countElement = document.getElementById(columnId + "-count");
        const columnTasks = filteredTasks.filter(
            (task) => task.column === columnId
        );

        // Update task count
        countElement.textContent = columnTasks.length;

        if (columnTasks.length === 0) {
            container.innerHTML =
                '<div class="empty-state">Belum ada tugas</div>';
        } else {
            container.innerHTML = columnTasks
                .map((task) => createTaskCard(task))
                .join("");
        }
    });
    updateOverdueStatus(); // Update status after rendering
}

// --- ASSIGN USER SEARCH LOGIC ---

/**
 * Logic baru untuk menangani klik user pada list yang terfilter.
 * Ini akan memilih user, mengosongkan search bar, dan me-render ulang list.
 */
function handleUserSelection(userId) {
    const user = users[userId];

    // Toggle user in global state
    const index = taskAssignedUsers.indexOf(userId);
    if (index > -1) {
        taskAssignedUsers.splice(index, 1);
        console.log(`❌ ${user.name} removed`);
    } else {
        taskAssignedUsers.push(userId);
        console.log(`✅ ${user.name} added`);
    }

    // Clear search input
    const searchInput = document.getElementById("userSearchInput");
    if (searchInput) {
        searchInput.value = "";
        searchInput.placeholder = `${user.name} dipilih...`; // Feedback visual
    }

    // Re-render dengan search kosong
    renderAssignedUsers("");

    // Reset placeholder dan fokus setelah delay
    setTimeout(() => {
        if (searchInput) {
            searchInput.placeholder = "Cari nama user...";
            searchInput.focus();
        }
    }, 1000); // Reset placeholder setelah 1 detik
}

// Render assigned users in modal, filtered by search term
function renderAssignedUsers(searchTerm = "") {
    const container = document.getElementById("assignedUsersContainer");
    if (!container) {
        console.error("❌ assignedUsersContainer not found!");
        return;
    }

    // Apply 3-column layout
    container.className = "users-container three-columns";

    const searchTermLower = searchTerm.toLowerCase().trim();
    let html = "";
    let matchCount = 0;

    Object.keys(users).forEach((userId) => {
        const user = users[userId];
        const isChecked = taskAssignedUsers.includes(userId);

        if (
            searchTermLower === "" ||
            user.name.toLowerCase().includes(searchTermLower) ||
            userId.toLowerCase().includes(searchTermLower)
        ) {
            matchCount++;
            html += `
                <div class="user-option compact ${isChecked ? "selected" : ""}" 
                     onclick="handleUserSelection('${userId}')">
                    <div class="user-option-avatar" style="background: ${
                        user.color
                    };">
                        ${user.avatar}
                    </div>
                    <span class="user-option-name">${user.name}</span>
                    ${isChecked ? '<span class="checkmark">✓</span>' : ""}
                </div>
            `;
        }
    });

    if (matchCount === 0) {
        html =
            '<div style="padding: 12px; color: #718096; text-align: center; grid-column: 1 / -1;">Tidak ada user yang cocok</div>';
    }

    container.innerHTML = html;
}

// Handler for the search input with debouncing for better performance
let searchDebounceTimer = null;
function filterAssignedUsers(searchValue = "") {
    console.log("🔍 filterAssignedUsers called with:", searchValue);

    // Clear previous timer
    if (searchDebounceTimer) {
        clearTimeout(searchDebounceTimer);
    }

    // Set new timer for smoother search
    searchDebounceTimer = setTimeout(() => {
        renderAssignedUsers(searchValue);
    }, 150);
}

// OLD toggleUserAssignment REMOVED and logic moved to handleUserSelection

// --- END ASSIGN USER SEARCH LOGIC ---

// Toggle subtask completion
function toggleSubtask(taskId, subtaskId) {
    const task = tasks.find((t) => t.id === taskId);
    if (task) {
        const subtask = task.subtasks.find((st) => st.id === subtaskId);
        if (subtask) {
            subtask.completed = !subtask.completed;
            renderTasks();
        }
    }
}

// Drag and drop functions
function handleDragStart(event, taskId) {
    draggedTask = tasks.find((task) => task.id === taskId);
    event.target.classList.add("dragging");
    event.dataTransfer.effectAllowed = "move";
}

function handleDragOver(event) {
    event.preventDefault();
    event.dataTransfer.dropEffect = "move";

    const column = event.currentTarget;
    if (!column.classList.contains("drop-zone")) {
        column.classList.add("drop-zone");
    }
}

function handleDrop(event, columnId) {
    event.preventDefault();

    const columns = document.querySelectorAll(".column");
    columns.forEach((col) => col.classList.remove("drop-zone"));

    if (draggedTask) {
        const taskIndex = tasks.findIndex((task) => task.id === draggedTask.id);
        if (taskIndex !== -1) {
            const isReactivating =
                draggedTask.column === "overdue" &&
                ["product-backlog", "in-progress", "pending"].includes(
                    columnId
                );

            // 1. Cek jika tugas dipindahkan dari Overdue ke kolom aktif manapun
            if (isReactivating) {
                taskToReactivateId = draggedTask.id;
                targetColumnForReactivation = columnId;
                // openReactivationModal(true) jika target pending, openReactivationModal(false) untuk backlog/in-progress
                openReactivationModal(columnId === "pending");
                return; // Hentikan logika drop standar
            }

            // 2. Logika yang sudah ada untuk Pending (non-overdue source)
            if (columnId === "pending" && draggedTask.column !== "pending") {
                pendingTaskId = draggedTask.id;
                openPendingModal();
                return;
            }

            // 3. Logika untuk Done
            if (columnId === "done" && draggedTask.column !== "done") {
                tasks[taskIndex].completedAt = new Date()
                    .toISOString()
                    .split("T")[0];
                tasks[taskIndex].pendingReason = null; // Clear pending reason
            }

            // Jika memindahkan dari 'pending' ke kolom lain, hapus alasan pending
            if (draggedTask.column === "pending" && columnId !== "pending") {
                tasks[taskIndex].pendingReason = null;
            }

            // Perbarui kolom untuk semua kasus non-pending dan non-reactivating
            if (columnId !== "pending" && !isReactivating) {
                tasks[taskIndex].column = columnId;
                filterTasks();
            }
        }
    }

    draggedTask = null;

    const draggingElements = document.querySelectorAll(".dragging");
    draggingElements.forEach((el) => el.classList.remove("dragging"));
}

// Delete task function
function deleteTask(taskId) {
    showConfirm(
        "Hapus Task",
        "Apakah Anda yakin ingin menghapus tugas ini? Tindakan ini tidak dapat dibatalkan.",
        () => {
            tasks = tasks.filter((task) => task.id !== taskId);
            filterTasks();
            showToast("success", "Berhasil!", "Task berhasil dihapus");
        },
        "danger"
    );
}

// --- Task Modal functions ---
function openAddTaskModal() {
    editingTaskId = null;
    currentSubtasks = [];

    document.getElementById("modalTitle").textContent = "Tambah Task Baru";
    document.getElementById("submitBtn").textContent = "Tambah Task";
    document.getElementById("taskModal").classList.add("active");
    document.body.style.overflow = "hidden";

    // Reset form
    document.getElementById("taskForm").reset();
    updateSubtasksList();

    // Set min date for new tasks
    document
        .getElementById("taskDeadline")
        .setAttribute("min", new Date().toISOString().split("T")[0]);

    // Reset assigned user search and list state
    taskAssignedUsers = [];
    const searchInput = document.getElementById("userSearchInput");
    if (searchInput) {
        searchInput.value = "";
    }

    // Wait for users to be loaded before rendering
    if (Object.keys(users).length === 0) {
        console.log("Users not loaded yet, loading now...");
        loadUsersFromAPI().then(() => {
            renderAssignedUsers();
        });
    } else {
        renderAssignedUsers();
    }

    // Uncheck all user checkboxes and reset bidang radio
    document
        .querySelectorAll(".bidang-checkbox")
        .forEach((radio) => (radio.checked = false));

    setTimeout(() => {
        document.getElementById("taskTitle").focus();
    }, 100);
}

function openEditTaskModal(taskId) {
    editingTaskId = taskId;
    const task = tasks.find((t) => t.id === taskId);

    if (!task) return;

    document.getElementById("modalTitle").textContent = "Edit Task";
    document.getElementById("submitBtn").textContent = "Update Task";
    document.getElementById("taskModal").classList.add("active");
    document.body.style.overflow = "hidden";

    // Fill form with task data
    document.getElementById("taskTitle").value = task.title;
    document.getElementById("taskPriority").value = task.priority;
    // Set value directly since input type="date" uses YYYY-MM-DD
    document.getElementById("taskDeadline").value = task.deadline || "";
    document.getElementById("taskDescription").value = task.description;

    // Set min date (cannot be before today)
    document
        .getElementById("taskDeadline")
        .setAttribute("min", new Date().toISOString().split("T")[0]);

    // Set bidang
    document.querySelectorAll(".bidang-checkbox").forEach((radio) => {
        radio.checked = task.bidang === radio.value;
    });

    // Set assigned users state and render the list
    console.log("Edit task - assigned users:", task.assignedUsers);
    taskAssignedUsers = Array.isArray(task.assignedUsers)
        ? task.assignedUsers.map((id) => id.toString())
        : [];
    console.log("taskAssignedUsers set to:", taskAssignedUsers);

    const searchInput = document.getElementById("userSearchInput");
    if (searchInput) {
        searchInput.value = "";
    }
    renderAssignedUsers();

    // Set subtasks - pastikan createdBy di-preserve
    currentSubtasks = (task.subtasks || []).map((subtask) => ({
        id: subtask.id,
        text: subtask.text,
        completed: subtask.completed,
        createdBy: subtask.createdBy ? subtask.createdBy.toString() : null,
        creatorName: subtask.creatorName || null,
    }));

    console.log("Loaded subtasks for edit:", currentSubtasks);
    updateSubtasksList();

    setTimeout(() => {
        document.getElementById("taskTitle").focus();
    }, 100);
}

function closeTaskModal() {
    document.getElementById("taskModal").classList.remove("active");
    document.body.style.overflow = "auto";
    document.getElementById("taskForm").reset();
    currentSubtasks = [];
    editingTaskId = null;
    taskAssignedUsers = []; // Clear state on close
}

// --- Pending Modal functions (non-overdue source) ---
function openPendingModal() {
    document.getElementById("pendingReasonInput").value = "";
    document.getElementById("pendingModal").classList.add("active");
    document.body.style.overflow = "hidden";
    setTimeout(() => {
        document.getElementById("pendingReasonInput").focus();
    }, 100);
}

function closePendingModal() {
    document.getElementById("pendingModal").classList.remove("active");
    document.body.style.overflow = "auto";
    pendingTaskId = null;
    filterTasks(); // Re-render to clear drop-zone on cancel
}

function handlePendingSubmit(event) {
    event.preventDefault();

    const reason = document.getElementById("pendingReasonInput").value.trim();

    if (reason && pendingTaskId) {
        const taskIndex = tasks.findIndex((t) => t.id === pendingTaskId);
        if (taskIndex !== -1) {
            tasks[taskIndex].column = "pending";
            tasks[taskIndex].pendingReason = reason;
            tasks[taskIndex].completedAt = null; // Clear completion date
            showToast(
                "success",
                "Berhasil!",
                "Task berhasil dipindahkan ke Pending"
            );
        }
        closePendingModal();
    }
}

// --- REACTIVATION MODAL FUNCTIONS (Overdue source) ---
function openReactivationModal(requiresPendingReason) {
    const today = new Date().toISOString().split("T")[0];

    // Atur nilai default dan batasan tanggal (min: hari ini)
    document.getElementById("newDeadlineInput").value = today;
    document.getElementById("newDeadlineInput").setAttribute("min", today);

    // Conditional display of Pending Reason field
    const reasonGroup = document.getElementById("pendingReasonGroup");
    const reasonInput = document.getElementById("reactivationPendingReason");

    if (requiresPendingReason) {
        reasonGroup.style.display = "block";
        reasonInput.setAttribute("required", "true");
        document.getElementById("reactivationSubmitBtn").textContent =
            "Pindahkan ke Pending & Simpan";
    } else {
        reasonGroup.style.display = "none";
        reasonInput.removeAttribute("required");
        document.getElementById("reactivationSubmitBtn").textContent =
            "Pindahkan & Simpan";
    }

    document.getElementById("reactivationModal").classList.add("active");
    document.body.style.overflow = "hidden";

    setTimeout(() => {
        document.getElementById("newDeadlineInput").focus();
    }, 100);
}

function closeReactivationModal() {
    document.getElementById("reactivationModal").classList.remove("active");
    document.body.style.overflow = "auto";
    taskToReactivateId = null;
    targetColumnForReactivation = null;
    // Reset pending reason fields
    document.getElementById("reactivationPendingReason").value = "";
    filterTasks(); // Re-render untuk membatalkan efek drag
}

function handleReactivationSubmit(event) {
    event.preventDefault();

    const deadlineISO = document.getElementById("newDeadlineInput").value; // YYYY-MM-DD
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
        const taskIndex = tasks.findIndex((t) => t.id === taskToReactivateId);

        if (taskIndex !== -1) {
            // Update task properties
            tasks[taskIndex].column = targetColumnForReactivation;
            tasks[taskIndex].deadline = deadlineISO;
            tasks[taskIndex].completedAt = null;

            // Set pending reason if target is pending, otherwise clear it
            tasks[taskIndex].pendingReason = isPendingTarget ? reason : null;

            const newDeadlineDisplay = formatDate(deadlineISO); // Get display format from function
            const message = isPendingTarget
                ? `Task dipindahkan ke Pending, deadline diperbarui menjadi ${newDeadlineDisplay}, dengan alasan: ${reason.substring(
                      0,
                      50
                  )}...`
                : `Deadline diperbarui menjadi ${newDeadlineDisplay} dan task dipindahkan ke ${targetColumnForReactivation.replace(
                      "-",
                      " "
                  )}!`;

            showToast("success", "Berhasil!", message);
        }
        closeReactivationModal();
    }
}
// --- END REACTIVATION MODAL FUNCTIONS ---

// Subtask management
function addSubtask() {
    const input = document.getElementById("subtaskInput");
    const text = input.value.trim();

    if (text) {
        const currentUserId = window.currentUser
            ? window.currentUser.id.toString()
            : "1";

        currentSubtasks.push({
            id: nextSubtaskId++,
            text: text,
            completed: false,
            createdBy: currentUserId, // String ID
        });
        input.value = "";
        updateSubtasksList();
    }
}

function removeSubtask(subtaskId) {
    currentSubtasks = currentSubtasks.filter((st) => st.id !== subtaskId);
    updateSubtasksList();
}

function updateSubtasksList() {
    const container = document.getElementById("subtaskList");
    if (currentSubtasks.length === 0) {
        container.innerHTML =
            '<div style="text-align: center; color: #a0aec0; font-style: italic; padding: 8px;">Belum ada subtask</div>';
        return;
    }

    container.innerHTML = currentSubtasks
        .map((subtask) => {
            let creatorDisplay = "";

            // Priority 1: Gunakan users object jika createdBy ada
            if (subtask.createdBy && users[subtask.createdBy]) {
                creatorDisplay = ` (${users[subtask.createdBy].name})`;
            }
            // Priority 2: Gunakan creatorName dari backend
            else if (subtask.creatorName && subtask.creatorName !== "Unknown") {
                creatorDisplay = ` (${subtask.creatorName})`;
            }
            // Priority 3: Jika baru dibuat (belum ada di DB)
            else if (
                subtask.createdBy &&
                window.currentUser &&
                subtask.createdBy === window.currentUser.id.toString()
            ) {
                creatorDisplay = ` (${window.currentUser.name})`;
            }

            return `
                <div class="subtask-edit-item">
                    <input type="checkbox" ${
                        subtask.completed ? "checked" : ""
                    } 
                           onchange="currentSubtasks.find(st => st.id === ${
                               subtask.id
                           }).completed = this.checked">
                    <span class="subtask-edit-text">${
                        subtask.text
                    }${creatorDisplay}</span>
                    <button type="button" class="remove-subtask-btn" onclick="removeSubtask(${
                        subtask.id
                    })">×</button>
                </div>
            `;
        })
        .join("");
}

// Add/Update task function
function handleTaskSubmit(event) {
    event.preventDefault();

    const title = document.getElementById("taskTitle").value.trim();
    const priority = document.getElementById("taskPriority").value;
    const deadlineISO = document.getElementById("taskDeadline").value; // Already YYYY-MM-DD
    const description = document.getElementById("taskDescription").value.trim();

    // Get selected bidang (radio button)
    const bidang =
        document.querySelector(".bidang-checkbox:checked")?.value || null;

    // Get selected assigned users from the global state (including those hidden by search)
    const assignedUsers = taskAssignedUsers;

    if (title && priority && deadlineISO && description && bidang) {
        // Validation to ensure date is not in the past
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

        if (editingTaskId) {
            // Update existing task
            const taskIndex = tasks.findIndex((t) => t.id === editingTaskId);
            if (taskIndex !== -1) {
                // Keep current column, completedAt, pendingReason
                const currentTask = tasks[taskIndex];

                tasks[taskIndex] = {
                    ...currentTask,
                    title,
                    priority,
                    deadline: deadlineISO,
                    description,
                    bidang,
                    assignedUsers, // Use the global state
                    subtasks: [...currentSubtasks],
                };
            }
            showToast("success", "Berhasil!", "Task berhasil diupdate!");
        } else {
            // Add new task
            const newTask = {
                id: nextTaskId++,
                title,
                priority,
                deadline: deadlineISO,
                column: "product-backlog", // New tasks start in backlog
                description,
                bidang,
                assignedUsers, // Use the global state
                createdAt: new Date().toISOString().split("T")[0],
                completedAt: null,
                pendingReason: null,
                subtasks: [...currentSubtasks],
            };

            tasks.push(newTask);
            showToast("success", "Berhasil!", "Task berhasil ditambahkan!");
        }

        filterTasks();
        closeTaskModal();
    } else {
        showToast("warning", "Peringatan!", "Semua field wajib diisi!");
    }
}

// Function to clear the user filter
function clearUserFilter() {
    selectedUserFilter = "";
    document.getElementById("selectedUserName").textContent = "";
    document.getElementById("userFilterDropdown").style.display = "none";
    document.getElementById("userSearchInput").value = ""; // Clear search input
    renderUserList(); // Re-render to clear selection
    filterTasks(); // Apply task filter
}

// Event listeners
document.addEventListener("DOMContentLoaded", function () {
    const columns = document.querySelectorAll(".column");
    columns.forEach((column) => {
        column.addEventListener("dragleave", function (e) {
            // Check if the drag is truly leaving the column, not just moving to a child element
            if (!column.contains(e.relatedTarget)) {
                column.classList.remove("drop-zone");
            }
        });
    });

    // Form submit listener for Task Modal
    document
        .getElementById("taskForm")
        .addEventListener("submit", handleTaskSubmit);

    // PREVENT ENTER KEY FROM SUBMITTING TASK FORM (hanya untuk input fields kecuali textarea)
    document
        .getElementById("taskForm")
        .addEventListener("keydown", function (e) {
            // Hanya prevent Enter untuk input fields tertentu, bukan untuk textarea dan userSearchInput
            if (
                e.key === "Enter" &&
                !e.shiftKey &&
                e.target.tagName !== "TEXTAREA" &&
                e.target.id !== "subtaskInput" &&
                e.target.id !== "userSearchInput"
            ) {
                e.preventDefault();
            }
        });

    // Form submit listener for Pending Modal - TETAP BISA ENTER
    document
        .getElementById("pendingForm")
        .addEventListener("submit", handlePendingSubmit);

    // Enter key for pending reason input - TETAP BISA ENTER
    const pendingReasonInput = document.getElementById("pendingReasonInput");
    if (pendingReasonInput) {
        pendingReasonInput.addEventListener("keydown", function (e) {
            if (e.key === "Enter" && !e.shiftKey) {
                e.preventDefault();
                document
                    .getElementById("pendingForm")
                    .dispatchEvent(new Event("submit"));
            }
        });
    }

    // Form submit listener for Reactivation Modal
    document
        .getElementById("reactivationForm")
        .addEventListener("submit", handleReactivationSubmit);

    // Close modal when clicking outside
    document
        .getElementById("taskModal")
        .addEventListener("click", function (e) {
            if (e.target === this) {
                closeTaskModal();
            }
        });

    document
        .getElementById("pendingModal")
        .addEventListener("click", function (e) {
            if (e.target === this) {
                closePendingModal();
            }
        });

    document
        .getElementById("reactivationModal")
        .addEventListener("click", function (e) {
            if (e.target === this) {
                closeReactivationModal();
            }
        });

    // Keyboard shortcuts
    document.addEventListener("keydown", function (e) {
        if (
            e.key === "Escape" &&
            document.getElementById("taskModal").classList.contains("active")
        ) {
            closeTaskModal();
        }

        if (
            e.key === "Escape" &&
            document.getElementById("pendingModal").classList.contains("active")
        ) {
            closePendingModal();
        }

        if (
            e.key === "Escape" &&
            document
                .getElementById("reactivationModal")
                .classList.contains("active")
        ) {
            closeReactivationModal();
        }

        if (
            e.shiftKey &&
            e.key.toLowerCase() === "n" &&
            !document.getElementById("taskModal").classList.contains("active")
        ) {
            e.preventDefault();
            openAddTaskModal();
        }
    });

    // Subtask input enter key - TETAP BISA ENTER
    document
        .getElementById("subtaskInput")
        .addEventListener("keypress", function (e) {
            if (e.key === "Enter") {
                e.preventDefault();
                addSubtask();
            }
        });

    // Initial render and periodic check for overdue tasks
    filterTasks();

    // Check for overdue tasks every minute
    setInterval(() => {
        updateOverdueTasks();
        filterTasks();
    }, 60000);

    // Close chat modal when clicking outside
    document
        .getElementById("chatModal")
        .addEventListener("click", function (e) {
            if (e.target === this) {
                closeChatModal();
            }
        });

    // Message input enter key - TETAP BISA ENTER
    const messageInput = document.getElementById("messageInput");
    if (messageInput) {
        messageInput.addEventListener("keydown", function (e) {
            if (e.key === "Enter" && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
    }

    if (document.getElementById("userListContainer")) {
        renderUserList();
    }

    // FIXED: Enter key untuk quick selection user - VERSI DIPERBAIKI DENGAN STOP PROPAGATION
    const searchInput = document.getElementById("userSearchInput"); // Ini untuk modal task
    const filterSearchInput = document.getElementById("userFilterSearchInput"); // Ini untuk filter header

    // Handler untuk user search di modal task
    if (searchInput) {
        searchInput.addEventListener("keydown", function (e) {
            if (e.key === "Enter") {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();

                console.log("Enter pressed in task modal user search");

                // Cari user pertama yang visible di hasil pencarian
                const firstUserOption = document.querySelector(
                    ".user-option.compact:not(.selected)"
                );

                if (firstUserOption) {
                    console.log("Found user option:", firstUserOption);

                    // Ekstrak userId dari atribut onclick
                    const onclickAttr = firstUserOption.getAttribute("onclick");
                    console.log("onclick attribute:", onclickAttr);

                    if (onclickAttr) {
                        let userId = null;
                        const match = onclickAttr.match(
                            /handleUserSelection\('([^']+)'\)/
                        );
                        if (match && match[1]) {
                            userId = match[1];
                        }

                        if (userId) {
                            console.log("Quick selecting user:", userId);
                            handleUserSelection(userId);

                            // Focus kembali ke search input setelah selection
                            setTimeout(() => {
                                searchInput.focus();
                                searchInput.value = ""; // Clear search
                                renderAssignedUsers(""); // Reset filter
                            }, 100);
                        }
                    }
                }
            }
        });

        // Real-time search saat mengetik
        searchInput.addEventListener("input", function (e) {
            filterAssignedUsers(e.target.value);
        });
    }

    // Handler untuk user filter di header (jika ada)
    if (filterSearchInput) {
        filterSearchInput.addEventListener("keydown", function (e) {
            if (e.key === "Enter") {
                e.preventDefault();
                // Untuk filter header, mungkin tidak perlu quick select
                // Biarkan hanya untuk search saja
            }
        });
    }
});

// Remove drop-zone class when drag ends
document.addEventListener("dragend", function () {
    const columns = document.querySelectorAll(".column");
    columns.forEach((col) => col.classList.remove("drop-zone"));

    const draggingElements = document.querySelectorAll(".dragging");
    draggingElements.forEach((el) => el.classList.remove("dragging"));
});

let currentChatTaskId = null;
let selectedFiles = [];
let nextCommentId = 10;
let nextAttachmentId = 10;

// Chat Modal Functions
function openChatModal(taskId) {
    currentChatTaskId = taskId;
    const task = tasks.find((t) => t.id === taskId);

    if (!task) return;

    document.getElementById(
        "chatTaskTitle"
    ).textContent = `Chat & Files - ${task.title}`;
    document.getElementById("chatModal").classList.add("active");
    document.body.style.overflow = "hidden";

    renderChatMessages();
    selectedFiles = [];
    updateSelectedFiles();

    setTimeout(() => {
        document.getElementById("messageInput").focus();
    }, 100);
}

function closeChatModal() {
    document.getElementById("chatModal").classList.remove("active");
    document.body.style.overflow = "auto";
    currentChatTaskId = null;
    selectedFiles = [];
    document.getElementById("messageInput").value = "";
}

function renderChatMessages() {
    const task = tasks.find((t) => t.id === currentChatTaskId);
    const container = document.getElementById("chatMessages");

    if (!task || !task.comments || task.comments.length === 0) {
        container.innerHTML =
            '<div style="text-align: center; color: #a0aec0; padding: 20px;">Belum ada komentar</div>';
        return;
    }

    // Reverse the comments array to show newest at bottom
    const sortedComments = [...task.comments].sort((a, b) => {
        return new Date(a.createdAt) - new Date(b.createdAt);
    });

    container.innerHTML = sortedComments
        .map((comment) => {
            const author = users[comment.author] || {
                name: comment.author,
                color: "#4299e1",
            };
            const attachmentsHTML =
                comment.attachments
                    ?.map(
                        (att) =>
                            `<a href="#" class="message-attachment" onclick="event.preventDefault(); downloadFile('${
                                att.url || "#"
                            }', '${att.name}')">
                <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
                ${att.name}
            </a>`
                    )
                    .join("") || "";

            return `
            <div class="chat-message">
                <div class="message-header">
                    <span class="message-author">${author.name}</span>
                    <span class="message-time">${formatDateTime(
                        comment.createdAt
                    )}</span>
                </div>
                <div class="message-text">${comment.text}</div>
                ${
                    attachmentsHTML
                        ? `<div class="message-attachments">${attachmentsHTML}</div>`
                        : ""
                }
            </div>
        `;
        })
        .join("");

    // Scroll to bottom with slight delay
    setTimeout(() => {
        container.scrollTop = container.scrollHeight;
    }, 100);
}

function sendMessage() {
    const messageText = document.getElementById("messageInput").value.trim();

    if (!messageText && selectedFiles.length === 0) {
        showToast(
            "warning",
            "Peringatan!",
            "Masukkan pesan atau pilih file untuk dikirim"
        );
        return;
    }

    const task = tasks.find((t) => t.id === currentChatTaskId);
    if (!task) return;

    // Initialize comments and attachments arrays if they don't exist
    if (!task.comments) task.comments = [];
    if (!task.attachments) task.attachments = [];

    // Create comment attachments
    const commentAttachments = selectedFiles.map((file) => ({
        id: nextAttachmentId++,
        name: file.name,
        size: formatFileSize(file.size),
        type: file.type,
    }));

    // Add attachments to task
    task.attachments.push(...commentAttachments);

    // Add comment
    const newComment = {
        id: nextCommentId++,
        text: messageText || "(File attached)",
        author: "john", // Current user
        createdAt: new Date(),
        attachments: commentAttachments,
    };

    task.comments.push(newComment);

    // Reset form
    document.getElementById("messageInput").value = "";
    selectedFiles = [];
    updateSelectedFiles();

    // Re-render
    renderChatMessages();
    renderTasks(); // Update task cards
}

function handleFileSelect(event) {
    selectedFiles = Array.from(event.target.files);
    updateSelectedFiles();
}

function updateSelectedFiles() {
    const container = document.getElementById("selectedFiles");
    if (selectedFiles.length === 0) {
        container.textContent = "";
    } else {
        container.textContent = `${
            selectedFiles.length
        } file terpilih: ${selectedFiles.map((f) => f.name).join(", ")}`;
    }
}

function formatFileSize(bytes) {
    if (bytes === 0) return "0 B";
    const k = 1024;
    const sizes = ["B", "KB", "MB", "GB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + " " + sizes[i];
}

function formatDateTime(date) {
    return new Date(date).toLocaleString("id-ID", {
        day: "2-digit",
        month: "short",
        hour: "2-digit",
        minute: "2-digit",
    });
}

function downloadFile(url, filename) {
    // Create temporary link and trigger download
    const link = document.createElement("a");
    link.href = url;
    link.download = filename;
    link.target = "_blank";
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    showToast("info", "Download", `Mengunduh file: ${filename}`);
}

// Function to toggle the user filter dropdown
function toggleUserFilterDropdown() {
    const dropdown = document.getElementById("userFilterDropdown");
    const isVisible = dropdown.style.display === "block";

    if (!isVisible) {
        renderUserList(); // Render the list when opening
        // Add a temporary click listener to close the dropdown when clicking outside
        document.addEventListener("click", closeUserFilterOutside, true);
        dropdown.style.display = "block";
    } else {
        // Remove click listener when closing
        document.removeEventListener("click", closeUserFilterOutside, true);
        dropdown.style.display = "none";
    }
}

// Function to close the dropdown when clicking outside
function closeUserFilterOutside(event) {
    const filterGroup = document.getElementById("userFilterGroup");
    const dropdown = document.getElementById("userFilterDropdown");

    // Cek apakah yang diklik BUKAN bagian dari filter group
    if (filterGroup && !filterGroup.contains(event.target)) {
        dropdown.style.display = "none";
        document.removeEventListener("click", closeUserFilterOutside, true);
    }
}

// Function to render the list of users
function renderUserList(searchQuery = "") {
    const container = document.getElementById("userListContainer");
    if (!container) return;

    let html = "";
    const query = searchQuery.toLowerCase().trim();

    // Update userNames from current users object
    userNames = Object.keys(users);

    const filteredUsers = userNames.filter((userKey) => {
        const user = users[userKey];
        if (!user) return false;
        // Filter berdasarkan user key atau nama lengkap user
        return (
            userKey.toLowerCase().includes(query) ||
            user.name.toLowerCase().includes(query)
        );
    });

    if (filteredUsers.length === 0) {
        html =
            '<div style="padding: 8px; color: #718096; font-size: 14px;">User tidak ditemukan.</div>';
    } else {
        filteredUsers.forEach((userKey) => {
            const user = users[userKey];
            const isSelected = userKey === selectedUserFilter;
            const selectedClass = isSelected ? " selected" : "";

            html += `
                <div class="user-filter-item${selectedClass}" onclick="selectUserFilter('${userKey}', '${
                user.name
            }')">
                    ${getAvatarHtml(userKey)}
                    <span class="user-name">${user.name}</span>
                </div>
            `;
        });
    }

    container.innerHTML = html;
}

// Function to filter the user list based on input
function filterUserList(query) {
    renderUserList(query);
}

// Function to select a user and apply the filter
function selectUserFilter(userKey, userName) {
    selectedUserFilter = userKey;
    document.getElementById("selectedUserName").textContent = `(${userName})`;
    document.getElementById("userFilterDropdown").style.display = "none";
    document.getElementById("userSearchInput").value = ""; // Clear search input
    renderUserList(); // Re-render to show selection
    filterTasks(); // Apply task filter
}

// Function to clear the user filter
function clearUserFilter() {
    selectedUserFilter = "";
    document.getElementById("selectedUserName").textContent = "";
    document.getElementById("userFilterDropdown").style.display = "none";
    document.getElementById("userSearchInput").value = ""; // Clear search input
    renderUserList(); // Re-render to clear selection
    filterTasks(); // Apply task filter
}
