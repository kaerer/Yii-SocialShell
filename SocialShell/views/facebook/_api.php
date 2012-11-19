<?php /* @var $socialModule SocialShellModule */ ?>
<?php if ($socialModule->config->facebook_api): ?>
    <!-- Facebook api -->
    <div id="fb-root"></div>
    <script type="text/javascript">
        window.fbAsyncInit = function() {
            FB.init({
                appId      : '<?= $socialModule->config->fb_app_id ?>', // App ID
                status     : true, // check login status
                cookie     : true, // enable cookies to allow the server to access the session
                xfbml      : true  // parse XFBML
            });

            FB.Canvas.setAutoGrow(500);

            //catch like event
            FB.Event.subscribe('edge.create', function(response) {
                //like butona tıklanma anı
                fb_like_callback(response);
            });

            //catch unlike event
            FB.Event.subscribe('edge.remove', function(response) {
                fb_unlike_callback(response);
            });

            //catch login and give permission event
//            FB.Event.subscribe('auth.login', function(response) {
//                fb_login_callback();
//            });

            //catch logout event
            FB.Event.subscribe('auth.logout', function(response) {
                fb_logout_callback(response);
            });

            FB.getLoginStatus(function(response) {
                response_global = response;
                if(fb_unique_id && response.status === 'connected') {
                    fb_loggedin = true;
                    fb_unique_id = response.authResponse.userID;
//                    access_token = response.authResponse.accessToken;
//                    signed_request = response.authResponse.signedRequest;
                    fb_loginalready_callback();

                }
                //response.authResponse;
            }, true);

            // Additional initialization code here
        };

        // Load the SDK Asynchronously
        (function(d){
            var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
            if (d.getElementById(id)) {return;}
            js = d.createElement('script'); js.id = id; js.async = true;
            js.src = "//connect.facebook.net/<?= $socialModule->config->locale ?>/all.js";
            ref.parentNode.insertBefore(js, ref);
        }(document));
    </script>
<?php endif; ?>