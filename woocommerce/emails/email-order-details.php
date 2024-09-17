<?php

/**
 * Order details table shown in emails.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-order-details.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 3.7.0
 */

defined('ABSPATH') || exit;

$text_align = is_rtl() ? 'right' : 'left';

do_action('woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text, $email); ?>

<h2>
	<?php
	if ($sent_to_admin) {
		$before = '<a class="link" href="' . esc_url($order->get_edit_order_url()) . '">';
		$after  = '</a>';
	} else {
		$before = '';
		$after  = '';
	}
	/* translators: %s: Order ID. */
	echo wp_kses_post($before . sprintf(__('[Order #%s]', 'woocommerce') . $after . ' (<time datetime="%s">%s</time>)', $order->get_order_number(), $order->get_date_created()->format('c'), wc_format_datetime($order->get_date_created())));
	?>
</h2>

<div style="margin-bottom: 40px;">
	<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family:'Montserrat-Medium', sans-serif;" border="1">
		<thead>
			<tr>
				<th class="td" scope="col" style="text-align:<?php echo esc_attr($text_align); ?>;"><?php esc_html_e('Product', 'woocommerce'); ?></th>
				<th class="td" scope="col" style="text-align:<?php echo esc_attr($text_align); ?>;"><?php esc_html_e('Quantity', 'woocommerce'); ?></th>
				<th class="td" scope="col" style="text-align:<?php echo esc_attr($text_align); ?>;"><?php esc_html_e('Price', 'woocommerce'); ?></th>
			</tr>
		</thead>
		<tbody>

			<?php
			$order_items = $order ? $order->get_items() : []; 
			$free_product_data = null; 
			foreach ($order_items as $item_id => $item) {
				$product = $item->get_product(); 
				if ($product->get_id() == 725) {
					$free_product_data = $item;
					continue;
				} 
				if ($product->is_type('variation')) {
					if (method_exists($product, 'has_parent') && $product->has_parent()) {
						$product = wc_get_product($product->get_parent_id());
					} elseif (property_exists($product, 'parent_id') && $product->parent_id > 0) {
						$product = wc_get_product($product->parent_id);
					}
				}

				echo '<tr>';
				echo '<td>';
				echo $product->get_name();
				echo '<ul style="font-size:small;margin:1em 0 0;padding:0;list-style:none"><li style="margin:.5em 0 0;padding:0">';
				echo '<strong style="float:left;margin-right:.25em;clear:both">weight:</strong>';
				echo '<p style="margin:0">' . $product->get_weight() . 'g' . '</p>';
				echo '</li></ul>';
				echo '</td>';
				echo '<td>' . $item->get_quantity() . '</td>';
				echo '<td>' . wc_price($item->get_total()) . '</td>';
				echo '</tr>';
			}
 
			if ($free_product_data) {
				$free_product = $free_product_data->get_product();
				echo '<tr>';
				echo '<td>' . $free_product->get_name() . '</td>';
				echo '<td>' . $free_product_data->get_quantity() . '</td>';
				echo '<td>' . wc_price($free_product_data->get_total()) . '</td>';
				echo '</tr>';
			}

			//original code
			// echo wc_get_email_order_items( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			// 	$order,
			// 	array(
			// 		'show_sku'      => $sent_to_admin,
			// 		'show_image'    => false,
			// 		'image_size'    => array(32, 32),
			// 		'plain_text'    => $plain_text,
			// 		'sent_to_admin' => $sent_to_admin,
			// 	)
			// );
			?>
		</tbody>
		<tfoot>
			<?php
			$item_totals = $order->get_order_item_totals();

			if ($item_totals) {
				$i = 0;
				foreach ($item_totals as $total) {
					$i++;
			?>
					<tr>
						<th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr($text_align); ?>; <?php echo (1 === $i) ? 'border-top-width: 4px;' : ''; ?>"><?php echo wp_kses_post($total['label']); ?></th>
						<td class="td" style="text-align:<?php echo esc_attr($text_align); ?>; <?php echo (1 === $i) ? 'border-top-width: 4px;' : ''; ?>"><?php echo wp_kses_post($total['value']); ?></td>
					</tr>
				<?php
				}
			}
			if ($order->get_customer_note()) {
				?>
				<tr>
					<th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr($text_align); ?>;"><?php esc_html_e('Note:', 'woocommerce'); ?></th>
					<td class="td" style="text-align:<?php echo esc_attr($text_align); ?>;"><?php echo wp_kses_post(nl2br(wptexturize($order->get_customer_note()))); ?></td>
				</tr>
			<?php
			}
			?>
		</tfoot>
	</table>
</div>

<?php do_action('woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text, $email); ?>