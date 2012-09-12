<?php

/**
 * Description of SocialConfig
 *
 * @author erce
 */
class SocialConfig extends SocialConfigBox {

    public function configure() {
//        $this->domain_url = 'https://'.Yii::app()->request->getServerName();
        $this->domain_url = Yii::app()->getBaseUrl(true);

        $this->fb_page_url = 'https://www.facebook.com/'.$this->fb_page_name;

        if ($this->fb_app_id)
            $this->fb_tab_url = $this->fb_page_url.'/app_'.$this->fb_app_id;

        switch (TRUE) {
            case (bool)$this->fb_tab_url:
                $this->share_url = $this->fb_tab_url;
                break;
            default:
                $this->share_url = $this->fb_canvas_url;
                break;
        }
    }

}