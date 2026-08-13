import './bootstrap';

// Fungsi untuk membuat dan menampilkan Toast Notification
function showToast(message) {

    (function injectNotificationStyles() {
        if (document.getElementById('toast-style')) return;
        const style = document.createElement('style');
        style.id = 'toast-style';
        style.textContent = `
                #toast-container {
                    position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                display: flex;
                flex-direction: column;
                gap: 10px;
                max-width: 350px;
            }
            .custom-toast {
                background: #1e293b; /* Warna gelap gelap elegan */
                color: #f1f5f9;
                padding: 12px 20px;
                border-radius: 8px;
                border-left: 4px solid #6d28d9; /* Aksen ungu premium */
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -4px rgba(0, 0, 0, 0.3);
                font-family: 'Roboto', sans-serif;
                font-size: 1.3rem;
                display: flex;
                align-items: center;
                gap: 10px;
                opacity: 0;
                transform: translateX(50px);
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            }
            .custom-toast.showNotification {
                opacity: 1;
                transform: translateX(0);
            }
            .custom-toast.hideNotification {
                opacity: 0;
                transform: translateX(100px);
            }
            .close-btn {
            background: transparent;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 5px;
            }
        `;
        document.head.appendChild(style);
    })();

    // 1. Dapatkan atau buat container notifikasi jika belum ada
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        document.body.appendChild(container);
    }
    // 3. Buat element toast baru
    const toast = document.createElement('div');
    toast.className = 'custom-toast';
    toast.innerHTML = `
        <i class="fa fa-info-circle" style="color: #a78bfa;"></i>
        <span>${message}</span>
        <button class="close-btn">&times;</button>
    `;
    container.appendChild(toast);

    const closeBtn = toast.querySelector('.close-btn');

    // 4. Picu animasi masuk (fade in & slide in)
    setTimeout(() => {
        toast.classList.add('showNotification');
    }, 10);

    // 5. Hilangkan otomatis setelah 4 detik
    setTimeout(() => {
        toast.classList.remove('showNotification');
        toast.classList.add('hideNotification');
        // Hapus elemen dari DOM setelah animasi keluar selesai (400ms)
        setTimeout(() => {
            toast.remove();
        }, 400);
    }, 5500);

    const closeNotification = () => {
        toast.classList.remove('showNotification');
        toast.classList.add('hideNotification');
        setTimeout(() => {
            toast.remove();
        }, 100);
    }

    closeBtn.addEventListener('click', closeNotification);
}

const currentUserId = document.querySelector('meta[name="user-id"]')?.content;

function appendActivityLog(message) {
    const logBox = document.getElementById('activity-log-box');
    if (!logBox) return;

    // Dapatkan waktu saat ini (HH:mm)
    const now = new Date();
    const timeStr = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: false });

    // Buat item log baru
    const item = document.createElement('div');
    item.className = 'log-item';
    item.style.cssText = 'font-size: 1.1rem; color: #cbd5e1; line-height: 1.4; word-break: break-word;';
    item.innerHTML = `<span style="color: #64748b; font-size: 1.1rem; margin-right: 4px;">[${timeStr}]</span> <span>${message}</span>`;


    logBox.appendChild(item);

    // Otomatis scroll ke paling bawah (seperti World Chat game)
    logBox.scrollTop = logBox.scrollHeight;
}

// Panggil appendActivityLog di dalam Echo listener:
window.Echo.channel('notifications')
    .listen('.NotificationChange', (e) => {
        const currentUserId = document.querySelector('meta[name="user-id"]')?.content;


        appendActivityLog(e.message);
        showToast(e.message);
    });



// import './bootstrap';

// document.addEventListener('DOMContentLoaded', () => {
//     window.Echo.channel('notifications')
//         .listen('.NotificationChange', (e) => {
//             alert(e.message);
//         })
// })