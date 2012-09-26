<?php

/**
 * SocialShell Yii Module
 */
Yii::Import('SocialShell.models.*');
Yii::Import('SocialShell.components.*');
Yii::Import('SocialShell.plugins.*');

/**
 * SocialShellModule
 *
 * @author Erce Erözbek <erce.erozbek@gmail.com>
 *
 * @property FacebookShell $obj_facebook FacebookShell instance
 * @property TwitterShell $obj_twitter TwitterShell instance
 * @property InstagramShell $obj_instagram InstagramShell instance
 *
 * @property array $user_info Facebook user data
 */
class SocialShellModule extends AbstractShell /* CWebModule */ {

    const VERSION = 0.4;

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
        $this->setImport(array(
            'SocialShell.models.*',
            'SocialShell.components.*',
            'SocialShell.plugins.*',
        ));
    }

    public function start_api() {
        if ($this->config->facebook_api) {
            $this->obj_facebook = new FacebookShell();
            $this->obj_facebook->setConfig($this->config);
            $this->obj_facebook->start_api();
        }
    }

    public static function start_view(SocialConfig $config) {
        return Yii::app()->controller->renderPartial('SocialShell.views.social._header', array('socialConfig' => $config));
    }

    public static function end_view(SocialConfig $config) {
        return Yii::app()->controller->renderPartial('SocialShell.views.social._footer', array('socialConfig' => $config));
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