<?php
/**
 * Komponen Reusable: Modal Konfirmasi Dialog
 *
 * @var string $modalId ID atribut HTML untuk modal dialog
 * @var string $title Judul modal dialog
 * @var string $description Deskripsi pesan konfirmasi
 * @var string $confirmButtonText Label teks tombol konfirmasi (default: 'Ya, Lanjutkan')
 * @var string $confirmButtonClass Kelas warna tombol konfirmasi (default: 'bg-rose-600 hover:bg-rose-700')
 */
$modalId            = $modalId ?? 'confirmDialogModal';
$title              = $title ?? 'Konfirmasi Tindakan';
$description        = $description ?? 'Apakah Anda yakin ingin memproses tindakan ini? Tindakan yang telah dijalankan tidak dapat dibatalkan.';
$confirmButtonText  = $confirmButtonText ?? 'Ya, Lanjutkan';
$confirmButtonClass = $confirmButtonClass ?? 'bg-rose-600 hover:bg-rose-700';
?>

<div class="modal-overlay fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4 transition-all opacity-0 invisible" id="<?= esc($modalId) ?>" role="dialog" aria-modal="true" aria-labelledby="<?= esc($modalId) ?>Title">
    <div class="modal-box w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl border border-slate-200 transition-all">
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-50 text-rose-600 mb-4">
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                <line x1="12" y1="9" x2="12" y2="13"></line>
                <line x1="12" y1="17" x2="12.01" y2="17"></line>
            </svg>
        </div>

        <h3 class="text-base font-bold text-slate-900 mb-1.5" id="<?= esc($modalId) ?>Title">
            <?= esc($title) ?>
        </h3>
        <p class="text-xs text-slate-500 leading-relaxed mb-6 modal-description">
            <?= esc($description) ?>
        </p>

        <form method="POST" id="<?= esc($modalId) ?>Form">
            <?= csrf_field() ?>
            <div class="flex items-center justify-end gap-2.5">
                <button type="button" class="btn btn-secondary rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-all cursor-pointer modal-btn-cancel">
                    Batal
                </button>
                <button type="submit" class="btn rounded-xl px-4 py-2 text-xs font-bold text-white shadow-xs transition-all cursor-pointer <?= esc($confirmButtonClass) ?>" id="<?= esc($modalId) ?>SubmitBtn">
                    <?= esc($confirmButtonText) ?>
                </button>
            </div>
        </form>
    </div>
</div>
