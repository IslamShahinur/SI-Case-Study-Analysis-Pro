<?php
namespace SI_CSAP\Core;
use SI_CSAP\Engine\EngineManager;
use SI_CSAP\Engine\OwnershipManager;

if (!defined('ABSPATH')) { exit; }

class Application {
    private static $instance = null;
    private $container;

    public static function get_instance(): Application {
        if (self::$instance === null) { self::$instance = new self(); }
        return self::$instance;
    }

    private function __construct() {
        $this->container = new Container();
        $this->register_services();
    }

    private function register_services() {
        $this->container->register('engine_manager', function($c) { return new EngineManager($c); });
        $this->container->register('ownership_manager', function($c) { return new OwnershipManager(); });
    }

    public function run() {
        load_plugin_textdomain('si-case-study-analysis-pro', false, dirname(SI_CAP_PLUGIN_BASENAME) . '/languages');
        $this->container->get('engine_manager')->boot();
        do_action('si_cap_loaded');
    }

    public function get_container(): Container { return $this->container; }
}
