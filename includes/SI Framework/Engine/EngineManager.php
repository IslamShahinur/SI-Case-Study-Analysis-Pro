<?php
namespace SI_CSAP\Engine;
use SI_CSAP\Core\Container;

if (!defined('ABSPATH')) { exit; }

class EngineManager {
    private $container;
    private $modules = [];

    public function __construct(Container $container) { $this->container = $container; }

    public function register_module(string $id, callable $initializer) {
        $this->modules[$id] = $initializer;
    }

    public function boot() {
        foreach ($this->modules as $id => $initializer) {
            try { $initializer($this->container); } 
            catch (\Exception $e) { error_log('SI CSAP: Failed to boot module ' . $id . ': ' . $e->getMessage()); }
        }
        do_action('si_cap_engine_booted');
    }
}
