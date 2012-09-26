<?php

/**
 * Description of SocialConfig
 *
 * @author erce
 */
abstract class SocialConfigBox extends stdClass {

    /**
     *
     * @var string
     */
    public $app_name = 'Social Shell Demo';

    /**
     *
     * @var string
     */
    public $domain_url = '';

    /**
     * tr_TR, en_US
     *
     * @var string
     */
    public $locale = 'tr_TR'; //en_US

    /**
     * ** Google Analytic ID **
     *
     * @var string $ga_code
     */
    public $ga_code = false;

    /*
     * ** Facebook Params **
     *
     * @var bool
     */

    public $facebook_api = false;

    /**
     *
     * @var int
     */
    public $fb_app_id = null;

    /**
     *
     * @var string
     */
    public $fb_app_secret = null;

    /**
     * V2 ids max 18 char,
     * @var int
     */
    public $fb_unique_id = null;

    /**
     * Auth Permissions
     *
     * comma seperated permissin string
     *
     * email,
     * user_likes, user_interests,
     * user_birthday, user_hometown,
     * offline_access,
     * publish_stream, publish_actions,
     * ...
     *
     * @var string
     */
    public $fb_permissions = 'email, user_likes, user_interests, user_birthday, user_hometown, offline_access, publish_actions';

    /**
     *
     * @var int
     */
    public $fb_page_id = null;

    /**
     *
     * @var bool
     */
    public $fb_page_admin = false;

    /**
     *
     * @var bool
     */
    public $fb_page_liked = false; //public $is_fan =& #- for

    public $fb_page_name = 'BaseApps';
    public $fb_page_url = 'https://www.facebook.com/';
    public $fb_page_params = array();
    public $fb_app_url = false;
    public $fb_tab_url = false;
    public $fb_tab_params = array();
    public $fb_canvas_url = false; //http://apps.facebook.com/sendekatil/

    /*
     * ** Twitter Params **
     *
     * @var bool
     */
    public $twitter_api = false;
    public $tw_key = null;
    public $tw_secret = null;
    public $tw_token = null;
    public $tw_token_secret = null;
    public $tw_unique_id = null;

    /*
     * ** Instagram Params **
     *
     * @var bool
     */
    public $instagram_api = false;
    public $in_key = null;
    public $in_secret = null;
    public $in_token = null;
    public $in_token_secret = null;
    public $in_unique_id = false;

    /*
     * ** Share Params **
     */
    public $share_url = false;
    public $share_image = false;

}