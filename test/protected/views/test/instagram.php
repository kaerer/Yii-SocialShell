<?php
$social->start_view();

$cs = Yii::app()->clientScript;
/* @var $cs CClientScript */
/* @var $config SocialConfig */
/* @var $social SocialShellModule */

//CVarDumper::dump($_POST, 4, 1);
//CVarDumper::dump($_FILES, 4, 1);

$r = Yii::app()->request;
//echo $r->getPathInfo();
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
    <?php echo CHtml::link('Login', $social->obj_instagram->get_loginUrl('basic,likes'), array('id' => 'login')); ?>
</div>
<div class="share">
    <a class="btn share-btn-friend"></a>
    <a class="btn share-btn-wall"></a>
</div>
<div class="info">
    <?php
    $tag = 'upskirt';
    CVarDumper::dump(array(
//        'session: ' => Yii::app()->session->toArray(),
//        'config: ' => $config,
//        'config: ' => $social->obj_instagram->getConfig(),
//        'user: ' => $social->obj_instagram->getApi()->getUser(),
//        'tab: ' => $social->config->in_loggedin ? $social->obj_instagram->getApi()->getTag($tag) : '',
//        'tagMedia: ' => $social->config->in_loggedin ? $social->obj_instagram->getApi()->getTagMedia($tag) : '',
//        'userMedias: ' => $social->config->in_loggedin ? $social->obj_instagram->getApi()->getUserMedia() : '',
//        'Media X: ' => $medias = $social->config->in_loggedin ? $social->obj_instagram->getApi()->getMedia('563036563_2146846') : '',
//        'cookies: ' => Yii::app()->request->cookies->toArray(),
        'Debug: ' => $social->debug()), 8, true);
    ?>
</div>
<div class="images">

    <?php
    $tag = 'tanker';
    if ($social->config->in_loggedin) {
        $next_max_id = false;
        $i = 0;
        do {
            if ($next_max_id)
                $social->obj_instagram->getApi()->addParam('max_id', $next_max_id);
            else
                $social->obj_instagram->getApi()->cleanParam();

            $medias = $social->obj_instagram->getApi()->getUserMedia('self', 30);

            foreach ($medias->data as $m) {
                $i++;
                echo '<div style="float: left; margin: 0 5px 5px 0">';
                echo '<img src="'.$m->images->thumbnail->url.'" alt="'.$i.'"/>';
                echo '</div>';
                $tmp = $m;
            }
            CVarDumper::dump($tmp, 5, 1);

            echo '<hr>';
//            CVarDumper::dump($medias->pagination, 5, 1);
            $next_max_id = (isset($medias->pagination) && isset($medias->pagination->next_max_id)) ? $medias->pagination->next_max_id : false;
        } while ($next_max_id && $i < 300);

        $i = 0;
        $medias = $social->obj_instagram->getApi()->getTagMedia($tag, 45);

        $m = array();
        if (isset($medias->data)) {
            foreach ($medias->data as $m) {
                $i++;
                echo '<div style="float: left; margin: 0 5px 5px 0">';
                echo '<img src="'.$m->images->thumbnail->url.'" alt="'.$i.'"/>';
                echo '</div>';
                CVarDumper::dump($m, 5, 1);
//                break;
            }
        } else {
            CVarDumper::dump($medias, 5, 1);
        }
    }
    ?>
</div>
<?php
$social->end_view();