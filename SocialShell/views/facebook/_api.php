<div id="fb-root"></div>
<?php
/* @var $this FacebookController */
/* @var $socialModule SocialShellModule */
/* @var $socialConfig SocialConfig */

if ($socialModule->config->facebook_api):
    $cs = Yii::app()->getClientScript();
    $cs->registerScript('facebookshell_core', '
    <!-- Facebook api -->
        window.fbAsyncInit = function () {
            FB.init({
                ' . ($socialModule->config->fb_app_id ? 'appId: ' . $socialModule->config->fb_app_id . ',' : '') . '
                status: true, // check login status
                cookie: true, // enable cookies to allow the server to access the session
                xfbml: true,  // parse XFBML
                channelUrl: window.location.protocol + "//" + window.location.host + "/channel.html",
            });

            FB.Canvas.setAutoGrow(true);

            //catch like event
            FB.Event.subscribe("edge.create", function (response) {
                //like butona tıklanma anı
                fb_like_callback(response);
            });

            //catch unlike event
            FB.Event.subscribe("edge.remove", function (response) {
                fb_unlike_callback(response);
            });

            //catch login and give permission event
//            FB.Event.subscribe("auth.login", function(response) {
//                fb_login_callback(response, function(){}, function(){}, true);
//            });

            //catch logout event
//            FB.Event.subscribe("auth.logout", function(response) {
//                fb_logout_callback(response);
//            });

            FB.getLoginStatus(function (response) {
                fb_loginalready_callback(response);
            }, true);
        };


        (function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/' . $socialModule->config->locale . '/all.js#xfbml=1&' . ($socialModule->config->fb_app_id ? 'appId=' . $socialModule->config->fb_app_id . '' : '') . '";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, "script", "facebook-jssdk"));


', CClientScript::POS_END);
endif;