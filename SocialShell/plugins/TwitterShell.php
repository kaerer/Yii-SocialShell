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

    private $api_object;
    public $access_token;

    public function &getApi() {
        return $this->api_object;
    }

    public function setApi(&$api_object) {
        $this->api_object = & $api_object;
    }

    public function start_api() {
        Yii::import('SocialShell.vendors.twitter.tmhOAuth');
        Yii::import('SocialShell.vendors.twitter.tmhUtilities');

//        $this->api_object = new Facebook(array(
//                    'appId' => $this->config->fb_app_id,
//                    'secret' => $this->config->fb_app_secret,
//                    'fileUpload' => true,
//                    'cookie' => true,
//                ));

        return $this->getApi();
    }

}

