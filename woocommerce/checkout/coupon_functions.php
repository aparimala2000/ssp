	<div class="coupon-detail-blk">
		<div class="mb-30">
			<a href="javascript:void(0);" class="coupon-close-btn"><i class="las la-times"></i></a>
		</div>
		<!-- <form id="voucherCodeForm">  -->
			<div class="position-relative mb-40">
				<div class="search-blk voucher-code">
					<input class="search-box w-100" type="text" id="apply_coupon_text" placeholder="Voucher Code" style="background-color: #f8f2f285;">
					<a href="javascript:void(0);" class="search-btn button apply_coupon_text">Apply</a>
				</div>
				<div class="err-msg">Please enter a valid coupon.</div>
			 
			</div>
		<!-- </form> -->
		<h6 class="md b-font mb-2">AVAILABLE COUPONS</h6>
		<div class="coupon-content mr-auto mb-5">
			<?php
			$coupon_array_range = [];
			foreach ($coupons as $coupon_post) {
				$coupon = new WC_Coupon($coupon_post->ID);
				$coupon_code     = $coupon->get_code();
				$coupon_minimum_spend = $coupon->get_minimum_amount();
				$coupon_maximum_spend = $coupon->get_maximum_amount();
				$coupon_array_range[$coupon_post->ID]['code'] = $coupon_code;
				$coupon_array_range[$coupon_post->ID]['min_value'] = $coupon_minimum_spend;
				$coupon_array_range[$coupon_post->ID]['max_value'] = $coupon_maximum_spend;
			}
			?>
			<?php
			$displayed_coupon = false;
			foreach ($coupons as $coupon_post) {
				$coupon = new WC_Coupon($coupon_post->ID);
				$coupon_code     = $coupon->get_code();
				$coupon_amount   = $coupon->get_amount();
				$coupon_description   = $coupon->get_description();
				$coupon_discount = $coupon->get_discount_type();
				$coupon_expiry   = $coupon->get_date_expires();
				$coupon_minimum_spend = $coupon->get_minimum_amount();
				$coupon_maximum_spend = $coupon->get_maximum_amount();
				// $is_customer_specific_coupon = get_post_meta($coupon_post->ID, 'customer_eligibility', true);
				$multiple_coupons_available = get_post_meta($coupon_post->ID, 'multiple_coupons', true);
			?>
				<?php
				if (($coupon_minimum_spend <= $coupon_cart_total && $coupon_maximum_spend >= $coupon_cart_total) && $multiple_coupons_available && $is_qualifying_customer) {
					$displayed_coupon = true;
				?>
					<div class="coupon-row" data-minimum-spend="<?php echo esc_attr($coupon_minimum_spend); ?>" data-maximum-spend="<?php echo esc_attr($coupon_maximum_spend); ?>" data-customer-specific="<?php echo esc_attr($is_customer_specific_coupon); ?>" data-coupon-code="<?php echo esc_attr($coupon->get_code()); ?>">

						<!-- <div class="coupon-row  <?php echo $enable_coupon . '>>>' . $coupon_minimum_spend . "<=" . $coupon_cart_total . "&&" . $coupon_maximum_spend . ">=" . $coupon_cart_total; ?>"> -->
						<div class="offer-label type1 mb-3">
							<span><?php echo ucfirst($coupon_code); ?></span>
						</div>
						<div>
							<span class="md mb-3"><?php echo $coupon_description; ?></span>
							<!-- <span class="md ash-color b-font mb-2" style="font-size: 11px;"><?php echo $coupon_description; ?></span> -->
						</div>
						<a href="javascript:void(0)" class="button mb tb condition_coupon" data-coupon="<?php echo $coupon_code; ?>" data-percentage="<?php echo $coupon_amount; ?>" data-coupon_minimum="<?php echo $coupon_minimum_spend; ?>" data-coupon_maximum="<?php echo $coupon_maximum_spend; ?>">Apply Coupon</a>

					</div>
				<?php
					//  break;
				} ?>
			<?php } ?>
			<?php if (!$displayed_coupon) {
			?>

				<?php foreach ($coupons as $coupon_post) {
					$coupon = new WC_Coupon($coupon_post->ID);
					$coupon_code     = $coupon->get_code();
					$coupon_amount   = $coupon->get_amount();
					$coupon_description   = $coupon->get_description();
					$coupon_discount = $coupon->get_discount_type();
					$coupon_expiry   = $coupon->get_date_expires();
					$coupon_minimum_spend = $coupon->get_minimum_amount();
					$coupon_maximum_spend = $coupon->get_maximum_amount();
					// $is_customer_specific_coupon = get_post_meta($coupon_post->ID, 'customer_eligibility', true);
					$multiple_coupons_available = get_post_meta($coupon_post->ID, 'multiple_coupons', true);
				?>



					<?php
					// Display available coupons only
					if (($coupon_minimum_spend <= $coupon_cart_total && $coupon_maximum_spend >= $coupon_cart_total && !$multiple_coupons_available) || $is_qualifying_customer && $is_customer_specific_coupon && !$multiple_coupons_available) {

					?>
						<div class="coupon-row" data-minimum-spend="<?php echo esc_attr($coupon_minimum_spend); ?>" data-maximum-spend="<?php echo esc_attr($coupon_maximum_spend); ?>" data-customer-specific="<?php echo esc_attr($is_customer_specific_coupon); ?>" data-coupon-code="<?php echo esc_attr($coupon->get_code()); ?>">

							<!-- <div class="coupon-row  <?php echo $enable_coupon . '>>>' . $coupon_minimum_spend . "<=" . $coupon_cart_total . "&&" . $coupon_maximum_spend . ">=" . $coupon_cart_total; ?>"> -->
							<div class="offer-label type1 mb-3">
								<span><?php echo ucfirst($coupon_code); ?></span>
							</div>
							<div>
								<span class="md mb-3"><?php echo $coupon_description; ?></span>
								<!-- <span class="md ash-color b-font mb-2" style="font-size: 11px;"><?php echo $coupon_description; ?></span> -->
							</div>
							<a href="javascript:void(0)" class="button mb tb condition_coupon" data-coupon="<?php echo $coupon_code; ?>" data-percentage="<?php echo $coupon_amount; ?>" data-coupon_minimum="<?php echo $coupon_minimum_spend; ?>" data-coupon_maximum="<?php echo $coupon_maximum_spend; ?>">Apply Coupon</a>

						</div>
			<?php
					}
				}
			} ?>
		</div>
		<span class="md b-font mb-2">UNAVAILABLE COUPONS</span>
		<div class="coupon-content mr-auto">
			<?php
			$displayed_coupon = false;
			foreach ($coupons as $coupon_post) {
				$coupon = new WC_Coupon($coupon_post->ID);
				$coupon_code     = $coupon->get_code();
				$coupon_amount   = $coupon->get_amount();
				$coupon_description   = $coupon->get_description();
				$coupon_discount = $coupon->get_discount_type();
				$coupon_expiry   = $coupon->get_date_expires();
				$coupon_minimum_spend = $coupon->get_minimum_amount();
				$coupon_maximum_spend = $coupon->get_maximum_amount();
				// $is_customer_specific_coupon = get_post_meta($coupon_post->ID, 'customer_eligibility', true);
				$multiple_coupons_available = get_post_meta($coupon_post->ID, 'multiple_coupons', true);

				if (!($coupon_minimum_spend <= $coupon_cart_total && $coupon_maximum_spend >= $coupon_cart_total) && $multiple_coupons_available && $is_qualifying_customer) {

					$displayed_coupon = true;
			?>
					<div class="coupon-row">
						<div class="offer-label type1 mb-3">
							<span><?php echo ucfirst($coupon_code); ?></span>
						</div>
						<div>
							<span class="md mb-3"><?php echo $coupon_description; ?></span>
							<!-- <span class="md ash-color b-font mb-2" style="font-size: 11px;"><?php echo $coupon_description; ?></span> -->
						</div>
						<a href="javascript:void(0)" class="button mb tb disable">Apply Coupon</a>
					</div>
			<?php
					// break;
				}
			} ?>
			<?php if (!$displayed_coupon) {
			?>
				<?php foreach ($coupons as $coupon_post) {
					$coupon = new WC_Coupon($coupon_post->ID);
					$coupon_code     = $coupon->get_code();
					$coupon_amount   = $coupon->get_amount();
					$coupon_description   = $coupon->get_description();
					$coupon_discount = $coupon->get_discount_type();
					$coupon_expiry   = $coupon->get_date_expires();
					$coupon_minimum_spend = $coupon->get_minimum_amount();
					$coupon_maximum_spend = $coupon->get_maximum_amount();
					// $is_customer_specific_coupon = get_post_meta($coupon_post->ID, 'customer_eligibility', true);
					$multiple_coupons_available = get_post_meta($coupon_post->ID, 'multiple_coupons', true);


					if (!($coupon_minimum_spend <= $coupon_cart_total && $coupon_maximum_spend >= $coupon_cart_total) && !$multiple_coupons_available && !($is_qualifying_customer && $is_customer_specific_coupon)) {

				?>
						<div class="coupon-row">
							<div class="offer-label type1 mb-3">
								<span><?php echo ucfirst($coupon_code); ?></span>
							</div>
							<div>
								<span class="md mb-3"><?php echo $coupon_description; ?></span>
								<!-- <span class="md ash-color b-font mb-2" style="font-size: 11px;"><?php echo $coupon_description; ?></span> -->
							</div>
							<a href="javascript:void(0)" class="button mb tb disable">Apply Coupon</a>
						</div>
			<?php
					}
				}
			} ?>
		</div>
	</div>