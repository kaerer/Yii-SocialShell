<?php
$pid = $products['pid'];
$title = $products['title'];
$text = $products['text'];
$url = $products['url'];

$img_url = $products['img_url'];
$redirect_url = $products['redirect'];

?>
<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:og="http://ogp.me/ns#"
      xmlns:fb="http://www.facebook.com/2008/fbml">
    <head>
        <title><?= $title ?></title>
        <meta property="og:title" content="<?= $title ?>"/>
        <meta property="og:url" content="<?= $url ?>"/>
        <meta property="og:image" content="<?= $img_url ?>"/>
        <meta property="og:site_name" content="<?= $social['app_name'] ?>"/>
        <meta property="og:type" content="website" />
        <meta property="og:description" content="<?= $text ?>"/>
    </head>
    <body>
        <script type="text/javascript">
            window.location = "<?php echo $redirect_url; ?>&id=<?php echo $pid ?>";
        </script>
        <img src="<?= $img_url ?>" alt="<?= $title ?>" />
    </body>
</html>