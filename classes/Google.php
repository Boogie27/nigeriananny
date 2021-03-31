<?php

class Google{
    
    private $_google = null,
            $_authUrl;

    public function __construct()
    {
        $this->_google = new Google_client();

        $this->_google->setClientId('653369834194-p048lc6ed0iep6m1bplejlbvirm2beko.apps.googleusercontent.com');
        $this->_google->setClientSecret('1yUaw8xTZL5FOg7ow61PvaZw');
        $this->_google->setRedirectUri('http://localhost/JOB/nigeriananny/');
        $this->_google->addScope('email');
        $this->_google->addScope('profile');
        $this->_authUrl =  $this->_google->createAuthUrl();
    }



  



    public function auth_url()
    {
        return $this->_authUrl;
    }


    public function auth_code($code)
    {
        return $this->_google->fetchAccessTokenWithAuthCode($code);
    }


    public function token($code)
    {
        return $this->_google->setAccessToken($code);
    }


    public function data()
    {
        $google_service = new Google_Service_Oauth2($this->_google);
        return $google_service->userinfo->get();
    }
}













// 653369834194-p048lc6ed0iep6m1bplejlbvirm2beko.apps.googleusercontent.com


// 1yUaw8xTZL5FOg7ow61PvaZw