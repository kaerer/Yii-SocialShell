<?php

/**
 * Facebook Shell Plugin
 *
 * @author Erce Erözbek <erce.erozbek@gmail.com>
 *
 * Facebook SDK Object
 * @property Facebook $api_object
 * @property string $access_token Facebook SDK access token
 *
 * @property array $user_info Facebook user data
 *
 * Facebook type comes from vendors.facebook.Facebook
 */
class FacebookShell extends AbstractPlugin
{

    const VERSION = 0.52;

    private $access_token;
    private $user_info;

    private $last_get_request_pagination = array();

    /**
     * @param Facebook $api_object
     */
    public function setApi(Facebook &$api_object)
    {
        $this->api_object = & $api_object;
    }

    /**
     * @param bool $silent_mode
     * @return $this
     * @throws Exception
     */
    public function start_api($silent_mode = false)
    {
        Yii::import('SocialShell.vendors.facebook.Facebook');
        self::set_header();

        if (!$silent_mode) {
            switch (true) {
                case!($this->config instanceof SocialConfigBox):
                    throw new Exception('Neet to be in facebook');
                    break;
                case!$this->config->fb_app_id:
                    throw new Exception('AppID not defined');
                    break;
                case!$this->config->fb_app_secret:
                    throw new Exception('AppSecret not defined');
                    break;
            }

            $api_object = new Facebook(array(
                'appId' => $this->config->fb_app_id,
                'secret' => $this->config->fb_app_secret,
                'fileUpload' => true,
                'cookie' => true,
            ));
            $this->setApi($api_object);

            if (isset($this->config->fb_external_access_token))
                $this->set_accessToken($this->config->fb_external_access_token);

            if ($this->config->fb_extend_access_token)
                $this->set_accessToken_extended();
            else
                $this->get_accessToken();
        }

        $this->process_pageParams($silent_mode);

        if ($this->config->fb_page_id) {
            $this->config->fb_page_url = $this->get_pageUrl(); //'https://www.facebook.com/'.$this->config->fb_page_name;
            $this->config->fb_tab_url = $this->get_tabUrl();
        }

        if (!$silent_mode) {
            if (!$this->config->fb_unique_id) {
                $this->config->fb_unique_id = $this->getApi()->getUser();
            }

            if ($this->config->fb_unique_id) {
                $this->config->fb_loggedin = true;
            }
        }

        $urlScript = Yii::app()->assetManager->publish(Yii::getPathOfAlias('SocialShell') . '/js/facebook.js');
        $cs = Yii::app()->getClientScript();
        $cs->registerScriptFile($urlScript, CClientScript::POS_HEAD);

        return $this->getApi();
    }

    public function get_uniqueID()
    {
        if (!$this->config->fb_unique_id)
            $this->config->fb_unique_id = $this->getApi()->getUser();
        return $this->config->fb_unique_id;
    }

    public function get_loginUrl($permissions = false, $redirect_url = false)
    {
        $params = array(
            'scope' => $permissions ? $permissions : $this->config->fb_permissions,
            'redirect_uri' => $redirect_url ? $redirect_url : $this->config->share_url
        );

        $loginUrl = $this->getApi()->getLoginUrl($params);
        return $loginUrl;
    }

    public function get_logoutUrl()
    {
        $loginUrl = $this->getApi()->getLogoutUrl();
        return $loginUrl;
    }

    public function set_accessToken_extended()
    {
        return ($this->getApi()->setExtendedAccessToken() === false) ? false : $this->get_accessToken();
    }

    public function get_accessToken()
    {
        $this->access_token = $this->getApi()->getAccessToken();
        $this->set_accessTokenSession($this->access_token);
        return $this->access_token;
    }

    public function get_appAccessToken()
    {
        $return = file_get_contents('https://graph.facebook.com/oauth/access_token?client_id='.$this->config->fb_app_id.'&client_secret='.$this->config->fb_app_secret.'&grant_type=client_credentials');

        if(strpos($return, 'access_token=') == 0){
            $this->access_token = str_replace('access_token=', '', $return);
        }

        return $this->access_token;
    }

    /**
     * @param bool $access_token
     * @param bool $renew
     * @return int 1:ok 2,0: error
     */
    public function set_accessToken($access_token = false, $renew = false)
    {
        if ($access_token) {
            $this->access_token = (true === is_object($access_token)) ? $access_token->access_token : $access_token;
            $this->getApi()->setAccessToken($this->access_token);
            $this->set_accessTokenSession($this->access_token);
            return 1;
        } elseif ($renew || $this->get_accessTokenSession() !== false) {
            $this->access_token = $this->get_accessTokenSession();
            $this->getApi()->setAccessToken($this->access_token);
            $this->set_accessTokenSession($this->access_token);
            return 2;
        } else {
            self::addError('access_token', 'empty', __METHOD__);
            return 0;
        }
    }

    public function get_taken_permissions()
    {
        return $this->get_object('/me/permissions');
    }

    public function check_permissions($permissions_string = false)
    {
        $taken_permissions = $this->get_permissions();

        $needed_permissions = explode(',', $permissions_string ? $permissions_string : $this->config->fb_permissions);
        if ($needed_permissions) {
            $needed_permissions = array_map('trim', $needed_permissions);
        }

        $missing_permissions = array();
        $result = true;
        if (count($taken_permissions) == 0) {
            $result = false;
            $missing_permissions = $needed_permissions;
        } else {
            foreach ($needed_permissions as $p) {
                if (!isset($taken_permissions[$p])) {
                    $missing_permissions[] = $p;
                    $result = false;
                }
            }
        }

        if ($result) {
            return true;
        } else {
            self::addError('permissions_taken', $taken_permissions, __METHOD__);
            self::addError('permissions_needed', $needed_permissions, __METHOD__);
            self::addError('permissions_missing', $missing_permissions, __METHOD__);
            return false;
        }
    }

    /**
     * Send post to wall
     *
     * @param type $link_text
     * @param type $link
     * @param type $caption
     * @param type $description
     * @param type $picture
     * @return boolean
     */
    public function post_feed($link_text, $link, $description = '', $picture = false, $caption = false, $to_unique_id = false)
    {
            $attachment = array(
                //'access_token' => $this->access_token(),
                'link' => $link,
                'name' => $link_text,
                'description' => $description,
//                'message' => "",
            );
            if ($caption)
                $attachment['caption'] = $caption;
            if ($picture)
                $attachment['picture'] = $picture;
        $result = $this->post_object('/' . ($to_unique_id ? $to_unique_id : 'me') . '/feed/', $attachment, 'POST', __METHOD__);
        return $result;
    }

    public function post_notification($to_unique_id, $template, $app_access_token = false)
    {
        if(!$app_access_token) $app_access_token = $this->socialModule->obj_facebook->get_appAccessToken();

        $attachment = array(
            'template' => $template,
            'access_token' => $app_access_token,
        );
        $result = $this->post_object('/' . $to_unique_id . '/notifications', $attachment, 'POST', __METHOD__);;
        return $result;
    }

    public function get_user_info($unique_id = false)
    {
        $unique_id = ($unique_id ? $unique_id : 'me');
        if (!isset($this->user_info[$unique_id])) {
            $this->user_info[$unique_id] = $this->get_object('/' . $unique_id);
        }
        return $this->user_info[$unique_id];
    }

    public function get_user_data($path = '', $unique_id = false)
    {
        $results = $this->get_object('/' . ($unique_id ? $unique_id : 'me') . '/' . trim($path, '/'));
        return $results;
    }

    public function get_object($object_path)
    {
        return $this->post_object($object_path, array(), 'GET', __METHOD__);
    }

    public function last_get_request_pagination()
    {
        return $this->last_get_request_pagination;
    }

    public function last_get_request_next()
    {
        if (!is_object($this->last_get_request_pagination)) $this->last_get_request_pagination = (object)$this->last_get_request_pagination;

        $clean = 'https://graph.facebook.com';
        if (isset($this->last_get_request_pagination->next)) return $this->get_object($this->last_get_request_pagination->next);
        return false;
    }

    public function last_get_request_previous()
    {
        if (!is_object($this->last_get_request_pagination)) $this->last_get_request_pagination = (object)$this->last_get_request_pagination;

        if (isset($this->last_get_request_pagination->previous)) return $this->get_object($this->last_get_request_pagination->previous);
        return false;
    }

    /**
     * Graph Api Post Method
     *
     * @param type $object_path
     * @param type $object_params
     * @param type $method
     * @return boolean
     */
    public function post_object($object_path, $object_params = array(), $method = 'POST', $action_name = 'post_object')
    {
        $results = false;
        try {
//            if (!isset($object_params['accessToken']))
//                $object_params['accessToken'] = $this->get_accessToken();
            $results = $this->getApi()->api($object_path, $method, $object_params); //.'?access_token='.$this->access_token()
        } catch (FacebookApiException $exc) {
            self::addError($action_name, $exc->getMessage(), __METHOD__);
        } catch (Exception $exc) {
//            self::addError('post_object', array($exc->getMessage(), $exc->getTraceAsString()), __METHOD__);
            self::addError($action_name, $exc->getMessage(), __METHOD__);
        }
        if (is_array($results)) {
            if (isset($results['paging'])) {
                $this->last_get_request_pagination = $results['paging'];
            }

            if (count($results) == 1 && isset($results[0])) {
                return $results[0];
            } elseif (!isset($results['id']) && isset($results['data'])) {
                return $results['data'];
            }
        }
        return $results;

    }

    public function delete_object($object_path)
    {
        return $this->post_object($object_path, array(), 'DELETE', __METHOD__);
    }

    /**
     * Call fql query
     * @param type $fql
     * @return type
     */
    public function get_fql($fql)
    {
        $params = array(
            'method' => 'fql.query',
            'query' => $fql,
        );
        $results = $this->get_object($params);
        return $results;
    }

    public function get_user_photos()
    {
        return $this->get_user_data('photos');
    }

    public function get_user_albums()
    {
        return $this->get_user_data('albums');
    }

    public function get_user_album_photos($album_id)
    {
        return $this->get_object($album_id . '/photos');
    }

    public function upload_photo($album_params = array('name', 'description'), $photo_params = array('file', 'description'))
    {

        $default_album_params = array('name' => 'uploaded photo', 'description' => 'uploaded by Yii Social Module facebookShell');
        $default_photo_params = array('description' => '');

        $album_params = array_merge($default_album_params, $album_params);
        $photo_params = array_merge($default_photo_params, $photo_params);

        $album_details = array(
            //'access_token' => $this->access_token(),
            'name' => $album_params['name'],
            'message' => $album_params['description'],
        );

        try {
            $album_uid = false;
            $albums = $this->getApi()->api('/me/albums');
            if (is_array($albums['data'])) {
                foreach ($albums['data'] as $a) {
                    if ($a['name'] == $album_details['name']) {
                        $album_uid = $a['id'];
                        break;
                    }
                }
            }
        } catch (FacebookApiException $exc) {
            self::addError('get_album', $exc, __METHOD__);
        } catch (Exception $exc) {
            self::addError('get_album', array($exc->getMessage(), $exc->getTraceAsString()), __METHOD__);
        }

        // album mevcutmu kontrolu yap
        if (!$album_uid) {
            try {
                #- Album yarat
                $create_album = $this->getApi()->api('/me/albums', 'POST', $album_details);
                if ($create_album) {
                    $album_uid = $create_album['id'];
                    self::addAction('create_album', $album_uid, __METHOD__);
                }
            } catch (FacebookApiException $exc) {
                self::addError('create_album', $exc, __METHOD__);
            } catch (Exception $exc) {
                self::addError('create_album', array($exc->getMessage(), $exc->getTraceAsString()), __METHOD__);
            }
        }

        $result = false;
        try {
            //Upload a photo to album of ID...
            $photo_details = array(
                //'access_token' => $this->access_token(),
                'image' => '@' . realpath($photo_params['file']),
                'message' => $photo_params['description'],
            );
            $photo = $this->getApi()->api('/' . $album_uid . '/photos', 'POST', $photo_details);
            if ($photo && isset($photo['id'])) {
                //TODO:: gelen $photo de?i?keni içinde link var mı acep? tekrar kontrol niheye
                self::addAction('create_photo', $photo['id'], __METHOD__);
                $photo_info = $this->getApi()->api('/' . $photo['id']);
                if ($photo_info) {
                    $this->ids['upload_photo'] = $photo_info;
                    $result = $photo_info['link'];
                }
            }
        } catch (FacebookApiException $exc) {
            self::addError('upload_photo', $exc, __METHOD__);
        } catch (Exception $exc) {
            self::addError('upload_photo', array($exc->getMessage(), $exc->getTraceAsString()), __METHOD__);
        }

        return $result;
    }

    public function get_addingAppToTabUrl($redirect_url = false)
    {
        return 'https://www.facebook.com/dialog/pagetab?app_id=' . $this->config->fb_app_id . '&next=' . ($redirect_url ? $redirect_url : $this->get_tabUrl());
    }

    public function get_tabUrl($params = false)
    {
        if (!$this->config->fb_tab_url) {
            $this->config->fb_tab_url = $this->get_pageUrl() . '/app_' . $this->config->fb_app_id;
        }
        $str_params = is_array($params) ? 'app_data=' . urlencode(json_encode($params)) : '';
        return $this->config->fb_tab_url . ($str_params ? '&' . $str_params : '');
    }

    public function get_pageUrl()
    {
        if (!$this->config->fb_page_url) {
            if ($this->config->fb_page_name) {
                $this->config->fb_page_url = self::get_pageUrlByPageName($this->config->fb_page_name);
            } else {
                $this->config->fb_page_url = self::get_pageUrlByID($this->config->fb_page_id);
            }
        }
        return $this->config->fb_page_url;
    }

    public function get_canvasUrl()
    {
        return $this->config->fb_canvas_url;
    }

    public function is_page_admin()
    {
        return (bool)$this->config->fb_page_admin;
    }

    public function is_page()
    {
        return (bool)$this->config->fb_page_id;
    }

    public function is_page_liked()
    {
        return (bool)$this->config->fb_page_liked;
    }

    public function process_pageParams($silent_mode = false)
    {
        $data = self::parse_signed_request($silent_mode, $this->config->fb_app_secret);
        if (is_array($data)) {
            $this->config->fb_page_params = $data;

            if (isset($data['page'])) {
                $this->config->fb_page_id = $data['page']['id'];
                $this->config->fb_page_admin = $data['page']['admin'];
                $this->config->fb_page_liked = $data['page']['liked'];
            }

            if (isset($data['user_id']))
                $this->config->fb_unique_id = $data['user_id'];

            if (isset($data['user'])) {
                $this->config->locale = $data['user']['locale'];
            }

            if (isset($data['app_data'])) {
                $this->config->fb_tab_params = CJSON::decode(urldecode($data['app_data']));
            }
        }
        return $data;
    }

    /**
     * Get users facebook pic
     * @param type $unique_id
     * @param type $size
     * square (50x50), small (50 x variable height), normal (100 x variable height), large (200 x variable height)"
     * @return string
     */
    public static function get_pictureUrl($unique_id, $size = 'large')
    {
        return '//graph.facebook.com/' . $unique_id . '/picture?type=' . $size;
    }

    public static function get_profileUrl($unique_id)
    {
        return 'https://www.facebook.com/profile.php?id=' . $unique_id;
    }

    public static function get_pageUrlByID($page_id = 0, $app_id = false)
    {
        return "https://www.facebook.com/pages/-/" . $page_id . ($app_id ? "?sk=app_" . $app_id : '');
    }

    public static function get_pageUrlByPageName($page_screen_name = 0, $app_id = false)
    {
        return "https://www.facebook.com/" . $page_screen_name . ($app_id ? "/app_" . $app_id : '');
    }

    public static function get_addPageUrl($app_id = 0, $next_target = false)
    {
        return 'https://www.facebook.com/dialog/pagetab?app_id=' . $app_id . '&next=' . ($next_target ? $next_target : 'http://facebook.com');
    }

    public static function get_likeButton($url, $width = '120')
    {
        if (!$width = (int)$width)
            $width = 120;
        return '<div class="fb-like" data-href="' . self::set_protocole(trim($url)) . '" data-send="false" data-layout="button_count" data-width="' . $width . '" data-show-faces="false"></div>';
    }

    public static function set_header()
    {
        #- ie fix for api
        header('P3P: CP="CAO PSA OUR"');
//        header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
//        header('P3P: CP="CAO DSP COR CURa ADMa DEVa PSAa PSDa IVAi IVDi CONi OUR OTRi IND PHY ONL UNI FIN COM NAV INT DEM STA"');
    }

    public static function set_meta($property, $value)
    {
        Yii::app()->clientScript->registerMetaTag($value, null, null, array('property' => $property));
    }

    /* System functions */

    private function get_accessTokenSession()
    {
        return self::getSession('fb_access_token');
    }

    private function set_accessTokenSession($access_token)
    {
        self::setSession('fb_access_token', $access_token);
    }

    public static function parse_signed_request($silent_mode = false, $fb_app_secret = false)
    {

        $signed_request = Yii::app()->request->getParam('signed_request');

        if (empty($signed_request) || !$signed_request || strpos($signed_request, '.') === false) {
            self::addError('signed_request', 'not valid', __METHOD__);
            return false;
        }

        list($encoded_sig, $payload) = explode('.', $signed_request, 2);

        #- decode the data
        $sig = self::base64_url_decode($encoded_sig);
        $data = json_decode(self::base64_url_decode($payload), true);

        if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
            //'Unknown algorithm. Expected HMAC-SHA256';
//            self::addError('algorithm', array('algorithm' => strtoupper($data['algorithm'])), __METHOD__);
            self::addError('algorithm', 'not supported', __METHOD__);
            return false;
        }

        if ($fb_app_secret) {
            #- check sig
            $expected_sig = hash_hmac('sha256', $payload, $fb_app_secret, $raw = true);

            if ($sig !== $expected_sig) {
//                self::addError('sign_notMatched - might_signed_request_injection', array('sig' => $sig, 'expected_sig' => $expected_sig, 'secret' => strlen($fb_app_secret)), __METHOD__);
                if (!$silent_mode)
                    return false;
            }
        }

        return $data;
    }

    private static function base64_url_decode($input)
    {
        return base64_decode(strtr($input, '-_', '+/'));
    }

}