<?php
namespace SI_CSAP\Engine;

if (!defined('ABSPATH')) { exit; }

class OwnershipManager {
    public function execute_mutation(callable $callback, string $context = 'unknown') {
        do_action('si_cap_pre_mutation', $context);
        try {
            $result = $callback();
            do_action('si_cap_post_mutation', $context, $result);
            return $result;
        } catch (\Exception $e) {
            do_action('si_cap_mutation_failed', $context, $e);
            throw $e;
        }
    }
}
