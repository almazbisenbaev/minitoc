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
            'mini_toc_css_variables',
            'CSS Variables',
            array($this, 'css_variables_callback'),
            'mini-toc-settings',
            'mini_toc_main_section'
        );
    }

    public function sanitize_options($options) {
        $options['title'] = sanitize_text_field($options['title']);
        $options['heading_level'] = intval($options['heading_level']);
        $options['css_variables'] = sanitize_textarea_field($options['css_variables']);
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

    public function css_variables_callback() {
        $options = get_option('mini_toc_options', array('css_variables' => ''));
        ?>
        <textarea name="mini_toc_options[css_variables]" rows="10" cols="50"><?php echo esc_textarea($options['css_variables']); ?></textarea>
        <?php
    }

    public function get_css_variables() {
        $options = get_option('mini_toc_options', array('css_variables' => ''));
        $css_variables = array();
        $lines = explode("\n", $options['css_variables']);
        foreach ($lines as $line) {
            list($key, $value) = explode(':', $line, 2);
            $css_variables[trim($key)] = trim($value);
        }
        return $css_variables;
    }

    public function get_option($key, $default = null) {
        $options = get_option('mini_toc_options', array());
        return isset($options[$key]) ? $options[$key] : $default;
    }
}
