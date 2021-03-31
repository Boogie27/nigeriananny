<?php


class Facebook{

    private $_facebook = null;


    public function __construct()
    {
        $this->_facebook = new \Facebook\Facebook(['app_id' => '268608751649972','app_secret' => '2b29b58cddf417552c5abe64c7373000', 'default_graph_version' => 'v2.10']);
    }



    public function login_url()
    {
        return $this->_facebook->getRedirectLoginHelper()->getLoginUrl('https://greenhouses-pro.co.uk/demo/', array('email'));
    }


}