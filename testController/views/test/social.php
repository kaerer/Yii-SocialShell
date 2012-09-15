<?php

$socialModule::start_view($social);

$cs = Yii::app()->clientScript;
/* @var $cs CClientScript */
/* @var $social SocialConfig */
/* @var $socialModule SocialShellModule */

//$cs->registerCoreScript('jquery');
//$cs->registerCoreScript('jquery.ui');
//$cs->registerScriptFile('/js/jquery.placeholder.min.js', CClientScript::POS_HEAD);
//$cs->registerScriptFile('/js/jquery.validate.min.js', CClientScript::POS_HEAD);
//$cs->registerScriptFile('/js/jmask/jquery.maskedinput-1.3.min.js', CClientScript::POS_HEAD);

FacebookShell::set_meta("fb:app_id", $social->fb_app_id);

//CVarDumper::dump($_POST, 4, 1);
//CVarDumper::dump($_FILES, 4, 1);

echo CHtml::link('Login', '#', array('id' => 'login'));
CVarDumper::dump($socialModule->obj_facebook->get_user_info(), 4, 1);
?>

<script type="text/javascript">
    $(document).ready(function(){
        $('#login').click(function(e){
            e.preventDefault();

            fb_login();
        });

        function login_callback(response){
            if(response.status == 'connected') {
                //            FB.api('/me', function(response) {
                //                //console.log(response);
                //                if(!fid) fid = response.id;
                //                $('#firstname').val(response.first_name);
                //                $('#lastname').val(response.last_name);
                //                if(response.email) $('#email').val(response.email);
                //            });
                //            $('#signed_request').val(signed_request);

                alert('Login oldu devam');
            } else {
                alert('Katılabilmek için uygulamamıza izin vermelisiniz.');
            }
        }

    });
</script>

Ugh!

<?php
CVarDumper::dump($social, 4, 1);
CVarDumper::dump($socialModule->debug(), 4, 1);
$socialModule::end_view($social);