<?php

namespace WpPluginStarter\Http;

use WP_REST_Request;
use WpPluginCore\Controllers\Controller as CoreController;

/**
 * Thin wrapper around WP_REST_Request that adds Laravel-like accessors and
 * a validate() shortcut backed by WpPluginCore\Controllers\Controller.
 *
 * Construct via Request::fromRest($wpRequest) inside a controller method.
 */
class Request
{
    public function __construct(public readonly WP_REST_Request $wp)
    {
    }

    public static function fromRest(WP_REST_Request $request): self
    {
        return new self($request);
    }

    /**
     * All input — JSON body merged with query params (JSON wins).
     */
    public function all(): array
    {
        $json  = $this->wp->get_json_params() ?? [];
        $query = $this->wp->get_query_params() ?? [];
        $body  = $this->wp->get_body_params() ?? [];

        return array_merge($query, $body, $json);
    }

    public function input(string $key, mixed $default = null): mixed
    {
        $all = $this->all();
        return $all[$key] ?? $default;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->all());
    }

    /**
     * Returns only the given keys, ignoring missing ones.
     */
    public function only(array $keys): array
    {
        return array_intersect_key($this->all(), array_flip($keys));
    }

    /**
     * Validate the request against a rules array. Returns the validated subset.
     * Throws InvalidArgumentException on failure (handled by Controller).
     *
     * @param array<string, array{required?: bool, rule?: \WpPluginCore\Enums\ValidationRule}> $rules
     */
    public function validated(array $rules): array
    {
        return (new class () extends CoreController {
            public function run(array $data, array $rules): array
            {
                return $this->validate($data, $rules);
            }
        })->run($this->all(), $rules);
    }

    public function header(string $name): ?string
    {
        $value = $this->wp->get_header($name);
        return $value !== '' ? $value : null;
    }

    public function ip(): ?string
    {
        $candidates = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
        foreach ($candidates as $key) {
            if (!empty($_SERVER[$key])) {
                return trim(explode(',', (string) $_SERVER[$key])[0]);
            }
        }
        return null;
    }
}
