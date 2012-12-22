<?php

/**
 * Description of Instagram Shell Plugin
 *
 * @author Erce Erözbek <erce.erozbek@gmail.com>
 *
 * @property Instagram $api_object Instagram SDK Object
 * @property string $access_token Instagram SDK access token
 *
 * @property array $user_info Instagram user data
 */
class InstagramShell extends AbstractPlugin {

    const VERSION = 0.2;

    private $access_token;
    private $user_info;

    public function setApi(Instagram &$api_object) {
        $this->api_object = & $api_object;
    }

    public function start_api($silent_mode = false) {
        Yii::import('SocialShell.vendors.instagram.Instagram');
        $r = Yii::app()->request;
        if (!$this->config->in_callback) {
            Yii::app()->session['tmp_callback_target'] = $r->getPathInfo();
            $this->config->in_callback = $this->config->domain_url.'/callback';
        }

        if (!$silent_mode) {
            switch (true) {
                case (!$this->config->in_key):
                    $this->addError('start_api', 'AppID not defined', __METHOD__);
                    break;
                case (!$this->config->in_secret):
                    $this->addError('start_api', 'Api does not have logout function', __METHOD__);
                    break;
            }
            $api_object = new Instagram(array(
                        'apiKey' => $this->config->in_key,
                        'apiSecret' => $this->config->in_secret,
                        'apiCallback' => $this->config->in_callback
                    ));

            $this->setApi($api_object);

            $this->set_accessTokenSession($this->get_accessToken());

        }

        $urlScript = Yii::app()->assetManager->publish(Yii::getPathOfAlias('SocialShell').'/js/instagram.js');
        $cs = Yii::app()->getClientScript();
        $cs->registerScriptFile($urlScript, CClientScript::POS_HEAD);
    }

    /**
     *
     * @param type basic, comments, relationships, likes
     * @return mixed url / false
     */
    public function get_loginUrl($permissions = false) {
        $permissions = $permissions ? $permissions : array_map('trim', explode(',', (string)$this->config->in_permissions));
        try {
            return $this->getApi()->getLoginUrl($permissions);
        } catch (Exception $exc) {
            $this->addError('get_loginUrl', $exc, __METHOD__);
        }

        return false;
    }

    public function get_logoutUrl() {
        $this->addError('get_logoutUrl', 'Api does not have logout function', __METHOD__);
        return false;
    }

    public function get_accessToken() {
        return $this->access_token ? $this->access_token : $this->getApi()->getAccessToken();
    }

    public function set_accessToken($access_token = false, $renew = false) {
        if ($access_token) {
            (true === is_object($access_token)) ? $this->access_token = $access_token->access_token : $this->access_token = $access_token;
            $this->getApi()->setAccessToken($this->access_token);
            $this->set_accessTokenSession($this->access_token);
        } elseif ($renew && $this->get_accessTokenSession() !== false) {
            $this->access_token = $this->get_accessTokenSession();
            $this->getApi()->setAccessToken($this->access_token);
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

    public function callback($callback_params = FALSE, $popup = true) {
        $callback_params = $callback_params ? $callback_params : Yii::app()->request->getParam('callback_params');
//        if($callback_params){
//            Yii::app()->session['in_callback_params'] = $callback_params;
//        } else {
//            $callback_params = Yii::app()->session['in_callback_params'];
//        }

        $result = array(
            'active' => false,
            'data' => false,
            'code' => false,
            'error' => false,
            'raw' => $callback_params,
        );

        $result['code'] = isset($callback_params['code']) ? $callback_params['code'] : false;
        $result['error'] = isset($callback_params['error_reason']) ? $callback_params['error_reason'] : false;
        if ($result['code']) {
            $result['data'] = $this->getApi()->getOAuthToken($callback_params['code']);
            if (is_object($result['data']) && isset($result['data']->access_token)) {
                self::setCookie('in_access_token', $result['data']->access_token);
                $this->getApi()->setAccessToken($result['data']);
            }
        } else {
            $access_token = self::getCookie('in_access_token');
            if ($access_token)
                $this->getApi()->setAccessToken($access_token);
        }

        if ($result['code'] || $result['error']) {
            $result['active'] = true;
        }

        $user = $this->getApi()->getUser();
        if (is_object($user) && isset($user->meta) && $user->meta->code == '200') {
            $this->config->in_loggedin = true;
        }

        if ($popup && $result['active']) {
            echo '<script>';
            echo 'window.opener.in_login_callback('.CJSON::encode($result).');';
            echo 'window.opener.console.log('.CJSON::encode($result).');';
            echo '</script>';
            Yii::app()->end();
        }

        return $result;
    }

}