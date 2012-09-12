<?php
/* @var $social SocialConfig */
if ($social->facebook_api) {
    $this->renderPartial('SocialShell.views.facebook._api', array('social' => $social));
}
if ($social->twitter_api){
    $this->renderPartial('SocialShell.views.twitter._api', array('social' => $social));
}
