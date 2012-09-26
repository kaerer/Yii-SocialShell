<?php
$social::start_view($config);

$cs = Yii::app()->clientScript;
/* @var $cs CClientScript */
/* @var $config SocialConfig */
/* @var $social SocialShellModule */

//CVarDumper::dump($_POST, 4, 1);
//CVarDumper::dump($_FILES, 4, 1);
?>

<script type="text/javascript">
    $(document).ready(function(){});
</script>
<style type="text/css"></style>
<div class="personal_msg">
    Ugh!
</div>
<div class="link">
</div>
<div class="share">
    TODO: Bu butonlara basımı takip ettir.
    <a class="btn share-btn-friend"></a>
    <a class="btn share-btn-wall"></a>
</div>
<div class="info">
    <?php
    CVarDumper::dump(array(
        'config: ' => $config,
        'Debug: ' => $social->debug()), 4, true);
    $social::end_view($config);
    ?>
</div>