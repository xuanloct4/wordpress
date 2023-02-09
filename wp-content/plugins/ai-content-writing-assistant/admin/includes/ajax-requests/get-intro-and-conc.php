<?php
namespace WpWritingAssistant\AjaxRequests;

class GetIntroAndConc
{

    private $ajax;

    /**
     * PreloadCaches constructor.
     */
    public function __construct($a)
    {
        $this->ajax = $a;
        add_action("wp_ajax_get_intro_and_conc", [$this, 'ajax']);
    }

    public function ajax()
    {
        \aiwa_checkNonce();
        $ai = new \OpenAIAPI($this->ajax->getSettings('api-key'));
        $ai->setModel('text-davinci-003');

        $lang = isset($_POST['lang']) ? sanitize_text_field($_POST['lang']) : "en";
        $lang_name = isset($_POST['lang_name']) ? sanitize_text_field($_POST['lang_name']) : "English";

        if (!empty($this->ajax->getSettings('lang_'.$lang))){
            wp_send_json_success(esc_html( $this->ajax->getSettings('lang_'.$lang)));
            wp_die();
        }
        $data = array(
            'prompt' => 'in the '.$lang.' language, what word is most used for "Introduction" and "Conclusion", output in js array.',
        );

        $response = $ai->complete($data);
        if (aiwa_is_json($response)){
            $json = json_decode($response);
            if (isset($json->choices)){
                $text = $json->choices[0]->text;
                $text = str_replace("\n", '<br>', $text);
                $content = $text;

                $this->ajax->setSettings( sanitize_key('lang_'.$lang), sanitize_text_field($content));
                //$ai->setResponse(esc_html($content));
                wp_send_json_success(esc_html($content));
            }
            else{
                if (isset($json->error) && $json->error->code == 'invalid_api_key'){
                    wp_send_json_error('__invalid_api_key__');
                }
            }

        }
        else{
            wp_send_json_error('__error__');
        }
        var_dump($response);
        wp_die();

    }
}
