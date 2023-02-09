<?php
function aiwa_content_structure_settings_item(){
    $key = 'ai_writing_assistant__';
    ?>

    <div class="settings-item">
        <label for="aiwa-generate-title"><span><?php _e('Generate post title', 'ai-writing-assistant'); ?></span>
            <input id="aiwa-generate-title" class="content-settings-input" type="checkbox" name="generate-title" <?php echo esc_attr(get_option($key.'generate-title', 'on')) == 'on' ? 'checked': ''; ?>>
        </label>
        <p><?php _e('Select this to generate blog post title.', 'ai-writing-assistant'); ?></p>
    </div>

    <div class="settings-item">
        <label for="aiwa-content-structure">
            <span><?php _e('Content Structure', 'ai-writing-assistant'); ?></span>
        </label>
        <select name="ai-content-structure" id="aiwa-content-structure" data-has-subsettings="">
            <?php
                aiwa_get_content_structure_options();
            ?>
        </select>

        <p><?php _e('Choose the content type of your blog post which fit your need!', 'ai-writing-assistant'); ?></p>
    </div>

    <div class="settings-item sub-settings-item <?php echo esc_attr(get_option($key.'ai-content-structure', 'topic_wise'))!='topic_wise'? 'aiwa-hidden': ''; ?>" data-subsettings-of="aiwa-content-structure" data-sub-settings-key="topic_wise">
        <label for="aiwa-how-many-topics"><span><?php _e('How many topics', 'ai-writing-assistant'); ?></span>
            <input id="aiwa-how-many-topics" class="content-settings-input" type="number" name="topics-count" value="<?php echo esc_attr(get_option($key.'topics-count', '5')); ?>" placeholder="5">
        </label>
        <p><?php _e('Enter a number of topics you want to add to your blog post.', 'ai-writing-assistant'); ?></p>
    </div>

    <div class="settings-item sub-settings-item <?php echo esc_attr(get_option($key.'ai-content-structure', 'topic_wise'))!='topic_wise'? 'aiwa-hidden': ''; ?>" data-subsettings-of="aiwa-content-structure" data-sub-settings-key="topic_wise">
        <label for="aiwa-topics-tag"><span><?php _e('Topics heading tag', 'ai-writing-assistant'); ?></span></label>
        <select name="aiwa-topics-tag" id="aiwa-topics-tag">
            <?php
                aiwa_get_topics_tag_options();
            ?>
        </select>
        <p><?php _e('Topics will automatically be wrapped by the selected heading tag.', 'ai-writing-assistant'); ?></p>
    </div>


    <div class="settings-item sub-settings-item <?php echo esc_attr(get_option($key.'ai-content-structure', 'topic_wise'))!='pros_and_cons'? 'aiwa-hidden': ''; ?>" data-subsettings-of="aiwa-content-structure" data-sub-settings-key="pros_and_cons">
        <label for="aiwa-how-many-pros-and-cons"><span><?php _e('How many pros and cons', 'ai-writing-assistant'); ?></span>
            <input id="aiwa-how-many-pros-and-cons" class="content-settings-input" type="number" name="pros-and-cons-count" value="<?php echo esc_attr(get_option($key.'pros-and-cons-count', '7')); ?>" placeholder="7">
        </label>
        <p><?php _e('Enter a number of pros and cons you want to add to your blog post.', 'ai-writing-assistant'); ?></p>
    </div>

    <div class="settings-item sub-settings-item <?php echo esc_attr(get_option($key.'ai-content-structure', 'topic_wise'))!='list'? 'aiwa-hidden': ''; ?>" data-subsettings-of="aiwa-content-structure" data-sub-settings-key="list">
        <label for="aiwa-how-many-list-items"><span><?php _e('How many list items', 'ai-writing-assistant'); ?></span>
            <input id="aiwa-how-many-list-items" class="content-settings-input" type="number" name="list-items-count" value="<?php echo esc_attr(get_option($key.'list-items-count', '10')); ?>" placeholder="10">
        </label>
        <p><?php _e('Enter a number of list items you want to add to your blog post.', 'ai-writing-assistant'); ?></p>
    </div>

    <div class="settings-item sub-settings-item <?php echo esc_attr(get_option($key.'ai-content-structure', 'topic_wise'))!='faq'? 'aiwa-hidden': ''; ?>" data-subsettings-of="aiwa-content-structure" data-sub-settings-key="faq">
        <label for="aiwa-how-many-faq-items"><span><?php _e('How many FAQ items', 'ai-writing-assistant'); ?></span>
            <input id="aiwa-how-many-faq-items" class="content-settings-input" type="number" name="faq-items-count" value="<?php echo esc_attr(get_option($key.'faq-items-count', '7')); ?>" placeholder="7">
        </label>
        <p><?php _e('Enter a number of FAQ items you want to add on your blog post.', 'ai-writing-assistant'); ?></p>
    </div>

    <div class="settings-item sub-settings-item <?php echo esc_attr(get_option($key.'ai-content-structure', 'topic_wise'))!='article'? 'aiwa-hidden': ''; ?>" data-subsettings-of="aiwa-content-structure" data-sub-settings-key="article">
        <label for="aiwa-how-many-article-paragraphs"><span><?php _e('How many paragraphs', 'ai-writing-assistant'); ?></span>
            <input id="aiwa-how-many-article-paragraphs" class="content-settings-input" type="number" name="article-paragraphs-count" value="<?php echo esc_attr(get_option($key.'article-paragraphs-count', '3')); ?>" placeholder="3">
        </label>
        <p><?php _e('Enter a number of paragraphs you want to add on your blog post article.', 'ai-writing-assistant'); ?></p>
    </div>

    <div class="settings-item sub-settings-item <?php echo esc_attr(get_option($key.'ai-content-structure', 'topic_wise'))!='interviews'? 'aiwa-hidden': ''; ?>" data-subsettings-of="aiwa-content-structure" data-sub-settings-key="interviews">
        <label for="aiwa-first-persion-name"><span><?php _e('First persion name', 'ai-writing-assistant'); ?></span>
            <input id="aiwa-first-persion-name" class="content-settings-input" type="text" name="first-person-name" value="<?php echo esc_attr(get_option($key.'first-person-name', '')); ?>" placeholder="John Doe">
        </label>
        <br/>
        <label for="aiwa-second-person-name"><span><?php _e('Second person name', 'ai-writing-assistant'); ?></span>
            <input id="aiwa-second-person-name" class="content-settings-input" type="text" name="second-person-name" value="<?php echo esc_attr(get_option($key.'second-person-name', '')); ?>" placeholder="Harry Potter">
        </label>
        <p><?php _e('Enter the first and second-person names of the interview characters.', 'ai-writing-assistant'); ?></p>
    </div>
    <?php
}

function aiwa_writing_styles_settings_item(){
    $key = 'ai_writing_assistant__';
    ?>
    <div class="settings-item">
        <label for="aiwa-writing-style">
            <span><?php _e('Writing Style', 'ai-writing-assistant'); ?></span>
        </label>
        <select id="aiwa-writing-style" name="writing-style">
            <?php
                aiwa_get_writing_styles_options();
            ?>
        </select>

        <p><?php _e('Choose the writing style of your blog post which fit your need!', 'ai-writing-assistant'); ?></p>
    </div>
    <?php
}

function aiwa_writing_tone_settings_item(){
    $key = 'ai_writing_assistant__';
    ?>
    <div class="settings-item">
        <label for="aiwa-writing-tone">
            <span><?php _e('Writing Tone', 'ai-writing-assistant'); ?></span>
        </label>
        <select id="aiwa-writing-tone" name="writing-tone">
            <?php
                aiwa_get_writing_tone_options();
            ?>
        </select>

        <p><?php _e('Choose the writing tone of your blog post which fit your need!', 'ai-writing-assistant'); ?></p>
    </div>
    <?php
}


function aiwa_call_to_action_settings_items(){
    $key = 'ai_writing_assistant__';
    ?>
    <div class="settings-item">
        <label for="aiwa-add-call-to-action"><span><?php _e('Add Call-to-Action', 'ai-writing-assistant'); ?></span>
            <input id="aiwa-add-call-to-action" name="add-call-to-action" class="content-settings-input" type="checkbox" data-has-subsettings="" <?php echo esc_attr(get_option($key.'add-call-to-action', 'off')) =='on' ? 'checked': ''; ?>>
        </label>
        <p><?php _e('Select to add "Conclution" text before conclution content.', 'ai-writing-assistant'); ?></p>
    </div>
    <div class="settings-item sub-settings-item  <?php echo esc_attr(get_option($key.'add-call-to-action', 'off')) =='on' ? '': 'aiwa-hidden'; ?>" data-sub-id="aiwa-add-call-to-action"  data-subsettings-of="aiwa-add-call-to-action">
        <label for="aiwa-call-to-action-url"><span><?php _e('Call-to-Action url', 'ai-writing-assistant'); ?></span>
            <input id="aiwa-call-to-action-url" name="call-to-action-url" class="content-settings-input" type="text" placeholder="https://yourlink.com" value="<?php echo esc_url(get_option($key.'call-to-action-url', '')); ?>">
        </label>
        <p><?php _e('Enter an url to add this on call-to-action text.', 'ai-writing-assistant'); ?></p>
    </div>
    <div class="settings-item sub-settings-item <?php echo esc_attr(get_option($key.'add-call-to-action', 'off')) =='on' ? '': 'aiwa-hidden'; ?>" data-sub-id="aiwa-add-call-to-action"  data-subsettings-of="aiwa-add-call-to-action">
        <label for="aiwa-call-to-action-position">
            <span><?php _e('Call-to-Action position', 'ai-writing-assistant'); ?></span>
        </label>
        <select name="call-to-action-position" id="aiwa-call-to-action-position">
            <option value="start" <?php echo esc_attr(get_option($key.'call-to-action-position', 'start')) =='start' ? 'selected': ''; ?>>Start</option>
            <option value="end" <?php echo esc_attr(get_option($key.'call-to-action-position', 'start')) =='end' ? 'selected': ''; ?>>End</option>
        </select>
        <p><?php _e('If you want to set the call-to-action section on top then select "Start", if bottom select "End"', 'ai-writing-assistant'); ?></p>
    </div>
    <?php
}

function aiwa_add_introduction_settings_items(){
    $key = 'ai_writing_assistant__';
    ?>

    <div class="settings-item">
        <label for="aiwa-add-introduction"><span><?php _e('Add introduction', 'ai-writing-assistant'); ?></span>
            <input id="aiwa-add-introduction" class="content-settings-input" name="add-introduction" type="checkbox" data-has-subsettings="" <?php echo esc_attr(get_option($key.'add-introduction', 'on')) =='on' ? 'checked': ''; ?>>
        </label>
        <p><?php _e('Add an introduction beginning of the topics.', 'ai-writing-assistant'); ?></p>
    </div>

    <div class="settings-item sub-settings-item <?php echo esc_attr(get_option($key.'add-introduction', 'on')) =='on' ? '': 'aiwa-hidden'; ?>" data-subsettings-of="aiwa-add-introduction">
        <label for="aiwa-add-introduction-text"><span><?php _e('Add "Introduction" text', 'ai-writing-assistant'); ?></span>
            <input id="aiwa-add-introduction-text" class="content-settings-input" name="add-introduction-text" type="checkbox" <?php echo esc_attr(get_option($key.'add-introduction-text', 'off')) =='on' ? 'checked': ''; ?>>
        </label>
        <p><?php _e('Select to add "Introduction:" text before the introduction content.', 'ai-writing-assistant'); ?></p>
    </div>

    <div class="settings-item sub-settings-item <?php echo esc_attr(get_option($key.'add-introduction', 'on')) =='on' ? '': 'aiwa-hidden'; ?>" data-subsettings-of="aiwa-add-introduction">
        <label for="aiwa-introduction-size"><span><?php _e('Introduction text size', 'ai-writing-assistant'); ?></span></label>
        <select name="introduction-size" id="aiwa-introduction-size">
            <option <?php echo esc_attr(get_option($key.'introduction-size', 'short')) =='short' ? 'selected': 'short'; ?> value=""><?php _e('Short', 'ai-writing-assistant'); ?></option>
            <option <?php echo esc_attr(get_option($key.'introduction-size', 'short')) =='medium' ? 'selected': 'medium'; ?> value=""><?php _e('Medium', 'ai-writing-assistant'); ?></option>
            <option <?php echo esc_attr(get_option($key.'introduction-size', 'short')) =='long' ? 'selected': 'long'; ?> value=""><?php _e('Long', 'ai-writing-assistant'); ?></option>
        </select>
        <p><?php _e('Select a size to set how long your introduction size is needed.', 'ai-writing-assistant'); ?></p>
    </div>

    <?php
}

function aiwa_add_conclusion_settings_items(){
    $key = 'ai_writing_assistant__';
    ?>

    <div class="settings-item">
        <label for="aiwa-add-conclusion"><span><?php _e('Add conclusion', 'ai-writing-assistant'); ?></span>
            <input id="aiwa-add-conclusion" class="content-settings-input" type="checkbox" name="add-conclusion" data-has-subsettings="" <?php echo esc_attr(get_option($key.'add-conclusion', 'on')) =='on' ? 'checked': ''; ?>>
        </label>
        <p><?php _e('Add conclusion end of the topics.', 'ai-writing-assistant'); ?></p>
    </div>

    <div class="settings-item sub-settings-item  <?php echo esc_attr(get_option($key.'add-conclusion', 'on')) =='on' ? '': 'aiwa-hidden'; ?>" data-subsettings-of="aiwa-add-conclusion">
        <label for="aiwa-add-conclusion-text"><span><?php _e('Add "Conclusion" text', 'ai-writing-assistant'); ?></span>
            <input id="aiwa-add-conclusion-text" class="content-settings-input" name="add-conclusion-text" type="checkbox" data-has-subsettings="" <?php echo esc_attr(get_option($key.'add-conclusion-text', 'off')) =='on' ? 'checked': ''; ?>>
        </label>
        <p><?php _e('Select to add "Conclusion:" text before the conclusion content.', 'ai-writing-assistant'); ?></p>
    </div>

    <div class="settings-item sub-settings-item <?php echo esc_attr(get_option($key.'add-conclusion', 'on')) =='on' ? '': 'aiwa-hidden'; ?>" data-subsettings-of="aiwa-add-conclusion">
        <label for="aiwa-conclusion-size"><span><?php _e('Introduction text size', 'ai-writing-assistant'); ?></span></label>
        <select name="conclusion-size" id="aiwa-conclusion-size">
            <option <?php echo esc_attr(get_option($key.'conclusion-size', 'short')) =='short' ? 'selected': 'short'; ?> value=""><?php _e('Short', 'ai-writing-assistant'); ?></option>
            <option <?php echo esc_attr(get_option($key.'conclusion-size', 'short')) =='medium' ? 'selected': 'medium'; ?> value=""><?php _e('Medium', 'ai-writing-assistant'); ?></option>
            <option <?php echo esc_attr(get_option($key.'conclusion-size', 'short')) =='long' ? 'selected': 'long'; ?> value=""><?php _e('Long', 'ai-writing-assistant'); ?></option>
        </select>
        <p><?php _e('Select a size for how long your conclusion size is needed.', 'ai-writing-assistant'); ?></p>
    </div>

    <?php
}

function aiwa_content_length_settings_items(){
    $key = 'ai_writing_assistant__';
    ?>

    <div class="settings-item">
        <label for="aiwa-content-length"><span><?php _e('Content length', 'ai-writing-assistant'); ?></span> </label>
        <select name="content-length" id="aiwa-content-length">
            <option <?php echo esc_attr(get_option($key.'content-length', 'long')) =='long' ? 'selected': ''; ?> value="long">Long</option>
            <option <?php echo esc_attr(get_option($key.'content-length', 'long')) =='medium' ? 'selected': ''; ?> value="medium">Medium</option>
            <option <?php echo esc_attr(get_option($key.'content-length', 'long')) =='short' ? 'selected': ''; ?> value="short">Short</option>
        </select>
        <p><?php _e('Select a content length that fit your need.', 'ai-writing-assistant'); ?></p>
    </div>

    <?php
}

function aiwa_auto_generate_image_settings_items(){
    $key = 'ai_writing_assistant__';

    $image_experiments = (array) get_option($key.'image_experiments', array('realistic', 'four_k', 'high_resolution', 'trending_in_artstation', 'artstation_three'));
    $image_experiments = array_map('esc_attr', $image_experiments);
    ?>

    <div class="settings-item">
        <label for="aiwa-auto-generate-image"><span><?php _e('Auto generate featured image', 'ai-writing-assistant'); ?></span> </label>
        <input id="aiwa-auto-generate-image" class="content-settings-input" name="auto-generate-image" type="checkbox" data-has-subsettings="" <?php echo esc_attr(get_option($key.'auto-generate-image', 'off')) =='on' ? 'checked': ''; ?>>
        <p><?php _e('Select this to auto-generate the thumbnail image. It will generate from your main prompt.', 'ai-writing-assistant'); ?></p>
    </div>

    <div class="settings-item sub-settings-item <?php echo esc_attr(get_option($key.'auto-generate-image', 'on')) =='on' ? '': 'aiwa-hidden'; ?>" data-subsettings-of="aiwa-auto-generate-image">
        <label>
            <span><?php _e('Image Size', 'ai-writing-assistant'); ?></span>
        </label>
        <select name="ai-image-size">
            <option <?php echo esc_attr(get_option($key.'ai-image-size', 'medium-plus'))=='thumbnail' ? 'selected': ''; ?> value="thumbnail"><?php _e('Thumbnail (256x256px)', 'ai-writing-assistant'); ?></option>
            <option <?php echo esc_attr(get_option($key.'ai-image-size', 'medium-plus'))=='medium' ? 'selected': ''; ?> value="medium"><?php _e('Medium (512x512px)', 'ai-writing-assistant'); ?></option>
            <option <?php echo esc_attr(get_option($key.'ai-image-size', 'medium-plus'))=='large' ? 'selected': ''; ?> value="large"><?php _e('Large (1024x1024px)', 'ai-writing-assistant'); ?></option>
        </select>
        <p><?php _e('Choose the size of the image you want to generate with DALL-E.', 'ai-writing-assistant'); ?></p>
    </div>

    <div class="settings-item sub-settings-item <?php echo esc_attr(get_option($key.'auto-generate-image', 'on')) =='on' ? '': 'aiwa-hidden'; ?>" data-subsettings-of="aiwa-auto-generate-image">
        <label>
            <span><?php _e('Image Experiments', 'ai-writing-assistant'); ?></span>
        </label>
        <br>
        <label for="aiwa-realistic" class="image-experiments"><input id="aiwa-realistic" <?php echo in_array('realistic', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[realistic]"> <?php _e('Realistic', 'ai-writing-assistant'); ?></label>
        <label for="aiwa-3D-render" class="image-experiments"><input id="aiwa-3D-render" <?php echo in_array('3D_render', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[3D_render]"> <?php _e('3D render', 'ai-writing-assistant'); ?></label>
        <label for="aiwa-4k" class="image-experiments"><input id="aiwa-4k" type="checkbox" <?php echo in_array('four_k', $image_experiments) ? 'checked': ''; ?> name="image_experiments[four_k]"> <?php _e('4K', 'ai-writing-assistant'); ?></label>
        <label for="aiwa_amazing_art" class="image-experiments"><input id="aiwa_amazing_art" <?php echo in_array('amazing_art', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[amazing_art]"> <?php _e('Amazing art', 'ai-writing-assistant'); ?></label>
        <label for="aiwa-high-resolution" class="image-experiments"><input id="aiwa-high-resolution" <?php echo in_array('high_resolution', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[high_resolution]"><?php _e('High resolution', 'ai-writing-assistant'); ?></label>
        <br>
        <label for="aiwa_trending_in_artstation" class="image-experiments"><input id="aiwa_trending_in_artstation" <?php echo in_array('trending_in_artstation', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[trending_in_artstation]"> <?php _e('Trending in artstation', 'ai-writing-assistant'); ?></label>
        <label for="aiwa_artstation_3" class="image-experiments"><input id="aiwa_artstation_3" type="checkbox" <?php echo in_array('artstation_three', $image_experiments) ? 'checked': ''; ?> name="image_experiments[artstation_three]"> <?php _e('Artstation 3', 'ai-writing-assistant'); ?></label>
        <label for="aiwa_oil_painting" class="image-experiments"><input id="aiwa_oil_painting" type="checkbox" <?php echo in_array('oil_painting', $image_experiments) ? 'checked': ''; ?> name="image_experiments[oil_painting]"> <?php _e('Oil painting', 'ai-writing-assistant'); ?></label>
        <label for="aiwa_digital_painting" class="image-experiments"><input id="aiwa_digital_painting" <?php echo in_array('digital_painting', $image_experiments) ? 'checked': ''; ?> type="checkbox" name="image_experiments[digital_painting]"> <?php _e('Digital painting', 'ai-writing-assistant'); ?></label>

        <p><?php _e('Choose the above styles to generate image.', 'ai-writing-assistant'); ?></p>
    </div>

    <?php
}

function aiwa_languages_settings_items(){
    $key = 'ai_writing_assistant__';
    ?>

    <div class="settings-item">
        <label>
            <span><?php _e('Select Language', 'ai-writing-assistant'); ?></span>
        </label>
        <select name="aiwa-language" id="aiwa-language">
            <?php
                aiwa_get_languages_options();
            ?>
        </select>
        <input type="hidden" name="aiwa_language_text" id="aiwa_language_text" value="<?php echo esc_attr(get_option($key . 'aiwa_language_text', 'English')); ?>">

        <p><?php _e('Select a language to generate contents with the language.', 'ai-writing-assistant'); ?></p>
    </div>


    <?php
}

function aiwa_post_types_and_categories(){
    ?>
    <div class="settings-item">
            <label for="aiwa-single-post-type"><span><?php echo __('Post Type','ai-writing-assistant'); ?></span> </label>
            <select name="single_generation_post_type" id="aiwa-single-post-type" data-has-subsettings="">
                <?php
                $post_types = aiwa_get_post_types();
                foreach ( $post_types as $post_type ) {
                    $post_type_object = get_post_type_object($post_type);
                    $isChecked = in_array($post_type, array('post')) ? 'selected': '';
                    echo '<option id="aiwa-'.esc_attr($post_type).'" '.esc_attr($isChecked).' value="'.esc_attr($post_type).'"> '.esc_attr($post_type_object->labels->singular_name).'</option>';
                }
                ?>
            </select>
            <p><?php echo __('Select a post type','ai-writing-assistant'); ?></p>
        </div>
        <?php
        $post_types = aiwa_get_post_types();
        $key = 'ai_writing_assistant__';
        if (!empty($post_types)){
            foreach ($post_types as $post_type) {
                $taxonomies = get_object_taxonomies( $post_type );
                if ( in_array( 'category', $taxonomies ) ) {
                    ?>
                    <div class="settings-item sub-settings-item <?php echo esc_attr(get_option($key.'ai-content-structure', 'post'))==$post_type? 'aiwa-hidden': ''; ?>" data-subsettings-of="aiwa-single-post-type" data-sub-settings-key="<?php echo $post_type; ?>">
                        <?php
                        $categories = get_categories( array(
                            'orderby' => 'name',
                            'parent'  => 0,
                            'hide_empty' => false
                        ) );

                        echo '<select id="aiwa-single-category">';
                        echo '<option value="">' . __('Select a category','ai-writing-assistant').'</option>';
                        foreach ( $categories as $category ) {
                            printf( '<option value="%1$s">%2$s</option>',
                                esc_attr( $category->term_id ),
                                esc_html( $category->name )
                            );
                        }
                        echo '</select>';
                        ?>
                        <p><?php echo __('Select a category','ai-writing-assistant'); ?></p>
                    </div>
                    <?php
                }
            }
        }
}
