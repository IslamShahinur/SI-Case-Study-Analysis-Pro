<?php
namespace SI_CSAP\Core;

if (!defined('ABSPATH')) { exit; }

class Requirements {
    public static function check(): bool {
        return self::check_php_version() && self::check_wp_version();
    }

    private static function check_php_version(): bool {
        return version_compare(PHP_VERSION, '8.0', '>=');
    }

    private static function check_wp_version(): bool {
        global $wp_version;
        return version_compare($wp_version, '6.4', '>=');
    }
}
