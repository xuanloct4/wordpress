<?php

// Declare a namespace for the class
namespace WpWritingAssistant;

// Define the AI_Writing_Assistant_Admin class
class AI_Writing_Assistant_Admin{

    // Declare a protected property to store the settings key
    protected $settingsKey = "ai_writing_assistant__";

    // Define the constructor method
    public function __construct()
    {
        // Call the require_admin_dependencies method
        $this->require_admin_dependencies();
        // Call the initWpActions method
        $this->initWpActions();
    }

    // Define the require_admin_dependencies method
    public function require_admin_dependencies()
    {
        // Require the add-menu-page.php file
        require_once 'includes/add-menu-page.php';
        // Create an instance of the AI_Writing_Assistant_Menu class
        new \AI_Writing_Assistant_Menu($this);

        //include admin global functions
        include 'includes/admin-global.php';

        //include aiwa settings items
        include 'includes/settings-menu-items.php';

        // Require all the ajax functionality
        require_once 'includes/ajax-requests.php';

        require_once 'includes/register-meta-boxes.php';
        new AddMetaBoxes_($this);

        // Require cdata
        require_once 'includes/cdata.php';

        require_once 'includes/review-notice.php';

        // Register AI custom metaboxes
        //require_once 'includes/meta-boxes.php';

        //new \AddMetaBoxes();
    }

    // Define the initWpActions method
    public function initWpActions()
    {
        if ($this->hasCurrentPostType()
            ||(isset($_GET['page']) && sanitize_text_field($_GET['page'])=='ai-writing-assistant')
            ||(isset($_GET['page']) && sanitize_text_field($_GET['page'])=='single-content-writer')
            ||(isset($_GET['page']) && sanitize_text_field($_GET['page'])=='auto-content-writer')
            ||(isset($_GET['page']) && sanitize_text_field($_GET['page'])=='ai-image-creator')
            ||(isset($_GET['page']) && sanitize_text_field($_GET['page'])=='chat-gpt-3-playground')
        ){
            add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
        }

        if (isset($_GET['page']) && sanitize_text_field($_GET['page'])=='auto-content-writer'){
            add_action('aiwa_after_promptbox_language', array($this, 'aiwa_after_promptbox_language'));
            add_filter('aiwa_promptbox_title', array($this, 'promptbox_title_for_auto_write'));
            add_filter('aiwa_metabox_settings', array($this, 'metabox_settings'));
        }
    }
    public function hasAccess()
    {
        require( ABSPATH . WPINC . '/pluggable.php' );
        $capabilities = $this->getSettings('user_roles',array('administrator'));

        if (!empty($capabilities)){
            foreach ($capabilities as $cap) {
                if (current_user_can($cap)){
                    return true;
                    break;
                }
            }
        }
        if (current_user_can('administrator')){
            return true;
        }
        return false;
    }
    public function hasCurrentPostType()
    {
        $postTypes = $this->getSettings('post_types', array('post', 'page', 'product'));

        if (in_array(aiwa_get_post_type(), $postTypes)){
            return true;
        }
        return false;
    }

    /**
     * Set Supreme Cache's settings
     * @since 1.0.0
     *
     * @return bool
     */
    public function setSettings( $settings_name="", $value ="")
    {
        if(!empty($settings_name)){
            $settings_name = $this->settingsKey . $settings_name;
            update_option($settings_name, $value);
        }
        return true;
    }

    public function enqueue_scripts()
    {
        wp_enqueue_style( 'jquery-ui-style' );
        wp_enqueue_style('ai-writing-assistant', AIWA_DIR_URL . '/admin/assets/css/ai-writing-assistant.css', array(), AIWA_VERSION);
        wp_enqueue_style('ai-promptbox', AIWA_DIR_URL . '/admin/assets/css/ai-writing-assistant-promptbox.css', array(), AIWA_VERSION);
        wp_enqueue_style('aiwa-timepicki', AIWA_DIR_URL . '/admin/assets/css/timepicki.css', array(), AIWA_VERSION);
        wp_enqueue_media();
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script('aiwa-clockinput', AIWA_DIR_URL . '/admin/assets/js/jquery.clockinput.min.js', array('jquery'), AIWA_VERSION, true);
        wp_enqueue_script('aiwa-time-dropper', AIWA_DIR_URL . '/admin/assets/js/timedropper-jquery.js', array('jquery'), AIWA_VERSION, true);
        wp_enqueue_script('aiwa-data-dropper', AIWA_DIR_URL . '/admin/assets/js/datedropper-javascript.js', array('jquery'), AIWA_VERSION, true);
        wp_enqueue_script('ai-writing-assistant', AIWA_DIR_URL . '/admin/assets/js/ai-writing-assistant.js', array('jquery', 'aiwa-clockinput', 'aiwa-data-dropper', 'aiwa-time-dropper'), AIWA_VERSION, true);
        wp_enqueue_script('ai-promptbox', AIWA_DIR_URL . '/admin/assets/js/ai-promptbox.js', array('jquery'), AIWA_VERSION, true);
        wp_enqueue_script('ai-promptbox-button', AIWA_DIR_URL . '/admin/assets/js/ai-promptbox-button.js', array('wp-components'), AIWA_VERSION, true);

        if(esc_attr(get_option('ai_writing_assistant__collapse_aiwa', 'on')) == 'on'){
            wp_add_inline_script('ai-promptbox', 'jQuery(document).ready((function(t){t(document).on("click",".code-box-insert-btn",(function(n){n.preventDefault(),0==t(".aiwa-single-generation-page").length&&(setTimeout((function(){t(".aiwa-writing-assistant-btn").removeClass("activate")}),500),t("#ai-writing-assistant-promptbox").slideUp(500))}))}));');
        }

    }

    public function metabox_settings($settings)
    {

        return array('content_settings', 'advanced_settings', 'language', 'content_length', 'include_keywords', 'writing_style', 'writing_tone', 'save_future');
    }

    public function aiwa_after_promptbox_language()
    {

        $key = 'ai_writing_assistant__';
        ?>
        <input type="hidden" name="aiwa_auto_content_settings" value="true">
        <div class="settings-item">
            <label for="aiwa-how-many-titles"><span><?php _e('How many titles', 'ai-writing-assistant'); ?></span>
                <input id="aiwa-how-many-titles" class="content-settings-input" type="number" name="titles-count" value="<?php echo esc_attr(get_option($key.'titles-count', '5')); ?>" placeholder="5">
            </label>
            <p><?php _e('Enter a number of topics you want to add to your blog post.', 'ai-writing-assistant'); ?></p>
        </div>
    <?php
        aiwa_post_types_and_categories();
    }

    public function promptbox_title_for_auto_write($title)
    {
        $title .= __(' (write a topic to get blog titles)', 'ai-writing-assistant');

        return $title;
    }

    /**
     * Get AI Writing Assistant setting
     * @since 1.0.0
     *
     * @return string | array
     */
    public function getSettings( $settings_name="", $default = "")
    {
        $settings_name = $this->settingsKey . $settings_name;
        $rc_sc_settings = get_option($settings_name);

        if(empty($rc_sc_settings) && !empty($default)){
            return $default;
        }

        return $rc_sc_settings;
    }


    /**
     * Remove Supreme Cache setting
     * @since 1.0.0
     *
     * @return bool
     */
    public function removeSettings( $settings_name="")
    {
        $settings_name = $this->settingsKey . $settings_name;
        $rc_sc_settings = delete_option($settings_name);

        if ($rc_sc_settings) {
            return true;
        }
        return false;
    }


    /**
     * Remove all Supreme Cache settings
     * @since 1.0.0
     *
     * @return bool
     */

    public function removeAllSettings()
    {
        global $wpdb;
        $removefromdb = $wpdb->query("UPDATE {$wpdb->prefix}options SET option_value = '' WHERE option_name LIKE '{$this->settingsKey}%'");

        if ($removefromdb) {
            return true;
        }
        return false;
    }

}



