<?php
class Mini_TOC_Shortcode {
    protected static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_shortcode('minitoc', array($this, 'minitoc_shortcode'));
    }

    public function minitoc_shortcode($atts) {
        global $post;
        $content = $post->post_content;

        $atts = shortcode_atts(array(
            'title' => Mini_TOC_Settings::get_instance()->get_option('title', 'Table of Contents'),
            'level' => Mini_TOC_Settings::get_instance()->get_option('heading_level', 1),
        ), $atts);

        // Mark the shortcode as used
        Mini_TOC::get_instance()->set_shortcode_used(true);

        // Find all headings
        preg_match_all('/<h([1-6]).*?>(.*?)<\/h[1-6]>/i', $content, $matches, PREG_SET_ORDER);

        if (empty($matches)) {
            return '';
        }

        $toc = '';
        if (!empty($atts['title'])) {
            $toc .= '<h2>' . esc_html($atts['title']) . '</h2>';
        }

        $toc .= '<ul class="minitoc">';
        $current_depth = 0;

        foreach ($matches as $match) {
            $depth = $match[1];
            $title = strip_tags($match[2]);
            $anchor = sanitize_title($title);

            if ($depth < $atts['level']) {
                continue;
            }

            // Add closing tags for previous levels if necessary
            while ($current_depth > $depth) {
                $toc .= '</ul></li>';
                $current_depth--;
            }

            // Add opening tags for new levels if necessary
            while ($current_depth < $depth) {
                $toc .= '<ul>';
                $current_depth++;
            }

            $toc .= '<li><a href="#' . $anchor . '">' . $title . '</a>';

            if ($current_depth == $depth) {
                $toc .= '</li>';
            }
        }

        // Close any remaining open tags
        while ($current_depth > 0) {
            $toc .= '</ul></li>';
            $current_depth--;
        }

        $toc .= '</ul>';

        return $toc;
    }
}
