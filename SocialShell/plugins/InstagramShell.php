<?php

/**
 * Description of Instagram Shell Plugin
 *
 * @author Erce Erözbek <erce.erozbek@gmail.com>
 *
 * @property object $api_object Instagram SDK Object
 * @property string $access_token Instagram SDK access token
 *
 * @property array $user_info Instagram user data
 */
class InstagramShell extends AbstractPlugin {

    const VERSION = 0.1;

    private $api_object;
    private $access_token;
    private $user_info;

    public function &getApi() {
        return $this->api_object;
    }

    public function setApi(Instagram &$api_object) {
        $this->api_object = & $api_object;
    }

    public function start_api() {
        Yii::import('SocialShell.vendors.instagram.Instagram');

        switch (true) {
            case (!$this->config->in_key):
                $this->addError('start_api', 'AppID not defined', __METHOD__);
                break;
            case (!$this->config->in_secret):
                $this->addError('start_api', 'Api does not have logout function', __METHOD__);
                break;
        }

        if (!$this->config->in_callback) {
            Yii::app()->session['tmp_callback_target'] = Yii::app()->request->getPathInfo();
            $this->config->in_callback = $this->config->domain_url.Yii::app()->createUrl('/callback');
        }

        $this->api_object = new Instagram(array(
                    'apiKey' => $this->config->in_key,
                    'apiSecret' => $this->config->in_secret,
                    'apiCallback' => $this->config->in_callback
                ));

        $this->set_accessTokenSession($this->get_accessToken());
    }

    public function get_loginUrl($permissions = false) {
        $permissions = $permissions ? $permissions : explode(',', (string)$this->config->in_permissions);
        return $this->api_object->getLoginUrl($permissions);
    }

    public function get_logoutUrl() {
        $this->addError('Api does not have logout function', $exc, __METHOD__);
        return false;
    }

    public function get_accessToken() {
        return $this->access_token ? $this->access_token : $this->api_object->getAccessToken();
    }

    public function set_accessToken($access_token = false, $renew = false) {
        if ($access_token) {
            (true === is_object($access_token)) ? $this->access_token = $access_token->access_token : $this->access_token = $access_token;
            $this->api_object->setAccessToken($this->access_token);
            $this->set_accessTokenSession($this->access_token);
        } elseif ($renew && $this->get_accessTokenSession() !== false) {
            $this->access_token = $this->get_accessTokenSession();
            $this->api_object->setAccessToken($this->access_token);
        } else {
            $this->addError('set_access_token', 'AccessToken is empty', __METHOD__);
        }
    }

    private function get_accessTokenSession() {
        return Yii::app()->session['in_access_token'];
    }

    private function set_accessTokenSession($access_token) {
        Yii::app()->session['in_access_token'] = $access_token;
    }

    public function callback($callback_params = FALSE) {
        $callback_params = $callback_params ? $callback_params : Yii::app()->request->getParam('callback_params');
//        if($callback_params){
//            Yii::app()->session['in_callback_params'] = $callback_params;
//        } else {
//            $callback_params = Yii::app()->session['in_callback_params'];
//        }
        $data = false;
        if ($callback_params) {
            $data = $this->api_object->getOAuthToken($callback_params);
            $this->api_object->setAccessToken($data);
        }
        return $data;
    }

}

