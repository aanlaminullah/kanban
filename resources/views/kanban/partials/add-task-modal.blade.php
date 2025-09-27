<div class="modal-overlay" id="taskModal">
    <div class="modal">
        <div class="modal-header">
            <div>
                <h3 class="modal-title" id="modalTitle">
                    Tambah Task Baru
                </h3>
                <div class="modal-subtitle">Shortcut: Shift+N</div>
            </div>
            <button class="close-btn" onclick="closeTaskModal()">
                &times;
            </button>
        </div>

        <form id="taskForm">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="taskTitle">Judul Task</label>
                    <input type="text" id="taskTitle" class="form-input" placeholder="Masukkan judul task..."
                        required />
                </div>
                <div class="form-group">
                    <label class="form-label" for="taskPriority">Prioritas</label>
                    <select id="taskPriority" class="form-select" required>
                        <option value="">Pilih Prioritas</option>
                        <option value="high">High 🔥</option>
                        <option value="medium">Medium ⬆️</option>
                        <option value="low">Low ➡️</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="taskDeadline" class="form-label">Deadline</label>
                    <input type="date" id="taskDeadline" class="form-input" required />
                </div>

                <div class="form-group">
                    <label class="form-label">Bidang</label>
                    <div class="bidang-container" id="bidangContainer">
                        <input type="radio" id="bidang-aptika" class="bidang-checkbox" name="bidang"
                            value="aptika" />
                        <label for="bidang-aptika" class="bidang-option aptika">Aptika</label>

                        <input type="radio" id="bidang-sarkom" class="bidang-checkbox" name="bidang"
                            value="sarkom" />
                        <label for="bidang-sarkom" class="bidang-option sarkom">Sarkom</label>

                        <input type="radio" id="bidang-sekretariat" class="bidang-checkbox" name="bidang"
                            value="sekretariat" />
                        <label for="bidang-sekretariat" class="bidang-option sekretariat">Sekretariat</label>
                    </div>
                </div>
            </div>

            <div class="form-group full-width">
                <label class="form-label" for="taskDescription">Deskripsi</label>
                <textarea id="taskDescription" class="form-input form-textarea" placeholder="Masukkan deskripsi task..." required></textarea>
            </div>

            <div class="form-group full-width">
                <label class="form-label">Assign User</label>
                <div class="search-box" style="margin-bottom: 12px; min-width: 100%">
                    <input type="text" id="userSearchInput" class="form-input" placeholder="Cari nama user..."
                        oninput="filterAssignedUsers()" />
                </div>
                <div class="users-container" id="assignedUsersContainer"
                    style="
                                max-height: 150px;
                                overflow-y: auto;
                                border: 1px solid #e2e8f0;
                                padding: 8px;
                                border-radius: 6px;
                            ">
                </div>
            </div>

            <div class="form-group full-width">
                <label class="form-label">Subtasks</label>
                <div class="subtasks-section">
                    <div class="subtask-input-container">
                        <input type="text" id="subtaskInput" class="subtask-input" placeholder="Tambah subtask..." />
                        <button type="button" class="add-subtask-btn" onclick="addSubtask()">
                            Tambah
                        </button>
                    </div>
                    <div class="subtask-list" id="subtaskList"></div>
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-secondary" onclick="closeTaskModal()">
                    Batal
                </button>
                <button type="submit" class="btn-primary" id="submitBtn">
                    Tambah Task
                </button>
            </div>
        </form>
    </div>
</div>
