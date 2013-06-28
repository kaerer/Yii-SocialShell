<?php

class CallbackController extends Controller {

    public $layout = false;
    public $callback_params = 'callback_params';

    public function actionIndex() {
        $target = Yii::app()->session['in_callback_redirect_to'] ?
            Yii::app()->session['in_callback_redirect_to'] :
            Yii::app()->request->getBaseUrl(true).'/'.Yii::app()->session['in_callback_started'];

        unset(Yii::app()->session['in_callback_started']);
        unset(Yii::app()->session['in_callback_redirect_to']);
        $target = rtrim($target, '/').'/?'.$this->callback_params.'='.urlencode(http_build_query($_REQUEST, '', '&amp;'));

        if(Yii::app()->session['in_callback_redirect_protocol'] == 'http'){
            $target = str_replace('https://', 'http://', $target);
        } else{
            $target = str_replace('http://', 'https://', $target);
        }

        $this->redirect($target);
    }

}