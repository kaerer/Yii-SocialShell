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
class InstagramShell extends AbstractPlugin
{

    const VERSION = 0.4;

    private $access_token;
    private $user_info;

    public function setApi(Instagram &$api_object)
    {
        $this->api_object = & $api_object;
    }

    public function start_api($silent_mode = false)
    {
        Yii::import('SocialShell.vendors.instagram.Instagram');
        $r = Yii::app()->request;
        if (!$this->config->in_callback) {
            $domain = SocialConfig::changeProtocole($this->config->domain_url, $this->config->in_callback_redirect_protocol);
            if (!$this->config->in_callback_redirect_to) {
                $this->config->in_callback_redirect_to = $domain . '/' . Yii::app()->controller->getId() . '/callback';
            }

            Yii::app()->session['in_callback_redirect_protocol'] = $this->config->in_callback_redirect_protocol;
            Yii::app()->session['in_callback_redirect_to'] = $this->config->in_callback_redirect_to;
            Yii::app()->session['in_callback_started'] = $r->getPathInfo();
            $this->config->in_callback = $domain.'/callback';
//            '/' . end(explode('/', Yii::app()->request->scriptFile)) .
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

            $this->set_accessToken(FALSE, TRUE);

            $user = $this->getApi()->getUser();
            if (is_object($user) && isset($user->meta) && $user->meta->code == '200') {
                $this->config->in_loggedin = true;
                $this->config->in_unique_id = $user->data->id;
                self::setSession('in_unique_id', $this->config->in_unique_id);
            }


//            $this->config->in_unique_id = $this->getApi()->
//            $this->config->in_unique_id = (int)$api_object->getUser();
//        $tmp_id = (int)self::getSession('in_unique_id');
//        if ($tmp_id) {
//            $this->config->in_unique_id = $tmp_id;
//        }

            $urlScript = Yii::app()->assetManager->publish(Yii::getPathOfAlias('SocialShell') . '/js/instagram.js');
            $cs = Yii::app()->getClientScript();
            $cs->registerScriptFile($urlScript, CClientScript::POS_HEAD);
        }

//        $this->config->in_loggedin = (bool)$this->config->in_unique_id;
    }

    /**
     *
     * @param type basic, comments, relationships, likes
     * @return mixed url / false
     */
    public function get_loginUrl($permissions = false)
    {
        $permissions = $permissions ? $permissions : array_map('trim', explode(',', (string)$this->config->in_permissions));
        try {
            return $this->getApi()->getLoginUrl($permissions);
        } catch (Exception $exc) {
            $this->addError('get_loginUrl', $exc, __METHOD__);
        }

        return false;
    }

    public function get_logoutUrl()
    {
        $this->addError('get_logoutUrl', 'Api does not have logout function', __METHOD__);
        return false;
    }

    public function get_accessToken()
    {
        if (empty($this->access_token))
            $this->access_token = $this->getApi()->getAccessToken();

        return $this->access_token;
    }

    public function set_accessToken($access_token = false, $renew = false)
    {
        if ($access_token) {
            (true === is_object($access_token)) ? $this->access_token = $access_token->access_token : $this->access_token = $access_token;
            $this->getApi()->setAccessToken($this->access_token);
            $this->set_accessTokenSession($this->access_token);
        } elseif ($renew || $this->get_accessTokenSession() !== false) {
            $this->access_token = $this->get_accessTokenSession();
            $this->getApi()->setAccessToken($this->access_token);
        } else {
            self::addError('access_token', 'empty', __METHOD__);
        }
    }

    private function get_accessTokenSession()
    {
        return Yii::app()->session['in_access_token'];
    }

    private function set_accessTokenSession($access_token)
    {
        Yii::app()->session['in_access_token'] = $access_token;
    }

    public function callback($callback_params = FALSE, $popup = true)
    {
        $callback_params = $callback_params ? $callback_params : urldecode(Yii::app()->request->getParam('callback_params'));

        if (is_string($callback_params))
            parse_str($callback_params, $callback_params);

        if ($callback_params) {
            self::setSession('in_callback_params', $callback_params);
        } else {
            $callback_params = self::getSession('in_callback_params');
        }

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
                $this->set_accessTokenSession($this->getApi()->getAccessToken());
                $result['active'] = true;
            }
        } else {
            $access_token = self::getCookie('in_access_token');
            if ($access_token)
                $this->getApi()->setAccessToken($access_token);
//            CVarDumper::dump($access_token,5,1);
        }

        if ($result['code'] == 200 || $result['error']) {
            $result['active'] = true;
        }

        $user = $this->getApi()->getUser();
        if (is_object($user) && isset($user->meta) && $user->meta->code == '200') {
            $this->config->in_loggedin = true;
            $this->config->in_unique_id = $user->data->id;
            self::setSession('in_unique_id', $this->config->in_unique_id);
        }
//        CVarDumper::dump($user,5,1);
//        CVarDumper::dump($this->config->in_unique_id,5,1);
//        CVarDumper::dump($result,5,1);
//        CVarDumper::dump($this->config->in_loggedin,5,1);
//        CVarDumper::dump($callback_params, 5, 1);

        if ($popup || $result['active']) {
            /**
             * Js callback çağırılıyor olması sayfa sonu anlamına gelmez, callback çağrılıp sonrasında resim kayıt işlemleri vs devam ediyor olacak.
             */
            echo '<script>' . "\n";
            echo 'var results = ' . CJSON::encode($result) . ';' . "\n";
            echo 'window.opener.in_loggedin = ' . CJavaScript::encode($this->config->in_loggedin) . ';' . "\n";
            echo 'window.opener.in_unique_id = ' . CJavaScript::encode($this->config->in_unique_id) . ';' . "\n";
            echo 'window.opener.in_user_profile = results;' . "\n";
            echo 'if (typeof window.opener.in_login_callback === "function") window.opener.in_login_callback(results); else window.close()' . "\n";
            echo '</script>' . "\n";
        }

        return $result;
    }

}