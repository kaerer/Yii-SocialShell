<?php

class CallbackController extends Controller
{

    public $layout = false;
    public $callback_params = 'callback_params';

    public function actionIndex()
    {
//        $script_file = end(explode('/', Yii::app()->request->scriptFile));
        $r = Yii::app()->request;
        $count = $r->getParam('count');

//        if (YII_DEBUG) {
//            CVarDumper::dump(array(
////                $_SESSION,
//                Yii::app()->session['in_callback_redirect_to'],
//                Yii::app()->session['in_callback_started'],
//                Yii::app()->session->getSessionID(),
//            ), 5, 1);
//            exit();
//        }

        $target = Yii::app()->session['in_callback_redirect_to'];
        unset($_REQUEST['count']);
        if ($target) {
//            Yii::app()->session['in_callback_started']
            $target = rtrim($target, '/') . '/?' . $this->callback_params . '=' . urlencode(http_build_query($_REQUEST, '', '&amp;'));

//            unset(Yii::app()->session['in_callback_started']);
//            unset(Yii::app()->session['in_callback_redirect_to']);
        } else {
//            ' . $script_file . '/
            $target = SocialConfig::getDomain() . '/callback/?' . http_build_query($_REQUEST, '', '&amp;') . '&count=' . ++$count;
            $target = SocialConfig::changeProtocole($target, $r->getIsSecureConnection() ? false : true);
        }

        if ($count < 3) {
            $this->redirect($target);
        }
//
    }

}