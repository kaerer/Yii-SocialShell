<?php

/**
 * Description of AbstractShell
 *
 * @author Erce Erözbek <erce.erozbek@gmail.com>
 */
abstract class AbstractShell {

    private $actions = array();
    private $errors = array();

    /**
     * Config Data
     * @var SocialConfig
     */
    protected $config;

    /**
     * is config loaded
     * @var bool
     */
    protected $loaded = false;

    /**
     * Get Config
     * @return SocialConfig
     */
    public function getConfig($all = false) { //& referans kullanmak
        if ($all)
            return $this->config;

        $config = array();
        foreach ($this->config as $k => $v) {
            if ($v)
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
    public function load(SocialConfig &$config) {
        $this->setConfig($config);
        $this->config->configure();

        $this->loaded = true;
    }

    public function getErrors() {
        return $this->errors;
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

    public function getActions() {
        return $this->actions;
    }

    public function addAction($key, $value, $group = 0) {
        $action = array(
            $key => $value
        );
        $this->actions[$group] = $action;
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

}
