<?php

namespace Plugin\Support\Database\Connections;

class WordpressDatabaseConnection extends Database
{
    private static $instance;
    protected $wpdb;

    private function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    public function select($table, $columns = '*', $where = '', $orderby = '', $limit = '')
    {
        // Build the SELECT query
        $query = "SELECT $columns FROM {$this->wpdb->prefix}$table";

        if (!empty($where)) {
            $query .= " WHERE $where";
        }

        if (!empty($orderby)) {
            $query .= " ORDER BY $orderby";
        }

        if (!empty($limit)) {
            $query .= " LIMIT $limit";
        }

        // Execute the query
        return $this->wpdb->get_results($query);
    }

    public function insert($table, $data)
    {
        // Prepare data for insertion
        $escaped_data = $this->prepareData($data);

        // Insert data into the database
        return $this->wpdb->insert($this->wpdb->prefix . $table, $escaped_data);
    }

    public function update($table, $data, $where)
    {
        // Prepare data for update
        $escaped_data = $this->prepareData($data);

        // Update data in the database
        return $this->wpdb->update($this->wpdb->prefix . $table, $escaped_data, $where);
    }

    public function delete($table, $where)
    {
        // Delete data from the database
        return $this->wpdb->delete($this->wpdb->prefix . $table, $where);
    }

    protected function prepareData($data)
    {
        // Escape data using $wpdb->prepare
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data[$key] = $this->wpdb->prepare('%s', $value);
            }
        }
        return $data;
    }

}
