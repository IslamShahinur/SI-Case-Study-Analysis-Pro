<?php
namespace SI_CSAP\Core;

if (!defined('ABSPATH')) { exit; }

class Bootstrap {
    public static function activate() {
        if (!Requirements::check()) {
            deactivate_plugins(SI_CAP_PLUGIN_BASENAME);
            wp_die(__('SI Case Study Analysis Pro requires PHP 8.0+ and WordPress 6.4+.', 'si-case-study-analysis-pro'));
        }
        update_option('si_cap_version', SI_CAP_VERSION);
        flush_rewrite_rules();
    }

    public static function deactivate() { flush_rewrite_rules(); }
    
    public static function upgrade($new_version, $old_version) { }
}
