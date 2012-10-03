<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml" xmlns:og="http://opengraphprotocol.org/schema/" xml:lang="tr" lang="tr">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="tr" />

        <?php
        $cs = Yii::app()->clientScript;
        $cs->registerCoreScript('jquery');
//        $cs->registerCoreScript('jquery.ui');
//        $cs->registerCssFile(Yii::app()->clientScript->getCoreScriptUrl().'/jui/css/base/jquery-ui.css');
//        $cs->registerScriptFile('/js/jmask/jquery.maskedinput-1.3.min.js');
//        $cs->registerScriptFile('/js/jquery.validate.min.js');
//        $cs->registerScriptFile('/js/jquery.blockUI.js');
        ?>
        <!-- blueprint CSS framework -->
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/blueprint.css" media="screen, projection" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />

        <!--[if lt IE 8]>
            <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
        <![endif]-->

        <link rel="shortcut icon" href="/favicon.ico" />
        <link rel="shortcut icon" href="/favicon.gif" />
        <link rel="icon" href="/favicon.gif" type="image/gif" />

        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>

    <body>
        <!-- Page -->
        <div class="container" id="page">
            <?php echo $content; ?>
        </div>
    </body>
</html>