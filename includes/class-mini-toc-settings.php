<?php
class Mini_TOC_Settings {
    protected static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('admin_menu', array($this, 'add_settings_page'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    public function add_settings_page() {
        add_options_page(
            'Mini TOC Settings',
            'Mini TOC',
            'manage_options',
            'mini-toc-settings',
            array($this, 'settings_page')
        );
    }

    public function register_settings() {
        register_setting('mini_toc_options_group', 'mini_toc_options', array($this, 'sanitize_options'));

        add_settings_section(
            'mini_toc_main_section',
            'Main Settings',
            null,
            'mini-toc-settings'
        );

        add_settings_field(
            'mini_toc_title',
            'Title',
            array($this, 'title_callback'),
            'mini-toc-settings',
            'mini_toc_main_section'
        );

        add_settings_field(
            'mini_toc_heading_level',
            'Heading Level',
            array($this, 'heading_level_callback'),
            'mini-toc-settings',
            'mini_toc_main_section'
        );

        add_settings_field(
            'mini_toc_bg_color',
            'Background Color',
            array($this, 'bg_color_callback'),
            'mini-toc-settings',
            'mini_toc_main_section'
        );

        add_settings_field(
            'mini_toc_border_color',
            'Border Color',
            array($this, 'border_color_callback'),
            'mini-toc-settings',
            'mini_toc_main_section'
        );

        add_settings_field(
            'mini_toc_border_radius',
            'Border Radius',
            array($this, 'border_radius_callback'),
            'mini-toc-settings',
            'mini_toc_main_section'
        );

        add_settings_field(
            'mini_toc_padding',
            'Padding',
            array($this, 'padding_callback'),
            'mini-toc-settings',
            'mini_toc_main_section'
        );

        add_settings_field(
            'mini_toc_margin',
            'Margin',
            array($this, 'margin_callback'),
            'mini-toc-settings',
            'mini_toc_main_section'
        );

        add_settings_field(
            'mini_toc_ul_padding',
            'UL Padding',
            array($this, 'ul_padding_callback'),
            'mini-toc-settings',
            'mini_toc_main_section'
        );

        add_settings_field(
            'mini_toc_link_color',
            'Link Color',
            array($this, 'link_color_callback'),
            'mini-toc-settings',
            'mini_toc_main_section'
        );
    }

    public function sanitize_options($options) {
        $options['title'] = sanitize_text_field($options['title']);
        $options['heading_level'] = intval($options['heading_level']);
        $options['bg_color'] = sanitize_hex_color($options['bg_color']);
        $options['border_color'] = sanitize_hex_color($options['border_color']);
        $options['border_radius'] = sanitize_text_field($options['border_radius']);
        $options['padding'] = sanitize_text_field($options['padding']);
        $options['margin'] = sanitize_text_field($options['margin']);
        $options['ul_padding'] = sanitize_text_field($options['ul_padding']);
        $options['link_color'] = sanitize_hex_color($options['link_color']);
        return $options;
    }

    public function settings_page() {
        ?>
        <div class="wrap">
            <h1>Mini TOC Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('mini_toc_options_group');
                do_settings_sections('mini-toc-settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function title_callback() {
        $options = get_option('mini_toc_options', array('title' => 'Table of Contents'));
        ?>
        <input type="text" name="mini_toc_options[title]" value="<?php echo esc_attr($options['title']); ?>" />
        <?php
    }

    public function heading_level_callback() {
        $options = get_option('mini_toc_options', array('heading_level' => 1));
        ?>
        <select name="mini_toc_options[heading_level]">
            <?php for ($i = 1; $i <= 6; $i++) : ?>
                <option value="<?php echo esc_attr($i); ?>" <?php selected($options['heading_level'], $i); ?>><?php echo esc_html("From H$i and lower"); ?></option>
            <?php endfor; ?>
        </select>
        <?php
    }

    public function bg_color_callback() {
        $options = get_option('mini_toc_options', array('bg_color' => '#ffffff11'));
        ?>
        <input type="text" name="mini_toc_options[bg_color]" value="<?php echo esc_attr($options['bg_color']); ?>" />
        <?php
    }

    public function border_color_callback() {
        $options = get_option('mini_toc_options', array('border_color' => '#ffffff11'));
        ?>
        <input type="text" name="mini_toc_options[border_color]" value="<?php echo esc_attr($options['border_color']); ?>" />
        <?php
    }

    public function border_radius_callback() {
        $options = get_option('mini_toc_options', array('border_radius' => '10px'));
        ?>
        <input type="text" name="mini_toc_options[border_radius]" value="<?php echo esc_attr($options['border_radius']); ?>" />
        <?php
    }

    public function padding_callback() {
        $options = get_option('mini_toc_options', array('padding' => '20px'));
        ?>
        <input type="text" name="mini_toc_options[padding]" value="<?php echo esc_attr($options['padding']); ?>" />
        <?php
    }

    public function margin_callback() {
        $options = get_option('mini_toc_options', array('margin' => '1em 0'));
        ?>
        <input type="text" name="mini_toc_options[margin]" value="<?php echo esc_attr($options['margin']); ?>" />
        <?php
    }

    public function ul_padding_callback() {
        $options = get_option('mini_toc_options', array('ul_padding' => '20px'));
        ?>
        <input type="text" name="mini_toc_options[ul_padding]" value="<?php echo esc_attr($options['ul_padding']); ?>" />
        <?php
    }

    public function link_color_callback() {
        $options = get_option('mini_toc_options', array('link_color' => 'red'));
        ?>
        <input type="text" name="mini_toc_options[link_color]" value="<?php echo esc_attr($options['link_color']); ?>" />
        <?php
    }

    public function get_css_variables() {
        $options = get_option('mini_toc_options', array(
            'bg_color' => '#ffffff11',
            'border_color' => '#ffffff11',
            'border_radius' => '10px',
            'padding' => '20px',
            'margin' => '1em 0',
            'ul_padding' => '20px',
            'link_color' => 'red'
        ));
        return array(
            '--minitoc-bg-color' => $options['bg_color'],
            '--minitoc-border-color' => $options['border_color'],
            '--minitoc-border-radius' => $options['border_radius'],
            '--minitoc-padding' => $options['padding'],
            '--minitoc-margin' => $options['margin'],
            '--minitoc-ul-padding' => $options['ul_padding'],
            '--minitoc-link-color' => $options['link_color'],
        );
    }

    public function get_option($key, $default = null) {
        $options = get_option('mini_toc_options', array());
        return isset($options[$key]) ? $options[$key] : $default;
    }
}
