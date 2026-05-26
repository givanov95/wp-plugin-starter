<?php

declare(strict_types=1);

namespace WpPluginStarter\Tests;

use PHPUnit\Framework\TestCase;

final class SmokeTest extends TestCase
{
    public function test_autoload_and_dependencies_are_wired(): void
    {
        // helpers.php (files autoload) defines the global helpers.
        $this->assertTrue(function_exists('config'));
        $this->assertTrue(function_exists('plugin_path'));

        // Core runtime dependencies resolve.
        $this->assertTrue(class_exists(\Illuminate\Support\Collection::class));
        $this->assertTrue(class_exists(\Carbon\Carbon::class));
        $this->assertTrue(class_exists(\WpPluginCore\Enums\ValidationRule::class));
    }
}
