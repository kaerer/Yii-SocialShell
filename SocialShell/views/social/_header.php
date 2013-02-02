<?php
$cs = Yii::app()->getClientScript();
//$r = Yii::app()->request;
/* @var $cs ClientScript */
/* @var $r CHttpRequest */
/* @var $socialModule SocialShellModule */

$controller = (YII_DEBUG ? '/debug.php/' : '/').Yii::app()->controller->getId();
?>
<script type="text/javascript">
    var domainUrl = '<?php echo $socialModule->config->domain_url; ?>';
    var appName = '<?php echo CHtml::encode($socialModule->config->app_name); ?>';
    var controller = domainUrl + '<?php echo $controller; ?>';

    var shareUrl = '<?php echo $socialModule->config->share_url; ?>';
    var shareImage = '<?php echo $socialModule->config->share_image; ?>';

<?php if ($socialModule->config->facebook_api): ?>
        var fb_pageUrl = '<?php echo $socialModule->obj_facebook->get_pageUrl(); ?>';
        var fb_tabUrl = '<?php echo $socialModule->obj_facebook->get_tabUrl(); ?>';

        var fb_page_id = '<?php echo $socialModule->config->fb_page_id; ?>';
        var fb_page_liked = <?php echo $socialModule->config->fb_page_liked ? 'true' : 'false'; ?>;
        var fb_page_signed_request = '<?php echo Yii::app()->request->getParam('signed_request')?>';
        //var fb_loggedin = <?php echo $socialModule->config->fb_loggedin ? 'true' : 'false'; ?>;
        var fb_loggedin = false;
        var fb_unique_id = '<?php echo $socialModule->config->fb_unique_id; ?>';
        var fb_signed_request = '<?php echo Yii::app()->request->getParam('signed_request')?>';
        var fb_permissions = '<?php echo $socialModule->config->fb_permissions; ?>';
        var fb_user_profile = false;
        var fb_takenperms = false;
        var fb_access_token = false;
<?php endif; ?>

<?php if ($socialModule->config->twitter_api): ?>
//        var tw_permissions = '<?php // echo $socialModule->config->tw_permissions; ?>';
        var tw_loggedin = false;
<?php endif; ?>

<?php if ($socialModule->config->instagram_api): ?>
        var in_login_url = '<?php echo $socialModule->obj_instagram->get_loginUrl(); ?>';
        var in_permissions = '<?php echo $socialModule->config->in_permissions; ?>';
        var in_loggedin = <?php echo $socialModule->config->in_loggedin ? 'true' : 'false'; ?>;
        var in_user_profile = false;
        var in_unique_id = '<?php echo $socialModule->config->in_unique_id; ?>';
<?php endif; ?>

    var process = false;
</script>
<?php
$urlScript = Yii::app()->assetManager->publish(Yii::getPathOfAlias('SocialShell').'/js/social_module.js');
$cs->registerScriptFile($urlScript, CClientScript::POS_HEAD);
