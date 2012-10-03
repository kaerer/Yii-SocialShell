<?php
/* @var $socialConfig SocialConfig */
if ($socialConfig->facebook_api) {
    $this->renderPartial('SocialShell.views.facebook._api', array('socialConfig' => $socialConfig));
}

if ($socialConfig->twitter_api){
    $this->renderPartial('SocialShell.views.twitter._api', array('socialConfig' => $socialConfig));
}

if ($socialConfig->instagram_api){
    $this->renderPartial('SocialShell.views.instagram._api', array('socialConfig' => $socialConfig));
}
