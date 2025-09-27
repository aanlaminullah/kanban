// --- EXPANDED USER DATA ---
const users = {
    john: {
        name: "John Doe",
        avatar: "JD",
        color: "#4299e1",
    },
    alice: {
        name: "Alice Smith",
        avatar: "AS",
        color: "#48bb78",
    },
    bob: {
        name: "Bob Johnson",
        avatar: "BJ",
        color: "#ed8936",
    },
    sarah: {
        name: "Sarah Wilson",
        avatar: "SW",
        color: "#e53e3e",
    },
    david: {
        name: "David Lee",
        avatar: "DL",
        color: "#68d391",
    },
    emma: {
        name: "Emma Brown",
        avatar: "EB",
        color: "#f6ad55",
    },
    frank: {
        name: "Frank Miller",
        avatar: "FM",
        color: "#38b2ac",
    },
    grace: {
        name: "Grace Hall",
        avatar: "GH",
        color: "#d53f8c",
    },
    henry: {
        name: "Henry King",
        avatar: "HK",
        color: "#00b5ad",
    },
    irene: {
        name: "Irene Scott",
        avatar: "IS",
        color: "#a0aec0",
    },
    jacob: {
        name: "Jacob Clark",
        avatar: "JC",
        color: "#b794f4",
    },
    kate: {
        name: "Kate Walker",
        avatar: "KW",
        color: "#e9d8fd",
    },
    liam: {
        name: "Liam Lewis",
        avatar: "LL",
        color: "#f6e05e",
    },
    mia: {
        name: "Mia Harris",
        avatar: "MH",
        color: "#9f7aea",
    },
    noah: {
        name: "Noah Young",
        avatar: "NY",
        color: "#4c51bf",
    },
};

const userColors = [
    "#f56565",
    "#48bb78",
    "#38b2ac",
    "#4299e1",
    "#9f7aea",
    "#ed8936",
    "#d53f8c",
    "#a0aec0",
    "#4c51bf",
    "#00b5ad",
];
const moreUsers = [
    "peter",
    "quinn",
    "ryan",
    "sophia",
    "thomas",
    "ursula",
    "victor",
    "wendy",
    "xavier",
    "yara",
    "zach",
];

moreUsers.forEach((id, index) => {
    const name = id.charAt(0).toUpperCase() + id.slice(1) + " Tester";
    const initials =
        id.charAt(0).toUpperCase() + name.split(" ")[1].charAt(0).toUpperCase();
    users[id] = {
        name: name,
        avatar: initials,
        color: userColors[index % userColors.length],
    };
});
// --- END EXPANDED USER DATA ---

// Enhanced tasks data with new fields
let tasks = [
    {
        id: 1,
        title: "Implementasi Sistem Login",
        deadline: "2024-09-20",
        description:
            "Membuat sistem autentikasi pengguna dengan JWT token dan validasi form",
        column: "product-backlog",
        priority: "high",
        bidang: "aptika", // TAMBAH
        assignedUsers: ["john", "alice", "peter", "sophia"], // Contoh banyak user
        createdAt: new Date("2024-09-01T10:30:00"), // TAMBAH
        completedAt: null, // TAMBAH
        pendingReason: null, // TAMBAH
        subtasks: [
            {
                id: 1,
                text: "Setup JWT library",
                completed: false,
                createdBy: "john", // TAMBAH
            },
            {
                id: 2,
                text: "Buat login form",
                completed: true,
                createdBy: "john",
            },
            {
                id: 3,
                text: "Validasi input",
                completed: false,
                createdBy: "alice",
            },
        ],
        attachments: [
            // TAMBAH
            {
                id: 1,
                name: "requirements.pdf",
                size: "2.3 MB",
                type: "pdf",
                uploadedBy: "john",
                uploadedAt: new Date("2024-09-02T10:00:00"),
            },
        ],
        comments: [
            // TAMBAH
            {
                id: 1,
                text: "Sudah mulai implementasi JWT",
                author: "john",
                createdAt: new Date("2024-09-02T11:00:00"),
                attachments: [],
            },
            {
                id: 2,
                text: "Perlu review lagi untuk security",
                author: "alice",
                createdAt: new Date("2024-09-02T15:30:00"),
                attachments: [
                    {
                        id: 2,
                        name: "security-checklist.docx",
                        size: "1.1 MB",
                        type: "docx",
                    },
                ],
            },
        ],
    },
    {
        id: 2,
        title: "Design UI Dashboard",
        deadline: "2024-10-20",
        description:
            "Merancang interface dashboard utama dengan komponen yang responsif",
        column: "product-backlog",
        priority: "medium",
        bidang: "sarkom",
        assignedUsers: ["alice", "frank"],
        createdAt: new Date("2024-09-10T11:00:00"),
        completedAt: null,
        pendingReason: null,
        subtasks: [
            {
                id: 1,
                text: "Wireframe",
                completed: true,
                createdBy: "alice",
            },
            {
                id: 2,
                text: "Mockup design",
                completed: false,
                createdBy: "alice",
            },
        ],
    },
    {
        id: 3,
        title: "Fix Bug Authentication",
        deadline: "2024-09-15",
        description:
            "Mengatasi masalah token expiry yang tidak ter-handle dengan baik",
        column: "in-progress",
        priority: "high",
        bidang: "aptika",
        assignedUsers: ["john", "henry"],
        createdAt: new Date("2024-09-14T09:00:00"),
        completedAt: null,
        pendingReason: null,
        subtasks: [
            {
                id: 1,
                text: "Reproduce bug",
                completed: true,
                createdBy: "john",
            },
            {
                id: 2,
                text: "Fix implementation",
                completed: false,
                createdBy: "john",
            },
            {
                id: 3,
                text: "Testing",
                completed: false,
                createdBy: "john",
            },
        ],
    },
    {
        id: 4,
        title: "Database Schema",
        deadline: "2024-10-12",
        description:
            "Membuat struktur database untuk aplikasi dengan relasi yang tepat",
        column: "product-backlog",
        priority: "medium",
        bidang: "sekretariat",
        assignedUsers: ["bob", "quinn"],
        createdAt: new Date("2024-09-05T14:00:00"),
        completedAt: null,
        pendingReason: null,
        subtasks: [
            {
                id: 1,
                text: "ERD Design",
                completed: false,
                createdBy: "bob",
            },
            {
                id: 2,
                text: "Migration scripts",
                completed: false,
                createdBy: "bob",
            },
        ],
    },
    {
        id: 5,
        title: "Personal Learning: React Hooks",
        deadline: "2024-10-25",
        description: "Mempelajari advanced React Hooks untuk project mendatang",
        column: "product-backlog",
        priority: "low",
        bidang: "sarkom",
        assignedUsers: ["john", "sarah", "yara"],
        createdAt: new Date("2024-09-26T16:00:00"),
        completedAt: null,
        pendingReason: null,
        subtasks: [
            {
                id: 1,
                text: "useCallback & useMemo",
                completed: false,
                createdBy: "sarah",
            },
            {
                id: 2,
                text: "Custom hooks",
                completed: false,
                createdBy: "sarah",
            },
            {
                id: 3,
                text: "Practice project",
                completed: false,
                createdBy: "john",
            },
        ],
    },
];

// --- NEW GLOBAL STATE FOR USER FILTER ---
let selectedUserFilter = "";
const userNames = Object.keys(users); // Array of user keys for filtering
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
function filterTasks() {
    const searchTerm =
        document.getElementById("searchInput")?.value?.toLowerCase() || "";
    // HAPUS/KOMENTARI: Filter kolom ini tidak lagi digunakan
    // const columnFilter = document.getElementById("columnFilter")?.value || "";
    const priorityFilter =
        document.getElementById("priorityFilter")?.value || "";
    const bidangFilter = document.getElementById("bidangFilter")?.value || "";

    // UBAH: Ambil nilai dari variabel global 'selectedUserFilter' yang baru
    // yang diisi oleh fungsi selectUserFilter()
    const userFilterKey = selectedUserFilter; // <-- PENTING: Ganti dengan variabel global yang baru
    // const userFilter = document.getElementById("userFilter")?.value?.toLowerCase() || ""; // <-- HAPUS/KOMENTARI BARIS LAMA INI

    filteredTasks = tasks.filter((task) => {
        // HAPUS: Logika Filter by column (karena diganti)
        // if (columnFilter && task.column !== columnFilter) return false;

        // Filter by priority
        if (priorityFilter && task.priority !== priorityFilter) return false;
        // Filter by bidang
        if (bidangFilter && task.bidang !== bidangFilter) return false;

        // UBAH: Filter by assigned user (Gunakan userFilterKey)
        // Ini adalah logika yang sekarang menggunakan variabel global
        if (userFilterKey) {
            // Cek apakah assignedUsers berisi key user yang dipilih (userFilterKey)
            if (
                !task.assignedUsers ||
                !task.assignedUsers.includes(userFilterKey)
            ) {
                return false;
            }
        }

        // --- LOGIKA FILTER LAMA INI DIHAPUS KARENA SUDAH DISEDERHANAKAN DI ATAS ---
        /*
        if (userFilter) {
            const userMatch = (task.assignedUsers || []).some((uid) => {
                const user = users[uid];
                if (!user) return false;
                return (
                    uid.toLowerCase().includes(userFilter) ||
                    user.name.toLowerCase().includes(userFilter)
                );
            });
            if (!userMatch) return false;
        }
        */
        // ------------------------------------------------------------------------

        // Filter by search term (judul, deskripsi, deadline, subtask, user)
        // ... (Logika search term tetap sama)
        if (searchTerm) {
            const inTitle = task.title?.toLowerCase().includes(searchTerm);
            const inDesc = task.description?.toLowerCase().includes(searchTerm);
            const inDeadline = task.deadline
                ?.toLowerCase()
                .includes(searchTerm);
            // Cari di subtask
            const inSubtask = (task.subtasks || []).some((st) =>
                st.text?.toLowerCase().includes(searchTerm)
            );
            // Cari di assigned user (nama atau id)
            const inUser = (task.assignedUsers || []).some((uid) => {
                const user = users[uid];
                if (!user) return false;
                return (
                    uid.toLowerCase().includes(searchTerm) ||
                    user.name.toLowerCase().includes(searchTerm)
                );
            });
            if (!(inTitle || inDesc || inDeadline || inSubtask || inUser))
                return false;
        }
        return true;
    });

    renderTasks(filteredTasks); // Pastikan ini menerima filteredTasks
    updateOverdueStatus();
}

// Create task card HTML
function createTaskCard(task) {
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

    // Generate recent comments HTML
    const commentsHTML =
        task.comments && task.comments.length > 0
            ? `<div class="chat-comments">
        ${task.comments
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

    // Chat section
    const chatSectionHTML =
        (task.attachments && task.attachments.length > 0) ||
        (task.comments && task.comments.length > 0)
            ? `<div class="task-chat-section">
        ${attachmentsHTML}
        ${commentsHTML}
        <button class="chat-toggle" onclick="openChatModal(${task.id})">
            ${task.comments?.length || 0} komentar • ${
                  task.attachments?.length || 0
              } file
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
    // Gunakan setTimeout untuk menunggu browser selesai mengubah status 'checked'
    // pada checkbox yang terhubung dengan label sebelum kita membaca statusnya.
    setTimeout(() => {
        const checkbox = document.getElementById(`user-${userId}`);
        const isChecked = checkbox.checked; // Ambil status akhir setelah klik

        // 1. Update the global state (Model)
        if (isChecked) {
            if (!taskAssignedUsers.includes(userId)) {
                taskAssignedUsers.push(userId);
            }
        } else {
            taskAssignedUsers = taskAssignedUsers.filter((id) => id !== userId);
        }

        // 2. UX Improvement: Kosongkan input pencarian jika user baru saja diseleksi
        if (isChecked) {
            document.getElementById("userSearchInput").value = "";
        }

        // 3. Re-render the list untuk menampilkan daftar user lengkap (jika search dikosongkan)
        // dengan user yang baru diseleksi tetap ter-highlight.
        filterAssignedUsers();
    }, 0); // 0ms timeout (microtask)
}

// Render assigned users in modal, filtered by search term
function renderAssignedUsers(searchTerm = "") {
    const container = document.getElementById("assignedUsersContainer");
    const searchTermLower = searchTerm.toLowerCase();

    let html = "";

    Object.keys(users).forEach((userId) => {
        const user = users[userId];
        // Check if the user is currently assigned (kept in global state taskAssignedUsers)
        const isChecked = taskAssignedUsers.includes(userId);

        // Filter logic: match user name or show all if search is empty
        if (
            searchTermLower === "" ||
            user.name.toLowerCase().includes(searchTermLower)
        ) {
            html += `
                        <input type="checkbox" id="user-${userId}" class="user-checkbox" value="${userId}" ${
                isChecked ? "checked" : ""
            }>
                        <label for="user-${userId}" class="user-option" onclick="handleUserSelection('${userId}')"> <div class="user-option-avatar" style="background: ${
                user.color
            };">${user.avatar}</div>
                            <span>${user.name}</span>
                        </label>
                    `;
        }
    });

    container.innerHTML = html;
}

// Handler for the search input
function filterAssignedUsers() {
    const searchTerm = document.getElementById("userSearchInput").value;
    renderAssignedUsers(searchTerm);
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
    if (confirm("Apakah Anda yakin ingin menghapus tugas ini?")) {
        tasks = tasks.filter((task) => task.id !== taskId);
        filterTasks();
    }
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
    document.getElementById("userSearchInput").value = "";
    renderAssignedUsers();

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
    taskAssignedUsers = [...(task.assignedUsers || [])];
    document.getElementById("userSearchInput").value = "";
    renderAssignedUsers();

    // Set subtasks
    currentSubtasks = [...(task.subtasks || [])];
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
            alert("Task berhasil dipindahkan ke Pending!");
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

            alert(message);
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
        currentSubtasks.push({
            id: nextSubtaskId++,
            text: text,
            completed: false,
            createdBy: "john", // Default creator for new subtasks
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
    } else {
        container.innerHTML = currentSubtasks
            .map(
                (subtask) => `
                    <div class="subtask-edit-item">
                        <input type="checkbox" ${
                            subtask.completed ? "checked" : ""
                        } 
                               onchange="currentSubtasks.find(st => st.id === ${
                                   subtask.id
                               }).completed = this.checked">
                        <span class="subtask-edit-text">${subtask.text} (${
                    users[subtask.createdBy]?.name || "Unknown"
                })</span>
                        <button type="button" class="remove-subtask-btn" onclick="removeSubtask(${
                            subtask.id
                        })">×</button>
                    </div>
                `
            )
            .join("");
    }
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
            alert("Deadline tidak boleh tanggal yang sudah lewat.");
            return;
        }

        if (assignedUsers.length === 0) {
            alert("Mohon tetapkan setidaknya satu user pada task ini.");
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
            alert("Task berhasil diupdate!");
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
            alert("Task berhasil ditambahkan!");
        }

        filterTasks();
        closeTaskModal();
    } else {
        alert(
            "Semua field (Judul, Prioritas, Deadline, Bidang, Deskripsi) wajib diisi!"
        );
    }
}

// Logout function
function logout() {
    if (confirm("Apakah Anda yakin ingin keluar?")) {
        alert("Anda telah berhasil logout!");
    }
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

    // Form submit listener for Pending Modal
    document
        .getElementById("pendingForm")
        .addEventListener("submit", handlePendingSubmit);

    // Form submit listener for Reactivation Modal
    document
        .getElementById("reactivationForm")
        .addEventListener("submit", handleReactivationSubmit); // Renamed

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
            // Renamed
            if (e.target === this) {
                closeReactivationModal(); // Renamed
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
            document.getElementById("reactivationModal").classList.contains(
                // Renamed
                "active"
            )
        ) {
            closeReactivationModal(); // Renamed
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

    // Subtask input enter key
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

    // Message input enter key (Ctrl+Enter to send)
    document
        .getElementById("messageInput")
        .addEventListener("keydown", function (e) {
            if (e.key === "Enter" && e.ctrlKey) {
                e.preventDefault();
                sendMessage();
            }
        });

    if (document.getElementById("userListContainer")) {
        renderUserList();
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

    container.innerHTML = task.comments
        .map((comment) => {
            const author = users[comment.author] || {
                name: comment.author,
                color: "#4299e1",
            };
            const attachmentsHTML =
                comment.attachments
                    ?.map(
                        (att) =>
                            `<a href="#" class="message-attachment" onclick="downloadFile('${att.name}')">
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

    // Scroll to bottom
    container.scrollTop = container.scrollHeight;
}

function sendMessage() {
    const messageText = document.getElementById("messageInput").value.trim();

    if (!messageText && selectedFiles.length === 0) {
        alert("Masukkan pesan atau pilih file untuk dikirim");
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

function downloadFile(filename) {
    // Simulate file download
    alert(`Downloading: ${filename}`);
    // In real implementation, this would trigger actual file download
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
    let html = "";
    const query = searchQuery.toLowerCase().trim();

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
