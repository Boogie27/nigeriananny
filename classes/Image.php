<?php


class Image {
    
    public $_image = null,
           $_error = false,
           $_width = null,
           $_height = null,
           $_passed = false,
           $_newFileName = null,
           $_fileExt = null,
           $_fileTmpName = null,
           $_fileDestination = null;

    public function __construct()
    {
        
    }

    public function resize_image($image, $param)
    {
        if(count($param))
        {
            $this->_width = $param['width'];
            $this->_height = $param['height'];

            if(!$this->check($image, $param)->error())
            {
                $name = $param['name'];
                if($this->image_resource($image, $name)->passed())
                {
                   return true;
                }
            }
        }
        return $this;
    }





    public function image_resize($type, $width, $height)
    {
        $black_image = imagecreatetruecolor($this->_width, $this->_height);
        imagecopyresampled($black_image, $type, 0, 0, 0, 0, $this->_width, $this->_height, $width, $height);
        return $black_image;
    }

  

    public function image_resource($image, $name)
    {
        $fileName = $image['name'];
        $fileTmpName = $image['tmp_name'];

        $endExt = explode('.', $fileName);
        $fileExt = strtolower(end($endExt));
        $file_allowed = ['jpg','png', 'jpeg', 'gif'];

        if(in_array($fileExt, $file_allowed))
        {
            $this->_passed = false;
            $image_resource =  getimagesize($fileTmpName); //get original image width, height, size and extension
            $type = $image_resource[2];
            $orignal_width = $image_resource[0];
            $original_height = $image_resource[1];

           

            switch($type)
            {
                case IMAGETYPE_JPEG:
                    $resourceType = imagecreatefromjpeg($fileTmpName);
                    $imageLayer = $this->image_resize($resourceType, $orignal_width, $original_height);
                    imagejpeg($imageLayer, $this->_fileDestination.$name); //move resized image to new file destination
                    $this->_passed = true;
                break;

                case IMAGETYPE_PNG:
                    $resourceType = imagecreatefrompng($fileTmpName);
                    $imageLayer = $this->image_resize($resourceType, $orignal_width, $original_height);
                    imagejpeg($imageLayer, $this->_fileDestination.$name);  //move resized image to new file destination
                    $this->_passed = true;
                break;

                case IMAGETYPE_GIF:
                    $resourceType = imagecreatefromgif($fileTmpName);
                    $imageLayer = $this->image_resize($resourceType, $orignal_width, $original_height);
                    imagejpeg($imageLayer, $this->_fileDestination.$name);  //move resized image to new file destination
                    $this->_passed = true;
                break;
            }
        }
        return $this;
    }




    
    public function upload_image($image, $param)
    {      
        $this->_error = false;
        if(count($param))
        {
            if($this->check($image, $param)->passed())
           {
               if($this->move_file_to_destination($param)->passed())
               {
                   $this->_passed = true;
               }
           }
        }
           return $this;
    }





    public function check($image, $param)
    {
        $this->_error = false;
        if($image)
        {

            $fileName = $image['name'];
            $tmp_name = $image['tmp_name'];
            $fileType = $image['type'];
            $fileSize = $image['size'];
            $error = $image['error'];

            $size_allowed = $param['size_allowed'];
            $fileDestination = $param['file_destination'];
            
            $endExt = explode('.', $fileName);
            $fileExt = strtolower(end($endExt));
            $file_allowed = ['jpg', 'jpeg','png', 'gif', 'pdf', 'docx'];

            if(!in_array($fileExt, $file_allowed))
            {
                $this->_error = "File type must be jpg, jpeg, png or gif";
            }else if(!$error === 0)
            {
                $this->_error = "there was an error trying to upload this file!";
            }else if($fileSize > $size_allowed){
                $this->_error = "file size is too large!";
            }
            //  if(!$fileDestination)
            //  {
            //     $this->_error = "Image file destination is required";
            //  }else{

            //     if(!in_array('file_destination', array_keys($param)))
            //     {
            //         $this->_error = "Wrong file destination key";
            //     }else{
            //         $dest_name = explode('/', $fileDestination);
            //         $name = $dest_name[count($dest_name) - 2];
            //     }
            //  }
        }
        if(!$this->_error)
        {
            $this->_fileExt = $fileExt;
            $this->_fileTmpName = $tmp_name;
            $this->_newFileName = $param['name'];
            $this->_fileDestination = $fileDestination;
            $this->_passed = true;
        }
        return $this;
    }




  


    public function move_file_to_destination($destination)
    {
        $this->_error = false;
        if($destination)
        { 
            if(move_uploaded_file($this->_fileTmpName, $this->_fileDestination.$this->_newFileName))
            {
                $this->_passed = true;
            }else{
                $this->_error = "Error, image was not moved!";
            }
        }else{
            $this->_error = "There was no file destination!";
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




    public static function delete($path)
    {
        if($path)
        {
            if(file_exists($path))
            {
                if(unlink($path))
                {
                    return true;
                }
            }
        }
        return false;
    }




    public static function files($string)
    {
        if($string)
        {
            if(isset($_FILES[$string]))
            {
                if($_FILES[$string]['name'])
                {
                    return $_FILES[$string];
                }
            }
        }
        return false;
    }




    public static function exists($string)
    {
        if(isset($_FILES[$string]))
        {
            if(!empty($_FILES[$string]['name']))
            {
                return true;
            }
        }
        return false;
    }




    public function get_user_image($string = null)
    {
        if($string){
            if(Auth::is_loggedin())
            {
                return url($string);
            }
        }
        return url('/shop/images/users/demo.png');
    }






    public static function name($image, $name = null)
    {
        if($image)
        {
            $fileExt = explode('.', $_FILES[$image]['name']);
            $fileExt = end($fileExt);
            if($name)
            {
                return $fileName = $name.'_'.uniqid().'.'.$fileExt;
            }
            return $_FILES[$image]['name'].'.'.$fileExt;
        }
        return  false;
    }


    public static function ext($image)
    {
        $fileExt = explode('.', $_FILES[$image]['name']);
        $fileExt = end($fileExt);
        
        return $fileExt;
    }



    // end
}



// <?php
// if(Input::post('upload'))
// {
//     $file = Input::files('image');
//     $image = new Image();

//     $fileExt = explode('.', $file['name']);
//     $fileExt = end($fileExt);

//     $fileName = 'image_'.uniqid().'.'.$fileExt;
//     $images = $image->resize_image($file, [ 'name' => $fileName, 'width' => 70, 'height' => 70, 'size_allowed' => 1000000,'file_destination' => 'images/gallery_image/' ]);
        
//         p($images);
// }


//  <form action="cart.php" method="post" enctype="multipart/form-data">
//     <input type="file" name="image">
//     <button type="submit" name="upload">upload...</button>
// </form>