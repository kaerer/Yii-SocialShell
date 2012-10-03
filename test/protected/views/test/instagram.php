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
    function login_callback(response){
        if(true) {
//            fb_unique_id = response.userID;
//            console.log(response);

            alert('Login oldu devam');
        } else {
            alert('Katılabilmek için uygulamamıza izin vermelisiniz.');
        }
    }

    $(document).ready(function(){
        $('#login').click(function(e){
            e.preventDefault();

            in_login();
        });

        $('#check_login').click(function(e){
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
    <?php echo CHtml::link('Login', '#', array('id' => 'login')); ?>
</div>
<div class="share">
    <a class="btn share-btn-friend"></a>
    <a class="btn share-btn-wall"></a>
</div>
<div class="info">
    <?php
    CVarDumper::dump(array(
        'session: ' => Yii::app()->session->toArray(),
//        'config: ' => $config,
        'config: ' => $social->obj_instagram->getConfig(),
//        'api: ' => $social->obj_instagram,
        'Debug: ' => $social->debug()), 4, true);
    $social::end_view($config);
    ?>
</div>