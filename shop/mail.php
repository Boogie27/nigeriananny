<?php include('../Connection.php');  ?>

<?php 

$app =  $connection->select('settings')->where('id', 1)->first();
?>






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        body{
            font-family: 'poppins', sans-serif;
            color: #555;
        }
        body a{
            color: #555; 
            text-decoration: none;
        }
        .container{
            width: 60%;
            margin: 0 auto;
            padding: 50px 0px;
        }
        .msg-header{
            width: 100%;
            margin-bottom: 50px;
            text-align: center;
        }
        .msg-header img{
            width: 50px;
            height: 50px;
            border-radius: 3px;
        }
        .msg-header h3{
            margin: 0px;
            font-size: 25px;
            letter-spacing: 2px;
        }
        .mgs-body p{
           text-align: center;
        }
        /* ********* FOOTER *********** */
        .bottom-footer{
            width: 100%;
            margin-top: 100px;
            padding: 60px 0px 10px 0px;
            background-color: rgb(246, 246, 246);
        }
        .bottom-footer .rights{
            font-size: 13px;
            text-align: center;

        }
        ul.ul-footer{
            padding-left: 0px;
            text-align: center;
        }
        ul.ul-footer li{
            margin: 0px 5px;
            font-size: 12px;
            display: inline-block;
            padding: 1px 10px;
            border-radius: 2px;
            margin-bottom: 5px;
            border: 1px solid #ccc;
        }
        .bottom-footer .rights{
            font-size: 10px;

        }

        @media only screen and (max-width: 992px){
            .container{
                width: 80%;
            }
        }
        @media only screen and (max-width: 767px){
            .container{
                width: 90%;
            }
        }
        @media only screen and (max-width: 567px){
            ul.ul-footer li{
                font-size: 9px;
                padding: 5px;
            }
            .bottom-footer .rights{
                font-size: 9px;
            }
            .container{
                width: 100%;
            }
            .msg-header img{
                width: 40px;
                height: 40px;
            }
            .mgs-body p{
                font-size: 12px;
            }
        }
        
    </style>
</head>
<body>
    <div class="container">
        <div class="msg-header">
            <img src="<?= asset($app->logo)?>" alt="<?= $app->app_name ?>">
            <h3><?= $app->app_name ?></h3>
        </div>
        <div class="mgs-body">
            <p>
                Thank you for shopping with nigeria nanny. <br>
                We have recieved Your order and it would be attended to shortly.
            </p>
        </div>
        
        <div class="bottom-footer">
            <ul class="ul-footer">
                <li><a href="<?= url('/') ?>">Find a worker</a></li>
                <li><a href="<?= url('/privacy') ?>">Privacy Policy</a></li>
                <li><a href="<?= url('/terms') ?>">Terms & Conditions</a></li>
                <li><a href="<?= url('/about') ?>">About us</a></li>
                <li><a href="<?= url('/shop') ?>">Market place</a></li>
                <li><a href="<?= url('/courses') ?>">Download courses</a></li>
                <li><a href="<?= url('/contact') ?>">Contact</a></li>
            </ul>
            <div class="rights"><?= $app->alrights?></div>
        </div>
    </div>
</body>
</html>




