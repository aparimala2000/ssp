<?php
if (!function_exists('encode_value')) {
	function encode_value($value = '')
	{
		if ($value != '') {
			return str_replace('=', '', base64_encode($value));
		}
	}
}
if (!function_exists('decode_value')) {
	function decode_value($value = '')
	{
		if ($value != '') {
			return base64_decode($value);
		}
	}
}
if (!function_exists('get_admin_input')) {
	function get_admin_input($type = 'text', $name = '', $label = 'Label', $value = '', $help_words = '', $other_values = '', $inp_id = '')
	{
		$help = ($help_words != '') ? '<br/><span class="description">(' . $help_words . ')</span>' : '';
		$ins = ($inp_id != '') ? 'id="' . $inp_id . '"' : '';
		$return = '';
		switch ($type) {
			case "email_field":
				$return .= '<tr valign="top"><th scope="row"><label>' . $label . '</label>' . $help . '</th><td><input class="" style="width:950px;height:5px;" type="text" ' . $ins . ' name="' . $name . '"  value="' . $value . '"/></td></tr>';
				break;
			case "text":
				$return .= '<tr valign="top"><th scope="row"><label>' . $label . '</label>' . $help . '</th><td><input class="regular-text" type="text" ' . $ins . ' name="' . $name . '"  value="' . $value . '"/></td></tr>';
				break;
			case "textarea":
				$return .= '<tr valign="top"><th scope="row"><label>' . $label . '</label>' . $help . '</th><td><textarea class="regular-text" ' . $ins . ' name="' . $name . '" rows="5" cols="70" >' . $value . '</textarea></td></tr>';
				break;
			case "editor":
				ob_start();
				$settings = array('wpautop' => true, 'media_buttons' => true, 'textarea_name' => $name, 'textarea_rows' => 5, 'tinymce' => true, 'quicktags' => true, 'drag_drop_upload' => true);
				wp_editor($value, $name, $settings);
				$editor_contents = ob_get_contents();
				ob_end_clean();
				$return .= '<tr valign="top"><th scope="row"><label>' . $label . '</label>' . $help . '</th><td>' . $editor_contents . '</td></tr>';
				break;
            case "up_image":
				$container = ($value != '') ? '<br/><img src="' . $value . '" style="max-width:300px;max-height: 150px;" alt="" />' : '';
				$return .= '<tr valign="top"><th scope="row"><label>' . $label . '</label>' . $help . '</th><td><div class="upload-image"><input class="regular-text up-image" readonly="readonly" ' . $ins . ' name="' . $name . '" id="' . $name . '" type="text" value="' . $value . '" /><input style="margin-right: 10px;" class="upload-button button button-secondary" type="button" rel="#' . $name . '" value="Upload" class="thickbox" /><input class="remove-button button button-secondary" type="button" value="Remove" /><div class="upload-image-container">' . $container . '</div><!-- upload-image-container --></div><!-- upload-image --></td></tr>';
				break;
            case "up_file":
				$container = ($value != '') ? '<br/><a href="' . $value . '" target="_blank" title="Download" >Download File</a>' : '';
				$return .= '<tr valign="top"><th scope="row"><label>' . $label . '</label>' . $help . '</th><td><div class="upload-file"><input class="regular-text up-file" readonly="readonly" ' . $ins . ' name="' . $name . '" type="text" value="' . $value . '"/><input class="file-upload-button" type="button" value="Upload" class="thickbox" /><input class="remove-file" type="button" value="Remove" /><div class="upload-file-container">' . $container . '</div></div>';
				break;
            case "color_picker":
				$return .= '<tr valign="top"><th scope="row"><label>' . $label . '</label>' . $help . '</th><td><input class="regular-text up-file" readonly="readonly" ' . $ins . ' name="' . $name . '" type="hidden" value="' . $value . '"/></td></tr>';
				$return .= '<script type="text/javascript">';
				$return .= ($inp_id != '') ? 'jQuery(document).ready(function() {jQuery("#' . $inp_id . '").wpColorPicker();});' : '';
				$return .= '</script></td></tr>';
				break;
            case "date_picker":
				$return .= '<tr valign="top"><th scope="row"><label>' . $label . '</label>' . $help . '</th><td><input class="regular-text up-file" readonly="readonly" ' . $ins . ' name="' . $name . '" type="text" value="' . $value . '"/>';
				$return .= '<script type="text/javascript">';
				$return .= ($inp_id != '') ? 'jQuery(document).ready(function() {jQuery("#' . $inp_id . '").datepicker({dateFormat : "yy-mm-dd",yearRange: "c-10:c+10", changeMonth: true, changeYear: true, gotoCurrent: true, showOtherMonths: true, selectOtherMonths: true});});' : '';
				$return .= '</script></td></tr>';
				break;
			case "select_multiple":
				$return .= '<tr valign="top"><th scope="row"><label>' . $label . '</label>' . $help . '</th><td>';
				$return .= '<select ' . $ins . ' name="' . $name . '" multiple>';
				if (!empty($other_values)) {
					if (is_array($other_values)) {
						foreach ($other_values as $select_lebel => $select_value) {
							$return .= '<option value="' . $select_value . '" ' . (($select_value == $value) ? 'selected="selected"' : '') . '>' . $select_lebel . '</option>';
						}
					} elseif ($other_values == 'cat_sliders') {
						$cat_args = array('type' => 'blog', 'orderby' => 'name', 'order' => 'ASC', 'hide_empty' => 1, 'hierarchical' => 1, 'exclude' => '', 'taxonomy' => 'slider-cat', 'pad_counts' => false);
						$categories = get_categories($cat_args);
						if (!empty($categories)) {
							foreach ($categories as $cat) {
								$return .= '<option value="' . $cat->slug . '" ' . (($cat->slug == $value) ? 'selected="selected"' : '') . '>' . $cat->name . '</option>';
							}
						}
					}
				} else {
					$posttype = array('blog');
					$page_args = array('post_type' => $posttype, 'numberposts' => -1);
					$pages = get_posts($page_args);
					if (!empty($pages)) {
						foreach ($pages as $pg) {
							$return .= '<option value="' . $pg->ID . '" ' . (($pg->ID == $value) ? 'selected="selected"' : '') . '>' . $pg->post_title . '</option>';
						}
					}
				}
				$return .= '</select></td></tr>';
				break;
            case "target":
				$return .= '<tr valign="top"><th scope="row"><label>' . $label . '</label>' . $help . '</th><td>';
				$return .= '<select ' . $ins . ' name="' . $name . '" class="' . $value . '">';
				$return .= '<option value="">Select</option>';
				$return .= '<option value="_target"' . (($value == '_target') ? 'selected="selected"' : '') . '>New Window</option>';
				$return .= '<option value="_self"' . (($value == '_self') ? 'selected="selected"' : '') . '>Same Window</option>';
				$return .= '</select></td></tr>';
				break;
			case "select":
				$return .= '<tr valign="top"><th scope="row"><label>' . $label . '</label>' . $help . '</th><td>';
				$return .= '<select ' . $ins . ' name="' . $name . '" multiple>';
				$return .= '<option value="">Select</option>';
				if (!empty($other_values)) {
					if (is_array($other_values)) {
						foreach ($other_values as $select_lebel => $select_value) {
							$return .= '<option value="' . $select_value . '" ' . (($select_value == $value) ? 'selected="selected"' : '') . '>' . $select_lebel . '</option>';
						}
					} elseif ($other_values == 'cat_sliders') {
						$cat_args = array('type' => 'blog', 'orderby' => 'name', 'order' => 'ASC', 'hide_empty' => 1, 'hierarchical' => 1, 'exclude' => '', 'taxonomy' => 'slider-cat', 'pad_counts' => false);
						$categories = get_categories($cat_args);
						if (!empty($categories)) {
							foreach ($categories as $cat) {
								$return .= '<option value="' . $cat->slug . '" ' . (($cat->slug == $value) ? 'selected="selected"' : '') . '>' . $cat->name . '</option>';
							}
						}
					}
				} else {
					$posttype = array('blog');
					$page_args = array('post_type' => $posttype, 'numberposts' => -1);
					$pages = get_posts($page_args);
					if (!empty($pages)) {
						foreach ($pages as $pg) {
							$return .= '<option value="' . $pg->ID . '" ' . (($pg->ID == $value) ? 'selected="selected"' : '') . '>' . $pg->post_title . '</option>';
						}
					}
				}
				$return .= '</select></td></tr>';
				break;
            case "radio":
				$return .= '<tr valign="top"><th scope="row"><label>' . $label . '</label>' . $help . '</th><td>';
                  if (!empty($other_values)) {
					if (is_array($other_values)) {
						$i = 1;
						foreach ($other_values as $select_lebel => $select_value) {
							$checked = '';
							if ($select_value == $value) {
								$checked = 'checked="checked"';
							} elseif ($i == 1) {
								$checked = 'checked="checked"';
							}
							$return .= '<label for="rdo_' . $name . '_' . $i . '">' . $select_lebel . '</label>';
							$return .= '<input id="rdo_' . $name . '_' . $i . '" type="radio" name="' . $name . '" ' . $checked . ' value="' . $select_value . '">';
							$i++;
						}
					}
				}
				$return .= '</td></tr>';
				break;
            case "checkbox":
				$return .= '<tr valign="top"><th scope="row"><label>' . $label . '</label>' . $help . '</th><td>';
                if (!empty($other_values)) {
					if (is_array($other_values)) {
						 $i = 1;
						foreach ($other_values as $select_lebel => $select_value) {
							$checked = '';
							if (is_array($value)) {
								if (in_array($select_value, $value)) {
									$checked = 'checked="checked"';
								}
							}

							$return .= ($i % 4 == 1) ? '<div class="check_box_seperator">' : '';
							$return .= '<div class="check_box_container">';
							$return .= '<input id="chk_' . $name . '_' . $i . '" type="checkbox" name="' . $name . '[]" ' . $checked . ' value="' . $select_value . '">';
							$return .= '<span for="chk_' . $name . '_' . $i . '">' . $select_lebel . '</span>&nbsp;';
							$return .= '</div>';
							$return .= ($i % 4 == 0) ? '</div><!-- check_box_seperator -->' : '';
 
							$i++;
						}
						$return .= ($i != 1) ? '</div><!-- check_box_seperator -->' : '';
					}
				} else {
					$checked = ($value == 1) ? 'checked="checked"' : '';
					 
					$return .= '<input id="chk_' . $name . '" type="checkbox" name="' . $name . '" ' . $checked . ' value="1">';
				}
				$return .= '</td></tr>';
				break;
            default:
				$return .= '<tr valign="top"><th scope="row"><label>' . $label . '</label>' . $help . '</th><td><input class="regular-text" type="text" name="' . $name . '"  value="' . $value . '"/></td></tr>';
				break;
		}
		return $return;
	}
}
if (!function_exists('truncatebywords')) {
	function truncatebywords($phrase, $max_words)
	{
		$phrase_array = explode(' ', $phrase);
		if (count($phrase_array) > $max_words && $max_words > 0)
			$phrase = implode(' ', array_slice($phrase_array, 0, $max_words)) . ' ...';
		return $phrase;
	}
}
if (!function_exists('truncatebychars')) {
	function truncatebychars($chars, $limit)
	{
		if (strlen($chars) <= $limit)
			return $chars;
		else
			return substr($chars, 0, $limit) . ' ...';;
	}
}
