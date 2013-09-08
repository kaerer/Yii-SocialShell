<?php
$cs = Yii::app()->getClientScript();
//$r = Yii::app()->request;
/* @var $cs ClientScript */
/* @var $r CHttpRequest */
/* @var $socialModule SocialShellModule */

//TODO:: controller a debug parametreli index gösterilmeli
$scriptUrl = Yii::app()->request->scriptUrl;
$script_file = end(explode('/', $scriptUrl));
$controller = '/' . (YII_DEBUG ? $script_file . '/' : '') . Yii::app()->controller->getId();
/*
var fb_loggedin = <?php echo $socialModule->config->fb_loggedin ? 'true' : 'false'; ?>;
$controller = '/'.Yii::app()->controller->getId();
*/

$cs->registerScript('socialshell_core', '
    /**
     * SocialShell
     * Social Media Api Collections
     *
     * @author  Erce Erözbek erce.erozbek@gmail.com
     *
     * admiting its globally v.a0.1 in general
     * there are lots of things & thinks to do.
     */
    var domainUrl = ' . CJavaScript::encode($socialModule->config->domain_url) . '
    var controller = domainUrl + ' . CJavaScript::encode($controller) . '
    var appName = ' . CJavaScript::encode($socialModule->config->app_name) . '

    var shareUrl = ' . CJavaScript::encode($socialModule->config->share_url) . '
    var shareImage = ' . CJavaScript::encode($socialModule->config->share_image) . '

    var process = false;
    ', CClientScript::POS_HEAD);

if ($socialModule->config->facebook_api) {
$cs->registerScript('socialshell_facebook_js', '
    var fb_pageUrl = ' . CJavaScript::encode($socialModule->obj_facebook->get_pageUrl()) . '
    var fb_tabUrl = ' . CJavaScript::encode($socialModule->obj_facebook->get_tabUrl()) . '

    var fb_page_id = ' . CJavaScript::encode($socialModule->config->fb_page_id) . '
    var fb_page_liked = ' . CJavaScript::encode($socialModule->config->fb_page_liked) . '
    var fb_page_signed_request = ' . CJavaScript::encode(Yii::app()->request->getParam('signed_request')) . '
    var fb_loggedin = false;
    var fb_unique_id = ' . CJavaScript::encode($socialModule->config->fb_unique_id) . '
    var fb_signed_request = ' . CJavaScript::encode(Yii::app()->request->getParam('signed_request')) . '
    var fb_permissions = ' . CJavaScript::encode($socialModule->config->fb_permissions) . '
    var fb_user_profile = false;
    var fb_takenperms = false;
    var fb_disable_track = ' . CJavaScript::encode($socialModule->config->fb_disable_track) . '
    var fb_access_token = false;
        ', CClientScript::POS_HEAD);
}

if ($socialModule->config->twitter_api) {
    $cs->registerScript('socialshell_twitter_js', '
    //var tw_permissions = ' . CJavaScript::encode($socialModule->config->tw_permissions) . ';
    var tw_loggedin = false;
        ', CClientScript::POS_HEAD);
}

if ($socialModule->config->instagram_api) {
    $cs->registerScript('socialshell_instagram_js', '
    var in_login_url = ' . CJavaScript::encode($socialModule->obj_instagram->get_loginUrl()) . '
    var in_permissions = ' . CJavaScript::encode($socialModule->config->in_permissions) . '
    var in_loggedin = ' . CJavaScript::encode($socialModule->config->in_loggedin) . '
    var in_user_profile = false;
    var in_unique_id = ' . CJavaScript::encode($socialModule->config->in_unique_id) . '
        ', CClientScript::POS_HEAD);
}

$urlScript = Yii::app()->assetManager->publish(Yii::getPathOfAlias('SocialShell') . '/js/social_module.js');
$cs->registerScriptFile($urlScript, CClientScript::POS_HEAD);