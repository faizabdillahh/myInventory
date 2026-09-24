<?php
/**
 * Komponen Reusable: Badge Indikator Status Stok Semantik (WCAG 2.1 AA)
 * 
 * @var int $currentStock Kuantitas stok fisik saat ini
 * @var int $minStock Batas minimum restock barang (default: 5)
 */
$currentStock = (int)($currentStock ?? 0);
$minStock     = (int)($minStock ?? 5);
?>

<?php if ($currentStock <= 0): ?>
    <span class="badge inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200/60" title="Stok Habis di Gudang">
        <span class="pulse-dot bg-rose-500 shrink-0"></span>
        <span>Stok Habis (0)</span>
    </span>
<?php elseif ($currentStock <= $minStock): ?>
    <span class="badge inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200/60" title="Batas Minimum Restock: <?= $minStock ?> unit">
        <span class="pulse-dot bg-amber-500 shrink-0"></span>
        <span>Menipis (<?= $currentStock ?> / Min: <?= $minStock ?>)</span>
    </span>
<?php else: ?>
    <span class="badge inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60" title="Batas Minimum Restock: <?= $minStock ?> unit">
        <span class="h-2 w-2 rounded-full bg-emerald-500 shrink-0"></span>
        <span>Tersedia (<?= $currentStock ?>)</span>
    </span>
<?php endif; ?>
