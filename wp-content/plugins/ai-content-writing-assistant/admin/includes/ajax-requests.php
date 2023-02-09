<?php

namespace WpWritingAssistant;

class AjaxRequests{

    public $settingsKey = "ai_writing_assistant__";
    /**
     * AjaxRequests constructor.
     */
    public function __construct()
    {
        $this->require_ajax_files();
        $this->initAjaxClasses();
    }

    public function require_ajax_files()
    {

        require 'ajax-requests/ajax-save-settings.php';
        require 'ajax-requests/get-ai-data.php';
        require 'ajax-requests/generate-placeholders.php';
        require 'ajax-requests/generate-image.php';
        require 'ajax-requests/save-single-generated-post.php';
        require 'ajax-requests/get-intro-and-conc.php';
    }

    public function initAjaxClasses()
    {
        new AjaxRequests\SaveSettings($this);
        new AjaxRequests\GetAIDATA($this);
        new AjaxRequests\GeneratePlaceholders($this);
        new AjaxRequests\GenerateImage($this);
        new AjaxRequests\SaveSingleGeneratedPost($this);
        new AjaxRequests\GetIntroAndConc($this);
    }




    /**
     * Set AI Writing Assistant's settings
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


    /**
     * Get Supreme Cashes setting
     * @since 1.0.0
     *
     * @return void
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

new AjaxRequests();
