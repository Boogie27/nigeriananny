<?php
use PHPMailer\PHPMailer\PHPMailer;

require_once("../PHPMailer/Exception.php");
require_once("../PHPMailer/PHPMailer.php");
require_once("../PHPMailer/SMTP.php");


class Mail{

   private $_mailer,
           $_passed = false,
           $_error = false,
           $_fromEmail = null,
           $_name = null,
           $_smtpHost = null,
           $_smtpPort = null,
           $_userName = null,
           $_smtpPassword = null;


    public function __construct()
    {
        $this->_mailer = new PHPMailer();
        $this->get_information();
    }


    public function get_information(){
        $connection = new DB();
        $emailSettings =  $connection->select('settings')->where('id', 1)->first();
        if($emailSettings)
        {
            $this->_name = $emailSettings->from_name ? $emailSettings->from_name : null;
            $this->_fromEmail = $emailSettings->from_email ? $emailSettings->from_email : null;
            $this->_smtpHost = $emailSettings->smtp_host ? $emailSettings->smtp_host : null;
            $this->_smtpPort = $emailSettings->smtp_port ? $emailSettings->smtp_port : null;
            $this->_userName = $emailSettings->smtp_username ? $emailSettings->smtp_username : null;
            $this->_smtpPassword = $emailSettings->smtp_password ? $emailSettings->smtp_password : null;
        }
    }


    public function mail($params = array())
    {
        $this->_error = false;
        if(count($params))
        {
            if(empty($this->_name))
            {
                $this->_error[] = ['name' => 'Sender name is required for sedning email'];
            }
            if(empty($params['to']))
            {
                $this->_error[] = ['to' => 'Recipient email is required'];
            }
            if(empty($this->_fromEmail))
            {
                $this->_error[] = ['from' => 'Sender email is required'];
            }
        }
        if(empty($this->_error))
        {
            $this->_passed = true;
            $this->_toEmail = $params['to'];
            $this->_image =  !empty($params['image']['name']) ? $params['image'] : '';
            $this->_body =   !empty($params['body']) ? $params['body'] : '';
            $this->_subject = !empty($params['subject']) ? $params['subject'] : '';
        }
        return $this;
    }

// smtp_port = 465


    public function send_email()
    {
        $this->_mailer->isSMTP();
        $this->_mailer->Host = $this->_smtpHost;
        $this->_mailer->SMTPAuth = true;
       
        $this->_mailer->Username = $this->_userName;
        $this->_mailer->Password = $this->_smtpPassword;
        $this->_mailer->Port = $this->_smtpPort;
        $this->_mailer->SMTPSecure = 'ssl';

        // // /email settings
         $this->_mailer->isHTML(true);
         $this->_mailer->setFrom($this->_fromEmail, $this->_name);
         $this->_mailer->addAddress($this->_toEmail);
         $this->_mailer->Subject =  $this->_subject;
         $this->_mailer->Body =  $this->_body;
         if(!empty($this->_image))
         {
            $this->_mailer->addAttachment($this->_image['tmp_name'], $this->_image['name']);
         }

        if(empty($this->error_count))
        {
           if($this->_mailer->send())
           {
               return true;
           }
        }
        return false;
    }


   

    public function error()
    {
        return $this->_error;
    }



    public function passed()
    {
        return $this->_passed;
    }


}