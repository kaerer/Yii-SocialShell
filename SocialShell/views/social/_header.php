<?php
$cs = Yii::app()->getClientScript();
/* @var $socialConfig SocialConfig */
/* @var $cs ClientScript */

$controller = (YII_DEBUG ? '/debug.php/' : '/').Yii::app()->controller->getId();
//TODO burası facebook.js nin obje olarak hazırlanacak yenı haline set edilecek...
//$js = <<<EOF
?>
<script type="text/javascript">
    var domainUrl   = '<?php echo $socialConfig->domain_url; ?>';
    var appName     = '<?php echo CHtml::encode($socialConfig->app_name); ?>';
    var controller  = '<?php echo $controller; ?>';

    var shareUrl    = '<?php echo $socialConfig->share_url; ?>';
    var shareImage  = '<?php echo $socialConfig->share_image; ?>';

<?php if ($socialConfig->facebook_api): ?>
        var fb_pageUrl      = '<?php echo $socialConfig->fb_page_url; ?>';
        var fb_tabUrl       = '<?php echo $socialConfig->fb_tab_url; ?>';
        var fb_page_liked   = <?php echo $socialConfig->fb_page_liked ? 'true' : 'false'; ?>;
        var fb_permissions  = '<?php echo $socialConfig->fb_permissions; ?>';
        var fb_takenperms   = false;
        var fb_loggedin     = false;
        var fb_signed_request = false;
<?php endif; ?>

<?php if ($socialConfig->twitter_api): ?>
        var tw_permissions  = '<?php echo $socialConfig->tw_permissions; ?>';
        var tw_loggedin     = false;
<?php endif; ?>

<?php if ($socialConfig->instagram_api): ?>
        var in_permissions  = '<?php echo $socialConfig->in_permissions; ?>';
        var in_loggedin     = false;
<?php endif; ?>

    var process         = false;
</script>
<?php
//EOF;
//$cs->registerScript('socialJs', $js, CClientScript::POS_HEAD);
//{Yii::app()->request->getQuery('signed_request')}
//$cs->registerScript('socialJs', $js, CClientScript::POS_HEAD);
//$scriptMap = array();
//$scriptFileName = 'social.js';

$urlScript = Yii::app()->assetManager->publish(Yii::getPathOfAlias('SocialShell').'/js/basic.js');
$cs->registerScriptFile($urlScript, CClientScript::POS_HEAD);
//    $scriptMap[Yii::getPathOfAlias('SocialShell').'/js/basic.js'] = $scriptFileName;

if ($socialConfig->facebook_api) {
    $urlScript = Yii::app()->assetManager->publish(Yii::getPathOfAlias('SocialShell').'/js/facebook.js');
    $cs->registerScriptFile($urlScript, CClientScript::POS_HEAD);
//    $scriptMap[Yii::getPathOfAlias('SocialShell').'/js/facebook.js'] = $scriptFileName;
}

if ($socialConfig->twitter_api) {
    $urlScript = Yii::app()->assetManager->publish(Yii::getPathOfAlias('SocialShell').'/js/twitter.js');
    $cs->registerScriptFile($urlScript, CClientScript::POS_HEAD);
//    $scriptMap[Yii::getPathOfAlias('SocialShell').'/js/twitter.js'] = $scriptFileName;
}

if ($socialConfig->instagram_api) {
    $urlScript = Yii::app()->assetManager->publish(Yii::getPathOfAlias('SocialShell').'/js/instagram.js');
    $cs->registerScriptFile($urlScript, CClientScript::POS_HEAD);
//    $scriptMap[Yii::getPathOfAlias('SocialShell').'/js/twitter.js'] = $scriptFileName;
}

//$cs->scriptMap = $scriptMap;
?>
