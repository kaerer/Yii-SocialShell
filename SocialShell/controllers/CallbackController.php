<?php

class CallbackController extends Controller {

    public $layout = false;
    public $callback_params = 'callback_params';

    public function actionIndex() {
        $target = (Yii::app()->session['in_callback_redirect_to'] ? Yii::app()->session['in_callback_redirect_to'] : Yii::app()->request->getBaseUrl(true).'/'.Yii::app()->session['in_callback_started']);
        unset(Yii::app()->session['in_callback_started']);
        unset(Yii::app()->session['in_callback_redirect_to']);
        $target = trim($target, '/').'/?'.$this->callback_params.'='.urlencode(http_build_query($_REQUEST, '', '&amp;'));
        $this->redirect($target);
    }

}