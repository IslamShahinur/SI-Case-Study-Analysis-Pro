<?php
namespace SI_CSAP\Engine;

if (!defined('ABSPATH')) { exit; }

class Renderer {
    public function render(string $template_name, array $args = [], bool $echo = true): string {
        $template_path = locate_template($template_name) ?: SI_CAP_PLUGIN_DIR . 'templates/' . $template_name;
        if (!file_exists($template_path)) { return ''; }
        ob_start();
        extract($args);
        include $template_path;
        $content = ob_get_clean();
        if ($echo) { echo $content; }
        return $content;
    }
}
