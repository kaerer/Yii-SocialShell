<?php
/* @var $socialModule SocialShellModule */
if (!YII_DEBUG && $socialModule->config->ga_code):
    $cs = Yii::app()->getClientScript();
    $cs->registerScript('ga_core', '
        var _gaq = _gaq || [];
        _gaq.push(["_setAccount", '.CJavaScript::encode($socialModule->config->ga_code).']);
        _gaq.push(["_trackPageview"]);

        (function() {
            var ga = document.createElement("script"); ga.type = "text/javascript"; ga.async = true;
            ga.src = ("https:" == document.location.protocol ? "https://ssl" : "http://www") + ".google-analytics.com/ga.js";
            var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ga, s);
        })();
', CClientScript::POS_END);
endif;