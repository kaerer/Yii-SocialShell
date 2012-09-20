<?php
$socialModule::start_view($social);

$cs = Yii::app()->clientScript;
/* @var $cs CClientScript */
/* @var $social SocialConfig */
/* @var $socialModule SocialShellModule */

FacebookShell::set_meta("fb:app_id", $social->fb_app_id);

//CVarDumper::dump($_POST, 4, 1);
//CVarDumper::dump($_FILES, 4, 1);
?>

<script type="text/javascript">
    function login_callback(response){
        if(response.status == 'connected') {
            fb_unique_id = response.userID;
            console.log(response);
            //            FB.api('/me', function(response) {
            //                if(!fid) fid = response.id;
            //                $('#firstname').val(response.first_name);
            //                $('#lastname').val(response.last_name);
            //                if(response.email) $('#email').val(response.email);
            //            });
            //

            //Form post anlarında sayfadaki signed_request kaybolmasın diye, form içine hidden gömüp izin anında set etmek bazen hayat kurtarıyor
            $('#signed_request').val(signed_request);

            alert('Login oldu devam');
        } else {
            alert('Katılabilmek için uygulamamıza izin vermelisiniz.');
        }
    }

    $(document).ready(function(){
        $('#login').click(function(e){
            e.preventDefault();

            fb_login();
        });

        $('#perm_email').click(function(e){
            e.preventDefault();

            fb_login('email');
        });

        $('#check_login').click(function(e){
            e.preventDefault();

            check_login();
        });

        $('.share-btn-friend').click(function(e){
            e.preventDefault();
            var text = 'Ben “prezervatif alırken başıma gelen en komik olay...” cümlesini tamamlayan hikayemi yazdım, katıldım. Sen de katıl, seçilen en komik olay seninki olsun, 1 Koli Durex kazan!';
            fb_notification(text);
        });

        $('.share-btn-wall').click(function(e){
            e.preventDefault();
            var link_text = 'Ben prezervatif alırken başıma gelen en komik olay..';
            var description = 'Prezervatif alırken başıma gelen en komik olayı yazıp, paylaştım. Sen de 1 Koli Durex kazanmak istiyorsan, hemen katıl.';
            fb_feed(link_text, 'Durex Türkiye', description, shareImage, shareLink);
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
    <br>
    <?php echo CHtml::link('Email İzni', '#', array('id' => 'perm_email')); ?>

    <?php echo CHtml::link('İzin Kontrol', '#', array('id' => 'check_login')); ?>
</div>
<div class="share">
    <a class="btn share-btn-friend"></a>
    <a class="btn share-btn-wall"></a>
</div>
<div class="info">
    <?php
    CVarDumper::dump(array(
        'config: ' => $social,
        'fb api: ' => $socialModule->obj_facebook->get_user_info(),
        'Debug: ' => $socialModule->debug()), 4, true);
    $socialModule::end_view($social);
    ?>
</div>