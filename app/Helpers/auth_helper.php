<?php

/**
 * Helper Otorisasi & Peran Pengguna (Role-Based Access Control)
 * Web Inventory (InventarisPro)
 */

if (!function_exists('current_user_role')) {
    /**
     * Mengambil peran (role) dari pengguna yang sedang login
     *
     * @return string
     */
    function current_user_role(): string
    {
        return (string) (session()->get('role') ?? 'staff');
    }
}

if (!function_exists('has_role')) {
    /**
     * Memeriksa apakah pengguna saat ini memiliki salah satu peran yang ditentukan
     *
     * @param string|array $roles
     * @return bool
     */
    function has_role(string|array $roles): bool
    {
        if (!session()->get('isLoggedIn')) {
            return false;
        }

        $currentRole = current_user_role();

        if (is_array($roles)) {
            return in_array($currentRole, $roles, true);
        }

        return $currentRole === $roles;
    }
}

if (!function_exists('is_admin')) {
    /**
     * Memeriksa apakah pengguna yang sedang login adalah Administrator
     *
     * @return bool
     */
    function is_admin(): bool
    {
        return has_role('admin');
    }
}

if (!function_exists('is_staff')) {
    /**
     * Memeriksa apakah pengguna yang sedang login adalah Staff
     *
     * @return bool
     */
    function is_staff(): bool
    {
        return has_role('staff');
    }
}
