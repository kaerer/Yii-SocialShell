<?php

/**
 * Description of Twitter Shell Plugin
 *
 * @author Erce Erözbek <erce.erozbek@gmail.com>
 *
 * @property object $api_object Twitter SDK Object
 * @property string $access_token Twitter SDK access token
 *
 * @property array $user_info Twitter user data
 */
class TwitterShell extends AbstractPlugin {

    const VERSION = 0.0;

    public $access_token;

    public function &getApi() {
        return $this->api_object;
    }

    public function setApi(&$api_object) {
        $this->api_object = & $api_object;
    }

    public function start_api($silent_mode = false) {
//        Yii::import('SocialShell.vendors.twitter.tmhOAuth');
//        Yii::import('SocialShell.vendors.twitter.tmhUtilities');

//        $this->api_object = new Facebook(array(
//                    'appId' => $this->config->fb_app_id,
//                    'secret' => $this->config->fb_app_secret,
//                    'fileUpload' => true,
//                    'cookie' => true,
//                ));

        $urlScript = Yii::app()->assetManager->publish(Yii::getPathOfAlias('SocialShell').'/js/twitter.js');
        $cs = Yii::app()->getClientScript();
        $cs->registerScriptFile($urlScript, CClientScript::POS_HEAD);

        return $this->getApi();
    }

}