<?php

/**
 * Description of SocialConfig
 *
 * @author erce
 */
class SocialConfig extends SocialConfigBox {

    public function configure() {
//        $this->domain_url = 'https://'.Yii::app()->request->getServerName();
//        $this->domain_url = str_replace('http://', 'https://', Yii::app()->getBaseUrl(true));
        $this->domain_url = self::getDomain();
    }

    public static function getDomain(){
        return Yii::app()->getBaseUrl(true);
    }

    public static function changeProtocole($target, $toSecure){
        if ($toSecure) {
//                $r->getIsSecureConnection() ? 'https' : 'http';
            $target = str_replace('http://', 'https://', $target);
        } else {
            $target = str_replace('https://', 'http://', $target);
        }

        return $target;
    }


}