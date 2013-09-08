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
    public $ga_code = null;

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
     *
     * @var string
     */
    public $fb_app_namespace = null;

    /**
     * Try to extend the access token of user
     * @var bool
     */
    public $fb_extend_access_token = false;
    /**
     * V2 ids max 18 char,
     * @var bigint
     */
    public $fb_unique_id = null;

    /**
     * Try to set the access_token
     * @var bool
     */
    public $fb_external_access_token = false;

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
     * 'email, user_likes, user_interests, user_birthday, user_hometown, offline_access, publish_actions'
     */
    public $fb_permissions = null;

    /**
     * Facebook fanpage ID
     * @var int
     */
    public $fb_page_id = null;

    /**
     * Is the user admin of the current page
     * @var bool
     */
    public $fb_page_admin = null;

    /**
     * Is the current user fan of the current page
     * @var bool
     */
    public $fb_page_liked = null; //public $is_fan =& #- for

    /**
     * Fanpage name
     * @var string
     */
    public $fb_page_name = null;
    public $fb_page_url = null;
    public $fb_page_params = array();
    public $fb_app_url = null;
    public $fb_tab_url = null;
    public $fb_tab_params = array();
    public $fb_canvas_url = null; //http://apps.facebook.com/sendekatil/
    public $fb_loggedin = null;
    public $fb_disable_track = true;

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
    public $tw_permissions = null;
    public $tw_unique_id = null;

    /*
     * ** Instagram Params **
     *
     * @var bool
     */
    public $instagram_api = false;
    public $in_key = null;
    public $in_secret = null;

    /**
     * Instagram app callback
     * //domain/callback
     * @var type
     */
    public $in_callback = null;
    /**
     * After instagram callback end successfully
     * callback controller redirect to the caller controller if $in_callback_redirect not setted
     * @var type
     */
    public $in_callback_redirect_to = null;

    public $in_callback_redirect_protocol = false;

    /**
     * Auth Permissions
     *
     * comma seperated permissin string
     *
     * basic, relationships, likes, comments
     *
     * @var string
     */
    public $in_permissions = 'basic, relationships, likes, comments';

    public $in_unique_id = null;
    public $in_loggedin = null;

    /*
     * ** Share Params **
     */
    public $share_url = null;
    public $share_image = null;

}