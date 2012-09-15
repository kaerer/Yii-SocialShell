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

    public function actionSocial(){

        $this->layout = '//layouts/facebook';

        $social = Yii::app()->getModule('SocialShell');
        /* @var $social SocialShellModule */

        #- Set SocialShell Object
        $config = new SocialConfig();
        $config->facebook_api = true;
        $config->fb_page_name = 'BaseApps';
        $config->fb_app_id = '143684392442216';
        $config->fb_app_secret = '60e4cc8bf31016623bcfb514a8607e5b';
        $config->twitter_api = true;
        $config->ga_code = '31';

        #- Run SocialShell
        $social->load($config);
        $social->start_api();

//        CVarDumper::dump($social->getConfig(),10,1);
//        CVarDumper::dump($config->api_facebook->debug(),10,1);

        $this->render('social', array(
            'socialModule' => $social,
            'social' => $config
        ));

    }

}