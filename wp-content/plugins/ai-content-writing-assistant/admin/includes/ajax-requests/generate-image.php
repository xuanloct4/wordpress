<?php
namespace WpWritingAssistant\AjaxRequests;

class GenerateImage
{

    private $ajax;

    /**
     * PreloadCaches constructor.
     */
    public function __construct($a)
    {
        $this->ajax = $a;
        add_action("wp_ajax_aiwa_generate_image", [$this, 'ajax']);
    }

    public function ajax()
    {
        \aiwa_checkNonce();
        $prompt = isset($_POST['prompt']) ? sanitize_text_field($_POST['prompt']) : "";
        $image_size = isset($_POST['image-size']) ? sanitize_text_field($_POST['image-size']) : "medium";
        $return_both = isset($_POST['return_both']) ? sanitize_key($_POST['return_both']) : false;

        $image_size_ = '512x512';
        if ($image_size=='thumbnail'){
            $image_size_ = '256x256';
        }
        elseif ($image_size=='medium'){
            $image_size_ = '512x512';
        }
        elseif ($image_size=='large'){
            $image_size_ = '1024x1024';
        }

        if (!empty($this->ajax->getSettings('api-key'))) {
            $ai = new \OpenAIAPI($this->ajax->getSettings('api-key'));
            $ai->setModel('text-davinci-003');

            $data = array(
                'prompt' => 'write a dall-e image generation prompt for "'.$prompt.'". Extream realistic. Do not add text.',
                'temperature' => 0,
                'max_tokens' => 500, //short: 128 , medium: 128, long: 1000 (for topic detailes)
                'top_p' => 1,
                'best_of' => 1,
                'frequency_penalty' => 0,
                'presence_penalty' => 0,
            );

           /* $response = $ai->complete($data);
            if (isset($response) && !empty($response)&&aiwa_is_json($response)){
                $data = array(
                    'prompt' => json_decode($response)->choices[0]->text,
                    'n' => 1,
                    'size' => $image_size_,
                );

            }*/

            $image_experiments = $this->ajax->getSettings('image_experiments', array());
            $image_styles = '';
            if (!empty($image_experiments)){
                $image_styles = implode(', ', $image_experiments);
                $image_styles = ' | ' . rtrim($image_styles, ',').'.';
            }

            $data = array(
                'prompt' => $prompt . $image_styles,
                'n' => 1,
                'size' => $image_size_,
            );
            $response = $ai->image($data);

            $url = $media_url = "";
            if (aiwa_is_json($response)){
                $json = json_decode($response);
                if (isset($json->data) && isset($json->data[0])){
                    $url = $json->data[0]->url;

                    $media_url = $this->upload_image_to_media_gallery($url, $return_both);
                }
            }

            //if (aiwa_is_url($media_url)){
                wp_send_json_success($media_url);
            /*}
            else{
                wp_send_json_error('failed_to_generate');
            }*/

        }
        else{
            wp_send_json_error('api_key_not_exist');
        }

        wp_die();


    }

    public function upload_image_to_media_gallery($url, $both = false)
    {
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        require_once( ABSPATH . 'wp-admin/includes/media.php' );

        $image_url = 'http://example.com/image.jpg';

        $tmp = download_url( $url );

        $file_array = array(
            'name'     => basename( $image_url ),
            'tmp_name' => $tmp
        );

        $id = media_handle_sideload( $file_array, 0 );

        if ( is_wp_error( $id ) ) {
            @unlink( $file_array['tmp_name'] );
            return $id;
        }
        $attachment = array();
        $attachment['id'] = $id;
        $attachment['url'] = wp_get_attachment_url( $id );
        return $attachment;
    }
}

