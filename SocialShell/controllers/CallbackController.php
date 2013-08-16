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

        /*CVarDumper::dump(array(
            Yii::app()->session['in_callback_redirect_to'],
            Yii::app()->session['in_callback_started'],
            Yii::app()->session->getSessionID(),
        ),5,1);*/

        if (Yii::app()->session['in_callback_redirect_to']) {
            $target = Yii::app()->session['in_callback_redirect_to'];
//            Yii::app()->session['in_callback_started']
            $target = rtrim($target, '/') . '/?' . $this->callback_params . '=' . urlencode(http_build_query($_REQUEST, '', '&amp;'));

            unset(Yii::app()->session['in_callback_started']);
            unset(Yii::app()->session['in_callback_redirect_to']);
        } else {
//            ' . $script_file . '/
            $target = SocialConfig::getDomain() . '/callback/?count='.++$count;
            $target = SocialConfig::changeProtocole($target, $r->getIsSecureConnection() ? 'https' : 'http');
        }

        if(!$count)
        {
            $this->redirect($target);
        }
//
    }

}