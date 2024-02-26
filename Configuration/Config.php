<?php

namespace Plugin\Configuration;

class Config
{
    private array $config;

    public function __construct()
    {
        $this->config = include(dirname(plugin_dir_path(__FILE__)). '/config.php');
    }

    public static function get(string $key)
    {
        $config = (new self())->config;
        $keys = explode('.', $key);

        foreach ($keys as $innerKey) {
            if (isset($config[$innerKey])) {
                $config = $config[$innerKey];
            } else {
                return null; // or throw an exception if needed
            }
        }

        return $config;
    }

}
