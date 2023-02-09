<h1><?php echo esc_html(__('AI Writing Assistant Settings')); ?></h1>

<div class="ai-writing-assistant-settings-panel">
    <ul class="nav nav-tabs" role="tablist" style="text-transform: uppercase">
        <?php require AIWA_DIR_PATH . 'admin/includes/tab/nav-items.php'; ?>
    </ul>

    <!-- Tab panes -->

        <div class="tab-content">

            <form id="ai-settings-form">
                <div class="tab-pane gpt-api-settings active" data-id="tabs-gpt-api-settings" role="tabpanel">
                    <?php require AIWA_DIR_PATH . 'admin/includes/tab/tab-pane/gpt-api-settings.php'; ?>
                </div>
                <div class="tab-pane content-settings" data-id="tabs-content-settings" role="tabpanel">
                    <?php require AIWA_DIR_PATH . 'admin/includes/tab/tab-pane/content-tab.php'; ?>
                </div>
                <div class="tab-pane general-settings" data-id="tabs-general-settings" role="tabpanel">
                    <?php require AIWA_DIR_PATH . 'admin/includes/tab/tab-pane/general-settings-tab.php'; ?>
                </div>


                <input type="hidden" name="from-aiwa-settings" value="1">

            </form>

                <div class="quick-action-item">
                    <button id="aiwa-save-settings" class="aiwa-button save-settings" role="button"> <span class="title"><?php _e('Save Settings', 'ai-writing-assistant'); ?></span><span class="dashicons dashicons-saved"></span></button> <span style="font-size: 13px;font-weight: normal;position: relative;margin-left: 8px;" class="aiwa-save-settings badge badge-success aiwa-hidden"><?php _e('Saved!', 'ai-writing-assistant'); ?></span>
                </div>
        </div>
</div>

