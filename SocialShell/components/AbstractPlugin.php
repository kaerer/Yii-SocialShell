<?php

/**
 * Description of AbstractShell
 *
 * @author Erce Erözbek <erce.erozbek@gmail.com>
 */
abstract class AbstractPlugin extends AbstractBase{

    protected $api_object = false;

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

}
