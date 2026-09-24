<?php
/**
 * ==============================================================================
 * FILE: includes/footer.php
 * DESKRIPSI: Template Footer HTML & Pemanggilan Skrip Interaksi JavaScript
 * ==============================================================================
 * Berisi penutup kontainer halaman, informasi hak cipta / footer, dan script app.js.
 */
?>
        </div> <!-- End .container -->
    </main> <!-- End main -->

    <!-- =========================================================================
     * FOOTER APLIKASI
     * ========================================================================= -->
    <footer class="footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> <strong>InventarisPro</strong> - Aplikasi Web PHP Modern (Login + CRUD).</p>
            <p style="font-size: 0.75rem; margin-top: 4px; color: #94a3b8;">Dibuat dengan PHP Native, PDO, MySQL, & CSS Custom.</p>
        </div>
    </footer>

    <!-- Skrip Interaksi JavaScript Utama -->
    <script src="assets/js/app.js"></script>
</body>
</html>
