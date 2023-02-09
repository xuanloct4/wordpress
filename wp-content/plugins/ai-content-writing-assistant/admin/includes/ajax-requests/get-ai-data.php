<?php
namespace WpWritingAssistant\AjaxRequests;

class getAIData{

    private $ajax;
    /**
     * PreloadCaches constructor.
     */
    public function __construct($a)
    {
        $this->ajax = $a;
        add_action("wp_ajax_ai_writing_assistant_ai_data", [$this, 'ajax']);
    }

    public function ajax()
    {
        \aiwa_checkNonce();

        @ini_set('zlib.output_compression',0);
        @ini_set('implicit_flush',1);
        @ob_end_clean();

        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header( 'Content-Encoding: none; ' );

        require AIWA_DIR_PATH . 'admin/includes/content-switches.php';

        $switch = new \AIWA_Content_Switches();

        if (!empty($this->ajax->getSettings('api-key'))){
            $ai = new \OpenAIAPI($this->ajax->getSettings('api-key'));
            $ai->setModel('text-davinci-003');

            $prompt = isset($_POST['prompt']) ? sanitize_text_field($_POST['prompt']) : "";
            $type = isset($_POST['type']) ? sanitize_key($_POST['type']) : "";
            $first_prompt = isset($_POST['first_prompt']) ? sanitize_text_field($_POST['first_prompt']) : "";

            $middle_prompt = $prompt;
            if ($type=="call_to_action" && !empty($first_prompt)){
                $middle_prompt = $first_prompt;
            }
            if ($type == "what_language"){
                $middle_prompt = '"'.$middle_prompt.'"';
            }

            $temperature = isset($_POST['temperature']) ? sanitize_text_field($_POST['temperature']) : "0.4";
            $max_tokens = isset($_POST['max-tokens']) ? sanitize_text_field($_POST['max-tokens']) : "2000";
            $top_p = isset($_POST['top-p']) ? sanitize_text_field($_POST['top-p']) : "1.0";
            $best_of = isset($_POST['best-of']) ? sanitize_text_field($_POST['best-of']) : "1.0";
            $frequency_penalty = isset($_POST['frequency-penalty']) ? sanitize_text_field($_POST['frequency-penalty']) : "0";
            $presence_penalty = isset($_POST['presence-penalty']) ? sanitize_text_field($_POST['presence-penalty']) : "0";

            $data = array(
                'prompt' => $switch->startingprompt() . $middle_prompt . $switch->endingprompt(),
                'temperature' => intval($temperature),
                'max_tokens' => intval($max_tokens), //short: 128 , medium: 128, long: 1000 (for topic detailes)
                'top_p' => intval($top_p),
                /*'best_of' => intval($best_of),*/
                'frequency_penalty' => intval($frequency_penalty),
                'presence_penalty' => intval($presence_penalty),
                'stream'=> true
            );

            $ai->complete($data, function($data, $ai){
                if (strpos($data, 'data:')!==false){
                    $content = "";
                    $split = explode('data: ', $data);
                    if (count($split)) {
                        foreach ($split as $element) {
                            if (rc_isJson($element)) {
                                $json = json_decode($element);
                                $text = $json->choices[0]->text;
                                $text = str_replace("\n", '<br>', $text);
                                $content .= $text;
                            }
                        }
                    }
                    //$ai->setResponse(esc_html($content));
                    echo esc_html($content);
                    flush();
                    //ob_flush();
                    if (ob_get_level() > 0) {ob_flush();}
                }
                else{
                    if (aiwa_is_json($data)){
                        $obj = json_decode($data);
                        if (isset($obj->error)){
                            if ($obj->error->code == 'invalid_api_key'){
                                echo '__invalid_api_key__';
                            }
                        }

                    }
                }


                return strlen($data);
            });

        }
        else{
            echo '__api-empty__';
        }

        wp_die();
    }

}

