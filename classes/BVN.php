<?php

class BVN extends DB{

    private $_error = false,
            $_passed = false,
            $_result = null,
            $_paystack = null,
            $_pdo = null;
 
    public function __construct()
    {
        $this->_pdo = self::instantiate();
        $settings =   $this->_pdo->select('settings')->where('id', 1)->first();
        $this->_paystack = $settings->is_paystack_activate ? $settings->paystack_secrete : $settings->paystack_public;
    }

    public function verify($param)
    {
        $last_name = isset($param['last_name']) ? $param['last_name'] : null;
        $first_name = isset($param['first_name']) ? $param['first_name'] : null;
        $middle_name = isset($param['middle_name']) ? $param['middle_name'] : null;

        $url = "https://api.paystack.co/bvn/match";

        $fields = [
            'bvn' => $param['bvn'],
            'account_number' => $param['account_number'],
            'bank_code' => $param['bank_code'],
            'first_name' => $first_name,
            'last_name' => $last_name,
            'middle_name' => $middle_name
        ];
        
        $fields_string = http_build_query($fields);
        
        //open connection
        $ch = curl_init();
        
        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer ".$this->_paystack,
            "Cache-Control: no-cache",
        ));
        
        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
        
        //execute post
        $result = curl_exec($ch);
        echo $result;
    }



    public function paystack_key()
    {
        return $this->_paystack;
    }


    public static function get_bank_code()
    {
        // get bank code using rest api
    }
}









// $fields = [
//     'bvn' => "xxxxxxxxxxx",
//     'account_number' => '0001234567',
//     'bank_code' => '058',
//     'first_name' => "Jane",
//     'last_name' => 'Doe',
//     'middle_name' => 'Loren'
//   ];