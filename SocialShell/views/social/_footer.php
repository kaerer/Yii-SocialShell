<?php
/* @var $socialConfig SocialConfig */
$this->renderPartial('SocialShell.views.social._api', array('socialConfig' => $socialConfig));
$this->renderPartial('SocialShell.views.analytics._ga', array('socialConfig' => $socialConfig));
?>
