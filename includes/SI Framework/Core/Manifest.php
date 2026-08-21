<?php
namespace SI_CSAP\Core;

if (!defined('ABSPATH')) { exit; }

class Manifest {
    private static $data = null;

    public static function get(): array {
        if (self::$data === null) {
            self::$data = include SI_CAP_PLUGIN_DIR . 'manifest.php';
        }
        return self::$data;
    }

    public static function get_value(string $key, $default = null) {
        $data = self::get();
        return $data[$key] ?? $default;
    }
}
