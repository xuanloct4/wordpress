<?php
namespace WpWritingAssistant;

class AddMetaBoxes_{
    private $admin;
    /**
     * AddMetaBoxes constructor.
     */
    public function __construct($a)
    {
        $this->admin = $a;
        //add_action( 'add_meta_boxes', [$this, 'my_custom_meta_box'] );

        if ($a->hasAccess() && ($a->hasCurrentPostType())
            ||(isset($_GET['page']) && sanitize_text_field($_GET['page'])=='ai-writing-assistant')
            ||(isset($_GET['page']) && sanitize_text_field($_GET['page'])=='single-content-writer')
            ||(isset($_GET['page']) && sanitize_text_field($_GET['page'])=='auto-content-writer')
            ||(isset($_GET['page']) && sanitize_text_field($_GET['page'])=='ai-image-creator')
            ||(isset($_GET['page']) && sanitize_text_field($_GET['page'])=='chat-gpt-3-playground')
        ){
            add_action('admin_footer', array($this, 'getpromptHtml'));
            add_action( 'add_meta_boxes', array($this, 'aiwa_writing_assistant_metabox' ));
        }

    }

    public function aiwa_writing_assistant_metabox() {
        add_meta_box(
            'aiwa_ai_metabox', // Unique ID
            __('AI Writing Assistant', 'ai-writing-assistant'), // Title
            array($this, 'aiwa_writing_assistant_metabox_html'), // Callback function
            array('post', 'page', 'product'), // Screen (post, page, link, attachment, or custom post type)
            'side', // Context (normal, advanced, or side)
            'high' // Priority (high, core, default, or low)
        );
    }

    public function aiwa_writing_assistant_metabox_html() {
        echo '<a id="aiwa-writing-assistant-btn" class="components-button aiwa-writing-assistant-btn aiwa-button" href="#">AI Writing Assistant <span class="aiwa_spinner hide_spin"></span></a><style>.components-button.aiwa-writing-assistant-btn.aiwa-button{padding:10px 12px;text-decoration:none;margin-left:0!important;height:auto!important}</style>';
    }


    public function getpromptHtml()
    {
        $placeholders = 'The future of the AI, Will Artificial Intelligence Lead to Job Losses?, Historical places of US, Quantum computing and its applications,Cybersecurity and data privacy,Climate change and its impact on the world,The future of AI,Artificial intelligence and machine learning,Sustainable energy sources and technologies,Global economic trends and forecasting,The future of transportation and mobility,Biotechnology and genetic engineering,The impact of social media on society,Blockchain technology and its uses,The future of healthcare and medicine,Space exploration and colonization,The future of work and the impact of automation,The impact of the internet on education,Virtual reality and its applications,The future of urban development and smart cities,Gaming and its impact on society,The future of food and agriculture';
        if (!empty(esc_attr(get_option('aiwa-placeholders', '')))){
            $placeholders .= ',' .  esc_attr(get_option('aiwa-placeholders', 'The history of AI'));
        }
        $title = __('AI/GPT-3 Prompt', 'ai-writing-assistant');
        $promptBoxTitle = apply_filters('aiwa_promptbox_title', $title);

        $settingsInclude = apply_filters('aiwa_metabox_settings', array('content_settings', 'advanced_settings', 'response_panel', 'language', 'post_title', 'content_structure', 'content_length', 'include_keywords', 'keyword_bold', 'exclude_keywords', 'writing_style', 'writing_tone', 'introduction', 'conclusion', 'cta', 'generate_images', 'save_future'));

        ?>
        <div id="aiwa-prompt-box-holder" style="display: none">
            <div id="ai-writing-assistant-promptbox" class="edit-post-visual-editor__ai-writing-assistant-wrapper aiwa-hidden">
                <div class="prompt-container clearfix">
                    <label for="prompt-input"><?php echo esc_attr($promptBoxTitle); ?></label> <br> <input id="prompt-input" type="text" name="prompt" class="clearfix" placeholder="<?php _e('Ameraca\'s historical places', 'ai-writing-assistant'); ?>">
                    <div id="ai-response" class="ai_response_hidden">
                        <div class="code-box code-box-dark">
                            <div class="code-box-header">
                                <button class="code-box-copy-btn" title="<?php _e('Copy to clipboard', 'ai-writing-assistant'); ?>">
                                    <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                        <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                                    </svg>
                                    <span style="color: #333;"><?php _e('Copy', 'ai-writing-assistant'); ?></span>
                                </button>
                                <button class="code-box-insert-btn" title="<?php _e('Insert as content', 'ai-writing-assistant'); ?>" style="display: flex;align-items: center;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="#000000" width="24px" height="24px" viewBox="0 0 32 32">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                        <g id="SVGRepo_iconCarrier">
                                            <defs>
                                                <style>.cls-1{fill:#333;}</style>
                                            </defs>
                                            <g data-name="insert icon" id="insert_icon">
                                                <path class="cls-1" d="M28,14H24a1,1,0,0,0,0,2h1.11c-.46,5.05-4.37,9-9.11,9s-8.65-3.95-9.11-9H8a1,1,0,0,0,0-2H4a1,1,0,0,0,0,2h.89C5.37,22.15,10.16,27,16,27s10.63-4.85,11.11-11H28a1,1,0,0,0,0-2Z"></path>
                                                <path class="cls-1" d="M12.06,11h0a1,1,0,0,0,1-1L13,6a1,1,0,0,0-1-1,1,1,0,0,0-1,1l.06,4A1,1,0,0,0,12.06,11Z"></path>
                                                <path class="cls-1" d="M20.06,11h0a1,1,0,0,0,1-1L21,6a1,1,0,0,0-1-1,1,1,0,0,0-1,1l.06,4A1,1,0,0,0,20.06,11Z"></path>
                                            </g>
                                        </g>
                                    </svg>
                                    <span><?php _e('Insert', 'ai-writing-assistant'); ?></span>
                                </button>
                            </div>
                            <pre class="code-box-pre aiwa-hidden"><textarea class="code-box-code"></textarea></pre>

                            <div class="aiwa-blog-post"></div>

                        </div>
                    </div>
                    <div class="ai-footer-buttons clearfix">
                        <div class="promptbox-left-buttons">
                            <?php if(in_array('content_settings', $settingsInclude)): ?>
                                <div class="aiwa-promptbox-button">
                                    <a href="#" id="aiwa-content-settings-btn" class="aiwa-settings-btn" data-settings="content-settings"><?php _e('Content settings', 'ai-writing-assistant'); ?></a>
                                </div>
                            <?php endif; ?>
                            <?php if(in_array('advanced_settings', $settingsInclude)): ?>
                                <div class="aiwa-promptbox-button">
                                    <a href="#" id="aiwa-advanced-settings-btn" class="aiwa-settings-btn" data-settings="advanced-settings"><?php _e('API Settings', 'ai-writing-assistant'); ?></a>
                                </div>
                            <?php endif; ?>
                            <?php do_action('aiwa_promptbox_footer_buttons'); ?>
                        </div>

                        <button id="aiwa-generate-ai-content" class="aiwa-button generate-ai-content" role="button" style="float: right;">
                            <span class="title"><?php _e('Generate', 'ai-writing-assistant'); ?></span>
                            <span class="aiwa_spinner hide_spin"></span>
                        </button>
                        <span style="font-size: 13px;font-weight: normal;float: right;margin-right: 10px;margin-top: 7px;padding: 6px 4px;" class="generation-complete badge badge-success aiwa-hidden"><?php _e('Generation Completed!', 'ai-writing-assistant'); ?></span>
                        <span style="font-size: 13px;font-weight: normal;float: right;margin-right: 10px;margin-top: 7px;padding: 6px 4px;" class="empty-prompt badge badge-danger aiwa-hidden"><?php _e('Please enter a prompt to generate contents.', 'ai-writing-assistant'); ?></span>
                        <a href="#" style="float:right;margin-right: 10px;" id="aiwa-cancel-btn" class="aiwa-cancel-btn aiwa-hidden"><?php _e('Cancel', 'ai-writing-assistant'); ?></a>
                        <span style="font-size: 11px; font-weight: normal; float: right; margin-right: 10px; margin-top: 7px; padding: 6px 4px;" class="featured-image-generation-complete badge badge-success aiwa-hidden"><?php _e('Featured image generation Completed!', 'ai-writing-assistant'); ?></span>
                    </div>

                    <form id="aiwa-ai-inputs" action="">
                        <div class="ai_response_hidden prompt-settings-item" id="content-settings" data-tab="content-settings"> <!--ai_response_hidden-->

                            <?php if(in_array('content_settings', $settingsInclude)) : ?>
                            <div class="content-settings-box code-box-dark">
                                <div class="code-box-header">
                                    <div class="title"><?php _e('Content Settings', 'ai-writing-assistant'); ?></div>
                                    <button class="minimize-btn" title="<?php _e('Minimize content settings panel', 'ai-writing-assistant'); ?>">
                                        <svg fill="#000000" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" id="minus" class="icon glyph" stroke="#000000" width="24px" height="24px">
                                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                            <g id="SVGRepo_iconCarrier">
                                                <path d="M19,13H5a1,1,0,0,1,0-2H19a1,1,0,0,1,0,2Z"></path>
                                            </g>
                                        </svg>
                                    </button>
                                </div>
                                <div class="aiwa-content-settings-panel aiwa-settings-panel-item">


                                    <?php
                                        if (in_array('language', $settingsInclude)){
                                            aiwa_languages_settings_items();
                                        }
                                    ?>

                                    <?php do_action('aiwa_after_promptbox_language'); ?>

                                    <?php
                                        if (in_array('content_structure', $settingsInclude)) {
                                            aiwa_content_structure_settings_item();
                                        }
                                    ?>

                                    <?php
                                        if (in_array('content_length', $settingsInclude)) {
                                            aiwa_content_length_settings_items();
                                        }
                                    ?>
                                    <?php if(in_array('include_keywords', $settingsInclude)): ?>
                                        <div class="settings-item">
                                            <label for="aiwa-include-keywords"><span><?php _e('Include Keywords', 'ai-writing-assistant'); ?></span>
                                                <input id="aiwa-include-keywords" class="content-settings-input" type="text" value="" name="include-keywords" placeholder="New York, Washington, Grand Canyon">
                                            </label>
                                            <p><?php _e('Enter some keywords to include these in generated content. Seperated by comma (,). AI may create related keywords of these keywords strong as well.', 'ai-writing-assistant'); ?></p>
                                        </div>
                                    <?php endif; ?>
                                    <?php if(in_array('keyword_bold', $settingsInclude)): ?>
                                        <div class="settings-item">
                                            <label for="aiwa-mark-keywords"><span><?php _e('Mark Keywords as bold', 'ai-writing-assistant'); ?></span>
                                                <input id="aiwa-mark-keywords" class="content-settings-input" type="checkbox" name="bold-keyword">
                                            </label>
                                            <p><?php _e('If checked then above keywords will bold/strong in the content.', 'ai-writing-assistant'); ?></p>
                                        </div>
                                    <?php endif; ?>

                                    <?php if(in_array('keyword_bold', $settingsInclude)): ?>
                                        <div class="settings-item">
                                            <label for="aiwa-exclude-keywords"><span><?php _e('Exclude Keywords', 'ai-writing-assistant'); ?></span>
                                                <input id="aiwa-exclude-keywords" class="content-settings-input" type="text" value="" name="exclude-keywords" placeholder="Alaska, California, Nevada">
                                            </label>
                                            <p><?php _e('Enter some keywords to exclude these from generated content. Seperated by comma (,).', 'ai-writing-assistant'); ?></p>
                                        </div>
                                    <?php endif; ?>

                                    <?php
                                        if (in_array('writing_style', $settingsInclude)){
                                            aiwa_writing_styles_settings_item();
                                        }
                                        if (in_array('writing_tone', $settingsInclude)) {
                                            aiwa_writing_tone_settings_item();
                                        }
                                        if (in_array('introduction', $settingsInclude)) {
                                            aiwa_add_introduction_settings_items();
                                        }
                                        if (in_array('conclusion', $settingsInclude)) {
                                            aiwa_add_conclusion_settings_items();
                                        }
                                        if (in_array('cta', $settingsInclude)) {
                                            aiwa_call_to_action_settings_items();
                                        }
                                        if (in_array('generate_images', $settingsInclude)) {
                                            aiwa_auto_generate_image_settings_items();
                                        }
                                    ?>

                                    <?php if(in_array('save_future', $settingsInclude)): ?>
                                        <div class="aiwa-save-for-future-setting settings-item">
                                            <button id="aiwa-save-for-future-use" class="aiwa-save-for-future-use">Save for Future</button><span style="font-size: 13px;font-weight: normal;position: relative;top: -20px;" class="future-save-btn badge badge-success aiwa-hidden"><?php _e('Saved!', 'ai-writing-assistant'); ?></span>
                                            <p style="margin: 0;color: #b01111;"><?php _e('It is not necessary to press this button every time after changing the above fields. Press only when you need those settings again in the future.', 'ai-writing-assistant'); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>


                            </div>
                        <?php endif; ?>
                        </div>

                        <?php if(in_array('save_future', $settingsInclude)): ?>
                         <div class="ai_response_hidden prompt-settings-item" id="advanced-settings" data-tab="advanced-settings">
                            <div class="advanced-settings-box code-box-dark">
                                <div class="code-box-header">
                                    <div class="title"><?php _e('API Settings', 'ai-writing-assistant'); ?></div>
                                    <button class="minimize-btn" title="<?php _e('Minimize API settings panel', 'ai-writing-assistant'); ?>">
                                        <svg fill="#000000" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" id="minus" class="icon glyph" stroke="#000000" width="24px" height="24px">
                                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                            <g id="SVGRepo_iconCarrier">
                                                <path d="M19,13H5a1,1,0,0,1,0-2H19a1,1,0,0,1,0,2Z"></path>
                                            </g>
                                        </svg>
                                    </button>
                                </div>
                                <div class="aiwa-advanced-settings-panel aiwa-settings-panel-item">

                                    <div class="settings-item">
                                        <div class="range-input"><label for="temperature"><span><?php _e('Temperature', 'ai-writing-assistant'); ?></span><input id="temperature-input" class="input-box" style="width:50px" type="text" value="0.5"></label><input type="range" min="0" max="1" value="0.5" step="0.01" id="temperature" class="slider" name="temperature"></div>
                                        <p><?php _e('Adjust the temperature to control the randomness of the generated text.', 'ai-writing-assistant'); ?></p>
                                    </div>
                                    <div class="settings-item">
                                        <div class="range-input"><label for="max-tokens"><span><?php _e('Max Tokens', 'ai-writing-assistant'); ?></span><input id="max-tokens-input" class="input-box" style="width:50px" type="text" value="2000"></label><input type="range" min="5" max="4000" value="2000" step="1" id="max-tokens" class="slider" name="max-tokens"></div>
                                        <p><?php _e('Set the maximum number of tokens to generate in a single request.', 'ai-writing-assistant'); ?></p>
                                    </div>
                                    <div class="settings-item">
                                        <div class="range-input"><label for="top-p"><span><?php _e('Top Prediction (Top-P)', 'ai-writing-assistant'); ?></span><input id="top-p-input" class="input-box" style="width:50px" type="text" value="1"></label><input type="range" min="0" max="1" value="1" step="0.01" id="top-p" class="slider" name="top-p"></div>
                                        <p><?php _e('Adjust the Top-P (Top Prediction) parameter to control the diversity of the generated text.', 'ai-writing-assistant'); ?></p>
                                    </div>
                                    <div class="settings-item">
                                        <div class="range-input"><label for="best-of"><span><?php _e('Best of', 'ai-writing-assistant'); ?></span><input id="best-of-input" class="input-box" style="width:50px" type="text" value="0.75"></label><input type="range" min="0" max="1" value="0.75" step="0.01" id="best-of" class="slider" name="best-of"></div>
                                        <p><?php _e('Set the number of generated sequences to return.', 'ai-writing-assistant'); ?></p>
                                    </div>
                                    <div class="settings-item">
                                        <div class="range-input"><label for="frequency-penalty"><span><?php _e('Frequency Penalty', 'ai-writing-assistant'); ?></span><input id="frequency-penalty-input" class="input-box" style="width:50px" type="text" value="0"></label><input type="range" min="0" max="2" value="0" step="0.01" id="frequency-penalty" class="slider" name="frequency-penalty"></div>
                                        <p><?php _e('Adjust the frequency penalty to control the frequency of words in the generated text.', 'ai-writing-assistant'); ?></p>
                                    </div>
                                    <div class="settings-item">
                                        <div class="range-input"><label for="presence-penalty"><span><?php _e('Presence Penalty', 'ai-writing-assistant'); ?></span><input id="presence-penalty-input" class="input-box" style="width:50px" type="text" value="1.03"></label><input type="range" min="0" max="2" value="1.03" step="0.01" id="presence-penalty" class="slider" name="presence-penalty"></div>
                                        <p><?php _e('Adjust the presence penalty to control the presence of words in the generated text.', 'ai-writing-assistant'); ?></p>
                                    </div>


                                    <div class="aiwa-save-for-future-setting settings-item">
                                        <button id="aiwa-save-for-future-use" class="aiwa-save-for-future-use">Save for Future</button><span style="font-size: 13px;font-weight: normal;position: relative;top: -20px;" class="future-save-btn badge badge-success aiwa-hidden"><?php _e('Saved!', 'ai-writing-assistant'); ?></span>
                                        <p style="margin: 0;color: #b01111;"><?php _e('It is not necessary to press this button every time after changing the above fields. Press only when you need those settings again in the future.', 'ai-writing-assistant'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php do_action('aiwa_after_promptbox_fields'); ?>
                        <input type="hidden" name="from-aiwa-settings" value="0">
                        <input type="hidden" name="generation_session_key" id="generation_session_key" value="">



                    </form>

                </div>
            </div>

            <div class="hidden-fields aiwa-hidden">
                <input type="hidden" name="introduction_text" class="introduction_text" value="Introduction">
                <input type="hidden" name="conclusion_text" class="conclusion_text" value="Conclusion">

                <input type="hidden" name="aiwa_running_task" id="aiwa_running_task" value="">
                <input type="hidden" name="aiwa_recent_task_completed" id="aiwa_recent_task_completed" value="">
                <input type="hidden" name="aiwa_title_requested" id="aiwa_title_requested" value="0">
                <input type="hidden" name="aiwa_content_structure_requested" id="aiwa_content_structure_requested" value="0">
                <input type="hidden" name="aiwa_introduction_requested" id="aiwa_introduction_requested" value="0">
                <input type="hidden" name="aiwa_conclusion_requested" id="aiwa_conclusion_requested" value="0">
                <input type="hidden" name="aiwa_call_to_action_requested" id="aiwa_call_to_action_requested" value="0">
                <input type="hidden" name="aiwa_current_topics_running" id="aiwa_current_topics_running" value="0">
                <input type="hidden" name="aiwa_title_completed" id="aiwa_title_completed" value="0">
                <input type="hidden" name="aiwa_introduction_completed" id="aiwa_introduction_completed" value="0">
                <input type="hidden" name="aiwa_conclusion_completed" id="aiwa_conclusion_completed" value="0">
                <input type="hidden" name="aiwa_call_to_action_completed" id="aiwa_call_to_action_completed" value="0">


                <input type="hidden" name="aiwa_title" id="aiwa_title" value="">
                <input type="hidden" name="aiwa_content_structure_completed" id="aiwa_content_structure_completed" value="0">
                <input type="hidden" name="aiwa_is_content_scroll" id="aiwa_is_content_scrollable" value="1">
                <input type="hidden" name="aiwa_is_generation_cancelled" id="aiwa_is_generation_cancelled" value="0">

                <input type="hidden" id="aiwa-placeholders" value="<?php echo esc_html($placeholders); ?>">
            </div>

        </div>

        <?php
    }

}



