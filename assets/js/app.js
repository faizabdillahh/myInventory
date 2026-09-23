/**
 * ==============================================================================
 * FILE: assets/js/app.js
 * DESKRIPSI: Skrip Interaksi Pengguna (Modal Konfirmasi, Notifikasi, Format Angka)
 * ==============================================================================
 */

// Menunggu hingga seluruh dokumen DOM selesai dimuat sebelum menjalankan skrip
document.addEventListener('DOMContentLoaded', () => {

    /* -------------------------------------------------------------------------
     * 1. FITUR AUTO-DISMISS ALERT / FLASH MESSAGE
     * ---------------------------------------------------------------------- */
    // Mencari elemen alert pada halaman
    const alertElements = document.querySelectorAll('.alert');
    if (alertElements.length > 0) {
        alertElements.forEach(alert => {
            // Memberikan waktu tampil selama 4.5 detik, lalu menghilang secara halus (fade out)
            setTimeout(() => {
                alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                
                // Menghapus elemen dari DOM setelah animasi fade selesai
                setTimeout(() => {
                    alert.remove();
                }, 500);
            }, 4500);
        });
    }

    /* -------------------------------------------------------------------------
     * 2. MODAL KONFIRMASI HAPUS PRODUK
     * ---------------------------------------------------------------------- */
    const deleteModal = document.getElementById('deleteConfirmModal');
    const deleteForm  = document.getElementById('deleteForm');
    const deleteItemName = document.getElementById('deleteItemName');
    const deleteButtons = document.querySelectorAll('.btn-trigger-delete');
    const cancelDeleteBtn = document.getElementById('btnCancelDelete');

    // Menghubungkan setiap tombol hapus di tabel ke modal konfirmasi
    if (deleteButtons.length > 0 && deleteModal && deleteForm) {
        deleteButtons.forEach(button => {
            button.addEventListener('click', (event) => {
                event.preventDefault();

                // Mengambil atribut data-id dan data-name dari tombol yang ditekan
                const productId = button.getAttribute('data-id');
                const productName = button.getAttribute('data-name');

                // Mengisi nama produk pada teks konfirmasi di dalam modal
                if (deleteItemName) {
                    deleteItemName.textContent = productName;
                }

                // Menentukan action URL pada form hapus menuju delete.php?id=...
                deleteForm.setAttribute('action', `delete.php?id=${encodeURIComponent(productId)}`);

                // Menampilkan modal dengan menambahkan kelas 'active'
                deleteModal.classList.add('active');
            });
        });
    }

    // Menutup modal jika tombol Batal ditekan
    if (cancelDeleteBtn && deleteModal) {
        cancelDeleteBtn.addEventListener('click', () => {
            deleteModal.classList.remove('active');
        });
    }

    // Menutup modal jika pengguna mengklik area backdrop luar modal
    if (deleteModal) {
        deleteModal.addEventListener('click', (event) => {
            if (event.target === deleteModal) {
                deleteModal.classList.remove('active');
            }
        });
    }

    /* -------------------------------------------------------------------------
     * 3. FORMAT TAMPILAN MATA UANG PADA FORM INPUT HARGA
     * ---------------------------------------------------------------------- */
    const priceInput = document.getElementById('inputHarga');
    if (priceInput) {
        // Mencegah input karakter selain angka pada kolom harga
        priceInput.addEventListener('input', (e) => {
            // Memastikan nilai tidak negatif
            if (parseFloat(e.target.value) < 0) {
                e.target.value = 0;
            }
        });
    }

});
