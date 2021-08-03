<?php include('../Connection.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  return Redirect::to('login.php');
}


$transactions = $connection->select('shop_transactions')->leftJoin('users', 'shop_transactions.buyer_id','=', 'users.id')
                      ->where('transaction_is_cancled', 0)->paginate(50);




$subAmount = 0;
$cancleAmount = 0;

$amounts = $connection->select('paid_products')->get();
if($amounts)
{
    foreach($amounts as $amount)
    {
        $subAmount += $amount->total_price;
        $cancleAmount += $amount->price * $amount->cancled_quantity;
    }
}

$totalEarnings = $subAmount - $cancleAmount;


// app banner settings
$banner =  $connection->select('settings')->where('id', 1)->first();
?>


<?php include('includes/header.php'); ?>


<!-- Main Header Nav -->
<?php include('includes/navigation.php') ?>

<!-- Main Header Nav For Mobile -->
<?php include('includes/mobile-navigation.php') ?>


<!-- Our Dashbord Sidebar -->
<?php include('includes/side-navigation.php') ?>


<!-- Our Dashbord -->
<div class="our-dashbord dashbord">
    <div class="dashboard_main_content">
        <div class="container-fluid">
            <div class="main_content_container">
                <div class="row">
                    <div class="col-lg-12">
                        <?php include('includes/mobile-drop-nav.php') ?><!-- mobile-navigation -->
                    </div>
                    <div class="col-lg-12">
                        <nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
                            <h4 class="title float-left">Manage transactions</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><b>Total earnings:</b> <span class="delivered"><?= money($totalEarnings) ?></span></li>
                            </ol>
                        </nav>
                        <div class="text">
                            Total Transactions: <?= count($transactions->result())?>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="item-table table-responsive"> <!-- table start-->
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                    <th scope="col">Customer</th>
                                    <th scope="col">Customer name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Reference</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="item-table-t">
                                <?php if($transactions->result()): 
                                foreach($transactions->result() as $transaction):    
                                ?>
                                   <tr>
                                        <td>
                                            <a href="<?= url('/admin/edit-customer.php?cid='.$transaction->buyer_id) ?>">
                                                <img src="<?= asset($transaction->user_image) ?>" alt="<?= $transaction->first_name ?>" class="table-img">
                                            </a>
                                        </td>
                                        <td><?= ucfirst($transaction->first_name).' '.ucfirst($transaction->last_name)?></td>
                                        <td><?= $transaction->email ?></td>
                                        <td><?= $transaction->phone ?></td>
                                        <td><?= $transaction->reference?></td>
                                        <td><?= money($transaction->amount)?></td>
                                        <td><?= date('d M Y', strtotime($transaction->date_paid)) ?></td>
                                        <td>
                                           <a href="<?= url('/admin/transaction-orders.php?tid='.$transaction->reference ) ?>"> <span class="view-btn">view orders</span></a>
                                        </td>
                                   </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                            <div class="col-lg-12">
                                <!-- pagination -->
                                <?php $transactions->links(); ?>

                                 <?php if(!count($transactions->result())): ?>
                                    <div class="empty-table">There are no transactions yet!</div>
                                <?php endif; ?>
                            </div>
                        </div><!-- table end-->
                    </div>
                </div>
                <div class="row mt50 mb50">
                    <div class="col-lg-6 offset-lg-3">
                        <div class="copyright-widget text-center">
                            <p class="color-black2"><?= $banner->alrights ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<a class="scrollToHome" href="#"><i class="flaticon-up-arrow-1"></i></a>
</div>










<?php  include('includes/footer.php') ?>
