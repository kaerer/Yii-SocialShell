<?php
/* @var $socialConfig SocialConfig */
$this->renderPartial('SocialShell.views.social._api', array('socialModule' => $socialModule));
$this->renderPartial('SocialShell.views.analytics._ga', array('socialModule' => $socialModule));
?>
