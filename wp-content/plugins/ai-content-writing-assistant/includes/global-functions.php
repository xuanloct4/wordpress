<?php

function aiwa_checkNonce()
{
    $nonce = isset($_POST['rc_nonce']) ? sanitize_key($_POST['rc_nonce']) : "";
    if(!empty($nonce)){
        if(!wp_verify_nonce( $nonce, "rc-nonce" )){
            echo json_encode(array('success' => 'false', 'status' => 'nonce_verify_error', 'response' => ''));

            die();
        }
    }
}

if (!function_exists('rc_isJson')){
    function rc_isJson($str) {
        json_decode($str);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}

if (!function_exists('rc_extractJson')){
    function rc_extractJson($str) {
        preg_match('/({.*})/', $str, $match);
        if (count($match) > 0) {
            return $match[0];
        } else {
            return null;
        }
    }
}

function aiwa_get_post_types(){
    $exclude_types = array('attachment', 'revision', 'nav_menu_item', 'oembed_cache', 'user_request');
    $args = array(
        'public'   => true,
    );
    $output = 'names'; // names or objects, note names is the default
    $operator = 'and'; // 'and' or 'or'
    $post_types = get_post_types($args, $output, $operator);
    $post_types = array_diff($post_types, $exclude_types);

    return $post_types;
}

function aiwa_add_select_option($name, $value="", $isSelected=false, $id="", $echo=true){
    if (!empty($name) && empty($value)){
        $value = str_replace(array(' ', '-'), '', strtolower($name));
    }
    $isSelected = $isSelected ? 'selected' : '';
    if ($echo){
        if (!empty($id)){
            echo '<option id="aiwa-'.esc_attr($id).'" '.esc_attr($isSelected).' value="'.esc_attr($value).'"> '.esc_attr($name).'</option>';
        }else{
            echo '<option '.esc_attr($isSelected).' value="'.esc_attr($value).'"> '.esc_attr($name).'</option>';
        }
    }else{
        if (!empty($id)){
            return '<option id="aiwa-'.esc_attr($id).'" '.esc_attr($isSelected).' value="'.esc_attr($value).'"> '.esc_attr($name).'</option>';
        }else{
            return '<option '.esc_attr($isSelected).' value="'.esc_attr($value).'"> '.esc_attr($name).'</option>';
        }
    }

}

function aiwa_get_time_after($after='10 minutes', $format="g:i A"){
    $current_time = current_time( "Y-m-d H:i:s", false );
    return date($format, strtotime("+ " . $after, strtotime($current_time)));
}
function aiwaHasAccess()
{
    require( ABSPATH . WPINC . '/pluggable.php' );
    $capabilities = get_option('ai_writing_assistant__user_roles',array('administrator'));

    if (!empty($capabilities)){
        foreach ($capabilities as $cap) {
            if (current_user_can($cap)){
                return true;
                break;
            }
        }
    }
    if (current_user_can('administrator')){
        return true;
    }
    return false;
}
function aiwa_get_post_type () {
    global $pagenow;

    $post_type = NULL;

    if(empty($post_type) && isset($_REQUEST['post_type']) && !empty($_REQUEST['post_type']))
        $post_type = sanitize_key($_REQUEST['post_type']);

    if(empty($post_type) && 'edit.php' == $pagenow)
        $post_type = 'post';

    if(empty($post_type) && 'post-new.php' == $pagenow)
        $post_type = 'post';

    if(empty($post_type) && isset($_REQUEST['post']) && !empty($_REQUEST['post']) && function_exists('get_post_type') && $get_post_type = get_post_type((int)$_REQUEST['post']))
        $post_type = $get_post_type;

    return $post_type;
}

function aiwa_get_content_structure_options(){
    $key = 'ai_writing_assistant__';
    aiwa_add_select_option(__('Topic-Wise', 'ai-writing-assistant'), 'topic_wise', esc_attr(get_option($key.'ai-content-structure', 'topic_wise')) =='topic_wise');
    aiwa_add_select_option(__('Article', 'ai-writing-assistant'), 'article', esc_attr(get_option($key.'ai-content-structure', 'topic_wise')) =='article');
    aiwa_add_select_option(__('Review', 'ai-writing-assistant'), 'review', esc_attr(get_option($key.'ai-content-structure', 'topic_wise')) =='review');
    aiwa_add_select_option(__('Opinion', 'ai-writing-assistant'), 'opinion', esc_attr(get_option($key.'ai-content-structure', 'topic_wise')) =='opinion');
    aiwa_add_select_option(__('FAQ', 'ai-writing-assistant'), 'faq', esc_attr(get_option($key.'ai-content-structure', 'topic_wise')) =='faq');

    //aiwa_add_select_option(__('Pros and Cons', 'ai-writing-assistant'), 'pros_and_cons', esc_attr(get_option($key.'ai-content-structure', 'topic_wise')) =='pros_and_cons');
    //aiwa_add_select_option(__('Tutorial', 'ai-writing-assistant'), 'tutorial', esc_attr(get_option($key.'ai-content-structure', 'topic_wise')) =='tutorial');
    //aiwa_add_select_option(__('How-to', 'ai-writing-assistant'), 'how-to', esc_attr(get_option($key.'ai-content-structure', 'topic_wise')) =='how-to');
    //aiwa_add_select_option(__('Analysis', 'ai-writing-assistant'), 'analysis', esc_attr(get_option($key.'ai-content-structure', 'topic_wise')) =='analysis');
    //aiwa_add_select_option(__('Interviews', 'ai-writing-assistant'), 'interviews', esc_attr(get_option($key.'ai-content-structure', 'topic_wise')) =='interviews');
    //aiwa_add_select_option(__('Case-study', 'ai-writing-assistant'), 'case-study', esc_attr(get_option($key.'ai-content-structure', 'topic_wise')) =='case-study');
    //aiwa_add_select_option(__('Guide', 'ai-writing-assistant'), 'guide', esc_attr(get_option($key.'ai-content-structure', 'topic_wise')) =='guide');
    //aiwa_add_select_option(__('Email', 'ai-writing-assistant'), 'email', esc_attr(get_option($key.'ai-content-structure', 'topic_wise')) =='email');
    //aiwa_add_select_option(__('Youtube script', 'ai-writing-assistant'), 'youtube_script', esc_attr(get_option($key.'ai-content-structure', 'topic_wise')) =='youtube_script');
    //aiwa_add_select_option(__('Social Media Post', 'ai-writing-assistant'), 'social_media_post', esc_attr(get_option($key.'ai-content-structure', 'topic_wise')) =='social_media_post');
    //aiwa_add_select_option(__('Table', 'ai-writing-assistant'), 'table', esc_attr(get_option($key.'ai-content-structure', 'topic_wise')) =='table');
}

function aiwa_get_topics_tag_options(){
    $key = 'ai_writing_assistant__';
    aiwa_add_select_option(__('h1', 'ai-writing-assistant'), 'h2', esc_attr(get_option($key.'aiwa-topics-tag', 'h2')) =='h1');
    aiwa_add_select_option(__('h2', 'ai-writing-assistant'), 'h2', esc_attr(get_option($key.'aiwa-topics-tag', 'h2')) =='h2');
    aiwa_add_select_option(__('h3', 'ai-writing-assistant'), 'h2', esc_attr(get_option($key.'aiwa-topics-tag', 'h2')) =='h3');
    aiwa_add_select_option(__('h4', 'ai-writing-assistant'), 'h2', esc_attr(get_option($key.'aiwa-topics-tag', 'h2')) =='h4');
    aiwa_add_select_option(__('h5', 'ai-writing-assistant'), 'h2', esc_attr(get_option($key.'aiwa-topics-tag', 'h2')) =='h5');
    aiwa_add_select_option(__('h6', 'ai-writing-assistant'), 'h2', esc_attr(get_option($key.'aiwa-topics-tag', 'h2')) =='h6');
}


function aiwa_get_writing_tone_options(){
    $key = 'ai_writing_assistant__';
    aiwa_add_select_option(__('Informative', 'ai-writing-assistant'), 'informative', esc_attr(get_option($key.'writing-tone', 'informative')) =='informative');
    aiwa_add_select_option(__('Professional', 'ai-writing-assistant'), 'professional', esc_attr(get_option($key.'writing-tone', 'informative')) =='professional');
    aiwa_add_select_option(__('Approachable', 'ai-writing-assistant'), 'approachable', esc_attr(get_option($key.'writing-tone', 'informative')) =='approachable');
    aiwa_add_select_option(__('Confident', 'ai-writing-assistant'), 'confident', esc_attr(get_option($key.'writing-tone', 'informative')) =='confident');
    aiwa_add_select_option(__('Enthusiastic', 'ai-writing-assistant'), 'enthusiastic', esc_attr(get_option($key.'writing-tone', 'informative')) =='enthusiastic');
    aiwa_add_select_option(__('Casual', 'ai-writing-assistant'), 'casual', esc_attr(get_option($key.'writing-tone', 'informative')) =='casual');
    aiwa_add_select_option(__('Respectful', 'ai-writing-assistant'), 'respectful', esc_attr(get_option($key.'writing-tone', 'informative')) =='respectful');
    aiwa_add_select_option(__('Sarcastic', 'ai-writing-assistant'), 'sarcastic', esc_attr(get_option($key.'writing-tone', 'informative')) =='sarcastic');
    aiwa_add_select_option(__('Serious', 'ai-writing-assistant'), 'serious', esc_attr(get_option($key.'writing-tone', 'informative')) =='serious');
    aiwa_add_select_option(__('Thoughtful', 'ai-writing-assistant'), 'thoughtful', esc_attr(get_option($key.'writing-tone', 'informative')) =='thoughtful');
    aiwa_add_select_option(__('Witty', 'ai-writing-assistant'), 'witty', esc_attr(get_option($key.'writing-tone', 'informative')) =='witty');
    aiwa_add_select_option(__('Passionate', 'ai-writing-assistant'), 'passionate', esc_attr(get_option($key.'writing-tone', 'informative')) =='passionate');
    aiwa_add_select_option(__('Lighthearted', 'ai-writing-assistant'), 'lighthearted', esc_attr(get_option($key.'writing-tone', 'informative')) =='lighthearted');
    aiwa_add_select_option(__('Hilarious', 'ai-writing-assistant'), 'hilarious', esc_attr(get_option($key.'writing-tone', 'informative')) =='hilarious');
    aiwa_add_select_option(__('Soothing', 'ai-writing-assistant'), 'soothing', esc_attr(get_option($key.'writing-tone', 'informative')) =='soothing');
    aiwa_add_select_option(__('Emotional', 'ai-writing-assistant'), 'emotional', esc_attr(get_option($key.'writing-tone', 'informative')) =='emotional');
    aiwa_add_select_option(__('Inspirational', 'ai-writing-assistant'), 'inspirational', esc_attr(get_option($key.'writing-tone', 'informative')) =='inspirational');
    aiwa_add_select_option(__('Objective', 'ai-writing-assistant'), 'objective', esc_attr(get_option($key.'writing-tone', 'informative')) =='objective');
    aiwa_add_select_option(__('Persuasive', 'ai-writing-assistant'), 'persuasive', esc_attr(get_option($key.'writing-tone', 'informative')) =='persuasive');
    aiwa_add_select_option(__('Vivid', 'ai-writing-assistant'), 'vivid', esc_attr(get_option($key.'writing-tone', 'informative')) =='vivid');
    aiwa_add_select_option(__('Imaginative', 'ai-writing-assistant'), 'imaginative', esc_attr(get_option($key.'writing-tone', 'informative')) =='imaginative');
    aiwa_add_select_option(__('Musical', 'ai-writing-assistant'), 'musical', esc_attr(get_option($key.'writing-tone', 'informative')) =='musical');
    aiwa_add_select_option(__('Rhythmical', 'ai-writing-assistant'), 'rhythmical', esc_attr(get_option($key.'writing-tone', 'informative')) =='rhythmical');
    aiwa_add_select_option(__('Humorous', 'ai-writing-assistant'), 'humorous', esc_attr(get_option($key.'writing-tone', 'informative')) =='humorous');
    aiwa_add_select_option(__('Critical', 'ai-writing-assistant'), 'critical', esc_attr(get_option($key.'writing-tone', 'informative')) =='critical');
    aiwa_add_select_option(__('Clear', 'ai-writing-assistant'), 'clear', esc_attr(get_option($key.'writing-tone', 'informative')) =='clear');
    aiwa_add_select_option(__('Neutral', 'ai-writing-assistant'), 'neutral', esc_attr(get_option($key.'writing-tone', 'informative')) =='neutral');
    aiwa_add_select_option(__('Objective', 'ai-writing-assistant'), 'objective', esc_attr(get_option($key.'writing-tone', 'informative')) =='objective');
    aiwa_add_select_option(__('Biased', 'ai-writing-assistant'), 'biased', esc_attr(get_option($key.'writing-tone', 'informative')) =='biased');
    aiwa_add_select_option(__('Passionate', 'ai-writing-assistant'), 'passionate', esc_attr(get_option($key.'writing-tone', 'informative')) =='passionate');
    aiwa_add_select_option(__('Argumentative', 'ai-writing-assistant'), 'argumentative', esc_attr(get_option($key.'writing-tone', 'informative')) =='argumentative');
    aiwa_add_select_option(__('Reflective', 'ai-writing-assistant'), 'reflective', esc_attr(get_option($key.'writing-tone', 'informative')) =='reflective');
    aiwa_add_select_option(__('Helpful', 'ai-writing-assistant'), 'helpful', esc_attr(get_option($key.'writing-tone', 'informative')) =='helpful');
    aiwa_add_select_option(__('Connective', 'ai-writing-assistant'), 'connective', esc_attr(get_option($key.'writing-tone', 'informative')) =='connective');
    aiwa_add_select_option(__('Assertive', 'ai-writing-assistant'), 'assertive', esc_attr(get_option($key.'writing-tone', 'informative')) =='assertive');
    aiwa_add_select_option(__('Energetic', 'ai-writing-assistant'), 'energetic', esc_attr(get_option($key.'writing-tone', 'informative')) =='energetic');
    aiwa_add_select_option(__('Relaxed', 'ai-writing-assistant'), 'relaxed', esc_attr(get_option($key.'writing-tone', 'informative')) =='relaxed');
    aiwa_add_select_option(__('Polite', 'ai-writing-assistant'), 'polite', esc_attr(get_option($key.'writing-tone', 'informative')) =='polite');
    aiwa_add_select_option(__('Clever', 'ai-writing-assistant'), 'clever', esc_attr(get_option($key.'writing-tone', 'informative')) =='clever');
    aiwa_add_select_option(__('Funny', 'ai-writing-assistant'), 'funny', esc_attr(get_option($key.'writing-tone', 'informative')) =='funny');
    aiwa_add_select_option(__('Amusing', 'ai-writing-assistant'), 'amusing', esc_attr(get_option($key.'writing-tone', 'informative')) =='amusing');
    aiwa_add_select_option(__('Comforting', 'ai-writing-assistant'), 'comforting', esc_attr(get_option($key.'writing-tone', 'informative')) =='comforting');
}

function aiwa_get_writing_styles_options(){
    $key = 'ai_writing_assistant__';
    aiwa_add_select_option(__('normal', 'ai-writing-assistant'), 'normal', esc_attr(get_option($key.'writing-style', 'normal')) =='normal');
    aiwa_add_select_option(__('business', 'ai-writing-assistant'), 'business', esc_attr(get_option($key.'writing-style', 'normal')) =='business');
    aiwa_add_select_option(__('legal', 'ai-writing-assistant'), 'legal', esc_attr(get_option($key.'writing-style', 'normal')) =='legal');
    aiwa_add_select_option(__('technical', 'ai-writing-assistant'), 'technical', esc_attr(get_option($key.'writing-style', 'normal')) =='technical');
    aiwa_add_select_option(__('marketing', 'ai-writing-assistant'), 'marketing', esc_attr(get_option($key.'writing-style', 'normal')) =='marketing');
    aiwa_add_select_option(__('creative', 'ai-writing-assistant'), 'creative', esc_attr(get_option($key.'writing-style', 'normal')) =='creative');
    aiwa_add_select_option(__('narrative', 'ai-writing-assistant'), 'narrative', esc_attr(get_option($key.'writing-style', 'normal')) =='narrative');
    aiwa_add_select_option(__('expository', 'ai-writing-assistant'), 'expository', esc_attr(get_option($key.'writing-style', 'normal')) =='expository');
    aiwa_add_select_option(__('reflective', 'ai-writing-assistant'), 'reflective', esc_attr(get_option($key.'writing-style', 'normal')) =='reflective');
    aiwa_add_select_option(__('persuasive', 'ai-writing-assistant'), 'persuasive', esc_attr(get_option($key.'writing-style', 'normal')) =='persuasive');
    aiwa_add_select_option(__('descriptive', 'ai-writing-assistant'), 'descriptive', esc_attr(get_option($key.'writing-style', 'normal')) =='descriptive');
    aiwa_add_select_option(__('instructional', 'ai-writing-assistant'), 'instructional', esc_attr(get_option($key.'writing-style', 'normal')) =='instructional');
    aiwa_add_select_option(__('news', 'ai-writing-assistant'), 'news', esc_attr(get_option($key.'writing-style', 'normal')) =='news');
    aiwa_add_select_option(__('personal', 'ai-writing-assistant'), 'personal', esc_attr(get_option($key.'writing-style', 'normal')) =='personal');
    aiwa_add_select_option(__('travel', 'ai-writing-assistant'), 'travel', esc_attr(get_option($key.'writing-style', 'normal')) =='travel');
    aiwa_add_select_option(__('recipe', 'ai-writing-assistant'), 'recipe', esc_attr(get_option($key.'writing-style', 'normal')) =='recipe');
    aiwa_add_select_option(__('poetic', 'ai-writing-assistant'), 'poetic', esc_attr(get_option($key.'writing-style', 'normal')) =='poetic');
    aiwa_add_select_option(__('satirical', 'ai-writing-assistant'), 'satirical', esc_attr(get_option($key.'writing-style', 'normal')) =='satirical');
    aiwa_add_select_option(__('formal', 'ai-writing-assistant'), 'formal', esc_attr(get_option($key.'writing-style', 'normal')) =='formal');
    aiwa_add_select_option(__('informal', 'ai-writing-assistant'), 'informal', esc_attr(get_option($key.'writing-style', 'normal')) =='informal');
}

function aiwa_get_languages_options(){
    $key = 'ai_writing_assistant__';
    ?>
    <option data-name="Deutsch" id="dde" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "de"? "selected":""; ?> value="de">Deutsch</option>
    <option data-name="English" id="den" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "en"? "selected":""; ?> value="en">English</option>
    <option data-name="español" id="des" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "es"? "selected":""; ?> value="es">español</option>
    <option data-name="español (Latinoamérica)" id="des-419" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "es-419"? "selected":""; ?> value="es-419">español (Latinoamérica)</option>
    <option data-name="français" id="dfr" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "fr"? "selected":""; ?> value="fr">français</option>
    <option data-name="hrvatski" id="dhr" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "hr"? "selected":""; ?> value="hr">hrvatski</option>
    <option data-name="italiano" id="dit" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "it"? "selected":""; ?> value="it">italiano</option>
    <option data-name="Nederlands" id="dnl" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "nl"? "selected":""; ?> value="nl">Nederlands</option>
    <option data-name="polski" id="dpl" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "pl"? "selected":""; ?> value="pl">polski</option>
    <option data-name="português (Brasil)" id="dpt-BR" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "pt-BR"? "selected":""; ?> value="pt-BR">português (Brasil)</option>
    <option data-name="português (Portugal)" id="dpt-PT" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "pt-PT"? "selected":""; ?> value="pt-PT">português (Portugal)</option>
    <option data-name="Tiếng Việt" id="dvi" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "vi"? "selected":""; ?> value="vi">Tiếng Việt</option>
    <option data-name="Türkçe" id="dtr" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "tr"? "selected":""; ?> value="tr">Türkçe</option>
    <option data-name="русский" id="dru" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ru"? "selected":""; ?> value="ru">русский</option>
    <option data-name="العربية" id="dar" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ar"? "selected":""; ?> value="ar">العربية</option>
    <option data-name="ไทย" id="dth" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "th"? "selected":""; ?> value="th">ไทย</option>
    <option data-name="한국어" id="dko" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ko"? "selected":""; ?> value="ko">한국어</option>
    <option data-name="中文 (简体)" id="dzh-CN" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "zh-CN"? "selected":""; ?> value="zh-CN">中文 (简体)</option>
    <option data-name="中文 (繁體)" id="dzh-TW" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "zh-TW"? "selected":""; ?> value="zh-TW">中文 (繁體)</option>
    <option data-name="香港中文" id="dzh-HK" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "zh-HK"? "selected":""; ?> value="zh-HK">香港中文</option>
    <option data-name="日本語" id="dja" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ja"? "selected":""; ?> value="ja">日本語</option>
    <option data-name="Acoli" id="dach" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ach"? "selected":""; ?> value="ach">Acoli</option>
    <option data-name="Afrikaans" id="daf" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "af"? "selected":""; ?> value="af">Afrikaans</option>
    <option data-name="Akan" id="dak" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ak"? "selected":""; ?> value="ak">Akan</option>
    <option data-name="azərbaycan" id="daz" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "az"? "selected":""; ?> value="az">azərbaycan</option>
    <option data-name="Balinese" id="dban" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ban"? "selected":""; ?> value="ban">Balinese</option>
    <option data-name="Basa Sunda" id="dsu" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "su"? "selected":""; ?> value="su">Basa Sunda</option>
    <option data-name="Bork, bork, bork!" id="dxx-bork" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "xx-bork"? "selected":""; ?> value="xx-bork">Bork, bork, bork!</option>
    <option data-name="bosanski" id="dbs" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "bs"? "selected":""; ?> value="bs">bosanski</option>
    <option data-name="brezhoneg" id="dbr" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "br"? "selected":""; ?> value="br">brezhoneg</option>
    <option data-name="català" id="dca" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ca"? "selected":""; ?> value="ca">català</option>
    <option data-name="Cebuano" id="dceb" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ceb"? "selected":""; ?> value="ceb">Cebuano</option>
    <option data-name="čeština" id="dcs" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "cs"? "selected":""; ?> value="cs">čeština</option>
    <option data-name="chiShona" id="dsn" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "sn"? "selected":""; ?> value="sn">chiShona</option>
    <option data-name="Corsican" id="dco" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "co"? "selected":""; ?> value="co">Corsican</option>
    <option data-name="créole haïtien" id="dht" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ht"? "selected":""; ?> value="ht">créole haïtien</option>
    <option data-name="Cymraeg" id="dcy" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "cy"? "selected":""; ?> value="cy">Cymraeg</option>
    <option data-name="dansk" id="dda" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "da"? "selected":""; ?> value="da">dansk</option>
    <option data-name="Èdè Yorùbá" id="dyo" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "yo"? "selected":""; ?> value="yo">Èdè Yorùbá</option>
    <option data-name="eesti" id="det" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "et"? "selected":""; ?> value="et">eesti</option>
    <option data-name="Elmer Fudd" id="dxx-elmer" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "xx-elmer"? "selected":""; ?> value="xx-elmer">Elmer Fudd</option>
    <option data-name="esperanto" id="deo" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "eo"? "selected":""; ?> value="eo">esperanto</option>
    <option data-name="euskara" id="deu" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "eu"? "selected":""; ?> value="eu">euskara</option>
    <option data-name="Eʋegbe" id="dee" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ee"? "selected":""; ?> value="ee">Eʋegbe</option>
    <option data-name="Filipino" id="dtl" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "tl"? "selected":""; ?> value="tl">Filipino</option>
    <option data-name="Filipino" id="dfil" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "fil"? "selected":""; ?> value="fil">Filipino</option>
    <option data-name="føroyskt" id="dfo" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "fo"? "selected":""; ?> value="fo">føroyskt</option>
    <option data-name="Frysk" id="dfy" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "fy"? "selected":""; ?> value="fy">Frysk</option>
    <option data-name="Ga" id="dgaa" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "gaa"? "selected":""; ?> value="gaa">Ga</option>
    <option data-name="Gaeilge" id="dga" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ga"? "selected":""; ?> value="ga">Gaeilge</option>
    <option data-name="Gàidhlig" id="dgd" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "gd"? "selected":""; ?> value="gd">Gàidhlig</option>
    <option data-name="galego" id="dgl" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "gl"? "selected":""; ?> value="gl">galego</option>
    <option data-name="Guarani" id="dgn" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "gn"? "selected":""; ?> value="gn">Guarani</option>
    <option data-name="Hacker" id="dxx-hacker" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "xx-hacker"? "selected":""; ?> value="xx-hacker">Hacker</option>
    <option data-name="Hausa" id="dha" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ha"? "selected":""; ?> value="ha">Hausa</option>
    <option data-name="ʻŌlelo Hawaiʻi" id="dhaw" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "haw"? "selected":""; ?> value="haw">ʻŌlelo Hawaiʻi</option>
    <option data-name="Ichibemba" id="dbem" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "bem"? "selected":""; ?> value="bem">Ichibemba</option>
    <option data-name="Igbo" id="dig" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ig"? "selected":""; ?> value="ig">Igbo</option>
    <option data-name="Ikirundi" id="drn" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "rn"? "selected":""; ?> value="rn">Ikirundi</option>
    <option data-name="Indonesia" id="did" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "id"? "selected":""; ?> value="id">Indonesia</option>
    <option data-name="interlingua" id="dia" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ia"? "selected":""; ?> value="ia">interlingua</option>
    <option data-name="IsiXhosa" id="dxh" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "xh"? "selected":""; ?> value="xh">IsiXhosa</option>
    <option data-name="isiZulu" id="dzu" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "zu"? "selected":""; ?> value="zu">isiZulu</option>
    <option data-name="íslenska" id="dis" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "is"? "selected":""; ?> value="is">íslenska</option>
    <option data-name="Jawa" id="djw" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "jw"? "selected":""; ?> value="jw">Jawa</option>
    <option data-name="Kinyarwanda" id="drw" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "rw"? "selected":""; ?> value="rw">Kinyarwanda</option>
    <option data-name="Kiswahili" id="dsw" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "sw"? "selected":""; ?> value="sw">Kiswahili</option>
    <option data-name="Klingon" id="dtlh" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "tlh"? "selected":""; ?> value="tlh">Klingon</option>
    <option data-name="Kongo" id="dkg" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "kg"? "selected":""; ?> value="kg">Kongo</option>
    <option data-name="kreol morisien" id="dmfe" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "mfe"? "selected":""; ?> value="mfe">kreol morisien</option>
    <option data-name="Krio (Sierra Leone)" id="dkri" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "kri"? "selected":""; ?> value="kri">Krio (Sierra Leone)</option>
    <option data-name="Latin" id="dla" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "la"? "selected":""; ?> value="la">Latin</option>
    <option data-name="latviešu" id="dlv" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "lv"? "selected":""; ?> value="lv">latviešu</option>
    <option data-name="lea fakatonga" id="dto" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "to"? "selected":""; ?> value="to">lea fakatonga</option>
    <option data-name="lietuvių" id="dlt" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "lt"? "selected":""; ?> value="lt">lietuvių</option>
    <option data-name="lingála" id="dln" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ln"? "selected":""; ?> value="ln">lingála</option>
    <option data-name="Lozi" id="dloz" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "loz"? "selected":""; ?> value="loz">Lozi</option>
    <option data-name="Luba-Lulua" id="dlua" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "lua"? "selected":""; ?> value="lua">Luba-Lulua</option>
    <option data-name="Luganda" id="dlg" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "lg"? "selected":""; ?> value="lg">Luganda</option>
    <option data-name="magyar" id="dhu" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "hu"? "selected":""; ?> value="hu">magyar</option>
    <option data-name="Malagasy" id="dmg" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "mg"? "selected":""; ?> value="mg">Malagasy</option>
    <option data-name="Malti" id="dmt" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "mt"? "selected":""; ?> value="mt">Malti</option>
    <option data-name="Māori" id="dmi" <?php echo esc_attr(get_option($key."aiwa-language","en") == "mi"? "selected":""); ?> value="mi">Māori</option>
    <option data-name="Melayu" id="dms" <?php echo esc_attr(get_option($key."aiwa-language","en") == "ms"? "selected":""); ?> value="ms">Melayu</option>
    <option data-name="Nigerian Pidgin" id="dpcm" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "pcm"? "selected":""; ?> value="pcm">Nigerian Pidgin</option>
    <option data-name="norsk" id="dno" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "no"? "selected":""; ?> value="no">norsk</option>
    <option data-name="norsk nynorsk" id="dnn" <?php echo esc_attr(get_option($key."aiwa-language","en") == "nn"? "selected":""); ?> value="nn">norsk nynorsk</option>
    <option data-name="Northern Sotho" id="dnso" <?php echo esc_attr(get_option($key."aiwa-language","en") == "nso"? "selected":""); ?> value="nso">Northern Sotho</option>
    <option data-name="Nyanja" id="dny" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ny"? "selected":""; ?> value="ny">Nyanja</option>
    <option data-name="o‘zbek" id="duz" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "uz"? "selected":""; ?> value="uz">o‘zbek</option>
    <option data-name="Occitan" id="doc" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "oc"? "selected":""; ?> value="oc">Occitan</option>
    <option data-name="Oromoo" id="dom" <?php echo esc_attr(get_option($key."aiwa-language","en") == "om"? "selected":""); ?> value="om">Oromoo</option>
    <option data-name="Pirate" id="dxx-pirate" <?php echo esc_attr(get_option($key."aiwa-language","en") == "xx-pirate"? "selected":""); ?> value="xx-pirate">Pirate</option>
    <option data-name="română" id="dro" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ro"? "selected":""; ?> value="ro">română</option>
    <option data-name="rumantsch" id="drm" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "rm"? "selected":""; ?> value="rm">rumantsch</option>
    <option data-name="Runasimi" id="dqu" <?php echo esc_attr(get_option($key."aiwa-language","en") == "qu"? "selected":""); ?> value="qu">Runasimi</option>
    <option data-name="Runyankore" id="dnyn" <?php echo esc_attr(get_option($key."aiwa-language","en") == "nyn"? "selected":""); ?> value="nyn">Runyankore</option>
    <option data-name="Seychellois Creole" id="dcrs" <?php echo esc_attr(get_option($key."aiwa-language","en") == "crs"? "selected":""); ?> value="crs">Seychellois Creole</option>
    <option data-name="shqip" id="dsq" <?php echo esc_attr(get_option($key."aiwa-language","en") == "sq"? "selected":""); ?> value="sq">shqip</option>
    <option data-name="slovenčina" id="dsk" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "sk"? "selected":""; ?> value="sk">slovenčina</option>
    <option data-name="slovenščina" id="dsl" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "sl"? "selected":""; ?> value="sl">slovenščina</option>
    <option data-name="Soomaali" id="dso" <?php echo esc_attr(get_option($key."aiwa-language","en") == "so"? "selected":""); ?> value="so">Soomaali</option>
    <option data-name="Southern Sotho" id="dst" <?php echo esc_attr(get_option($key."aiwa-language","en") == "st"? "selected":""); ?> value="st">Southern Sotho</option>
    <option data-name="srpski (Crna Gora)" id="dsr-ME" <?php echo esc_attr(get_option($key."aiwa-language","en") == "sr-ME"? "selected":""); ?> value="sr-ME">srpski (Crna Gora)</option>
    <option data-name="srpski (latinica)" id="dsr-Latn" <?php echo esc_attr(get_option($key."aiwa-language","en") == "sr-Latn"? "selected":""); ?> value="sr-Latn">srpski (latinica)</option>
    <option data-name="suomi" id="dfi" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "fi"? "selected":""; ?> value="fi">suomi</option>
    <option data-name="svenska" id="dsv" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "sv"? "selected":""; ?> value="sv">svenska</option>
    <option data-name="Tswana" id="dtn" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "tn"? "selected":""; ?> value="tn">Tswana</option>
    <option data-name="Tumbuka" id="dtum" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "tum"? "selected":""; ?> value="tum">Tumbuka</option>
    <option data-name="türkmen dili" id="dtk" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "tk"? "selected":""; ?> value="tk">türkmen dili</option>
    <option data-name="Twi" id="dtw" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "tw"? "selected":""; ?> value="tw">Twi</option>
    <option data-name="Wolof" id="dwo" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "wo"? "selected":""; ?> value="wo">Wolof</option>
    <option data-name="Ελληνικά" id="del" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "el"? "selected":""; ?> value="el">Ελληνικά</option>
    <option data-name="беларуская" id="dbe" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "be"? "selected":""; ?> value="be">беларуская</option>
    <option data-name="български" id="dbg" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "bg"? "selected":""; ?> value="bg">български</option>
    <option data-name="кыргызча" id="dky" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ky"? "selected":""; ?> value="ky">кыргызча</option>
    <option data-name="қазақ тілі" id="dkk" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "kk"? "selected":""; ?> value="kk">қазақ тілі</option>
    <option data-name="македонски" id="dmk" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "mk"? "selected":""; ?> value="mk">македонски</option>
    <option data-name="монгол" id="dmn" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "mn"? "selected":""; ?> value="mn">монгол</option>
    <option data-name="српски" id="dsr" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "sr"? "selected":""; ?> value="sr">српски</option>
    <option data-name="татар" id="dtt" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "tt"? "selected":""; ?> value="tt">татар</option>
    <option data-name="тоҷикӣ" id="dtg" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "tg"? "selected":""; ?> value="tg">тоҷикӣ</option>
    <option data-name="українська" id="duk" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "uk"? "selected":""; ?> value="uk">українська</option>
    <option data-name="ქართული" id="dka" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ka"? "selected":""; ?> value="ka">ქართული</option>
    <option data-name="հայերեն" id="dhy" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "hy"? "selected":""; ?> value="hy">հայերեն</option>
    <option data-name="ייִדיש" id="dyi" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "yi"? "selected":""; ?> value="yi">ייִדיש</option>
    <option data-name="עברית" id="diw" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "iw"? "selected":""; ?> value="iw">עברית</option>
    <option data-name="ئۇيغۇرچە" id="dug" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ug"? "selected":""; ?> value="ug">ئۇيغۇرچە</option>
    <option data-name="اردو" id="dur" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ur"? "selected":""; ?> value="ur">اردو</option>
    <option data-name="پښتو" id="dps" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ps"? "selected":""; ?> value="ps">پښتو</option>
    <option data-name="سنڌي" id="dsd" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "sd"? "selected":""; ?> value="sd">سنڌي</option>
    <option data-name="فارسی" id="dfa" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "fa"? "selected":""; ?> value="fa">فارسی</option>
    <option data-name="کوردیی ناوەندی" id="dckb" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ckb"? "selected":""; ?> value="ckb">کوردیی ناوەندی</option>
    <option data-name="ትግርኛ" id="dti" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ti"? "selected":""; ?> value="ti">ትግርኛ</option>
    <option data-name="አማርኛ" id="dam" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "am"? "selected":""; ?> value="am">አማርኛ</option>
    <option data-name="বাংলা" id="dbn" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "bn"? "selected":""; ?> value="bn">বাংলা</option>
    <option data-name="नेपाली" id="dne" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ne"? "selected":""; ?> value="ne">नेपाली</option>
    <option data-name="मराठी" id="dmr" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "mr"? "selected":""; ?> value="mr">मराठी</option>
    <option data-name="हिन्दी" id="dhi" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "hi"? "selected":""; ?> value="hi">हिन्दी</option>
    <option data-name="ਪੰਜਾਬੀ" id="dpa" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "pa"? "selected":""; ?> value="pa">ਪੰਜਾਬੀ</option>
    <option data-name="ગુજરાતી" id="dgu" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "gu"? "selected":""; ?> value="gu">ગુજરાતી</option>
    <option data-name="ଓଡ଼ିଆ" id="dor" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "or"? "selected":""; ?> value="or">ଓଡ଼ିଆ</option>
    <option data-name="தமிழ்" id="dta" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ta"? "selected":""; ?> value="ta">தமிழ்</option>
    <option data-name="Assamese" id="Assamese" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "Assamese"? "selected":""; ?> value="Assamese">অসমীয়া</option>
    <option data-name="తెలుగు" id="dte" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "te"? "selected":""; ?> value="te">తెలుగు</option>
    <option data-name="ಕನ್ನಡ" id="dkn" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "kn"? "selected":""; ?> value="kn">ಕನ್ನಡ</option>
    <option data-name="മലയാളം" id="dml" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "ml"? "selected":""; ?> value="ml">മലയാളം</option>
    <option data-name="සිංහල" id="dsi" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "si"? "selected":""; ?> value="si">සිංහල</option>
    <option data-name="ລາວ" id="dlo" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "lo"? "selected":""; ?> value="lo">ລາວ</option>
    <option data-name="မြန်မာ" id="dmy" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "my"? "selected":""; ?> value="my">မြန်မာ</option>
    <option data-name="ខ្មែរ" id="dkm" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "km"? "selected":""; ?> value="km">ខ្មែរ</option>
    <option data-name="ᏣᎳᎩ" id="dchr" <?php echo esc_attr(get_option($key."aiwa-language","en")) == "chr"? "selected":""; ?> value="chr">ᏣᎳᎩ</option>
    <?php
}
