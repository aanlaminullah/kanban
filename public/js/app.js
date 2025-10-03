// public/js/app.js - Global Utilities

// Global users object (will be populated from API)
window.users = {};

// Toast Notification System
window.toastCounter = 0;

window.showToast = function (type, title, message, duration = 5000) {
    const toastId = `toast-${++window.toastCounter}`;
    const container =
        document.getElementById("toastContainer") ||
        window.createToastContainer();

    const icons = {
        success: `<svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                  </svg>`,
        error: `<svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>`,
        warning: `<svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                  </svg>`,
        info: `<svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                 <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
               </svg>`,
    };

    const toast = document.createElement("div");
    toast.id = toastId;
    toast.className = `toast ${type}`;
    toast.innerHTML = `
        <div class="toast-header">
            <div class="toast-title">
                <span class="toast-icon">${icons[type]}</span>
                ${title}
            </div>
            <button class="toast-close" onclick="window.removeToast('${toastId}')">&times;</button>
        </div>
        <div class="toast-message">${message}</div>
        <div class="toast-progress" style="animation-duration: ${duration}ms"></div>
    `;

    container.appendChild(toast);

    // Trigger animation
    setTimeout(() => toast.classList.add("show"), 10);

    // Auto remove after duration
    setTimeout(() => window.removeToast(toastId), duration);

    // Click to dismiss
    toast.addEventListener("click", () => window.removeToast(toastId));
};

window.createToastContainer = function () {
    const container = document.createElement("div");
    container.id = "toastContainer";
    container.className = "toast-container";
    document.body.appendChild(container);
    return container;
};

window.removeToast = function (toastId) {
    const toast = document.getElementById(toastId);
    if (toast) {
        toast.classList.remove("show");
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }
};

// Confirmation Dialog
window.showConfirm = function (title, message, onConfirm, type = "warning") {
    return new Promise((resolve) => {
        const modal = document.getElementById("confirmModal");
        const titleEl = document.getElementById("confirmTitle");
        const messageEl = document.getElementById("confirmMessage");
        const iconEl = document.getElementById("confirmIcon");
        const cancelBtn = document.getElementById("confirmCancel");
        const okBtn = document.getElementById("confirmOk");

        // Create modal if doesn't exist
        if (!modal) {
            window.createConfirmModal();
        }

        titleEl.textContent = title;
        messageEl.textContent = message;
        iconEl.className = `confirm-icon ${type}`;

        modal.classList.add("active");
        document.body.style.overflow = "hidden";

        const handleConfirm = () => {
            modal.classList.remove("active");
            document.body.style.overflow = "auto";
            if (onConfirm) onConfirm();
            resolve(true);
            cleanup();
        };

        const handleCancel = () => {
            modal.classList.remove("active");
            document.body.style.overflow = "auto";
            resolve(false);
            cleanup();
        };

        const cleanup = () => {
            okBtn.removeEventListener("click", handleConfirm);
            cancelBtn.removeEventListener("click", handleCancel);
        };

        okBtn.addEventListener("click", handleConfirm);
        cancelBtn.addEventListener("click", handleCancel);

        // Close with ESC
        const handleEsc = (e) => {
            if (e.key === "Escape") {
                handleCancel();
                document.removeEventListener("keydown", handleEsc);
            }
        };
        document.addEventListener("keydown", handleEsc);
    });
};

// Create confirm modal if doesn't exist
window.createConfirmModal = function () {
    if (document.getElementById("confirmModal")) return;

    const modalHTML = `
        <div id="confirmModal" class="modal-overlay">
            <div class="modal-container confirm-modal">
                <div class="confirm-icon" id="confirmIcon"></div>
                <h3 id="confirmTitle">Konfirmasi</h3>
                <p id="confirmMessage">Apakah Anda yakin?</p>
                <div class="confirm-actions">
                    <button id="confirmCancel" class="btn-secondary">Batal</button>
                    <button id="confirmOk" class="btn-primary">Ya</button>
                </div>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML("beforeend", modalHTML);
};

// Global logout function
window.logout = function () {
    window.showConfirm(
        "Logout",
        "Apakah Anda yakin ingin keluar dari aplikasi?",
        () => {
            const logoutForm = document.getElementById("logoutForm");
            if (logoutForm) {
                logoutForm.submit();
            } else {
                // Fallback jika form tidak ditemukan
                window.showToast(
                    "error",
                    "Error",
                    "Logout form tidak ditemukan"
                );
                console.error("Logout form tidak ditemukan");
            }
        },
        "warning"
    );
};

// Initialize global utilities when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
    // Create toast container if doesn't exist
    if (!document.getElementById("toastContainer")) {
        window.createToastContainer();
    }

    // Create confirm modal if doesn't exist
    window.createConfirmModal();

    // Global keyboard shortcuts
    document.addEventListener("keydown", function (e) {
        // ESC to close modals
        if (e.key === "Escape") {
            const confirmModal = document.getElementById("confirmModal");
            if (confirmModal && confirmModal.classList.contains("active")) {
                confirmModal.classList.remove("active");
                document.body.style.overflow = "auto";
            }
        }
    });
});
