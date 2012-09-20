<?php
$socialModule::start_view($social);

$cs = Yii::app()->clientScript;
/* @var $cs CClientScript */
/* @var $social SocialConfig */
/* @var $socialModule SocialShellModule */

//CVarDumper::dump($_POST, 4, 1);
//CVarDumper::dump($_FILES, 4, 1);
?>

<script type="text/javascript">
    function login_callback(response){
        if(response.status == 'connected') { // ?? response
            console.log(response);
            alert('Login oldu devam');
        } else {
            alert('Katılabilmek için uygulamamıza izin vermelisiniz.');
        }
    }

    $(document).ready(function(){

        $('.share-btn-tweeter').click(function(e){
            e.preventDefault();
            shareLink = shareUrl;
            var text = 'Prezervatif alırken başına gelen en komik olayı paylaşıp 1Koli Durex kazanmak istiyorsan tıkla! #basimanelergeldi ' + shareLink;
            tw_share(text);
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
    <?php // echo CHtml::link('Login', '#', array('id' => 'login')); ?>
    <?php // echo CHtml::link('Email İzni', '#', array('id' => 'perm_email')); ?>
    <?php // echo CHtml::link('İzin Kontrol', '#', array('id' => 'check_login')); ?>
</div>
<div class="share">
    <a class="btn share-btn-tweeter"></a>
</div>
<div class="info">
    <?php
    CVarDumper::dump(array(
        'config: ' => $social,
//        'tw api: ' => $socialModule->obj_twitter->get_user_info(),
        'Debug: ' => $socialModule->debug()), 4, true);
    $socialModule::end_view($social);
    ?>
</div>