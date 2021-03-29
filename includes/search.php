








<!-- page banner start-->
<div class="banner-container">
		<div class="page-banner" style="background-image: url('images/home/4.jpg')">
			<div class="banner-head">
				<h4>Find an employee in a minute</h4>
				<h3>Join us and explore thousands of employees</h3>
			</div>
			<form action="<?= url('/jobs') ?>" method="GET" id="multi-selete-box">
				<div class="col-lg-3">
					<div class="ui_kit_select_box">
						<select name="category" class="selectpicker custom-select-lg mb-3">
							<option value="">All category</option>
							<?php $categories = $connection->select('job_categories')->where('is_category_featured', 1)->get(); 
							if(count($categories)): 
							foreach($categories as $category):?>
								<option value="<?= $category->category_slug?>"><?= $category->category_name?></option>
							<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="ui_kit_select_box">
						<select name="state" class="selectpicker custom-select-lg mb-3">
						<option value="">All states</option>
						<?php $states = $connection->select('states')->where('is_active', 1)->get(); 
						if(count($states)):
							foreach($states as $state):?>
								<option value="<?= strtolower($state->state) ?>"><?= strtoupper($state->state) ?></option>
							<?php endforeach; ?>
						<?php else: ?>
							<option value="">There are no states</option>
						<?php endif; ?>
						</select>
					</div>
				</div>
				<div class="col-lg-2">
					<div class="banner-search-btn">
						<button type="submit" class="btn-fill">Search worker</button>
					</div>
				</div>
			</form>
		</div>
	</div>
<!-- page banner end-->