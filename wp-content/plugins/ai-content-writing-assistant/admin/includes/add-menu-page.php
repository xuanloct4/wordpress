<?php
class AI_Writing_Assistant_Menu {

    private $admin;

    // Constructor function
    public function __construct($a) {
        // Add an action hook to create the menu page

        $this->admin = $a;

        if ($a->hasAccess()){
            add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
        }

    }


    // Function to add the menu page
    public function add_menu_page() {
        // Use the add_menu_page function to add a new menu page to the WordPress dashboard

        add_menu_page(
            __('AI Writing Assistant', 'ai-writing-assistant'), // Page title
            __('AI Writing Assistant', 'ai-writing-assistant'), // Menu title
            'publish_posts', // Capability (user role) required to access the menu page
            'ai-writing-assistant', // Menu slug (unique identifier)
            array( $this, 'render_menu_page' ), // Function to render the menu page
            'dashicons-text', // Icon URL
            4  // Menu position
        );

        add_submenu_page(
            'ai-writing-assistant', // Parent menu slug
            __('Single Content Writer', 'ai-writing-assistant'), // Page title
            __('Single Content Writer', 'ai-writing-assistant'), // Menu title
            'publish_posts', // Capability
            'single-content-writer', // Menu slug
            array($this, 'single_content_writer') // Function to display the page content
        );

        add_submenu_page(
            'ai-writing-assistant', // Parent menu slug
            __('Auto Content Writer', 'ai-writing-assistant'), // Page title
            __('Auto Content Writer', 'ai-writing-assistant'), // Menu title
            'publish_posts', // Capability
            'auto-content-writer', // Menu slug
            array($this, 'auto_content_writer') // Function to display the page content
        );
        add_submenu_page(
            'ai-writing-assistant', // Parent menu slug
            __('AI Image Creator', 'ai-writing-assistant'), // Page title
            __('AI Image Creator', 'ai-writing-assistant'), // Menu title
            'publish_posts', // Capability
            'ai-image-creator', // Menu slug
            array($this, 'image_creator_callback') // Function to display the page content
        );
        /*add_submenu_page(
            'ai-writing-assistant', // Parent menu slug
            __('GPT-3 Playground', 'ai-writing-assistant'), // Page title
            __('GPT-3 Playground', 'ai-writing-assistant'), // Menu title
            'publish_posts', // Capability
            'chat-gpt-3-playground', // Menu slug
            array($this, 'chat_gpt3_playground') // Function to display the page content
        );*/

    }

    // Function to render the menu page content
    public function render_menu_page() {
        // Render the menu page content here
        require 'menu-pages/settings-page-display.php';
    }
    public function single_content_writer() {
        require 'menu-pages/generate-single-post.php';
    }
    public function auto_content_writer() {
        require 'menu-pages/auto-content-writer.php';
    }
    public function chat_gpt3_playground() {
        echo aiwa_coming_soon();
    }
    public function image_creator_callback() {
        echo aiwa_coming_soon();
    }
}


