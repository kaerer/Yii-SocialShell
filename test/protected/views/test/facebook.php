<?php
/* @var $this TestController */
/* @var $social SocialShellModule */
/* @var $config SocialConfig */

$social->start_view();
$cs = Yii::app()->clientScript;

/* @var $cs CClientScript */

FacebookShell::set_meta("fb:app_id", $config->fb_app_id);

//CVarDumper::dump($_POST, 4, 1);
//CVarDumper::dump($_FILES, 4, 1);
?>

    <script type="text/javascript">
        function login_callback() {
            FB.api('/me', function (response) {

                console.log(response);

                if (!fb_unique_id)
                    fb_unique_id = response.id;

                var ul = $('<ul>');
                $.each(response, function(i,v){
                    var li = $('<li>').html(i + ': ' + response[i]);
                    ul.append(li);
                })
                $('.info.js').find('.content').html(ul);
            });
        }

        $(document).ready(function () {
            $('#login').click(function (e) {
                e.preventDefault();

                fb_login(fb_permissions, login_callback);
            });

            $('#perm_email').click(function (e) {
                e.preventDefault();

                fb_login('email', login_callback);
            });

            $('#check_login').click(function (e) {
                e.preventDefault();

                fb_check_login(function(){
                    if(confirm('Değişkenler yüklensin mi?')) login_callback();
                }, function(){
                    alert('Login olunmadı');
                });

            });

            $('.share-btn-friend').click(function (e) {
                e.preventDefault();
                var text = 'ActionText';
                fb_notification(text);
            });

            $('.share-btn-wall').click(function (e) {
                e.preventDefault();
                var link_text = 'Link Text';
                var description = 'Long Text';
                fb_feed(link_text, <?php echo CJavaScript::encode($this->socialConfig->fb_page_name) ?>, description, shareImage, shareLink);
            });


        });
    </script>
    <style type="text/css">
        .info {
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
    <div class="info js">
        <h2>Js Api Result</h2>

        <div class="content">

        </div>
    </div>
    <div class="info php">
        <h2>PHP Api Result</h2>

        <div class="content">
            <?php
            CVarDumper::dump(array(
                'session: ' => Yii::app()->session->toArray(),
                'config: ' => $social->obj_facebook->getConfig(),
//        'config: ' => $config,
                'fb user info: ' => $social->obj_facebook->get_user_info(),
                'Debug: ' => $social->debug()), 4, true);
            ?>
        </div>
    </div>
<?php
$social->end_view();