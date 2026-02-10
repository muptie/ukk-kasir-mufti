/**
 * Kedai Muptie - JavaScript Helper Functions
 * Version: 1.0.0
 */

// Format number to Rupiah
function formatRupiah(angka) {
    return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Parse Rupiah to number
function parseRupiah(rupiah) {
    return parseInt(rupiah.replace(/[^0-9]/g, ''));
}

// Confirm delete action
function confirmDelete(message) {
    return confirm(message || 'Apakah Anda yakin ingin menghapus data ini?');
}

// Show notification
function showNotification(message, type = 'info') {
    // Type: success, danger, warning, info
    const notification = document.createElement('div');
    notification.className = `alert alert-${type}`;
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.style.minWidth = '300px';
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        <span>${message}</span>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Auto-hide alerts
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});

// Number input validation
function validateNumber(input) {
    input.value = input.value.replace(/[^0-9]/g, '');
}

// Price input validation
function validatePrice(input) {
    let value = input.value.replace(/[^0-9]/g, '');
    input.value = value;
}

// Prevent form double submission
function preventDoubleSubmit(form) {
    let submitted = false;
    form.addEventListener('submit', function(e) {
        if (submitted) {
            e.preventDefault();
            return false;
        }
        submitted = true;
        
        // Re-enable after 3 seconds as fallback
        setTimeout(() => {
            submitted = false;
        }, 3000);
    });
}

// Print function
function printPage() {
    window.print();
}

// Debounce function for search
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Search table
function searchTable(input, tableId) {
    const filter = input.value.toUpperCase();
    const table = document.getElementById(tableId);
    const tr = table.getElementsByTagName('tr');
    
    for (let i = 1; i < tr.length; i++) {
        let found = false;
        const td = tr[i].getElementsByTagName('td');
        
        for (let j = 0; j < td.length; j++) {
            if (td[j]) {
                const txtValue = td[j].textContent || td[j].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    found = true;
                    break;
                }
            }
        }
        
        tr[i].style.display = found ? '' : 'none';
    }
}

// Copy to clipboard
function copyToClipboard(text) {
    const textarea = document.createElement('textarea');
    textarea.value = text;
    document.body.appendChild(textarea);
    textarea.select();
    document.execCommand('copy');
    document.body.removeChild(textarea);
    showNotification('Berhasil disalin!', 'success');
}

// Get current date in YYYY-MM-DD format
function getCurrentDate() {
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

// Get current time in HH:MM format
function getCurrentTime() {
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    return `${hours}:${minutes}`;
}

// Auto-focus first input
document.addEventListener('DOMContentLoaded', function() {
    const firstInput = document.querySelector('input[autofocus]');
    if (firstInput) {
        firstInput.focus();
    }
});

// Loading overlay
function showLoading() {
    const overlay = document.createElement('div');
    overlay.id = 'loading-overlay';
    overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 99999;
    `;
    overlay.innerHTML = '<div class="loading"></div>';
    document.body.appendChild(overlay);
}

function hideLoading() {
    const overlay = document.getElementById('loading-overlay');
    if (overlay) {
        overlay.remove();
    }
}

// Export functions to global scope
window.formatRupiah = formatRupiah;
window.parseRupiah = parseRupiah;
window.confirmDelete = confirmDelete;
window.showNotification = showNotification;
window.validateNumber = validateNumber;
window.validatePrice = validatePrice;
window.preventDoubleSubmit = preventDoubleSubmit;
window.printPage = printPage;
window.debounce = debounce;
window.searchTable = searchTable;
window.copyToClipboard = copyToClipboard;
window.getCurrentDate = getCurrentDate;
window.getCurrentTime = getCurrentTime;
window.showLoading = showLoading;
window.hideLoading = hideLoading;
