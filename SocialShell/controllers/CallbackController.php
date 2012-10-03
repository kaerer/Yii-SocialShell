<?php

class CallbackController extends Controller {
    public $layout = false;

    public function actionIndex() {
        $target = '/'.Yii::app()->session['tmp_callback_target'];
        unset(Yii::app()->session['tmp_callback_target']);
        $this->redirect($this->createAbsoluteUrl($target, array(
            'callback_params' => $_REQUEST
        )));
    }

}