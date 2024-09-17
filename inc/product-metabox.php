<?php
add_action('admin_menu', 'template_metabox_options');

function template_metabox_options()
{
    $types = array('product');
    foreach ($types as $type) {
        add_meta_box('template_metabox_options', 'USP', 'template_metabox_options_design', $type);
    }
}

function template_metabox_options_design($post)
{
    $msg_name = get_post_meta($post->ID, 'msg_name', true);
    $titleTag = get_post_meta($post->ID, 'title_tag', true);
    $image = get_post_meta($post->ID, 'image', true);
?>
    <div>
        <table cellpadding="3" cellspacing="18" border="0" id='ground_floor' class='fontsize10' style='font-size: 10px;'>
            <tr>
                <td class="left"><label for="tax-order">
                        <h3>Image</h3>
                    </label></td>
                <td class="left">
                    <input type="text" name="image" id="image" value="<?php echo $image; ?>" style="width: 100%;" />
                    <input type="button" id="upload_image_button" class="button" value="Upload Image" />
                    <input type="hidden" name="image" id="image" value="<?php echo esc_attr(get_post_meta($post->ID, 'image', true)); ?>">
                </td>
            </tr>
            <tr>
                <td class="left"><label for="tax-order">
                        <h3>Title</h3>
                    </label></td>
                <td class="right">
                    <input type="text" name="title_tag" id="title_tag" value="<?php echo $titleTag; ?>" style="width: 100%;">
                </td>
            </tr>
            <tr>
                <td class="left"><label for="tax-order">
                        <h3>Description</h3>
                    </label></td>
                <td class="left">
                    <input type="text" name="msg_name" id="msg_name" value="<?php echo $msg_name; ?>" style="width: 100%;">
                </td>
            </tr>
        </table>
    </div>
<?php
}

add_action('save_post', 'save_template_metabox');

function save_template_metabox($post_id)
{
    // do not save if this is an auto save routine
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return $post_id;

    if (array_key_exists('msg_name', $_REQUEST)) {
        update_post_meta($post_id, 'msg_name', $_REQUEST['msg_name']);
    }

    if (array_key_exists('title_tag', $_REQUEST)) {
        update_post_meta($post_id, 'title_tag', $_REQUEST['title_tag']);
    }

    if (array_key_exists('image', $_REQUEST)) {
        update_post_meta($post_id, 'image', $_REQUEST['image']);
    }
}

// Add custom script for media upload
function add_custom_media_upload_script()
{
    wp_enqueue_media();
    wp_register_script('custom-media-upload', get_template_directory_uri() . '/js/custom-media-upload.js', array('jquery'));
    wp_enqueue_script('custom-media-upload');
}
add_action('admin_enqueue_scripts', 'add_custom_media_upload_script');
?>

<script>
    jQuery(document).ready(function($) {
        var mediaUploader;

        $('#upload_image_button').click(function(e) {
            e.preventDefault();

            // If the media uploader exists, open it.
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }

            // Otherwise, create the media uploader.
            mediaUploader = wp.media.frames.file_frame = wp.media({
                title: 'Upload Image',
                button: {
                    text: 'Choose Image'
                },
                multiple: false
            });

            // When an image is selected, run a callback.
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();

                $('#image').val(attachment.url);
            });

            // Open the media uploader.
            mediaUploader.open();
        });
    });
</script>