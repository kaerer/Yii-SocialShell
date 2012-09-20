<?php

class TestController extends Controller {

    public function actionIndex() {

        if (!YII_DEBUG) {
            $url = 'http://www.baseiletisim.com';
            yii::app()->request->redirect($url);
        }

        $date = date('yyyy-MM-dd');
        $dateFormatter = new CDateFormatter('tr_tr');
        echo $dateFormatter->formatDateTime(time(), 'medium', false);

//        CVarDumper::dump($dateFormatter, 5, true);
    }

    public function actionFacebook() {

        $this->layout = '//test/layout';

        $social = Yii::app()->getModule('SocialShell');
        /* @var $social SocialShellModule */

        #- Set SocialShell Object
        $config = new SocialConfig();
        $config->facebook_api = true;
        $config->fb_page_name = 'BaseApps';
        $config->fb_app_id = '143684392442216';
        $config->fb_app_secret = '60e4cc8bf31016623bcfb514a8607e5b';
        $config->fb_permissions = 'user_likes, user_interests, user_birthday, user_hometown';

        #- Run SocialShell
        $social->load($config);
        $social->start_api();

//        CVarDumper::dump($social->getConfig(),10,1);
//        CVarDumper::dump($config->api_facebook->debug(),10,1);

        $this->render('facebook', array(
            'socialModule' => $social,
            'social' => $config
        ));
    }

    public function actionAnalytics() {

        $this->layout = '//test/layout';

        $social = Yii::app()->getModule('SocialShell');
        /* @var $social SocialShellModule */

        #- Set SocialShell Object
        $config = new SocialConfig();
        $config->ga_code = '999999999';

        #- Run SocialShell
        $social->load($config);
        $social->start_api();

//        CVarDumper::dump($social->getConfig(),10,1);
//        CVarDumper::dump($config->api_facebook->debug(),10,1);

        $this->render('analytics', array(
            'socialModule' => $social,
            'social' => $config
        ));
    }

    public function actionTwitter() {

        $this->layout = '//test/layout';

        $social = Yii::app()->getModule('SocialShell');
        /* @var $social SocialShellModule */

        #- Set SocialShell Object
        $config = new SocialConfig();
        $config->tw_key = 'wzlTkmtoq5EPuJvEPPXp6w';
        $config->tw_secret = 'g6CIdlGX3GUSWoVtuehJGIvXFborkcQ93ovPnNPHSo';

        /*
          Request token URL	https://api.twitter.com/oauth/request_token
          Authorize URL	https://api.twitter.com/oauth/authorize
          Access token URL	https://api.twitter.com/oauth/access_token
         */

        // Debug mod açıkken api jslerini çağırmaz
        $config->twitter_api = true;

        #- Run SocialShell
        $social->load($config);
        $social->start_api();

//        CVarDumper::dump($social->getConfig(),10,1);
//        CVarDumper::dump($config->api_facebook->debug(),10,1);

        $this->render('twitter', array(
            'socialModule' => $social,
            'social' => $config
        ));
    }

}