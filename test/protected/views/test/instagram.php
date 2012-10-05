<?php
$social->start_view();

$cs = Yii::app()->clientScript;
/* @var $cs CClientScript */
/* @var $config SocialConfig */
/* @var $social SocialShellModule */

//CVarDumper::dump($_POST, 4, 1);
//CVarDumper::dump($_FILES, 4, 1);
?>

<script type="text/javascript">
    $(document).ready(function() {
        $('#login').click(function(e) {
            e.preventDefault();

            in_login();
        });

        $('#check_login').click(function(e) {
            e.preventDefault();

            check_login();
        });

    });
</script>
<style type="text/css">
    .info{
        overflow: hidden;
    }
</style>
<div class="personal_msg">
    Ugh!
</div>
<div class="link">
    <?php echo CHtml::link('Login', $social->obj_instagram->get_loginUrl(), array('id' => 'login')); ?>
</div>
<div class="share">
    <a class="btn share-btn-friend"></a>
    <a class="btn share-btn-wall"></a>
</div>
<div class="info">
    <?php
    $tag = 'okul';
    CVarDumper::dump(array(
        'session: ' => Yii::app()->session->toArray(),
//        'config: ' => $config,
        'config: ' => $social->obj_instagram->getConfig(),
        'user: ' => $social->obj_instagram->getApi()->getUser(),
//        'tab: ' => $social->config->in_loggedin ? $social->obj_instagram->getApi()->getTag($tag) : '',
//        'tagMedia: ' => $social->config->in_loggedin ? $social->obj_instagram->getApi()->getTagMedia($tag) : '',
        'userMedias: ' => $social->config->in_loggedin ? $social->obj_instagram->getApi()->getUserMedia() : '',
//        'Media X: ' => $medias = $social->config->in_loggedin ? $social->obj_instagram->getApi()->getMedia('563036563_2146846') : '',
//        'cookies: ' => Yii::app()->request->cookies->toArray(),
        'Debug: ' => $social->debug()), 8, true);
    ?>
</div>
<div class="images">
    <?php
    if ($social->config->in_loggedin) {
        $medias = $social->obj_instagram->getApi()->getUserMedia();
        foreach ($medias->data as $m) {
            echo '<div style="float: left; margin: 0 5px 5px 0">';
            echo '<img src="'.$m->images->thumbnail->url.'"/>';
            echo '</div>';
        }
    }
    ?>
</div>
<?php
$social->end_view();