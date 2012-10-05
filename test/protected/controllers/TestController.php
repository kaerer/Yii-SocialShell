<?php

/**
 * Test Controller
 * Social Shell Examples
 * @author Erce Erözbek <erce.erozbek@gmail.com>
 *
 * @property SocialShellModule $socialModule SocialShellModule instance
 * @property SocialConfig $socialConfig SocialConfig instance
 *
 */
class TestController extends Controller {

    public $socialModule = null;
    public $socialConfig = array();

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

    public function actionCleanSession() {
        if (YII_DEBUG) {
            Yii::app()->session->destroy();
            echo 1;
        }
    }

    public function actionFacebook() {

        $this->layout = '//test/layout';

        $this->socialModule = Yii::app()->getModule('SocialShell');
        /* @var $this->socialModule SocialShellModule */

        #- Set SocialShell Object
        $this->socialConfig = new SocialConfig();
        $this->socialConfig->facebook_api = true;
        $this->socialConfig->fb_page_name = 'BaseApps';
        $this->socialConfig->fb_app_id = '143684392442216';
        $this->socialConfig->fb_app_secret = '60e4cc8bf31016623bcfb514a8607e5b';
        $this->socialConfig->fb_permissions = 'user_likes, user_interests, user_birthday, user_hometown';

        #- Run SocialShell
        $this->socialModule->load($this->socialConfig);
        $this->socialModule->start_api();

        $this->socialConfig->share_url = $this->socialModule->obj_facebook->get_tabUrl();
        $this->socialConfig->share_image = $this->socialConfig->domain_url.'/images/socialshell/share.png';

        $this->render('facebook', array(
            'social' => $this->socialModule,
            'config' => $this->socialConfig
        ));
    }

    public function actionAnalytics() {

        $this->layout = '//test/layout';

        $this->socialModule = Yii::app()->getModule('SocialShell');

        #- Set SocialShell Object
        $this->socialConfig = new SocialConfig();
        $this->socialConfig->ga_code = '999999999';

        #- Run SocialShell
        $this->socialModule->load($this->socialConfig);
        $this->socialModule->start_api();

        $this->render('analytics', array(
            'social' => $this->socialModule,
            'config' => $this->socialConfig
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

        $this->render('twitter', array(
            'social' => $social,
            'config' => $config
        ));
    }

    public function actionInstagram() {

        $this->layout = '//test/layout';

        $social = Yii::app()->getModule('SocialShell');
        /* @var $social SocialShellModule */

        #- Set SocialShell Object
        $config = new SocialConfig();
        $config->instagram_api = true;
        $config->in_key = '065f5749a0eb43d0bb1225825722ee35';
        $config->in_secret = '4389446b8b7d47a1823937538dd26555';

        #- Run SocialShell
        $social->load($config);
        $social->start_api();

        $login_data = $social->obj_instagram->callback();

        $this->render('instagram', array(
            'social' => $social,
            'config' => $config
        ));
    }

    public function actionObject() {
        $post = $_POST;
        $status = false;
        $error = array(
            'name' => 'Boş Bırakmayınız'
        );

        if (count($post) > 0) {
            $status = true;
        } else {
            $error = true;
        }

        $result = array(
            'success' => $status,
            'error' => $error,
            'hooop' => 'Napıyon? :)',
            'post' => $post,
        );
        echo CJSON::encode($result);
    }

    public function actionSacinTarzin() {
        $this->layout = false;
        $this->render('//demo/sacintarzin');
    }

}