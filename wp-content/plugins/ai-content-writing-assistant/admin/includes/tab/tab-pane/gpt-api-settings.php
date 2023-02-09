<?php
    $key = 'ai_writing_assistant__';
?>
    <!-- API Key input field with tooltip -->
<?php if(current_user_can('administrator')): ?>
    <div class="settings-item">
        <label>
            <span><?php _e('API Key', 'ai-writing-assistant'); ?></span>
            <input type="text" name="api-key" value="<?php echo !empty(get_option($key.'api-key', '')) ? esc_attr(aiwa_open_api_chars(get_option($key.'api-key', ''))) : ''; ?>" placeholder="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" style="width: 310px;">
            <div class="tooltip">
                ?
                <span class="tooltiptext">
                    <?php _e('Enter your GPT-3 API key here. You can get your API key from the <a href="https://beta.openai.com/account/api-keys">OpenAI Beta Signup</a> page.', 'ai-writing-assistant'); ?>
                </span>
            </div>
            <p><?php _e('Enter your API key to use the GPT-3 API. <a href="https://beta.openai.com/account/api-keys" target="_blank">Get the API key</a>', 'ai-writing-assistant'); ?></p>
        </label>
    </div>
<?php endif; ?>
    <!-- Temperature input field with tooltip and description -->
    <div class="settings-item">
        <div class="range-input">
            <label for="temperature"><span><?php _e('Temperature', 'ai-writing-assistant'); ?></span><input id="temperature-input" class="input-box" style="width: 50px;" type="text" value="<?php echo esc_attr(get_option($key.'temperature', '0.5')); ?>"></label>
            <input type="range" min="0" max="1" value="<?php echo esc_attr(get_option($key.'temperature', '0.4')); ?>" step="0.01" id="temperature" class="slider" name="temperature">
        </div>

        <p><?php _e('Adjust the temperature to control the randomness of the generated text.', 'ai-writing-assistant'); ?></p>
    </div>


    <!-- Max tokens input field with tooltip and description -->
    <div class="settings-item">
        <!--<input type="number" name="max-tokens" value="256">-->
        <div class="range-input">
            <label for="max-tokens"><span><?php _e('Max Tokens', 'ai-writing-assistant'); ?></span>
                <input id="max-tokens-input" class="input-box" style="width: 50px;" type="text" value="<?php echo esc_attr(get_option($key.'max-tokens', '2000')); ?>">
            </label>

            <input type="range" min="5" max="4000" value="<?php echo esc_attr(get_option($key.'max-tokens', '2000')); ?>" step="1" id="max-tokens" class="slider" name="max-tokens">
        </div>
        <p><?php _e('Set the maximum number of tokens to generate in a single request.', 'ai-writing-assistant'); ?></p>
    </div>

    <!-- Top-P input field with tooltip and description -->
    <div class="settings-item">
        <div class="range-input">
            <label for="top-p"><span><?php _e('Top Prediction (Top-P)', 'ai-writing-assistant'); ?></span>
                <input id="top-p-input" class="input-box" style="width: 50px;" type="text" value="<?php echo esc_attr(get_option($key.'top-p', '0.5')); ?>">
            </label>
            <input type="range" min="0" max="1" value="<?php echo esc_attr(get_option($key.'top-p', '0.5')); ?>" step="0.01" id="top-p" class="slider" name="top-p">
        </div>
        <!--<input type="number" name="top-p" value="1.0">
        <div class="tooltip">
            ?
            <span class="tooltiptext">
        The Top-P parameter controls the diversity of the generated text. A higher value will result in more diverse text, while a lower value will result in more predictable text.
      </span>
        </div>-->
        <p><?php _e('Adjust the Top-P (Top Prediction) parameter to control the diversity of the generated text.', 'ai-writing-assistant'); ?></p>
    </div>

    <!-- "Best of" input field with tooltip and description -->
    <div class="settings-item">
        <div class="range-input">
            <label for="best-of"><span><?php _e('Best of', 'ai-writing-assistant'); ?></span>
                <input id="best-of-input" class="input-box" style="width: 50px;" type="text" value="<?php echo esc_attr(get_option($key.'best-of', '1')); ?>">
            </label>
            <input type="range" min="0" max="1" value="<?php echo esc_attr(get_option($key.'best-of', '1')); ?>" step="0.01" id="best-of" class="slider" name="best-of">
        </div>
        <p><?php _e('Set the number of generated sequences to return.', 'ai-writing-assistant'); ?></p>
    </div>
<!--    <label>
        <span>Best of</span>
        <input type="number" name="best-of" value="1">
        <div class="tooltip">
            ?
            <span class="tooltiptext">
        The "best of" parameter controls the number of generated sequences that are returned. A higher value will result in more diverse generated sequences, but will also increase the response time.
      </span>
        </div>
        <p>Set the number of generated sequences to return.</p>
    </label>-->


    <!-- "Frequency penalty" input field with tooltip and description -->
    <div class="settings-item">
        <div class="range-input">
            <label for="frequency-penalty"><span><?php _e('Frequency Penalty', 'ai-writing-assistant'); ?></span>
                <input id="frequency-penalty-input" class="input-box" style="width: 50px;" type="text" value="<?php echo esc_attr(get_option($key.'frequency-penalty', '0')); ?>"></label>
            <input type="range" min="0" max="2" value="<?php echo esc_attr(get_option($key.'frequency-penalty', '0')); ?>" step="0.01" id="frequency-penalty" class="slider" name="frequency-penalty">
        </div>
        <p><?php _e('Adjust the frequency penalty to control the frequency of words in the generated text.', 'ai-writing-assistant'); ?></p>
    </div>

    <div class="settings-item">
        <div class="range-input">
            <label for="presence-penalty"><span><?php _e('Presence Penalty', 'ai-writing-assistant'); ?></span>
                <input id="presence-penalty-input" class="input-box" style="width: 50px;" type="text" value="<?php echo esc_attr(get_option($key.'presence-penalty', '0')); ?>"></label>
            <input type="range" min="0" max="2" value="<?php echo esc_attr(get_option($key.'presence-penalty', '0')); ?>" step="0.01" id="presence-penalty" class="slider" name="presence-penalty">
        </div>
        <p><?php _e('Adjust the presence penalty to control the presence of words in the generated text.', 'ai-writing-assistant'); ?></p>
    </div>

<!--    <label>
        <span>Frequency penalty</span>
        <input type="number" name="frequency-penalty" value="0">
        <div class="tooltip">
            ?
            <span class="tooltiptext">
        The frequency penalty controls the frequency of words in the generated text. A higher value will result in a lower frequency of words, while a lower value will result in a higher frequency of words.
      </span>
        </div>
        <p>Adjust the frequency penalty to control the frequency of words in the generated text.</p>
    </label>-->

<!--    <label>
        <span>Presence penalty</span>
        <input type="number" name="presence-penalty" value="0">
        <div class="tooltip">
            ?
            <span class="tooltiptext">
        The presence penalty controls the presence of words in the generated text. A higher value will result in a lower presence of words, while a lower value will result in a higher presence of words.
      </span>
        </div>
        <p>Adjust the presence penalty to control the presence of words in the generated text.</p>
    </label>-->

<input type="hidden" id="aiwa-placeholders-is-set" value="<?php echo !empty(get_option('aiwa-placeholders', '')) ? '1': '0'; ?>">

<?php


