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
