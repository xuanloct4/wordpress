<?php

class AIWA_Content_Switches{
    public function startingprompt()
    {

        $type = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : "";
        $value = $text = "";
        if ($type=="topic_wise" && isset($_POST['topics-count'])){
            $value = isset($_POST['topics-count']) ? intval(sanitize_key($_POST['topics-count'])) : "";
            $text = ($value > 1) ? 'topics' : 'topic';
        }
        if ($type=="article" && isset($_POST['article-paragraphs-count'])){
            $value = isset($_POST['article-paragraphs-count']) ? intval(sanitize_key($_POST['article-paragraphs-count'])) : "3";
            $text = ($value > 1) ? 'paragraphs' : 'paragraph';
        }
        if ($type=="faq" && isset($_POST['faq-items-count'])){
            $value = isset($_POST['faq-items-count']) ? intval(sanitize_key($_POST['faq-items-count'])) : "5";
            $text = ($value > 1) ? 'FAQ' : 'FAQs';
        }

        switch ($type) {
            case 'title':
                return "randomely select a blog title about ";
                break;
            case 'call_to_action':
                return "write a long  call-to-action about ";
                break;
            case 'introduction':
                return "write an SEO friendly introduction about ";
                break;
            case 'conclusion':
                return "write an SEO friendly conclution about ";
                break;
            case 'topic_wise':
                return "write {$value} {$text} about ";
                break;
            case 'topic_detailes':
                return "write about ";
                break;
            case 'article':
                return "write an article in {$value} {$text} about ";
                break;
            case 'review':
                return "write a review about ";
                break;
            case 'opinion':
                return "write an opinion about ";
                break;
            case 'faq':
                return "write {$value} {$text} about ";
                break;
            default:
                return "write blog post in detailed about ";
        }

    }

    public function endingprompt()
    {

        $type = isset($_POST['type']) ? sanitize_key($_POST['type']) : "";
        $value = "";
        if ($type == 'call_to_action'){
            $value = isset($_POST['call-to-action-url']) ? sanitize_text_field($_POST['call-to-action-url']) : "http://example.com";
        }
        if ($type == 'introduction'){
            $value = isset($_POST['introduction-size']) ? sanitize_key($_POST['introduction-size']) : "short";
        }
        if ($type == 'conclusion'){
            $value = isset($_POST['conclusion-size']) ? sanitize_key($_POST['conclusion-size']) : "short";
        }
        if ($type == 'topic_detailes'){
            $value = isset($_POST['content-length']) ? sanitize_key($_POST['content-length']) : "short";
        }


        $include = '';
        if (isset($_POST['include-keywords']) && !empty(sanitize_text_field($_POST['include-keywords']))){
            $include = ' you may include these keywords if needed: ' . sanitize_text_field($_POST['include-keywords']) . '.';
        }
        $exclude = '';
        if (isset($_POST['include-keywords']) && !empty(sanitize_text_field($_POST['exclude-keywords']))){
            $exclude = ' Must exclude these keywords: ' . sanitize_text_field($_POST['exclude-keywords']) . '.';
        }
        $bold = '';
        if (isset($_POST['bold-keyword']) && !empty($include)){
            $bold = ' Wrap include keywords with <strong>.';
        }
        $writing_style = '';
        if (isset($_POST['writing-style']) && !empty($_POST['writing-style']) && sanitize_key($_POST['writing-style']) != 'normal'){
            $writing_style = ' Writing style: ' . sanitize_key($_POST['writing-style']) . '.';
        }
        $writing_tone = '';
        if (isset($_POST['writing-tone']) && !empty($_POST['writing-tone'])/* && $type == 'topic_detailes'*/){
            $writing_tone = ' Writing tone: ' . sanitize_key($_POST['writing-tone']) . '.';
        }
        $in_language = "";
        if (isset($_POST['aiwa_language_text'])&&!empty($_POST['aiwa_language_text']) && sanitize_text_field($_POST['aiwa_language_text']) !== "English"){
            $in_language = ' Write in "'.sanitize_text_field($_POST['aiwa_language_text']).'" language.';
        }
        $faq_text = '. must write "Question" and "Answer" text and wrap with HTML tags where needed. ' . $writing_style . $writing_tone . $in_language;
        /*if(!empty($in_language)){
            $faq_text = '. must write translated "Question" and "Answer" text with '.$in_language.' language. wrap with HTML tags where needed. ' . $writing_style . $writing_tone . $in_language;
        }*/

        switch ($type) {
            case 'title':
                return ", and make this SEO friendly." . $writing_style . $writing_tone . $in_language;
                break;
            case 'call_to_action':
                return " and add this link \"{$value}\" and link will wrap with <a> tag. Add a h3 tag if needed." . $writing_style . $writing_tone . $in_language;
                break;
            case 'introduction':
                return "  in a {$value} size." . $writing_style . $writing_tone . $in_language;
                break;
            case 'conclusion':
                return "  in a {$value} size." . $writing_style . $writing_tone . $in_language;
                break;
            case 'topic_wise':
                return '. ' . $writing_style . $writing_tone . $in_language;
                break;
            case 'article':
                return '. wrap with HTML tags where needed. ' . $writing_style . $writing_tone . $in_language;
                break;
            case 'review':
                return '. wrap with HTML tags where needed. ' . $writing_style . $writing_tone . $in_language;
                break;
            case 'opinion':
                return '. wrap with HTML tags where needed. ' . $writing_style . $writing_tone . $in_language;
                break;
            case 'faq':
                return $faq_text;
                break;
            case 'topic_detailes':
                return ". Write a {$value} description about it." . $include . $exclude . $bold . $writing_style . $writing_tone . $in_language;
                break;
            default:
                return "";
        }
    }
}