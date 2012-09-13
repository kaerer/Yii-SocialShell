<?php

$cs = Yii::app()->getClientScript();
//$cs->registerScriptFile('/js/fb.js');
/* @var $social SocialConfig */
/* @var $cs ClientScript */

$appName = CHtml::encode($social->app_name);
$controller = (YII_DEBUG ? 'debug.php' : '').'/'.Yii::app()->controller->getId();
$page_liked = $social->fb_page_liked ? 'true' : 'false';

$js = <<<EOF
    var domainUrl   = '{$social->domain_url}';

    var canvasUrl   = '{$social->fb_canvas_url}';
    var pageUrl     = '{$social->fb_page_url}';
    var tabUrl      = '{$social->fb_tab_url}';

    var mainUrl     = domainUrl;

    var appName     = '{$appName}';
    var fb_unique_id= '{$social->fb_unique_id}';
    var req_perms   = '{$social->fb_permissions}';
    var shareLink   = '{$social->share_url}';
    var shareImage  = '{$social->share_image}';

    //true; //false;
    var page_liked  = {$page_liked};

    var controller  = '{$controller}';
    var ajax        = null;
    var dialog      = null;
    var process     = null;
    var action      = null;
    var loggedin    = false;  // js api ye göre login olma durumu
    var takenperms  = false;  // login sonrası alınmış izinler listesi
    var response    = false;

    var access_token = false;
    var signed_request = false;
EOF;

//{Yii::app()->request->getQuery('signed_request')}

$cs->registerScript('socialJs', $js, CClientScript::POS_HEAD);

//$scriptMap = array();
//$scriptFileName = 'social.js';

if ($social->facebook_api) {
    $urlScript = Yii::app()->assetManager->publish(Yii::getPathOfAlias('SocialShell').'/js/facebook.js');
    $cs->registerScriptFile($urlScript, CClientScript::POS_HEAD);
//    $scriptMap[Yii::getPathOfAlias('SocialShell').'/js/facebook.js'] = $scriptFileName;
}
if ($social->twitter_api) {
    $urlScript = Yii::app()->assetManager->publish(Yii::getPathOfAlias('SocialShell').'/js/twitter.js');
    $cs->registerScriptFile($urlScript, CClientScript::POS_HEAD);
//    $scriptMap[Yii::getPathOfAlias('SocialShell').'/js/twitter.js'] = $scriptFileName;
}

//$cs->scriptMap = $scriptMap;
?>
