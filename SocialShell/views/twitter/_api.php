<?php
/* @var $socialModule SocialShellModule */
if ($socialModule->config->twitter_api) {

    $cs = Yii::app()->getClientScript();
    $cs->registerScript('twittershell_core', '
    <!-- Twitter Api -->
    !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
', CClientScript::POS_END);

}
