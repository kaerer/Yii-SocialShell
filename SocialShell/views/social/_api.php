<?php
/* @var $socialModule SocialShellModule */
if ($socialModule->config->facebook_api) {
    $this->renderPartial('SocialShell.views.facebook._api', array('socialModule' => $socialModule));
}

if ($socialModule->config->twitter_api){
    $this->renderPartial('SocialShell.views.twitter._api', array('socialModule' => $socialModule));
}

if ($socialModule->config->instagram_api){
    $this->renderPartial('SocialShell.views.instagram._api', array('socialModule' => $socialModule));
}
