<?php /* @var $socialModule SocialShellModule */ ?>
<?php if ($socialModule->config->twitter_api): ?>
    <!-- Twitter Api -->
    <script>
            !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
    </script>
<?php endif; ?>
