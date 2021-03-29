<?php




class Validator{

public $_error = null,
       $_passed = false;



public function validate($parameters = array())
{
    $this->_error = null;
    $this->_passed = false;
    
    if(count($parameters))
    {
        foreach($parameters as $key => $param)
        {
            $field = explode('_', $key);
            $field_name = implode(' ', $field);

            $conditions = explode('|', $param);
            foreach($conditions as $cond)
            {
                if($cond == 'required' && empty($_POST[$key]))
                {
                    $this->_error[$key] = '*'.$field_name.' is required';
                }else if(!empty($_POST[$key]) && $cond == 'email' && !filter_var($_POST[$key], FILTER_VALIDATE_EMAIL)){
                    $this->_error[$key] = '*Wrong email format';
                }else{
                    $columns = explode(':', $cond);
                    foreach($columns as $val)
                    {
                        switch($columns[0])
                        {
                            case 'min':
                                if(!empty($_POST[$key]) && strlen($_POST[$key]) < $columns[1])
                                {
                                    $this->_error[$key] = '*'.$field_name.' must be a minimum of '.$columns[1].' characters';
                                }
                            break;

                            case 'max':
                                if(!empty($_POST[$key]) && strlen($_POST[$key]) > $columns[1])
                                {
                                    $this->_error[$key] = '*'.$field_name.' must be a maximum of '.$columns[1].' characters';
                                }
                            break;

                            case 'match':
                                if($_POST[$key] != $_POST[$columns[1]])
                                {
                                    $this->_error[$key] = '*'.$field_name.' must match '.$columns[1];
                                }
                            break;

                            case 'unique':
                                $connection = new DB();
                                $unique_email = $connection->select($columns[1])->where('email', $_POST[$key])->first();
                                if($unique_email)
                                {
                                    $this->_error[$key] = '*Email already exists';
                                }
                            break;

                            case 'number':
                                if(!is_numeric($_POST[$key]))
                                {
                                    $this->_validate_error[$key] = '*'.$field_name.' must be of numbers';
                                }
                            break;
                        }
                    }
                }
            }
        }
        
        if(empty($this->_error))
        {
            $this->_passed = true;
        }
    }
    return $this;
}




public function error()
{
    return $this->_error;
}



public function passed()
{
    return $this->_passed;
}


    // end
}