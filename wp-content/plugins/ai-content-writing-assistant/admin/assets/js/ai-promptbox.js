(function ($) {
    'use strict';

    function generate_contents(params, first_prompt, prompt) {
        if ("generate-title" in params) {
            /*Getting the title*/
            Object.assign(params, {"type": "title", "first_prompt": first_prompt});

            aiwa_ajax(prompt, params, function (content) {
                $('.blog-title h1').html(aiwa_getTitle(content));
            }, 'title')

                .then(function (res) {

                    if ($('#aiwa_title').val().length > 0){
                        prompt = $('#aiwa_title').val();
                    }

                    /*If call-to-action set to the start of the content*/

                    if ("add-call-to-action" in params && params['call-to-action-position'] === 'start') {
                        Object.assign(params, {"type": "call_to_action"});

                        aiwa_ajax(prompt, params, function (response) {
                            $('.cta-start.cta').removeClass('aiwa-hidden').html(remove_first_br(unescapeHTML(response)));
                        }, 'call_to_action').then(function () {

                            /*Is introduction has selected*/
                            if ("add-introduction" in params) {
                                if ("add-introduction-text" in params) {
                                    add_introduction_text();
                                }

                                Object.assign(params, {"type": "introduction"});
                                aiwa_ajax(prompt, params, add_introduction, 'introduction').then(function () {
                                    content_structure_request_and_rest(prompt, params);
                                })
                            } else {
                                content_structure_request_and_rest(prompt, params);
                            }
                        })
                    } else { //if call-to-action not set
                        /*Is introduction has selected*/
                        if ("add-introduction" in params) {

                            if ("add-introduction-text" in params) {
                                add_introduction_text();
                            }
                            Object.assign(params, {"type": "introduction"});
                            aiwa_ajax(prompt, params, add_introduction, 'introduction').then(function () {
                                content_structure_request_and_rest(prompt, params);
                            })
                        } else {
                            content_structure_request_and_rest(prompt, params);
                        }
                    }


                })
        } else {
            if ("add-call-to-action" in params && params['call-to-action-position'] === 'start') {
                Object.assign(params, {"type": "call_to_action"});

                aiwa_ajax(prompt, params, function (response) {
                    $('.cta-start.cta').removeClass('aiwa-hidden').html(remove_first_br(unescapeHTML(response)));
                }, 'call_to_action').then(function () {

                    /*Is introduction has selected*/
                    if ("add-introduction" in params) {

                        if ("add-introduction-text" in params) {
                            add_introduction_text();
                        }
                        Object.assign(params, {"type": "introduction"});
                        aiwa_ajax(prompt, params, add_introduction, 'introduction').then(function () {
                            content_structure_request_and_rest(prompt, params);
                        })
                    } else {
                        content_structure_request_and_rest(prompt, params);
                    }
                })
            } else { //if call-to-action not set
                /*Is introduction has selected*/
                if ("add-introduction" in params) {

                    if ("add-introduction-text" in params) {
                        add_introduction_text();
                    }
                    Object.assign(params, {"type": "introduction"});
                    aiwa_ajax(prompt, params, add_introduction, 'introduction').then(function () {
                        content_structure_request_and_rest(prompt, params);
                    })
                } else {
                    content_structure_request_and_rest(prompt, params);
                }
            }
        }
    }

    $(document).on("click", ".generate-ai-content", function (e) {
        e.preventDefault();

        var prompt = $('#prompt-input').val();

        if (!$('#aiwa-generate-ai-content .aiwa_spinner').hasClass('hide_spin')){
            return;
        }

        if (prompt===""){
            $('.empty-prompt.badge').fadeIn(300);
            setTimeout(function(){
                $('.empty-prompt.badge').fadeOut(300);
            }, 4000);

            return;
        }

        var session_key = generateKey();
        $('#generation_session_key').val(session_key);

        var first_prompt = prompt;

        var inputs = $('#aiwa-ai-inputs').serialize();
        let params = Object.fromEntries(new URLSearchParams(inputs));
        Object.assign(params, {'session_key': session_key});

        generate_init(params);

        if ('auto-generate-image' in params && params['auto-generate-image'] === 'on'){
            if ($('.aiwa-single-generation-page').length){
                generate_image(prompt, 'true');
            }
            else{
                generate_image(prompt);
            }
        }

        generate_contents(params, first_prompt, prompt);


    });

    function generate_image(prompt, return_both='false') {
        var datas = {
            'action': 'aiwa_generate_image',
            'rc_nonce': aiwa.nonce,
            'prompt': prompt,
            'image-size': $('[name="ai-image-size"]').val(),
            'return_both': return_both
        };


        var inputs = $('#aiwa-ai-inputs').serialize();
        let params = Object.fromEntries(new URLSearchParams(inputs));

        Object.assign(datas, params);

        $.ajax({
            url: aiwa.ajax_url,
            data: datas,
            type: 'post',
            dataType: 'json',

            beforeSend: function () {

            },
            success: function (r) {
                console.log(r)
                if (r.success) {
                    $('.featured-image-generation-complete').fadeIn(300);
                    setTimeout(function(){
                        $('.featured-image-generation-complete').fadeOut(300);
                    }, 5000);
                    if ($('.aiwa-single-generation-page').length){
                        if (r.data.id!==undefined){
                            $('.aiwa-featured-image-section').removeClass('aiwa-hidden');
                            $('.aiwa-featured-image').attr('src', r.data.url);
                            $('.featured_image_id').val(r.data.id);
                        }
                    }
                    else if(wp.media.featuredImage!==undefined){
                        wp.media.featuredImage.set( r.data.id );
                        setTimeout(function(){
                            if ($('.components-button.is-destructive:not(.editor-post-trash)').length){
                                $('.components-button.is-destructive:not(.editor-post-trash)').click();
                                setTimeout(function(){
                                    $('.components-button.editor-post-featured-image__toggle').click();
                                    $('.media-frame').addClass('aiwa-hidden');
                                    $('.button.media-button').click();
                                    $('.media-frame').removeClass('aiwa-hidden');
                                }, 200);
                            }
                            else{
                                $('.components-button.editor-post-featured-image__toggle').click();
                                $('.media-frame').addClass('aiwa-hidden');
                                $('.button.media-button').click();
                                $('.media-frame').removeClass('aiwa-hidden');
                            }

                        }, 500);
                    }


                } else {
                    console.log('Something went wrong, please try again!');
                }

            }, error: function () {

            }
        });
    }


    $(document).on("click", ".aiwa-blog-post", function (e) {
        e.preventDefault();
        $('#aiwa_is_content_scrollable').val("0");
    });

    $(document).on("click", "#aiwa-cancel-btn", function (e) {
        e.preventDefault();
        $('#aiwa_is_generation_cancelled').val("1");
        $('#aiwa-generate-ai-content .aiwa_spinner').addClass('hide_spin');
    });

    function scrollToBottom() {
        if ($('#aiwa_is_content_scrollable').val()==="1"){
            $('.aiwa-blog-post').scrollTop($('.aiwa-blog-post')[0].scrollHeight - $('.aiwa-blog-post').height());
        }
    }
    function add_introduction_text() {
        $('.introduction h2').removeClass('aiwa-hidden');
        scrollToBottom();
        content_streaming($('.introduction_text').val(),$('.introduction h2'));
    }

    function content_streaming(str="", element, speedInMiliSeconds=50) {
        var i = 0;
        var AutoRefresh = setInterval(function () {
            var ii = i+1;
            element.text(str.substring(0, ii));

            if (str.length<=ii){
                clearInterval(AutoRefresh);
            }
            i++;
        }, speedInMiliSeconds);
    }

    function add_introduction(response) {
        $('.introduction p').html(remove_first_br(unescapeHTML(response)));
        scrollToBottom();
    }

    function add_conclusion(response) {
        $('.conclusion p').html(remove_first_br(unescapeHTML(response)));
        scrollToBottom()
    }
    function add_conclusion_text() {
        $('.conclusion h2').removeClass('aiwa-hidden');
        scrollToBottom()
        content_streaming($('.conclusion_text').val(),$('.conclusion h2'));
    }

    function generate_init(params) {
        $('#aiwa_is_content_scrollable').val("1");
        $('#ai-response').removeClass('aiwa-hidden-important');
        $('.aiwa-cancel-btn').removeClass('aiwa-hidden');
        $('.aiwa-blog-post').removeClass('expandable').html("");
        $('#aiwa_content_structure_completed').val("0")

        $('#aiwa-generate-ai-content .title').text('Generating'); //Todo

        $('#aiwa-generate-ai-content .aiwa_spinner').removeClass('hide_spin');
        $('#aiwa_is_generation_cancelled').val("0")

        var html = '<button class="extend-blog-post-preview" style="visibility: hidden;"><svg class="extend" fill="#000000" height="32px" width="32px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <path d="M469.5,0H44.755C20.697,0,0,18.838,0,42.891v424.745C0,491.689,20.697,512,44.755,512H469.5 c24.059,0,42.5-20.311,42.5-44.364V42.891C512,18.838,493.558,0,469.5,0z M490.213,468.372c0,12.061-9.778,21.84-21.84,21.84 H43.628c-12.063,0-21.84-9.779-21.84-21.84V43.628c0-12.061,9.778-21.84,21.84-21.84h424.745c12.063,0,21.84,9.779,21.84,21.84 V468.372z"></path> </g> </g> <g> <g> <path d="M436.872,65.362H327.936c-6.016,0-10.894,4.872-10.894,10.894s4.878,10.894,10.894,10.894h82.638L298.447,198.91 c-4.255,4.255-4.255,10.963,0,15.218c2.128,2.128,4.915,3.101,7.702,3.101c2.787,0,5.01-1.109,7.138-3.237l111.564-112.176v82.638 c0,6.021,4.878,10.894,10.894,10.894s10.894-4.872,10.894-10.894V75.519C446.638,69.497,442.888,65.362,436.872,65.362z"></path> </g> </g> <g> <g> <path d="M226.42,285.872c-4.255-4.255-11.431-4.439-15.686-0.184L98.043,397.816v-82.638c0-6.021-4.878-10.894-10.894-10.894 s-10.894,4.872-10.894,10.894v108.936c0,0.715,0.638,1.428,0.779,2.133c0.066,0.338,0.474,0.652,0.572,0.979 c0.105,0.351,0.326,0.71,0.467,1.05c0.168,0.404,0.463,0.771,0.675,1.149c0.134,0.239,0.274,0.492,0.427,0.723 c0.8,1.199,1.85,2.231,3.05,3.032c0.234,0.154,0.496,0.261,0.739,0.396c0.375,0.21,0.745,0.434,1.146,0.601 c0.342,0.141,0.701,0.221,1.053,0.327c0.327,0.096,0.641,0.59,0.979,0.657c0.703,0.141,1.419,0.583,2.133,0.583h108.936 c6.016,0,10.894-4.872,10.894-10.894s-4.878-10.894-10.894-10.894h-82.638l112.128-112.495 C230.958,297.208,230.676,290.128,226.42,285.872z"></path> </g> </g> </g></svg><svg class="compress aiwa-hidden" fill="#000000" height="32px" width="32px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512.201 512.201" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <g> <path d="M509.081,3.193c-4.16-4.16-10.88-4.16-15.04,0l-195.2,195.2V75.086c0-5.333-3.84-10.133-9.067-10.88 c-6.613-0.96-12.267,4.16-12.267,10.56V224.1c0,5.867,4.8,10.667,10.667,10.667h149.013c5.333,0,10.133-3.84,10.88-9.067 c0.96-6.613-4.16-12.267-10.56-12.267H313.881l195.2-195.093C513.241,14.18,513.241,7.353,509.081,3.193z"></path> <path d="M224.174,277.433H75.161c-5.333,0-10.133,3.84-10.88,9.067c-0.96,6.613,4.16,12.267,10.56,12.267h123.627L3.268,493.86 c-4.267,4.053-4.373,10.88-0.213,15.04c4.16,4.16,10.88,4.373,15.04,0.213c0.107-0.107,0.213-0.213,0.213-0.213l195.2-195.093 v123.2c0,5.333,3.84,10.133,9.067,10.88c6.613,0.96,12.267-4.16,12.267-10.56V288.1 C234.841,282.233,230.041,277.433,224.174,277.433z"></path> </g> </g> </g> </g></svg></button>';
        if ('generate-title' in params){
            html += '<div class="blog-title"><h1></h1></div>';
        }
        if ('add-call-to-action' in params && params['call-to-action-position'] === 'start'){
            html += '<div class="cta-start cta aiwa-hidden"></div>';
        }
        if ('add-introduction' in params){
            html += '<div class="introduction"><h2 class="aiwa-hidden"></h2><p></p></div>';

        }
        if ('ai-content-structure' in params && params['ai-content-structure']==='topic_wise'){
            html += '<div class="topics"></div>';
        }
        else{
            html += '<div class="ai-generated-contents"></div>';
        }
        if ('add-conclusion' in params){
            html += '<div class="conclusion"><h2 class="aiwa-hidden"></h2><p></p></div>';
        }
        if ('add-call-to-action' in params && params['call-to-action-position'] === 'end'){
            html += '<div class="cta-end cta aiwa-hidden"></div>';
        }
        $('.aiwa-blog-post').html(html);

        if ( ("aiwa-language" in params && params['aiwa-language'] !== "en") && ('add-introduction' in params && "add-introduction-text" in params) ||  ('add-conclusion' in params && "add-conclusion-text" in params) ){
            getIntroAndConcText(params['aiwa-language'], params['aiwa-language-text']).then(
                function (e) {
                    var array;
                    if (typeof e.data == "string"){
                        array = JSON.parse(remove_first_br(unescapeHTML(e.data)));
                    }
                    else{
                        array = e;
                    }

                    $('.introduction_text').val(array[0]);
                    $('.conclusion_text').val(array[1]);
                },
                function (error) {
                    console.log("Introduction or conclusion text could not set for unknown reason.")
                }
            )
        }

        empty_html_implements_in_preview_body(params);

        setTimeout(function () {
            $('#ai-response').slideDown(500);
        }, 1000);




    }


    function generate_finished(){
        $('.aiwa-cancel-btn').addClass('aiwa-hidden');
        $('#aiwa-generate-ai-content .aiwa_spinner').addClass('hide_spin');
        $('#aiwa-generate-ai-content .title').text('Generate'); //Todo
        $('.generation-complete.badge').fadeIn(300);

        setTimeout(function(){
            $('.generation-complete.badge').fadeOut(300);
        }, 5000);
    }
    function generate_canceled(session_key){
        var cncl_btn = $('.aiwa-cancel-btn[cancelled_session]');
        if (cncl_btn.length && cncl_btn.attr('cancelled_session') === session_key){
            return;
        }
        $('.aiwa-cancel-btn').addClass('aiwa-hidden').attr('cancelled_session', session_key);
        $('#aiwa-generate-ai-content .aiwa_spinner').addClass('hide_spin');
        $('#aiwa-generate-ai-content .title').text('Generate'); //Todo
    }

    function content_structure_request_and_rest(prompt, params) {

        if ("ai-content-structure" in params) {
            if (params['ai-content-structure']==='topic_wise'){
                Object.assign(params, {"type" : "topic_wise"});
                aiwa_ajax(prompt, params, null, 'topic_wise').then(function (response) {
                    var topics = getTheListToArray(remove_first_br(unescapeHTML(response)));

                    Object.assign(params, {"type" : "topic_detailes"});
                    request_topic_detailes_recursively(topics, params, 1);

                    checkForTopicsComplete().then(function () {
                        if ("add-conclusion" in params) {
                            if ("add-conclusion-text" in params) {
                                add_conclusion_text();
                            }
                            Object.assign(params, {"type" : "conclusion"});
                            aiwa_ajax(prompt, params, add_conclusion, 'conclusion').then(function () {
                                if ("add-call-to-action" in params && params['call-to-action-position']==='end') {
                                    Object.assign(params, {"type" : "call_to_action"});

                                    aiwa_ajax(prompt, params, function (response) {
                                        $('.cta-end.cta').removeClass('aiwa-hidden').html(remove_first_br(unescapeHTML(response)));
                                    }, 'call_to_action').then(function () {
                                        generate_finished();
                                    })
                                }
                                else{
                                    generate_finished();
                                }
                            })
                        }else if ("add-call-to-action" in params && params['call-to-action-position']==='end') {
                            Object.assign(params, {"type" : "call_to_action"});

                            aiwa_ajax(prompt, params, function (response) {
                                $('.cta-end.cta').removeClass('aiwa-hidden').html(remove_first_br(unescapeHTML(response)));
                            }, 'call_to_action').then(function () {
                                generate_finished();
                            })
                        }
                        else{
                            generate_finished();
                        }
                    })

                })
            }
            else{
                Object.assign(params, {"type" : params['ai-content-structure']});
                aiwa_ajax(prompt, params, add_blog_contents, 'not_topic_wise').then(function (response) {

                    if ("add-conclusion" in params) {
                        if ("add-conclusion-text" in params) {
                            add_conclusion_text();
                        }
                        Object.assign(params, {"type" : "conclusion"});
                        aiwa_ajax(prompt, params, add_conclusion, 'conclusion').then(function () {
                            if ("add-call-to-action" in params && params['call-to-action-position']==='end') {
                                Object.assign(params, {"type" : "call_to_action"});

                                aiwa_ajax(prompt, params, function (response) {
                                    $('.cta-end.cta').removeClass('aiwa-hidden').html(remove_first_br(unescapeHTML(response)));
                                }, 'call_to_action').then(function () {
                                    generate_finished();
                                })
                            }
                            else{
                                generate_finished();
                            }
                        })
                    }else if ("add-call-to-action" in params && params['call-to-action-position']==='end') {
                        Object.assign(params, {"type" : "call_to_action"});

                        aiwa_ajax(prompt, params, function (response) {
                            $('.cta-end.cta').removeClass('aiwa-hidden').html(remove_first_br(unescapeHTML(response)));
                        }, 'call_to_action').then(function () {
                            generate_finished();
                        })
                    }
                    else{
                        generate_finished();
                    }

                })
            }
        }
        //Object.assign(params, {"type" : "content_structure"});
        /*aiwa_ajax(prompt, params, content_structure, 'content_structure').then(function () {

        })*/
    }

    function request_topic_detailes_recursively(topics, params, now) {

        var i = now-1;
        var prompt = topics[i];

        var to = topicsTitleStream(topics[i], i);
        setTimeout(function(){
            aiwa_ajax(prompt, params, function (response) {
                $('.topics .topic-'+now+' p').html(remove_first_br(unescapeHTML(response)));
            }, 'topic_wise').then(function () {
                if (topics.length>now){
                    Object.assign(params, {"type" : "topic_detailes"});
                    request_topic_detailes_recursively(topics, params, now+1);
                }else{
                    $('#aiwa_content_structure_completed').val("1");
                }
            });
            var ii = i+1;
            $('.topics .topic-'+ii+' .move-buttons').removeClass('aiwa-hidden');
        }, to);
    }

    function add_blog_contents(response) {
        $('.ai-generated-contents').html(remove_first_br(unescapeHTML(response)));
    }

    function getIntroAndConcText(lang="en", lang_name = 'English', need='introduction') {
        var json = {"en":["Introduction","Conclusion"],"bn":["ভূমিকা","উপসংহার"],"es":["Introducción","Conclusión"],"de":["Einleitung","Schlussfolgerung"],"fr":["Introduction","Conclusion"],"hr":["Uvod","Zaključak"],"it":["Introduzione","Conclusione"],"nl":["Inleiding","Conclusie"],"pl":["Wprowadzenie","Wnioski"],"pt-BR":["Introdução","Conclusão"],"pt-PT":["Introdução","Conclusão"],"vi":["Giới thiệu","Kết luận"],"tr":["Giriş","Sonuç"],"ru":["Введение","Заключение"],"ar":["مقدمة","خاتمة"],"th":["บทนำ","บทสรุป"],"ko":["서론","결론"],"zh-CN":["引言","结论"],"zh-TW":["引言","結論"],"zh-HK":["引言","结论"],"ja":["「はじめに」","「結論」"],"ach":["Entwodiksyon","Konklizyon"],"af":["Inleiding","Gevolgtrekking"],"ak":["Pengantar","Kesimpulan"],"az":["Giriş","Nəticə"],"mk":["Вовед","Заклучок",["Вовед","Заклучок"]],"mn":["Танилцуулга","Дүгнэлт"],"hi":["परिचय","निष्कर्ष"],"sr":["Увод","Закључак"],"tt":["Кереш","Йомгаклау"],"tg":["Муқаддима","Хулоса"],"uk":["Вступ","Висновок"],"id":["Pendahuluan","Kesimpulan"],"ro":["Introducere","Concluzie"],"rm":["Ro-ràdh","Co-dhùnadh"],"sl":["Uvod","Zaključek"],"sk":["Úvod","Záver"],"tk":["Giriş","Netije"],"tw":["Nnianim asɛm","Awiei"],"be":["Уводзіны","Заключэнне"],"bg":["Въведение","Заключение"],"ky":["Кириш","Корутунду"],"kk":["Кіріспе","Қорытынды"],"fil":["Panimula","Konklusyon"],"fo":["Inngangur","Niðurstaða"],"fy":["Ynlieding","Konklúzje"],"ga":["Réamhrá","Conclúid"],"gd":["Ro-ràdh","Co-dhùnadh"],"gn":["Introduzione","Conclusione"],"haw":["Introduction","Hoʻopau"],"bem":["Sumo","Mhedziso"],"rn":["Intangiriro","Umwanzuro"],"xh":["Intshayelelo","Isiphelo"],"zu":["Isingeniso","Isiphetho"],"pa":["ਜਾਣ-ਪਛਾਣ","ਸਿੱਟਾ"]};
        return new Promise((resolve, reject) => {
            if (lang in json) {
                resolve(json[lang]);
            } else {
                aiwa_ajax_('get_intro_and_conc', {'lang': lang, 'lang_name': lang_name}).then(
                    function (e) {
                        resolve(e);
                    },
                    function (error) {
                        reject(new Error('Something is not right!'));
                    }
                )
            }
        })
    }
    function checkForTopicsComplete() {
        return new Promise((resolve, reject) => {
            var intVal = setInterval(function () {
                if ($('#aiwa_content_structure_completed').val() == "1"){
                    resolve('success');
                    clearInterval(intVal);
                }
            }, 3000);
        });
    }

    function topicsTitleStream(string, topic_class) {
        topic_class += 1;
        var splitWords = string.split(' ');
        for (let i = 0; i < splitWords.length; i++) {
            setTimeout(function(){
                $('.topics .topic-'+topic_class+' .topic-heading').append(splitWords[i]+' ');
                $('#aiwa-generate-ai-content .aiwa_spinner').removeClass('hide_spin');
                scrollToBottom()
            }, 100*i);
        }

        return (100*splitWords.length);
    }
    function generateKey() {
        const characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        let key = '';
        for (let i = 0; i < 8; i++) {
            key += characters.charAt(Math.floor(Math.random() * characters.length));
        }
        return key;
    }


    function aiwa_request_content_body(prompt, params) {
        Object.assign(params, {"type":"content_body"});
        aiwa_ajax(prompt, params, function (response) {

        }, 'call_to_action');
    }

    function aiwa_getTitle(html) {
        return aiwa_replace_double_quo(aiwa_remove_html_tags(unescapeHTML(html)));
    }

    function unescapeHTML(escapedHTML) {
        return escapedHTML.replace(/&lt;/g,'<').replace(/&gt;/g,'>').replace(/&amp;/g,'&').replace(/&quot;/g,'"');
    }

    function aiwa_remove_html_tags(content) {
        return content.replace(/<[^>]+>/g, '');
    }
    function aiwa_replace_double_quo(content) {
        return content.replace(/"/g, '');
    }
    function remove_first_br(content) {
        var index = content.indexOf("<br><br>");
        return content.substring(index+8);
    }
    function br_remove(content) {
        content = content.replace(/<br>/g, "");
        return content.replace(/\n/g, "");
    }
    function getTheListToArray(string) {
        if (string.includes("<br>")){
            return aiwa_removeNumbers(string).split("<br>");
        }
        else{
            return aiwa_removeNumbers(string).split("</br>");
        }
    }

    function empty_html_implements_in_preview_body(params) {
        if ("ai-content-structure" in params && params['ai-content-structure'] === 'topic_wise'){
            if ("topics-count" in params && params['topics-count'] !== ''){
                var topics = parseInt(params['topics-count']);
                var topics_html = "";
                var topics_heading_tag = 'h2';
                if ("aiwa-topics-tag" in params && params['aiwa-topics-tag'] !== ''){
                    topics_heading_tag = params['aiwa-topics-tag'];
                }

                for (let i = 1; i <= topics; i++) {
                    topics_html += '<div class="topic topic-'+i+'"><'+topics_heading_tag+' class="topic-heading"></'+topics_heading_tag+'><div class="move-buttons aiwa-hidden"><button class="move-up" onclick="moveUp.call(this)"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7 14L12 9L17 14H7Z" fill="#000000"></path></svg></button><button class="move-down" onclick="moveDown.call(this)"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7 10L12 15L17 10H7Z" fill="#000000"></path></svg></button></div><p></p></div>';
                }

                $('.aiwa-blog-post .topics').html(topics_html);
            }
        }
    }

    function aiwa_ajax(prompt="", ai_inputs, callback=null, currentAction="") {
        if ($('#aiwa_is_generation_cancelled').val() === "1"){
            generate_canceled(ai_inputs['session_key']);
            return;
        }
        if ('session_key' in ai_inputs && ai_inputs['session_key']!==$('#generation_session_key').val()){
            generate_canceled(ai_inputs['session_key']);
            return;
        }

        var datas = {
            'action': 'ai_writing_assistant_ai_data',
            'rc_nonce': aiwa.nonce,
            'prompt': prompt,
        };
        if (Object.keys(ai_inputs).length > 0){
            Object.assign(datas, ai_inputs);
        }

        var ai_response = "";
        return new Promise((resolve, reject) => {
            return $.ajax({
                url: aiwa.ajax_url,
                data: datas,
                type: 'post',
                //dataType: 'json',

                beforeSend: function () {

                },
                xhrFields: {
                    // Getting on progress streaming response
                    onprogress: function (e) {
                        var response = e.target.response;
                        ai_response = response;
                        if ($('#aiwa_is_generation_cancelled').val() === "1"){
                            generate_canceled(ai_inputs['session_key']);
                            reject("");
                            return;
                        }
                        if ('session_key' in ai_inputs && ai_inputs['session_key']!==$('#generation_session_key').val()){
                            generate_canceled(ai_inputs['session_key']);
                            reject("");
                            return;
                        }
                        if(callback!==null) callback(response, ai_inputs);

                        scrollToBottom()

                    }

                },
                success: function (e) {

                    $('#aiwa_recent_task_completed').val(currentAction);

                    if (currentAction==='title'){
                        $('#aiwa_title').val(aiwa_getTitle(ai_response));
                    }
                    if ( ai_response.trim()== "__api-empty__" ){
                        alert("Please enter the API key on the settings panel first.");
                        $('#ai-response').addClass('aiwa-hidden-important');
                        generate_canceled(ai_inputs['session_key']);
                        reject(ai_response);
                    }else if(ai_response.trim()=='__invalid_api_key__'){
                        alert("API key is invalid. Please update that and try again.");
                        $('#ai-response').addClass('aiwa-hidden-important');
                        generate_canceled(ai_inputs['session_key']);
                        reject(ai_response);
                    }
                    else{
                        resolve(ai_response);
                    }
                    //console.log(ai_response)
                },
                error: function (e) {
                    $('#aiwa-generate-ai-content .aiwa_spinner').addClass('hide_spin');
                    $('#aiwa-generate-ai-content .title').text('Generate'); //Todo
                    if (currentAction==='title'){
                        $('#aiwa_title').val(aiwa_getTitle(ai_response));
                    }
                    reject(ai_response);
                    generate_canceled(ai_inputs['session_key']);
                }
            })
        });

    }

    function aiwa_ajax_(action, input_data) {
        var datas = {
            'action': action,
            'rc_nonce': aiwa.nonce
        };
        Object.assign(datas, input_data);

        return new Promise((resolve, reject) => {
            return $.ajax({
                url: aiwa.ajax_url,
                data: datas,
                type: 'post',
                dataType: 'json',

                beforeSend: function () {

                },
                success: function (r) {
                    if (r.success) {
                        resolve(r)
                    } else {
                        reject(new Error('Something is not right!'));
                        console.log('Something went wrong, please try again!');
                    }

                }, error: function () {
                    reject(new Error('Something is not right!'));
                }
            });
        })
    }

    $(document).on("mousemove", ".aiwa-blog-post", function () {
        if ($(this).innerHeight() > 299){
            $(this).addClass('expandable');
        }else{
            $(this).removeClass('expandable');
        }
    });

    $(document).on("click", ".extend-blog-post-preview .extend", function (e) {
        e.preventDefault();
        $('.aiwa-blog-post.expandable').css({'max-height': 'max-content'});
        $(this).hide();
        $('.extend-blog-post-preview .compress').show();

    });
    $(document).on("click", ".extend-blog-post-preview .compress", function (e) {
        e.preventDefault();
        $('.aiwa-blog-post.expandable').css({'max-height': '400px'});
        $(this).hide();
        $('.extend-blog-post-preview .extend').show();

    });


    function splitWords(string) {
        return string.split(' ');
    }

    $(document).on("input", ".range-input .slider", function () {
        $(this).siblings('label').children('input').val($(this).val());
    });

    $('.range-input label .input-box').keydown(function(event) {
        if(event.keyCode !== 8 && !$.isNumeric(event.key)) {
            event.preventDefault();
        }
    });

    $(document).on("input", ".range-input label .input-box", function () {
        $(this).closest('.range-input').find('.slider').val($(this).val());
        $(this).closest('.range-input').find('.slider').change();
    });


    $(document).on("click", "#aiwa-save-for-future-use", function (e) {
        e.preventDefault();
        var formData = $('#aiwa-ai-inputs').serialize(); // serialize the form data

        var datas = {
            'action': 'ai_writing_assistant_save_settings',
            'rc_nonce': aiwa.nonce,
            'formData': formData,
        };

        var select = $('[name="ai-image-size"]').val();
        var custom = $('[name="custom-ai-image-size"]').val();
        var regex = /^\d+x\d+$/; // Regular expression to check for "XxY" format

        if (select == 'custom' && !regex.test(custom)) { // If input does not match the regular expression
            alert("Input must be in the format like '100x100'"); //todo
            return false; // Prevent form submission
        }

        $.ajax({
            url: aiwa.ajax_url,
            data: datas,
            type: 'post',
            dataType: 'json',

            beforeSend: function () {
            },
            success: function (r) {
                if (r.success) {
                    $('#aiwa-save-for-future-use').siblings('.badge').removeClass('aiwa-hidden');
                    setTimeout(function(){
                        $('#aiwa-save-for-future-use').siblings('.badge').addClass('aiwa-hidden');
                    }, 5000);

                } else {
                    console.log('Something went wrong, please try again!'); //Todo

                }

            }, error: function () {
            }
        });

    });
    $(document).on("click", ".aiwa-settings-btn", function (e) {
        e.preventDefault();
        var setting = $(this).data('settings');
        $('.prompt-settings-item').not('.prompt-settings-item[data-tab="'+setting+'"]').slideUp();
        $('.prompt-settings-item[data-tab="'+setting+'"]').slideToggle();

    });

    $(document).on("click", ".minimize-btn", function (e) {
        e.preventDefault();
        $(this).closest('.prompt-settings-item').slideUp();
    });

    $(document).on("change", "select[data-has-subsettings]", function () {
        var id = $(this).attr('id');
        var settings_key = $(this).val();

        $('[data-subsettings-of="'+id+'"]').not('[data-sub-settings-key="'+settings_key+'"]').slideUp(300);
        $('[data-subsettings-of="'+id+'"][data-sub-settings-key="'+settings_key+'"]').slideDown(300);
    });

    $(document).on("change", "input[data-has-subsettings]", function () {
        var id = $(this).attr('id');
        var is_checked = $(this).is(':checked');
        if (is_checked){
            $('[data-subsettings-of="'+id+'"]').slideDown(300);
        }
        else{
            $('[data-subsettings-of="'+id+'"]').slideUp(300);
        }
    });

    $(document).on("change", '[name="ai-image-size"]', function () {
        if ($(this).val() == 'custom'){
            $('[name="custom-ai-image-size"]').removeClass('aiwa-hidden');
        }else{
            $('[name="custom-ai-image-size"]').addClass('aiwa-hidden');
        }
    });

    function getTitleHtml(titles) {

        var title_arrays = titles.split("\n");
        title_arrays = aiwa_removeNumbers(title_arrays);

        if (title_arrays.length){
            var arrays_length = (title_arrays.length)-1;
            var rand = aiwa_rand(0, arrays_length);
            return title_arrays[rand];
        }

    }

    function aiwa_removeNumbers(list) {
        return list.replace(/\d\.+/g, "");
    }

    function aiwa_rand(min, max) { // min and max included
        return Math.floor(Math.random() * (max - min + 1) + min)
    }

    $(document).on("click", "#aiwa-writing-assistant-btn", function (e) {
        e.preventDefault();
        if (!$(this).hasClass('loading')){
            $(this).toggleClass('activate');
            $('#ai-writing-assistant-promptbox').slideToggle();
        }

    });

    $(document).on("change", "#aiwa-language", function () {
        var language = $(this).find('option:selected').data('name');
        $('#aiwa_language_text').val(language);
    });

    $(document).on("focus", ".aiwa-single-content-title", function () {
        $(this).siblings('label').addClass('screen-reader-text')
    });

    $(document).on("click", ".code-box-insert-btn", function (e) {
        e.preventDefault();
        var generated_title = $('.aiwa-blog-post .blog-title h1');
        var html = $(".aiwa-blog-post").clone();
        html.find(".extend-blog-post-preview, .blog-title, .move-buttons, .aiwa-hidden").remove();
        html.find("*").removeClass(function(index, className) {
            return (className.match(/(^|\s)topic-\S+/g) || []).join(' ');
        });
        if ($('#content').length) {
            $('#content').focus();
        }

        if ($('#aiwa-editor').length){
            $('#aiwa-editor').focus();
        }
        $('.html-active #aiwa-editor-tmce').click();
        if (tinymce.activeEditor) {
            $('#title').siblings('label').addClass('screen-reader-text')
            $('#title').val(generated_title.html());

            setTimeout(function(){
                tinymce.activeEditor.execCommand('mceInsertContent', false, html.html());
            }, 1000);
        } else {
            if (wp.data.dispatch('core/editor')==undefined){
                return
            }

            if (generated_title.length){
                wp.data.dispatch('core/editor').editPost({title: generated_title.html()});
            }


            var name = 'core/paragraph';
            var insertedBlock = wp.blocks.createBlock(name, {
                content: html.html(),
            });
            wp.data.dispatch('core/editor').insertBlocks(insertedBlock);
        }


    });

    $(document).ready(function () {

        const d = new Date();
        let year = d.getFullYear();

        new dateDropper({
            selector: '.aiwa-date-picker',
            expandable: true,
            minYear: year,
            maxYear: 2050,
            defaultDate: true
        });
        /*$('.aiwa-time-picker').timeDropper({
            autoswitch: true,
            meridians: true,
            mousewheel: true,
            init_animation: 'dropDown'
        });*/
        $('.aiwa-time-picker').timepicki();
    });

    $(document).on("click", ".code-box-copy-btn", function (e) {
        e.preventDefault();

        var html = $(".aiwa-blog-post").clone();
        html.find(".extend-blog-post-preview, .move-buttons").remove();
        html.find("*").removeClass(function(index, className) {
            return (className.match(/(^|\s)topic-\S+/g) || []).join(' ');
        });

        $('textarea.code-box-code').html(html.html());

        var t = $(this);

        var textarea = $('#ai-response .code-box-code');
        textarea.parent().show(); // make the textarea visible
        textarea.select(); // Select the contents of the textarea
        document.execCommand("copy"); // Execute the copy command
        textarea.hide(); // hide the textarea


        t.children('span').text('Copied!'); //Todo

        setTimeout(function(){
            t.children('span').text('Copy'); //Todo
        }, 5000);


    });

    $(document).ready(function () {

        if($('#aiwa_single_generation_promptbox').length){
            $('#aiwa_single_generation_promptbox').prepend($('#ai-writing-assistant-promptbox'));
            $('#ai-writing-assistant-promptbox').removeClass('aiwa-hidden');
        }

        if($('#aiwa-auto-content-writer').length){
            $('#aiwa-auto-content-writer').prepend($('#ai-writing-assistant-promptbox'));
            $('#ai-writing-assistant-promptbox').removeClass('aiwa-hidden');
        }

        const timeoutVar = setTimeout(function () {
            var gutenbergVitual_editor = document.getElementsByClassName('editor-post-title__input');
            var promptBoxHolder = document.getElementById('aiwa-prompt-box-holder');
            if (gutenbergVitual_editor!==null && promptBoxHolder !== null){
                const newElement = document.createElement('div');
                newElement.innerHTML = promptBoxHolder.innerHTML;

                const element = gutenbergVitual_editor[0];

                if (element !== undefined) {
                    element.parentNode.insertAdjacentHTML('beforebegin', newElement.outerHTML);
                }
            }
            var titleDiv = jQuery("#titlediv");
            if (titleDiv.length && titleDiv.siblings('#ai-writing-assistant-promptbox').length == 0){
                $('#ai-writing-assistant-promptbox').insertBefore(jQuery("#titlediv"));
            }


            if (document.getElementById('aiwa-placeholders') != null) {
                var placeholders = document.getElementById('aiwa-placeholders').value;
                placeholders = placeholders.split(',');
                var rand = aiwa_rand2(0, (placeholders.length - 1));
                var placeholder_init_text = aiwa_removeNumbers2(placeholders[rand]).trim();
                //document.getElementById('prompt-input').setAttribute('placeholder', placeholder_init_text);


                document.getElementById('prompt-input').setAttribute('placeholder', '');
                for (let i = 0; i < placeholder_init_text.length; i++) {
                    setTimeout(function () {
                        var placeholder = document.getElementById('prompt-input').getAttribute('placeholder');
                        document.getElementById('prompt-input').setAttribute('placeholder', placeholder + placeholder_init_text[i]);
                    }, i * 50);
                }


                var AutoRefresh = setInterval(function () {
                    var rand = aiwa_rand2(0, (placeholders.length - 1));
                    aiwa_replace_placeholder_like_stream(aiwa_removeNumbers2(placeholders[rand]).trim());
                }, 10000);

            }
            const bPost = document.getElementsByClassName('aiwa-blog-post')[0];
            if (bPost !== undefined && window.innerHeight < 600) {
                bPost.style.maxHeight = '300px';
            }

            var aiwa_btn = document.getElementById('aiwa-writing-assistant-btn');
            if (aiwa_btn !== null) {
                aiwa_btn.classList.remove("loading");
            }

        }, 2000);
    });


})(jQuery);

function moveUp() {
    let topic = this.parentNode.parentNode;
    topic.parentNode.insertBefore(topic, topic.previousSibling);
}

function moveDown() {
    let topic = this.parentNode.parentNode;
    topic.parentNode.insertBefore(topic.nextSibling, topic);
}
function aiwa_rand2(min, max) { // min and max included
    return Math.floor(Math.random() * (max - min + 1) + min)
}
function aiwa_removeNumbers2(list) {
    return list.replace(/\d\.|\d\d\.+/g, "");
}
function aiwa_replace_placeholder_like_stream(string){
    var prompt_input = document.getElementById('prompt-input');
    prompt_input.setAttribute('placeholder', '');
    for (let i = 0; i < string.length; i++) {

        setTimeout(function(){
            var placeholder = document.getElementById('prompt-input').getAttribute('placeholder');
            prompt_input.setAttribute('placeholder', placeholder + string[i]);
        }, i*50);
    }
}


jQuery(function($) {
    'use strict';


    var media_properties = ['id', 'url'];

    jQuery('.aiwa-media-remove').on('click', function(e) {
        e.preventDefault();

        if (jQuery(this).attr('data-browse-button')) var $browse = jQuery(jQuery(this).attr('data-browse-button'));
        else var $browse = jQuery(this).siblings('.media-browse');

        if (!$browse.length) {
            alert('No sibling browse button found, or the data-browse-button attribute had no matching elements');
            return false;
        }

        $browse.data('attachment', false).trigger('attachment-removed');

        // Trigger the update for the browse button's fields
        for (i = 0; i < media_properties.length; i++) {
            var media_key = media_properties[i];
            var selector = $browse.attr('data-media-' + media_key); // data-media-url, data-media-link, data-media-height

            if (selector) {
                var $target = jQuery(selector);

                if ($target.length) {
                    $target.val('').trigger('media-updated').trigger('change');
                }
            }
        }

        return false;
    });

    var file_frame;
    jQuery('.aiwa-media-browse').on('click', function(e) {
        e.preventDefault();

        var $this = jQuery(this);

        if (!wp || !wp.media) {
            alert('The media gallery is not available. You must admin_enqueue this function: wp_enqueue_media()');
            return;
        }

        // If the media frame already exists, reopen it.
        if (file_frame) {
            file_frame.open();
            return;
        }

        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
            title: $this.attr('data-media-title') || 'Browsing Media',
            button: {
                text: $this.attr('data-media-text') || 'Select',
            },
            multiple: false // Set to true to allow multiple files to be selected
        });

        // When an image is selected, run a callback.
        file_frame.on('select', function() {
            // We set multiple to false so only get one image from the uploader
            var attachment = file_frame.state().get('selection').first().toJSON();
            //console.log(attachment)
            $('.aiwa-featured-image').attr('src', attachment.url)
            $('.aiwa-featured-image-section').removeClass('aiwa-hidden');
            $('.featured_image_id').val(attachment.id);

        });

        // Finally, open the modal
        file_frame.open();
    });

    /*single post generation codes*/

    $(document).on("click", ".aiwa-button.save-single-generation", function (e) {
        e.preventDefault();

        var title = $('.aiwa-single-content-title').val();
        var content = tinymce.get("aiwa-editor").getContent();
        var post_type = $('#aiwa-single-post-type').val();
        var post_status = $('#aiwa-single-post-status').val();
        var featured_image_id = $('.featured_image_id').val();

        var date_picker = $('.aiwa-date-picker').val();
        var time_picker = $('.aiwa-time-picker').val();
        var category = $('#aiwa-single-category').val();

        if (!title.length && post_status !== 'draft'){
            alert('Post title is empty! Please enter a title or save as draft.'); //todo
            return;
        }

        var datas = {
            'action': 'aiwa_save_single_post_generation',
            'rc_nonce': aiwa.nonce,
            'title': title,
            'content': content,
            'post_type': post_type,
            'post_status': post_status,
            'featured_image_id': featured_image_id,
            'date_picker': date_picker,
            'time_picker': time_picker,
            'category': category,
        };

        $.ajax({
            url: aiwa.ajax_url,
            data: datas,
            type: 'post',
            dataType: 'json',

            beforeSend: function () {

            },
            success: function (r) {
                if (r.success) {
                    $('.single-generated-post-saved').fadeIn(100);

                    var seconds = 5;
                    var AutoRefresh = setInterval(function () {
                        seconds -= 1;
                        $('.in-five-seconds').text(seconds);

                        if (seconds==0){
                            clearInterval(AutoRefresh);
                            seconds = 5;
                            setTimeout(function(){
                                $('.in-five-seconds').text('5');
                            }, 1000);

                        }
                    }, 1000);

                    setTimeout(function(){
                        $('#prompt-input').val("");
                        $('#ai-response').hide();
                        $('#title-prompt-text').removeClass('screen-reader-text');
                        $('#title').val("");
                        $('.featured_image_id').val("");
                        $('.aiwa-featured-image-section').hide();

                        if ($('#aiwa-editor').length){
                            $('#aiwa-editor').focus();
                        }

                        if (tinymce.activeEditor) {
                            $('.html-active #aiwa-editor-tmce').click();

                            setTimeout(function(){
                                tinymce.activeEditor.setContent('');
                            }, 500);
                        }

                        $('.single-generated-post-saved').fadeOut(200);
                    }, 5000);

                } else {
                    console.log('Something went wrong, please try again!');
                }

            }, error: function () {

            }
        });
    });

});