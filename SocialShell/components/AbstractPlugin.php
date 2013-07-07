<?php

/**
 * Description of AbstractShell
 *
 * @author Erce Erözbek <erce.erozbek@gmail.com>
 */
abstract class AbstractPlugin {

    protected $api_object;

    /**
     * Return instance of the plugin class
     * @return $this->api_object
     */
    public function &getApi() {
        return $this->api_object;
    }

    /**
     * @param $api_object
     */
    public function setApi(&$api_object) {
        $this->api_object = & $api_object;
    }

    /**
     * Load Config
     * @param SocialConfig $config
     */
//    public function load(SocialConfig &$config) {
//        $this->setConfig($config);
//        $this->config->configure();
//
//        $this->loaded = true;
//    }

    /** ----------- * */
    protected static $actions = array();
    protected static $errors = array();

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

    public static function getErrors($key = false) {
        return $key ? (isset(self::$errors[$key]) ? self::$errors[$key] : false) : self::$errors;
    }

    public static function addError($key, $value, $group = 0) {
        $error = array(
            $key => $value
        );
        self::$errors[$group] = $error;
    }

    public static function cleanErrors() {
        self::$errors = array();
    }

    public static function getActions($key = false) {
        return $key ? (isset(self::$actions[$key]) ? self::$actions[$key] : false) : self::$errors;
    }

    public static function addAction($key, $value, $group = 0) {
        self::$actions[$group][$key][] = $value;
    }

    public static function cleanActions() {
        self::$actions = array();
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

    public static function get_protocole(){
        return Yii::app()->getRequest()->isSecureConnection ? 'https://' : 'http://';
    }

    public static function set_protocole($url){
        return preg_replace ('/http(s)?\:\/\//i', self::get_protocole(), $url);
    }

}
