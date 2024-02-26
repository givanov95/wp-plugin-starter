<?php

namespace Plugin\Support\Redirects;

class Redirector
{
    public static function redirect(string $slug, bool $isAdminPanel = true)
    {
        return $isAdminPanel ? admin_url("admin.php?page=$slug") : home_url($slug);
    }
}
