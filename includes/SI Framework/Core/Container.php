<?php
namespace SI_CSAP\Core;

if (!defined('ABSPATH')) { exit; }

class Container {
    private $instances = [];
    private $shared = [];

    public function register(string $id, callable $concrete, bool $shared = true) {
        $this->instances[$id] = $concrete;
        if ($shared) { $this->shared[$id] = null; }
    }

    public function resolve(string $id) {
        if (!isset($this->instances[$id])) {
            throw new \Exception("Service {$id} not registered.");
        }
        if (isset($this->shared[$id]) && $this->shared[$id] !== null) {
            return $this->shared[$id];
        }
        $instance = $this->instances[$id]($this);
        if (isset($this->shared[$id])) { $this->shared[$id] = $instance; }
        return $instance;
    }

    public function get(string $id) { return $this->resolve($id); }
}
