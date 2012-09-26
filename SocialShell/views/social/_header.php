<?php

$cs = Yii::app()->getClientScript();
/* @var $socialConfig SocialConfig */
/* @var $cs ClientScript */

$controller = (YII_DEBUG ? 'debug.php/' : '/').Yii::app()->controller->getId();
//TODO burası facebook.js nin obje olarak hazırlanacak yenı haline set edilecek...

//$js = <<<EOF
?>
<script type="text/javascript">
    var domainUrl   = '<?php echo $socialConfig->domain_url; ?>';

    var canvasUrl   = '<?php echo $socialConfig->fb_canvas_url; ?>';
    var pageUrl     = '<?php echo $socialConfig->fb_page_url; ?>';
    var tabUrl      = '<?php echo $socialConfig->fb_tab_url; ?>';

    var mainUrl     = domainUrl;

    var appName     = '<?php echo CHtml::encode($socialConfig->app_name); ?>';
    var fb_unique_id= '<?php echo $socialConfig->fb_unique_id; ?>';
    var req_perms   = '<?php echo $socialConfig->fb_permissions; ?>';
    var shareUrl    = '<?php echo $socialConfig->share_url; ?>';
    var shareImage  = '<?php echo $socialConfig->share_image; ?>';

    //true; //false;
    var page_liked  = <?php echo $socialConfig->fb_page_liked ? 'true' : 'false'; ?>;

    var controller  = '<?php echo $controller; ?>';
    var ajax        = null;
    var dialog      = null;
    var process     = null;
    var action      = null;
    var loggedin    = false;  // js api ye göre login olma durumu
    var takenperms  = false;  // login sonrası alınmış izinler listesi
    var response    = false;

    var access_token = false;
    var signed_request = false;
</script>
<?php
//EOF;
//$cs->registerScript('socialJs', $js, CClientScript::POS_HEAD);


//{Yii::app()->request->getQuery('signed_request')}
//$cs->registerScript('socialJs', $js, CClientScript::POS_HEAD);
//$scriptMap = array();
//$scriptFileName = 'social.js';

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

//$cs->scriptMap = $scriptMap;
?>
