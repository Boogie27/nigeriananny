<?php


class Facebook{

    private $_facebook = null;


    public function __construct()
    {
        $this->_facebook = new \Facebook\Facebook(['app_id' => '268608751649972','app_secret' => '2b29b58cddf417552c5abe64c7373000', 'default_graph_version' => 'v2.10']);
    }



    public function facebook_helper()
    {
       return $this->__facebook = $this->_facebook->getRedirectLoginHelper();
    }



    public function login_url()
    {
        return $this->facebook_helper()->getLoginUrl('http://localhost/JOB/nigeriananny/', array('email'));
    }


    public function access_token()
    {
        return $this->facebook_helper()->getAccessToken();
    }

    public function user_data()
    {
        $token = $this->access_token();
        $this->_facebook->setDefaultAccessToken($token);
        $info = $this->_facebook->get('/me?fields=name,email', $token);

        return $info->getGraphUser();
    }

}