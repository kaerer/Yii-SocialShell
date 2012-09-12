<?php
$socialModule::start_view($social);
$cs = Yii::app()->clientScript;
/* @var $cs CClientScript */
/* @var $social SocialConfig */

$cs->registerCssFile('/css/fontface/MyriadWebPro.css');

$cs->registerCoreScript('jquery');
//$cs->registerCoreScript('jquery.ui');
//$cs->registerScriptFile('/js/jquery.placeholder.min.js', CClientScript::POS_HEAD);
//$cs->registerScriptFile('/js/jquery.validate.min.js', CClientScript::POS_HEAD);
//$cs->registerScriptFile('/js/jmask/jquery.maskedinput-1.3.min.js', CClientScript::POS_HEAD);

FacebookShell::set_meta("fb:app_id", $social->fb_app_id);
?>
<script type="text/javascript">
    $(document).ready(function() {
    });
</script>
<style type="text/css">
</style>
<div id="wrapper">
    <div id="wrapper_in">
        <?php
        CVarDumper::dump($social, 5, 1);
        ?>
    </div>
</div>
<?php
$socialModule::end_view($social);