<?php
// ==========================================================================================================================================================================
//            WHERE CLAUSE USING MEHTOD CHAINING
// ==========================================================================================================================================================================
/*
*  list of methods to chain
*  ------------------------
*
*  1) Where
*  2) Limit
*  3) Random
*  4) LeftJoin
*  5) Get
*  6) First

   To insert into database
   ------------------------
   7) Create
   8) Save
*/



class DB{
    
    
private static $_instance = null;
private $_query,
        $_pdo,
        $_sql,
        $_result = null,
        $_passed = false,
        $_value = array(),
        $_error = array();

   

public function __construct()
{
    $connect = $GLOBALS['mysql'];
    try{
        $this->_pdo = new PDO('mysql:host='.$connect['host'].'; dbname='.$connect['db'], $connect['username'], $connect['password']);
        // echo "connected";
    }catch(PDOExecption $e){
        die($e->getMessage());
    }
}



public static function instantiate()
{
    if(!isset(self::$_instance))
    {
        self::$_instance = new DB();
    }
    
    return self::$_instance;
}


   





public function select($table)
{
    if($table)
    {
        $query = "SELECT * FROM {$table}";
        $this->_query = $query;
    }
    return $this;
}








public function where($field, $parameter_1, $parameter_2 = null)
{
    $query = null;
    $old_query = explode(' ', $this->_query);
    $operators = ['>', '<', '>=','=' , '<=', 'RLIKE'];

    if(!$parameter_2)
    {
        if(!in_array('WHERE', $old_query)){
            $query = " WHERE $field = ?";
        }else{
            $query = " AND $field = ?";
        }
        $this->_value[] = $parameter_1;
    }else if($parameter_2)
    {
        try{
            if(!in_array($parameter_1, $operators)){
                throw new Exception('The operator ( '.$parameter_1.' ) does not exists in operators array on WHERE CLAUSE');
            }
        }catch(Exception $e)
        {
            echo " Message: ".$e->getMessage();
            die();
        }


        if(!in_array('WHERE', $old_query))
        {
            if($parameter_1 == 'RLIKE')
            {
                $query = " WHERE $field $parameter_1 '^[{ $parameter_2 }].*$'";
            }else{
                $query = " WHERE $field $parameter_1 ?";
            }
        }else{
            $query = " AND $field $parameter_1 ?";
        }     
        
        $this->_value[] = $parameter_2;
    }

    $this->_query .= $query;
    return $this;
}






public function query()
{
    $this->_error = false;
    if($this->_sql = $this->_pdo->prepare($this->_query))
    {
        if($this->_value)
        {
            $x = 1;
            foreach($this->_value as $values)
            {
                $this->_sql->bindValue($x, $values);
                $x++;
            }
        }
    
        if($this->_sql->execute())
        {
            $this->_result = $this->_sql->fetchAll(PDO::FETCH_OBJ);
        }else{
            try{
               throw new Exception("Field does not exists in the database table or an error occoured!");
            }catch(Exception $e)
            {
                echo " Message: ".$e->getMessage();
                die();
            }
        }
    }
    return true;
}




public function get()
{
    if($this->query())
    {
        $result =  $this->_result;
        $this->init(); //initialize the class
        return $result;
    }
  return false;
}




// initialize DB class
private function init()
{
    $this->_result = null;
    $this->_sql = null;
    $this->_value = null;
    $this->_query = null; //comment this
}








public function first()
{
    if($this->query())
    {
        $result = null;
        if(count($this->_result))
        {
            $result =  $this->_result[0];
        }
        $this->init(); //initialize the  class
        return $result;
    }
  return false;
}






public function limit($start = null, $end = null)
{
    $query = '';
    if($start && $end)
    {
        $query = " LIMIT $start, $end";
    }else if($start == 0 && $end){
        $query = " LIMIT $end";
    }else{
        $query = " LIMIT $start";
    }
    $this->_query .= $query;
    return $this;
}






public function orderBy($column_1 = null, $column_2 = null)
{
    $query = '';
    $array = ['DESC', 'ASC'];
    if($column_1 && !in_array($column_1, $array))
    {
        $query .= " ORDER BY ".$column_1;
    }

    if($column_2 && !in_array($column_2, $array))
    {
        $query .= ", ".$column_2;
    }else{
        $query .= " ".$column_2;
    }

    $this->_query .= $query;
    return $this;
}








public function random()
{
    if($this->_query)
    {
        $query = " ORDER BY RAND()";
    }
    $this->_query .= $query;
    return $this;
}






public function paginate($page_number)
{
    if($page_number)
    {
        $page_url = current_url();
        $page = Input::exists('get') && Input::get('page') ? Input::get('page') : 1;
        $start = ($page - 1) * $page_number;
        $product = $this->limit($start, $page_number);
        
        $this->_paginateValues = $this->_value;
        $this->_paginateQuery = $this->_query;
        if($this->query())
        {
            $this->_pageNumber = $page_number;
            $this->_paginationResult = $this->_result;
            $this->_sql = null;
            $this->_value = null;
            $this->_result = null;
            return $this;
        }
    }
    return false;
}




public function result()
{
    return $this->_paginationResult;
}






public function links()
{
    if($this->_paginateQuery)
    {
    
        $this->_pageNumber;  //number of page
        $array = explode('LIMIT', $this->_paginateQuery)[0];
        
        $this->_query = $array;
        $this->_value = $this->_paginateValues;
        
        if($this->query())
        {
            $result =  $this->_result;
            if($count = count($result))
            {
                // print_r($result);
                $this->_paginateValues = null;
                $this->init(); //initialize the class
                $this->compute_pagination_links($count);            
            }
        }

    }
}






public function compute_pagination_links($count)
{
    if($count > $this->_pageNumber)
    {
        $total = ceil($count / $this->_pageNumber);
        $page = Input::exists('get') && Input::get('page') ? Input::get('page') : 1;
    
        $page_url = current_url();
    
    
        $pagination = '';
    
        $pagination .=  '<div class="col-lg-12">
                            <div class="mbp_pagination">
                                <ul class="page_navigation">
                                    <li class="page-item disable">';
                                    if($page > 1)
                                    {
        $pagination     .=             '<a class="page-link" href="'.$this->load_page($page, 'minus').'" tabindex="-1" aria-disabled="tru"> <span class="flaticon-left-arrow"></span> Prev</a>';                                     
                                    }
        $pagination     .=          '</li>';
                                    for($x = 1; $x <= $total; $x++)
                                    {
                                        if(Input::exists('get'))
                                        {
                                            if(!array_key_exists('page', $_GET))
                                            {
                                                $page_url = current_url().'&page='.$x;   
                                            }else{
                                                $page_url = $this->load_page($x);
                                            }
                                        }else{
                                            $page_url = current_url().'?page='.$x; 
                                        }
                                        
                                        $active = $page == $x ? 'active' : '';
                                        $pagination .=  '<li class="page-item '.$active.'"><a class="page-link" href="'.$page_url.'">'.$x.'</a></li>';
                                    }
                                
        $pagination .=              '<li class="page-item">';
                                    if($page < $total)
                                    {
        $pagination .=                 '<a class="page-link" href="'.$this->load_page($page, 'plus').'">Next <span class="flaticon-right-arrow-1"></span></a>';
                                    }
        $pagination .=              '</li>
                                </ul>
                            </div>
                        </div>';

        echo $pagination;
        
   }
}






public function load_page($index, $action = null)
{
    if(Input::exists('get') && Input::get('page'))
    {
        if($index)
        {
            $pageUrl_array = $_GET;
            $replace = 'page='.$index;
            if($action && $action == 'plus')
            {
                $index++;
                $replace = 'page='.$index;
            }else if($action && $action == 'minus')
            {
                $index--;
                $replace = 'page='.$index;
            }

            $find = 'page='.$pageUrl_array['page'];
            $page_url = str_replace($find, $replace, current_url());
            return $page_url;
        }
    }else if(Input::exists('get') && !Input::get('page') && $action == 'plus')
    {
        return current_url().'&page=2';
    }else if(!Input::exists('get') && $action == 'plus')
    {
        return current_url().'?page=2';
    }

   
    return false;
}



















public function leftJoin($table, $field_1, $operator, $field_2)
{
    if($table)
    {
        $query = '';
        $operators = ['>', '<', '>=','=' , '<=', 'RLIKE'];

        try{
            if(!in_array($operator, $operators)){
                throw new Exception('The operator ( '.$parameter_1.' ) does not exists in operators array in LEFTJOIN');
            }
        }catch(Exception $e)
        {
            echo " Message: ".$e->getMessage();
            die();
        }

        if(in_array($operator, $operators))
        {
            $query = " LEFT JOIN $table ON $field_1 $operator $field_2";
            $this->_query .= $query;
        }
    }
    return $this;
}






public function greatest($table_1, $table_2)
{
    
}








public function create($table, $params = array())
{
    if($table)
    {
        $value = "";
        $keys = array_keys($params);
        $x = 1;
        foreach($params as $param => $key){
           $value .= " ? ";
           if($x < count($params)){
               $value .= ", ";
           }
           $x++;
        }
        $query = "INSERT INTO {$table} (".implode(",", $keys).") VALUE($value)";
        $this->_query = $query;
        $this->_value = $params;

        if($this->query())
        {
            $this->_passed = true;
            $this->init(); //initialize the  class
        }
    }
    return $this;
}





public function update($table, $params = array())
{
    if(count($params)){
        $values = "";
        $itemsValue = array();
        $x = 1;
        foreach($params as $param => $keys){
            $values .= "{$param} = ?";
            if($x < count($params)){
                $values .= ", ";
            }
            $x++;
        }
        $query = "UPDATE {$table} SET {$values}";
        $this->_query = $query;
        $this->_value = $params;
    }
    return $this;
}







public function save()
{
    if($this->_query)
    {
        if($this->query())
        {
            $this->_passed = true;
            $this->init(); //initialize the  class
        } 
    }
  return $this;
}





public function delete($table)
{
    if($table)
    {
        $this->_query = "DELETE FROM {$table}";
        return $this;
    }
    return false;
}




public function passed()
{
    return $this->_passed;
}









public function validate($parameters = array())
{
    $this->_validate_error = null;
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
                    $this->_validate_error[$key] = '*'.$field_name.' is required';
                }else if(!empty($_POST[$key]) && $cond == 'email' && !filter_var($_POST[$key], FILTER_VALIDATE_EMAIL)){
                    $this->_validate_error[$key] = '*Wrong email format';
                }else{
                    $columns = explode(':', $cond);
                    foreach($columns as $val)
                    {
                        switch($columns[0])
                        {
                            case 'min':
                                if(!empty($_POST[$key]) && strlen($_POST[$key]) < $columns[1])
                                {
                                    $this->_validate_error[$key] = '*'.$field_name.' must be a minimum of '.$columns[1].' characters';
                                }
                            break;

                            case 'max':
                                if(!empty($_POST[$key]) && strlen($_POST[$key]) > $columns[1])
                                {
                                    $this->_validate_error[$key] = '*'.$field_name.' must be a maximum of '.$columns[1].' characters';
                                }
                            break;

                            case 'unique':
                                $unique_email = self::select($columns[1])->where('email', '=', $_POST[$key])->get(); //after checking this email it will clear all the error message thats why i used $this->_validate_error
                                if(count($unique_email))
                                {
                                    $this->_validate_error[$key] = '*Email already exists';
                                }
                            break;

                            case 'match':
                                if($_POST[$key] != $_POST[$columns[1]])
                                {
                                    $this->_validate_error[$key] = '*'.$field_name.' must match '.$columns[1];
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
        
        if(!empty($this->_validate_error))
        {
            Session::put('old', $_POST);
            Session::errors('errors', $this->_validate_error);
            return Redirect::back();
        }else{
            Session::delete('old');
            $this->_passed = true;
        }
    }
    return $this;
}













     //get instance here
}