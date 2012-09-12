<?php

/**
 * Description of AbstractShell
 *
 * @author erce
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
    public function getConfig() { //& referans kullanmak
        return $this->config;
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

    public function getErrors(){
        return $this->errors;
    }

    public function addError($key, $value, $group = 0){
        $error = array(
            $key => $value
        );
        $this->errors[$group] = $error;
    }

    public function cleanErrors(){
        $this->errors = array();
    }

    public function getActions(){
        return $this->actions;
    }

    public function addAction($key, $value, $group = 0){
        $action = array(
            $key => $value
        );
        $this->actions[$group] = $action;
    }

    public function cleanActions(){
        $this->actions = array();
    }

}
