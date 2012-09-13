<?php

/**
 * Description of Twitter Shell Plugin
 *
 * @author erce
 */
class TwitterShell extends AbstractShell {

    const VERSION = 0.1;

    private $obj;
    public $access_token;

    public function &getApi() {
        return $this->obj;
    }

    public function setApi(&$api) {
        $this->obj = & $api;
    }

    public function start_api() {
        Yii::import('SocialShell.vendors.twitter.tmhOAuth');
        Yii::import('SocialShell.vendors.twitter.tmhUtilities');

        $this->obj = new Facebook(array(
                    'appId' => $this->config->fb_app_id,
                    'secret' => $this->config->fb_app_secret,
                    'fileUpload' => true,
                    'cookie' => true,
                ));

        return $this->getApi();
    }

    public function get_unique() {}

    public function get_login_url($permissions = false, $redirect_url = false) {
        $params = array(
            'scope' => $permissions ? $permissions : $this->config->fb_permissions,
            'redirect_uri' => $redirect_url ? $redirect_url : $this->config->share_url
        );

        $loginUrl = $this->obj->getLoginUrl($params);
        return $loginUrl;
    }

    public function redirect_login($permissions = false, $redirect_url = false) {
        $target = $this->get_login_url($permissions, $redirect_url);
        $this->redirect($target);
    }

    public function get_taken_permissions() {
        return $this->get_object('/me/permissions');
    }

    public function check_permissions($permissions_string = false) {
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
            $this->addError('permissions_taken', $taken_permissions, __METHOD__);
            $this->addError('permissions_needed', $needed_permissions, __METHOD__);
            $this->addError('permissions_missing', $missing_permissions, __METHOD__);
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
    public function post_feed($link_text, $link, $description = '', $picture = false, $caption = false, $unique_id = false) {
        try {
            $attachment = array(
                //'access_token' => $this->access_token(),
                'link' => $link,
                'name' => $link_text,
                'description' => $description,
                'message' => "",
            );
            if ($caption)
                $attachment['caption'] = $caption;
            if ($picture)
                $attachment['picture'] = $picture;
            $result = $this->obj->api('/'.($unique_id ? $unique_id : 'me').'/feed/', 'POST', $attachment);
            $this->addAction('share', $result, __METHOD__);
            return $result;
        } catch (Exception $exc) {
            $this->addError('share', array($exc->getMessage(), $exc->getTraceAsString()), __METHOD__);
            return false;
        }
    }

    public function get_user_info($unique_id = false) {
        if (!$this->user_info) {
            $this->user_info = $this->get_object('/'.($unique_id ? $unique_id : 'me'));
        }
        return $this->user_info;
    }

    public function get_user_data($path = '', $unique_id = false) {
        $results = $this->get_object(($unique_id ? '/'.$unique_id : '/me').'/'.trim($path, '/'));
        return $results;
    }

    public function get_object($object_path) {
        try {
            $results = $this->obj->api($object_path); //.'?access_token='.$this->access_token()
        } catch (Exception $exc) {
            $this->addError('data', array($exc->getMessage(), $exc->getTraceAsString()), __METHOD__);
        }
        if (isset($results)) {
            if (is_array($results)) {
                if (count($results) == 1 && isset($results[0])) {
                    return $results[0];
                }
//                elseif (isset($results['data'])) {
//                    return $results['data'];
//                }
            }
            return $results;
        } else {
            return false;
        }
    }

    /**
     * Graph Api Post Method
     *
     * @param type $object_path
     * @param type $object_params
     * @param type $method
     * @return boolean
     */
    public function post_object($object_path, $object_params = array(), $method = 'POST') {
        try {
            if (!isset($object_params['accessToken']))
                $object_params['accessToken'] = $this->get_accessToken();
            $results = $this->obj->api($object_path, $method, $object_params); //.'?access_token='.$this->access_token()
        } catch (Exception $exc) {
            $this->addError('data', array($exc->getMessage(), $exc->getTraceAsString()), __METHOD__);
        }
        if (isset($results)) {
            if (is_array($results)) {
                if (count($results) == 1 && isset($results[0])) {
                    return $results[0];
                } elseif (isset($results['data'])) {
                    return $results['data'];
                }
            }
            return $results;
        } else {
            return false;
        }
    }

    public function upload_photo($album_params = array('name', 'description'), $photo_params = array('file', 'description')) {

        $default_album_params = array('name' => 'uploaded photo', 'description' => 'uploaded by facebook_shell');
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
            $albums = $this->obj->api('/me/albums');
            if (is_array($albums['data'])) {
                foreach ($albums['data'] as $a) {
                    if ($a['name'] == $album_details['name']) {
                        $album_uid = $a['id'];
                        break;
                    }
                }
            }
        } catch (Exception $exc) {
            $this->addError('get_album', array($exc->getMessage(), $exc->getTraceAsString()), __METHOD__);
        }

        // album mevcutmu kontrolu yap
        if (!$album_uid) {
            try {
                #- Album yarat
                $create_album = $this->obj->api('/me/albums', 'POST', $album_details);
                if ($create_album) {
                    $album_uid = $create_album['id'];
                    $this->addAction('create_album', $album_uid, __METHOD__);
                }
            } catch (Exception $exc) {
                $this->addError('create_album', array($exc->getMessage(), $exc->getTraceAsString()), __METHOD__);
            }
        }

        $result = false;
        try {
            //Upload a photo to album of ID...
            $photo_details = array(
                //'access_token' => $this->access_token(),
                'image' => '@'.realpath($photo_params['file']),
                'message' => $photo_params['description'],
            );
            $photo = $this->obj->api('/'.$album_uid.'/photos', 'POST', $photo_details);
            if ($photo && isset($photo['id'])) {
                //TODO:: gelen $photo değişkeni içinde link var mı acep? tekrar kontrol niheye
                $this->addAction('create_photo', $photo['id'], __METHOD__);
                $photo_info = $this->obj->api('/'.$photo['id']);
                if ($photo_info) {
                    $this->ids['upload_photo'] = $photo_info;
                    $result = $photo_info['link'];
                }
            }
        } catch (Exception $exc) {
            $this->addError('upload_photo', array($exc->getMessage(), $exc->getTraceAsString()), __METHOD__);
        }

        return $result;
    }

    public function get_accessToken() {
        return $this->access_token ? $this->access_token : $this->obj->getAccessToken();
    }

    public function set_accessToken($access_token = false, $renew = false) {
//        CVarDumper::dump($this->access_token, 4, true);
//        CVarDumper::dump(Yii::app()->session->toArray(), 4, true);
        if ($renew || !$this->access_token) {
            try {
                $this->access_token = $this->obj->getAccessToken();
                Yii::app()->session['access_token'] = $this->access_token;
            } catch (Exception $exc) {
                $this->addError('set_access_token', array($exc->getMessage(), $exc->getTraceAsString()));
            }
        }

        if ($renew && isset(Yii::app()->session['access_token']) && !empty(Yii::app()->session['access_token'])) {
            $this->access_token = Yii::app()->session['access_token'];
            $this->obj->setAccessToken($this->access_token);
        } else {
            $this->access_token = $access_token;
            $this->obj->setAccessToken($access_token);
        }
        return $this->access_token;
    }

    /**
     * Get users facebook pic
     * @param type $unique_id
     * @param type $size
     * square (50x50), small (50 x variable height), normal (100 x variable height), large (200 x variable height)"
     * @return string
     */
    public static function get_pictureUrl($unique_id, $size = 'large') {
        $url = 'https://graph.facebook.com/'.$unique_id.'/picture?type='.$size;
        return $url;
    }

}

