<?php include('../Connection.php');  ?>
<?php
if(!Input::exists('get') || !Input::get('nid'))
{
    return view('/admin-nanny/news-letters');
}
// ===========================================
// GET NEWS LETTER
// ===========================================
$news_letters = $connection->select('news_letters')->where('id', Input::get('nid'))->first();

// ============================================
    // app banner settings
// ============================================
$banner =  $connection->select('settings')->where('id', 1)->first();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        *{
            padding: 0px;
            margin: 0px;
        }
        .content{
            padding: 30px;
            background-color: rgb(240, 240, 240);
        }
          .news-header{
              text-align: center;
          }
          .container{
              width: 80%;
              margin: 0 auto;
              padding: 30px 20px;
              background-color: #fff;
          }
          .news-header h4{
              color: #333333;
              margin-top: 10px;
              font-family: Arial,Helvetica Neue,Helvetica,sans-serif;
              font-size: 30px;
          }
          h4, h3, h2, h1, h5, h6, p, li{
            color: #333333;
            margin: 0px;
            font-family: Arial,Helvetica Neue,Helvetica,sans-serif;
          }
          .content-header p{
              margin-top: 20px;
              text-align: center;
          }
          p.content-body{
             margin: 0 auto;
             margin-top: 20px;
          }
          .footer{
              padding: 50px 0px;
              text-align: center;
          }
          .footer ul{
              padding:0px;
              margin: 0px;
            list-style: none;
          }
          .footer ul li{
            
          }
          .footer-header{
              font-size: 20px;
          }
          .anchor{
              float: right;
              color: blue;
              text-decoration: none;
          }
          @media only screen and (max-width: 767px){
            .container{
                width: 90%;
                padding: 30px 10px;
            }
            .content{
                padding: 20px 0px;
                width: 95%;
                margin: 0 auto;
                background-color: rgb(240, 240, 240);
            }
          }
    </style>
</head>
<body>
     <div class="content">
        <div class="container">
            <div class="news-header">
                    <img src="<?= asset($banner->logo) ?>" alt="">
                    <h4><?= $banner->app_name ?></h4>
            </div>
            <div class="news-body">
                    <div class="content-header"><p><?= $news_letters->header?></p></div>
                    <p class="content-body"><?= $news_letters->body?></p>
                    <div class="footer">
                        <ul>
                            <li class="footer-header"><?= $banner->app_name ?></li>
                            <li><?= $banner->address ?></li>
                        </ul>
                    </div>
                    <a href="<?= url('/admin-nanny/news-letters') ?>" class="anchor">Back</a>
            </div>
        </div>
     </div>
</body>
</html>