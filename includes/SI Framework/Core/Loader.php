<?php
namespace SI_CSAP\Core;

if (!defined('ABSPATH')) { exit; }

class Loader {
    public static function register() {
        spl_autoload_register([self::class, 'autoload']);
    }

    public static function autoload(string $class) {
        $prefix = 'SI_CSAP\\';
        $base_dir = SI_CAP_PLUGIN_DIR . 'includes/';
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) { return; }
        $relative_class = substr($class, $len);
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
        if (file_exists($file)) { require $file; }
    }
}
