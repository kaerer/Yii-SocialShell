<?php

/**
 * Description of AbstractShell
 *
 * @author Erce Erözbek <erce.erozbek@gmail.com>
 */
abstract class AbstractBase{

    protected $actions = array();
    protected $errors = array();

    /**
     * Config Data
     * @var SocialConfig
     */
    public $config;

    /**
     * is config loaded
     * @var bool
     */
    public $loaded = false;

    abstract function start_api();
    /**
     * Get Config
     * @return SocialConfig
     */
    public function getConfig($all = false) { //& referans kullanmak
        if ($all)
            return $this->config;

        $config = array();
        foreach ($this->config as $k => $v) {
            if ($v !== null)
                $config[$k] = $v;
        }

        return $config;
    }

    /**
     * Set Config
     * @param SocialConfig $config
     */
    public function setConfig(SocialConfig &$config) {
        $this->config = &$config;
    }

    /**
     * Load Config
     * @param SocialConfig $config
     */
    public function loadConfig(SocialConfig &$config) {
        $this->setConfig($config);
        $this->config->configure();

        $this->loaded = true;
    }

    public function getErrors($key = false) {
        return $key ? (isset($this->errors[$key]) ? $this->errors[$key] : false) : $this->errors;
    }

    public function addError($key, $value, $group = 0) {
        $error = array(
            $key => $value
        );
        $this->errors[$group] = $error;
    }

    public function cleanErrors() {
        $this->errors = array();
    }

    public function getActions($key = false) {
        return $key ? (isset($this->actions[$key]) ? $this->actions[$key] : false) : $this->errors;
    }

    public function addAction($key, $value, $group = 0) {
        $this->actions[$group][$key][] = $value;
    }

    public function cleanActions() {
        $this->actions = array();
    }

    public static function redirect($target, $js = true) {
        if (!headers_sent() && $js == false) {
            header('Location: '.$target);
            exit();
        }
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">top.location.href = "'.$target.'";</script>';
        exit();
    }

    public function debug() {
        return array('IDS' => $this->getActions(), 'ERRORS' => $this->getErrors());
    }

    public static function setCookie($name, $value) {
        $cookie = new CHttpCookie($name, $value);
        $cookie->expire = time() + 604800; //60 * 60 * 24 * 7;
        Yii::app()->request->cookies[$name] = $cookie;
    }

    public static function getCookie($name) {
        return isset(Yii::app()->request->cookies[$name]) ? Yii::app()->request->cookies[$name]->value : false;
    }

    public static function getSession($name) {
        return isset(Yii::app()->session[$name]) ? Yii::app()->session[$name] : false;
    }

    public static function setSession($name, $value) {
        Yii::app()->session[$name] = $value;
    }


}
