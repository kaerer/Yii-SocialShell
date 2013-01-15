<?php

/**
 * Description of SocialConfig
 *
 * @author erce
 */
class SocialConfig extends SocialConfigBox {

    public function configure() {
//        $this->domain_url = 'https://'.Yii::app()->request->getServerName();
        $this->domain_url = str_replace('http://', 'https://', Yii::app()->getBaseUrl(true));
    }

}