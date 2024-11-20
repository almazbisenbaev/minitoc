<?php
class Mini_TOC {
    protected static $instance = null;
    private $shortcode_used = false;
    private $css_enqueued = false;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Lower priority (higher number) to run after theme setup
        add_action('wp_enqueue_scripts', array($this, 'register_styles'), 20);
        add_filter('the_content', array($this, 'add_anchors'));
        
        // Add a late filter to ensure styles are enqueued if needed
        add_action('wp_head', array($this, 'maybe_enqueue_styles'), 999);
    }

    public function register_styles() {
        // Just register the styles, don't enqueue yet
        wp_register_style(
            'mini-toc-style', 
            plugins_url('assets/css/mini-toc-style.css', dirname(__FILE__)),
            array(),
            '1.0.0'
        );
    }

    public function maybe_enqueue_styles() {
        if ($this->shortcode_used && !$this->css_enqueued) {
            wp_enqueue_style('mini-toc-style');
            
            $css_variables = Mini_TOC_Settings::get_instance()->get_css_variables();
            $css = '';
            foreach ($css_variables as $key => $value) {
                $css .= "$key: $value;\n";
            }
            
            wp_add_inline_style('mini-toc-style', ":root {\n$css\n}");
            $this->css_enqueued = true;
        }
    }

    public function add_anchors($content) {
        $pattern = '/<h([1-6]).*?>(.*?)<\/h[1-6]>/i';
        return preg_replace_callback($pattern, function($matches) {
            $anchor = sanitize_title(strip_tags($matches[2]));
            return '<h' . $matches[1] . ' id="' . $anchor . '">' . $matches[2] . '</h' . $matches[1] . '>';
        }, $content);
    }

    public function set_shortcode_used($used = true) {
        $this->shortcode_used = $used;
        
        // If shortcode is used and styles haven't been enqueued yet, do it now
        if ($used && !$this->css_enqueued) {
            $this->maybe_enqueue_styles();
        }
    }
}