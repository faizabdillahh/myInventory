/**
 * ==============================================================================
 * FILE: assets/js/app.js
 * DESKRIPSI: Skrip Interaksi Pengguna (Modal Konfirmasi, Notifikasi, Accessibility,
 *            Anti-Double Submit, dan Format Angka)
 * ==============================================================================
 */

// Menunggu hingga seluruh dokumen DOM selesai dimuat sebelum menjalankan skrip
document.addEventListener('DOMContentLoaded', () => {

    /* -------------------------------------------------------------------------
     * 0. MODERN MOBILE SLIDE-OVER DRAWER (OPEN / CLOSE WITH BACKDROP)
     * ---------------------------------------------------------------------- */
    const btnToggleMobileMenu = document.getElementById('btnToggleMobileMenu');
    const mobileNavDrawer = document.getElementById('mobileNavDrawer');
    const mobileNavPanel = document.getElementById('mobileNavPanel');
    const btnCloseMobileDrawer = document.getElementById('btnCloseMobileDrawer');

    function openMobileDrawer() {
        if (!mobileNavDrawer) return;
        mobileNavDrawer.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeMobileDrawer() {
        if (!mobileNavDrawer) return;
        mobileNavDrawer.classList.remove('active');
        document.body.style.overflow = '';
    }

    if (btnToggleMobileMenu) {
        btnToggleMobileMenu.addEventListener('click', (e) => {
            e.stopPropagation();
            openMobileDrawer();
        });
    }

    if (btnCloseMobileDrawer) {
        btnCloseMobileDrawer.addEventListener('click', closeMobileDrawer);
    }

    if (mobileNavDrawer) {
        mobileNavDrawer.addEventListener('click', (e) => {
            if (e.target === mobileNavDrawer) {
                closeMobileDrawer();
            }
        });
    }

    // Tutup mobile drawer saat tombol Esc ditekan
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' || e.key === 'Esc') {
            closeMobileDrawer();
        }
    });

    /* -------------------------------------------------------------------------
     * 1. FITUR AUTO-DISMISS ALERT / FLASH MESSAGE DENGAN TOMBOL TUTUP
     * ---------------------------------------------------------------------- */
    const alertElements = document.querySelectorAll('.alert-dismissible, .alert');
    if (alertElements.length > 0) {
        alertElements.forEach(alert => {
            // Tombol close manual jika ada
            const closeBtn = alert.querySelector('.alert-close');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    dismissAlert(alert);
                });
            }

            // Auto dismiss setelah 5 detik
            setTimeout(() => {
                dismissAlert(alert);
            }, 5000);
        });
    }

    function dismissAlert(el) {
        if (!el || !el.parentNode) return;
        el.style.transition = 'opacity 0.4s ease, transform 0.4s ease, max-height 0.4s ease';
        el.style.opacity = '0';
        el.style.transform = 'translateY(-8px)';
        setTimeout(() => {
            if (el.parentNode) el.remove();
        }, 400);
    }

    /* -------------------------------------------------------------------------
     * 2. FUNGSI GLOBAL PENGELOLA MODAL & SHORTCUT ESCAPE
     * ---------------------------------------------------------------------- */
    function closeAllModals() {
        document.querySelectorAll('.modal-overlay.active, .modal-backdrop.active, [data-modal].active').forEach(modal => {
            modal.classList.remove('active');
            if (modal.classList.contains('modal-backdrop')) {
                modal.style.display = 'none';
            }
        });
    }

    // Shortcut ESC untuk menutup modal apapun yang sedang aktif
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' || e.key === 'Esc') {
            closeAllModals();
        }
    });

    /* -------------------------------------------------------------------------
     * 3. MODAL KONFIRMASI LOGOUT
     * ---------------------------------------------------------------------- */
    const logoutModal = document.getElementById('logoutConfirmModal');
    const logoutTriggers = document.querySelectorAll('.btn-trigger-logout');
    const cancelLogoutBtn = document.getElementById('btnCancelLogout');

    if (logoutTriggers.length > 0 && logoutModal) {
        logoutTriggers.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                logoutModal.classList.add('active');
            });
        });
    }

    if (cancelLogoutBtn && logoutModal) {
        cancelLogoutBtn.addEventListener('click', () => {
            logoutModal.classList.remove('active');
        });
    }

    if (logoutModal) {
        logoutModal.addEventListener('click', (e) => {
            if (e.target === logoutModal) {
                logoutModal.classList.remove('active');
            }
        });
    }

    /* -------------------------------------------------------------------------
     * 4. MODAL KONFIRMASI HAPUS PRODUK
     * ---------------------------------------------------------------------- */
    const deleteModal = document.getElementById('deleteConfirmModal');
    const deleteForm  = document.getElementById('deleteForm');
    const deleteItemName = document.getElementById('deleteItemName');
    const deleteButtons = document.querySelectorAll('.btn-trigger-delete');
    const cancelDeleteBtn = document.getElementById('btnCancelDelete');

    if (deleteButtons.length > 0 && deleteModal && deleteForm) {
        deleteButtons.forEach(button => {
            button.addEventListener('click', (event) => {
                event.preventDefault();

                const productId = button.getAttribute('data-id');
                const productName = button.getAttribute('data-name');

                if (deleteItemName) {
                    deleteItemName.textContent = productName;
                }

                const baseAction = deleteForm.getAttribute('data-base-action') || 'products/delete';
                deleteForm.setAttribute('action', `${baseAction}/${encodeURIComponent(productId)}`);

                deleteModal.classList.add('active');
                if (cancelDeleteBtn) cancelDeleteBtn.focus();
            });
        });
    }

    if (cancelDeleteBtn && deleteModal) {
        cancelDeleteBtn.addEventListener('click', () => {
            deleteModal.classList.remove('active');
        });
    }

    if (deleteModal) {
        deleteModal.addEventListener('click', (event) => {
            if (event.target === deleteModal) {
                deleteModal.classList.remove('active');
            }
        });
    }

    /* -------------------------------------------------------------------------
     * 5. MODAL MUTASI STOK CEPAT
     * ---------------------------------------------------------------------- */
    const movementModal = document.getElementById('stockMovementModal');
    const movementButtons = document.querySelectorAll('.btn-trigger-movement');
    const cancelMovementBtn = document.getElementById('btnCancelMovement');
    const movementProductId = document.getElementById('movementProductId');
    const movementProductName = document.getElementById('movementProductName');
    const movementProductSku = document.getElementById('movementProductSku');
    const movementProductStock = document.getElementById('movementProductStock');
    const movementJumlah = document.getElementById('movementJumlah');
    const movementJumlahLabel = document.getElementById('movementJumlahLabel');
    const movementTypeRadios = document.querySelectorAll('input[name="tipe"]');

    if (movementButtons.length > 0 && movementModal) {
        movementButtons.forEach(button => {
            button.addEventListener('click', (event) => {
                event.preventDefault();

                const productId = button.getAttribute('data-id');
                const productSku = button.getAttribute('data-sku');
                const productName = button.getAttribute('data-name');
                const productStock = button.getAttribute('data-stock');

                if (movementProductId) movementProductId.value = productId;
                if (movementProductName) movementProductName.textContent = productName;
                if (movementProductSku) movementProductSku.textContent = productSku;
                if (movementProductStock) movementProductStock.textContent = productStock;

                if (movementJumlah) {
                    movementJumlah.value = '';
                    setTimeout(() => movementJumlah.focus(), 100);
                }

                movementModal.classList.add('active');
            });
        });
    }

    // Dinamisasi pilihan supplier / customer berdasarkan tipe mutasi
    const movementSupplierGroup = document.getElementById('movementSupplierGroup');
    const movementCustomerGroup = document.getElementById('movementCustomerGroup');

    if (movementTypeRadios.length > 0 && movementJumlahLabel && movementJumlah) {
        movementTypeRadios.forEach(radio => {
            radio.addEventListener('change', () => {
                if (radio.value === 'ADJUSTMENT') {
                    movementJumlahLabel.innerHTML = 'Total Stok Fisik Baru (Hasil Opname) <span class="text-rose-500">*</span>';
                    movementJumlah.placeholder = 'Contoh: 50 (angka stok aktual baru)';
                    movementJumlah.min = '0';
                    if (movementSupplierGroup) movementSupplierGroup.classList.add('hidden');
                    if (movementCustomerGroup) movementCustomerGroup.classList.add('hidden');
                } else if (radio.value === 'OUT') {
                    movementJumlahLabel.innerHTML = 'Jumlah Unit Keluar <span class="text-rose-500">*</span>';
                    movementJumlah.placeholder = 'Contoh: 5 (dikurangi dari stok)';
                    movementJumlah.min = '1';
                    if (movementSupplierGroup) movementSupplierGroup.classList.add('hidden');
                    if (movementCustomerGroup) movementCustomerGroup.classList.remove('hidden');
                } else {
                    movementJumlahLabel.innerHTML = 'Jumlah Unit Masuk <span class="text-rose-500">*</span>';
                    movementJumlah.placeholder = 'Contoh: 10 (ditambahkan ke stok)';
                    movementJumlah.min = '1';
                    if (movementSupplierGroup) movementSupplierGroup.classList.remove('hidden');
                    if (movementCustomerGroup) movementCustomerGroup.classList.add('hidden');
                }
            });
        });
    }

    if (cancelMovementBtn && movementModal) {
        cancelMovementBtn.addEventListener('click', () => {
            movementModal.classList.remove('active');
        });
    }

    if (movementModal) {
        movementModal.addEventListener('click', (event) => {
            if (event.target === movementModal) {
                movementModal.classList.remove('active');
            }
        });
    }

    /* -------------------------------------------------------------------------
     * 6. MODAL KONFIRMASI HAPUS SUPPLIER
     * ---------------------------------------------------------------------- */
    const deleteSupplierModal = document.getElementById('deleteSupplierModal');
    const deleteSupplierForm  = document.getElementById('deleteSupplierForm');
    const deleteSupplierName  = document.getElementById('deleteSupplierName');
    const deleteSupplierButtons = document.querySelectorAll('.btn-trigger-delete-supplier');
    const cancelDeleteSupplierBtn = document.getElementById('btnCancelDeleteSupplier');

    if (deleteSupplierButtons.length > 0 && deleteSupplierModal && deleteSupplierForm) {
        deleteSupplierButtons.forEach(button => {
            button.addEventListener('click', (event) => {
                event.preventDefault();

                const supplierId = button.getAttribute('data-id');
                const supplierName = button.getAttribute('data-name');

                if (deleteSupplierName) {
                    deleteSupplierName.textContent = supplierName;
                }

                const baseAction = deleteSupplierForm.getAttribute('data-base-action') || 'suppliers/delete';
                deleteSupplierForm.setAttribute('action', `${baseAction}/${encodeURIComponent(supplierId)}`);

                deleteSupplierModal.classList.add('active');
            });
        });
    }

    if (cancelDeleteSupplierBtn && deleteSupplierModal) {
        cancelDeleteSupplierBtn.addEventListener('click', () => {
            deleteSupplierModal.classList.remove('active');
        });
    }

    if (deleteSupplierModal) {
        deleteSupplierModal.addEventListener('click', (event) => {
            if (event.target === deleteSupplierModal) {
                deleteSupplierModal.classList.remove('active');
            }
        });
    }

    /* -------------------------------------------------------------------------
     * 7. MODAL KONFIRMASI HAPUS PELANGGAN
     * ---------------------------------------------------------------------- */
    const deleteCustomerModal = document.getElementById('deleteCustomerModal');
    const formDeleteCustomer  = document.getElementById('formDeleteCustomer');
    const deleteCustomerName  = document.getElementById('deleteCustomerName');
    const deleteCustomerWarning = document.getElementById('deleteCustomerWarning');
    const deleteCustomerButtons = document.querySelectorAll('.btn-delete-customer');
    const btnCloseDeleteCustomer = document.getElementById('btnCloseDeleteCustomer');
    const btnCancelDeleteCustomer = document.getElementById('btnCancelDeleteCustomer');

    if (deleteCustomerButtons.length > 0 && deleteCustomerModal && formDeleteCustomer) {
        deleteCustomerButtons.forEach(button => {
            button.addEventListener('click', (event) => {
                event.preventDefault();

                const customerId = button.getAttribute('data-id');
                const customerName = button.getAttribute('data-name');
                const transactions = parseInt(button.getAttribute('data-transactions') || '0', 10);

                if (deleteCustomerName) {
                    deleteCustomerName.textContent = customerName;
                }

                if (deleteCustomerWarning) {
                    deleteCustomerWarning.style.display = transactions > 0 ? 'block' : 'none';
                }

                const currentOrigin = window.location.origin;
                const pathParts = window.location.pathname.split('/');
                const basePath = pathParts.slice(0, pathParts.indexOf('public') + 1).join('/');
                formDeleteCustomer.setAttribute('action', `${currentOrigin}${basePath}/customers/delete/${encodeURIComponent(customerId)}`);

                deleteCustomerModal.style.display = 'flex';
                deleteCustomerModal.classList.add('active');
            });
        });
    }

    const closeCustomerModal = () => {
        if (deleteCustomerModal) {
            deleteCustomerModal.style.display = 'none';
            deleteCustomerModal.classList.remove('active');
        }
    };

    if (btnCloseDeleteCustomer) btnCloseDeleteCustomer.addEventListener('click', closeCustomerModal);
    if (btnCancelDeleteCustomer) btnCancelDeleteCustomer.addEventListener('click', closeCustomerModal);
    if (deleteCustomerModal) {
        deleteCustomerModal.addEventListener('click', (e) => {
            if (e.target === deleteCustomerModal) closeCustomerModal();
        });
    }

    /* -------------------------------------------------------------------------
     * 8. ANTI-DOUBLE SUBMIT & LOADING STATE PADA FORM POST
     * ---------------------------------------------------------------------- */
    const postForms = document.querySelectorAll('form[method="POST"], form[method="post"]');
    postForms.forEach(form => {
        form.addEventListener('submit', (e) => {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                // Cegah double click
                if (submitBtn.disabled) {
                    e.preventDefault();
                    return;
                }

                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-75', 'cursor-not-allowed');

                // Simpan teks asli
                const originalContent = submitBtn.innerHTML;
                submitBtn.setAttribute('data-original-content', originalContent);

                // Tambahkan spinner mini
                submitBtn.innerHTML = `
                    <svg class="animate-spin -ml-0.5 mr-2 h-3.5 w-3.5 text-current inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Memproses...</span>
                `;

                // Timeout pengaman jika respons lambat atau validasi HTML gagal
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                    submitBtn.innerHTML = originalContent;
                }, 8000);
            }
        });
    });

    /* -------------------------------------------------------------------------
     * 9. FORMAT TAMPILAN MATA UANG PADA FORM INPUT HARGA
     * ---------------------------------------------------------------------- */
    const priceInputs = document.querySelectorAll('input[name="harga"], #inputHarga, #harga');
    priceInputs.forEach(priceInput => {
        const previewEl = document.getElementById(priceInput.id + 'Preview') || 
                          priceInput.parentElement.querySelector('.currency-helper-preview') ||
                          priceInput.closest('.form-group')?.querySelector('.currency-helper-preview');

        const updateCurrencyPreview = () => {
            const rawVal = priceInput.value.replace(/[^0-9]/g, '');
            if (rawVal && parseInt(rawVal, 10) >= 0) {
                const formatted = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(rawVal);
                if (previewEl) {
                    previewEl.textContent = 'Nominal Terbaca: ' + formatted;
                    previewEl.classList.remove('hidden');
                }
            } else {
                if (previewEl) {
                    previewEl.textContent = '';
                    previewEl.classList.add('hidden');
                }
            }
        };

        priceInput.addEventListener('input', (e) => {
            if (parseFloat(e.target.value) < 0) {
                e.target.value = 0;
            }
            updateCurrencyPreview();
        });

        // Inisialisasi saat form edit terbuka dengan nilai awal
        if (priceInput.value) {
            updateCurrencyPreview();
        }
    });

    /* -------------------------------------------------------------------------
     * 10. GLOBAL SHORTCUT '/' & 'Ctrl+K' UNTUK PENCARIAN (LINEAR / GITHUB STYLE)
     * ---------------------------------------------------------------------- */
    function focusGlobalSearch() {
        const searchInput = document.querySelector('input[name="q"], .search-input');
        if (searchInput) {
            searchInput.focus();
            searchInput.select();
            searchInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else {
            // Jika berada di luar halaman katalog produk, alihkan ke katalog
            window.location.href = '/products';
        }
    }

    document.addEventListener('keydown', (e) => {
        // Jangan picu jika sedang mengetik di dalam input, textarea, atau elemen contenteditable
        const activeTag = document.activeElement ? document.activeElement.tagName.toUpperCase() : '';
        const isEditable = document.activeElement && (document.activeElement.isContentEditable || activeTag === 'INPUT' || activeTag === 'TEXTAREA' || activeTag === 'SELECT');

        // Shortcut '/'
        if (e.key === '/' && !isEditable) {
            e.preventDefault();
            focusGlobalSearch();
        }

        // Shortcut 'Ctrl+K' atau 'Cmd+K'
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
            e.preventDefault();
            focusGlobalSearch();
        }
    });

    const btnTriggerOmnisearch = document.getElementById('btnTriggerOmnisearch');
    if (btnTriggerOmnisearch) {
        btnTriggerOmnisearch.addEventListener('click', () => {
            focusGlobalSearch();
        });
    }

    const btnMobileQuickSearch = document.getElementById('btnMobileQuickSearch');
    if (btnMobileQuickSearch) {
        btnMobileQuickSearch.addEventListener('click', () => {
            focusGlobalSearch();
        });
    }

    /* -------------------------------------------------------------------------
     * 11. UNIVERSAL PASSWORD VISIBILITY TOGGLE (SHOW / HIDE)
     * ---------------------------------------------------------------------- */
    const togglePasswordBtns = document.querySelectorAll('.btn-toggle-password');
    togglePasswordBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const targetId = btn.getAttribute('data-target');
            const targetInput = targetId ? document.getElementById(targetId) : btn.parentElement.querySelector('input');
            
            if (!targetInput) return;

            const isPassword = targetInput.getAttribute('type') === 'password';
            targetInput.setAttribute('type', isPassword ? 'text' : 'password');

            // Ganti ikon mata (eye vs eye-off)
            const iconEye = btn.querySelector('.icon-eye');
            const iconEyeOff = btn.querySelector('.icon-eye-off');
            if (iconEye && iconEyeOff) {
                if (isPassword) {
                    iconEye.classList.add('hidden');
                    iconEyeOff.classList.remove('hidden');
                    btn.setAttribute('title', 'Sembunyikan kata sandi');
                    btn.setAttribute('aria-label', 'Sembunyikan kata sandi');
                } else {
                    iconEye.classList.remove('hidden');
                    iconEyeOff.classList.add('hidden');
                    btn.setAttribute('title', 'Tampilkan kata sandi');
                    btn.setAttribute('aria-label', 'Tampilkan kata sandi');
                }
            }
        });
    });

    /* -------------------------------------------------------------------------
     * 12. TOAST NOTIFICATION UTILITY (FEEDBACK CEPAT ENTERPRISE)
     * ---------------------------------------------------------------------- */
    function showToast(message, type = 'success') {
        let container = document.getElementById('appToastContainer');
        if (!container) {
            container = document.createElement('div');
            container.id = 'appToastContainer';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        toast.className = 'app-toast';
        
        const iconSvg = type === 'success' 
            ? `<svg class="h-4 w-4 text-emerald-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>`
            : `<svg class="h-4 w-4 text-blue-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>`;

        toast.innerHTML = `${iconSvg}<span>${message}</span>`;
        container.appendChild(toast);

        setTimeout(() => {
            toast.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(10px) scale(0.95)';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    /* -------------------------------------------------------------------------
     * 13. FITUR SALIN KODE SKU KE CLIPBOARD (CLICK-TO-COPY)
     * ---------------------------------------------------------------------- */
    document.querySelectorAll('.btn-copy-sku').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const sku = btn.getAttribute('data-sku');
            if (!sku) return;

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(sku).then(() => {
                    showToast(`SKU <strong>${sku}</strong> disalin ke clipboard!`);
                }).catch(() => {
                    fallbackCopyText(sku);
                });
            } else {
                fallbackCopyText(sku);
            }
        });
    });

    function fallbackCopyText(text) {
        const tempInput = document.createElement('input');
        tempInput.value = text;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        showToast(`SKU <strong>${text}</strong> disalin ke clipboard!`);
    }

    /* -------------------------------------------------------------------------
     * 14. GENERATOR SVG BARCODE DINAMIS & MODAL PREVIEW
     * ---------------------------------------------------------------------- */
    const barcodeModal = document.getElementById('barcodePreviewModal');
    const barcodeSvgTarget = document.getElementById('barcodeSvgTarget');
    const barcodeSkuLabel = document.getElementById('barcodeSkuLabel');
    const barcodeNameLabel = document.getElementById('barcodeNameLabel');
    const btnCancelBarcode = document.getElementById('btnCancelBarcode');
    const btnCloseBarcodeModal = document.getElementById('btnCloseBarcodeModal');
    const btnPrintBarcodeLabel = document.getElementById('btnPrintBarcodeLabel');

    function generateCode128Svg(code) {
        // Pola garis barcode Code 128 deterministik berbasis hash karakter string
        let barsHtml = '';
        let x = 10;
        const barHeight = 55;

        // Guard pattern awal
        barsHtml += `<rect x="${x}" y="0" width="3" height="${barHeight}" fill="#0f172a" />`;
        x += 5;
        barsHtml += `<rect x="${x}" y="0" width="2" height="${barHeight}" fill="#0f172a" />`;
        x += 4;

        // Render batang untuk setiap karakter
        for (let i = 0; i < code.length; i++) {
            const charCode = code.charCodeAt(i);
            const w1 = ((charCode % 3) + 1);
            const w2 = (((charCode >> 1) % 2) + 1);
            const w3 = (((charCode >> 2) % 3) + 1);
            const gap = ((charCode % 2) + 2);

            barsHtml += `<rect x="${x}" y="0" width="${w1}" height="${barHeight}" fill="#0f172a" />`;
            x += w1 + gap;
            barsHtml += `<rect x="${x}" y="0" width="${w2}" height="${barHeight}" fill="#0f172a" />`;
            x += w2 + gap;
            barsHtml += `<rect x="${x}" y="0" width="${w3}" height="${barHeight}" fill="#0f172a" />`;
            x += w3 + gap;
        }

        // Guard pattern akhir
        barsHtml += `<rect x="${x}" y="0" width="3" height="${barHeight}" fill="#0f172a" />`;
        x += 5;
        barsHtml += `<rect x="${x}" y="0" width="2" height="${barHeight}" fill="#0f172a" />`;
        x += 15;

        return `<svg viewBox="0 0 ${x} ${barHeight}" class="h-16 w-auto max-w-full" xmlns="http://www.w3.org/2000/svg">${barsHtml}</svg>`;
    }

    document.querySelectorAll('.btn-trigger-barcode').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const sku = btn.getAttribute('data-sku') || '';
            const name = btn.getAttribute('data-name') || '';

            if (barcodeSvgTarget && barcodeSkuLabel && barcodeModal) {
                barcodeSvgTarget.innerHTML = generateCode128Svg(sku);
                barcodeSkuLabel.textContent = sku;
                if (barcodeNameLabel) barcodeNameLabel.textContent = name;
                barcodeModal.classList.add('active');
            }
        });
    });

    if (btnCancelBarcode && barcodeModal) {
        btnCancelBarcode.addEventListener('click', () => {
            barcodeModal.classList.remove('active');
        });
    }

    if (btnCloseBarcodeModal && barcodeModal) {
        btnCloseBarcodeModal.addEventListener('click', () => {
            barcodeModal.classList.remove('active');
        });
    }

    if (btnPrintBarcodeLabel) {
        btnPrintBarcodeLabel.addEventListener('click', () => {
            window.print();
        });
    }

    if (barcodeModal) {
        barcodeModal.addEventListener('click', (e) => {
            if (e.target === barcodeModal) {
                barcodeModal.classList.remove('active');
            }
        });
    }

});
