<?php

namespace WpPluginStarter\Models;

use WpPluginCore\Database\Database;

/**
 * Thin domain model on top of WpPluginCore\Database\Database.
 *
 * Add more methods (search, scope-likes, etc.) as your needs grow. Keeping
 * the table + allowed columns colocated here means there's one place to
 * touch when the schema changes.
 */
class Example
{
    private const TABLE = 'wp_plugin_starter_examples';

    private const COLUMNS = [
        'id',
        'title',
        'email',
        'status',
        'created_at',
    ];

    public function db(): Database
    {
        return new Database(self::TABLE, allowedColumns: self::COLUMNS);
    }

    public function create(array $data): int
    {
        return $this->db()->insert($data + [
            'created_at' => current_time('mysql'),
        ]);
    }

    public function find(int $id): ?array
    {
        return $this->db()->find($id);
    }

    public function delete(int $id): int
    {
        return $this->db()->delete(['id' => $id]);
    }

    /**
     * @return array{items: array, total: int}
     */
    public function paginate(int $page, int $perPage, ?string $status = null): array
    {
        $conditions = $status ? ['status' => $status] : [];

        return [
            'items' => $this->db()->paginate(
                $conditions,
                $page,
                $perPage,
                ['created_at' => 'DESC'],
            ),
            'total' => $this->db()->count($conditions),
        ];
    }
}
