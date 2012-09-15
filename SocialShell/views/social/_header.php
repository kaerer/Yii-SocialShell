<?php

$cs = Yii::app()->getClientScript();
/* @var $social SocialConfig */
/* @var $cs ClientScript */

$appName = CHtml::encode($social->app_name);
$controller = (YII_DEBUG ? 'debug.php' : '').'/'.Yii::app()->controller->getId();
$page_liked = $social->fb_page_liked ? 'true' : 'false';


//TODO burası facebook.js nin obje olarak hazırlanacak yenı haline set edilecek...

//$js = <<<EOF
?>
<script type="text/javascript">
    var domainUrl   = '<?php echo $social->domain_url; ?>';

    var canvasUrl   = '<?php echo $social->fb_canvas_url; ?>';
    var pageUrl     = '<?php echo $social->fb_page_url; ?>';
    var tabUrl      = '<?php echo $social->fb_tab_url; ?>';

    var mainUrl     = domainUrl;

    var appName     = '<?php echo $appName; ?>';
    var fb_unique_id= '<?php echo $social->fb_unique_id; ?>';
    var req_perms   = '<?php echo $social->fb_permissions; ?>';
    var shareUrl    = '<?php echo $social->share_url; ?>';
    var shareImage  = '<?php echo $social->share_image; ?>';

    //true; //false;
    var page_liked  = <?php echo $page_liked; ?>;

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
