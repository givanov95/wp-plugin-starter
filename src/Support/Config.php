<?php

namespace WpPluginStarter\Support;

/**
 * Lightweight config repository.
 *
 * Reads PHP files from a directory (each file returns an array) and exposes
 * dot-notation access: config('app.vite.dev_server_url').
 */
class Config
{
    private array $items = [];
    private bool $loaded = false;

    public function __construct(private readonly string $directory)
    {
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $this->ensureLoaded();

        $segments = explode('.', $key);
        $value = $this->items;

        foreach ($segments as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }
            $value = $value[$segment];
        }

        return $value;
    }

    public function set(string $key, mixed $value): void
    {
        $this->ensureLoaded();

        $segments = explode('.', $key);
        $ref = &$this->items;

        foreach ($segments as $i => $segment) {
            if ($i === array_key_last($segments)) {
                $ref[$segment] = $value;
                return;
            }
            if (!isset($ref[$segment]) || !is_array($ref[$segment])) {
                $ref[$segment] = [];
            }
            $ref = &$ref[$segment];
        }
    }

    public function all(): array
    {
        $this->ensureLoaded();
        return $this->items;
    }

    private function ensureLoaded(): void
    {
        if ($this->loaded) {
            return;
        }
        $this->loaded = true;

        if (!is_dir($this->directory)) {
            return;
        }

        foreach (glob(rtrim($this->directory, '/') . '/*.php') ?: [] as $file) {
            $name  = basename($file, '.php');
            $items = require $file;
            if (is_array($items)) {
                $this->items[$name] = $items;
            }
        }
    }
}
