<?php
/* @var $social SocialConfig */
$this->renderPartial('SocialShell.views.social._api', array('social' => $social));
$this->renderPartial('SocialShell.views.analytics._ga', array('social' => $social));
?>
