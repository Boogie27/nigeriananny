<?php
$settings = $connection->select('settings')->where('id', 1)->first();
?>

<div class="header_top home2">
		<div class="container">
			<div class="row">
				<div class="col-xl-12">
					<div class="ht_left_widget home2 float-left">
						<ul>
							<li class="list-inline-item">
								<div class="logo-widget">
									<img class="img-fluid" src="<?= asset($settings->logo)?>" alt="header-logo.png">
									<span><?= $settings->app_name ? $settings->app_name : '' ?></span>
								</div>
							</li>
							
							<li class="list-inline-item">
								<div class="ht_search_widget">
									<div class="header_search_widget home2">
										<form action="<?= url('/jobs')?>" method="GET" class="form-inline mailchimp_form">
											<input type="text" name="title" class="form-control mb-2 mr-sm-2" id="inlineFormInputMail2" value="<?= Input::exists('get') && !empty(Input::get('title')) ? Input::get('title') : ''?>" placeholder="Search for employee jobs by title">
											<button type="submit" class="btn btn-primary mb-2"><span class="flaticon-magnifying-glass"></span></button>
										</form>
									</div>
								</div>
							</li>
						</ul>
					</div>
					<div class="ht_right_widget float-right">
						<ul class="text-right">
							<li class="list-inline-item"><i class="fa fa-phone"></i>  <a href="#">Call: <?= $settings->phone ? $settings->phone : ''; ?></a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	