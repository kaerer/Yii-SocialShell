<?php

/**
 * SocialShell Yii Module
 */
class SocialShellModule extends AbstractShell /* CWebModule */ {

    const VERSION = 0.1;

    /**
     * Facebook Api Object
     * @var FacebookShell
     */
    public $obj_facebook;

    /**
     * Twitter Api Object
     * @var TwitterShell
     */
    public $obj_twitter;

    /**
     * Instagram Api Object
     * @var InstagramShell
     */
    public $obj_instagram;

    public function init() {
        Yii::Import('SocialShell.models.*');
        Yii::Import('SocialShell.components.*');
        Yii::Import('SocialShell.plugins.*');
    }

    public function start_api() {
        if ($this->config->facebook_api) {
            $this->obj_facebook = new FacebookShell();
            $this->obj_facebook->setConfig($this->config);
            $this->obj_facebook->start_api();
        }
    }

    public static function start_view($config) {
        return Yii::app()->controller->renderPartial('SocialShell.views.social._header', array('social' => $config));
    }

    public static function end_view($config) {
        return Yii::app()->controller->renderPartial('SocialShell.views.social._footer', array('social' => $config));
    }

}

/*
    public function init() {
        $this->setImport(array(
            'SocialShell.models.*',
            'SocialShell.components.*',
            'SocialShell.plugins.*',
        ));
    }

*/