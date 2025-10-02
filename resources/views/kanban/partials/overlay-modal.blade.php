<div class="modal-overlay" id="pendingModal">
    <div class="pending-modal">
        <div class="modal-header">
            <h3 class="modal-title">Alasan Pending</h3>
            <button class="close-btn" onclick="closePendingModal()">
                &times;
            </button>
        </div>

        <form id="pendingForm">
            <div class="form-group full-width">
                <label class="form-label" for="pendingReasonInput">Mengapa task ini dipending?</label>
                <textarea id="pendingReasonInput" class="form-input form-textarea" placeholder="Masukkan alasan task dipending..."
                    required></textarea>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-secondary" onclick="closePendingModal()">
                    Batal
                </button>
                <button type="submit" class="btn-primary">
                    Pindah ke Pending
                </button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="reactivationModal">
    <div class="pending-modal">
        <div class="modal-header">
            <h3 class="modal-title">Atur Deadline Baru</h3>
            <button class="close-btn" onclick="closeReactivationModal()">
                &times;
            </button>
        </div>

        <form id="reactivationForm">
            <div class="form-group full-width">
                <label class="form-label" for="newDeadlineInput">Deadline baru (harus hari ini atau
                    setelahnya):</label>
                <input type="date" id="newDeadlineInput" class="form-input" required />
            </div>

            <div class="form-group full-width" id="pendingReasonGroup" style="display: none">
                <label class="form-label" for="reactivationPendingReason">Alasan Pending:</label>
                <textarea id="reactivationPendingReason" class="form-input form-textarea"
                    placeholder="Masukkan alasan task dipending..." required></textarea>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-secondary" onclick="closeReactivationModal()">
                    Batal
                </button>
                <button type="submit" class="btn-primary" id="reactivationSubmitBtn">
                    Pindahkan & Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Chat Modal -->
<div class="modal-overlay" id="chatModal">
    <div class="chat-modal">
        <div class="modal-header">
            <h3 class="modal-title" id="chatTaskTitle">Chat & Files - Task Title</h3>
            <button class="close-btn" onclick="closeChatModal()">&times;</button>
        </div>

        <div class="chat-messages" id="chatMessages">
            <!-- Messages will be inserted here -->
        </div>

        <div class="chat-input-section">
            <div class="chat-input-container">
                <textarea id="messageInput" class="chat-input" placeholder="Tulis komentar..."></textarea>
                <button class="send-message-btn" onclick="sendMessage()">Kirim</button>
            </div>

            <div class="file-upload-section">
                <div class="file-input-wrapper">
                    <input type="file" id="fileInput" class="file-input" multiple onchange="handleFileSelect(event)">
                    <button class="file-upload-btn" onclick="document.getElementById('fileInput').click()">
                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        Attach File
                    </button>
                </div>
                <div class="selected-files" id="selectedFiles"></div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>

<!-- Confirmation Modal -->
<div class="modal-overlay" id="confirmModal">
    <div class="confirm-modal">
        <div class="confirm-icon" id="confirmIcon">
            <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                    clip-rule="evenodd" />
            </svg>
        </div>
        <div class="confirm-title" id="confirmTitle">Konfirmasi</div>
        <div class="confirm-message" id="confirmMessage">Apakah Anda yakin?</div>
        <div class="confirm-actions">
            <button class="btn-confirm cancel" id="confirmCancel">Batal</button>
            <button class="btn-confirm danger" id="confirmOk">Ya</button>
        </div>
    </div>
</div>
