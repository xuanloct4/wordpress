<?php
namespace WpWritingAssistant;

class PluginActivator{

    public function activator()
    {
        update_option( 'aiwa_wp_plugin_activation_date', time() );
    }
}