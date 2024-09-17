<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('.upload-button').click(function(e) {
            e.preventDefault();
            var dfield = jQuery(jQuery(this).attr('rel'));
            var up_image_container = jQuery(this).parents('.upload-image').find('.upload-image-container');
            var image = wp.media({
                    title: 'Upload Image',
                    // mutiple: true if you want to upload multiple files at once
                    multiple: false
                }).open()
                .on('select', function(e) {
                    // This will return the selected image from the Media Uploader, the result is an object
                    var uploaded_image = image.state().get('selection').first();
                    // We convert uploaded_image to a JSON object to make accessing it easier
                    // Output to the console uploaded_image
                    console.log(uploaded_image);
                    var image_url = uploaded_image.toJSON().url;
                    // Let's assign the url value to the input field
                    dfield.val(image_url);
                    up_image_container.html('<br/><img src="' + image_url + '" style="max-width:300px;max-height: 150px;" alt="banner-image" />');
                });
        });

        function common_remove_upload(img_id) {
            var answer = confirm("Are you sure?");
            if (answer) {
                jQuery(img_id).val('');
                jQuery('#save_changes').trigger('click');
                return true;
            } else {
                return false; // Do nothing.
            }
        }
        jQuery('.remove-button').click(function() {
            var path = jQuery(this).parent('.upload-image').find('.up-image');
            if (common_remove_upload(path) == true) {
                jQuery(this).parents('.upload-image').find('.upload-image-container').html('');
            }

        });
        jQuery('.rem-file').click(function() {
            /*alert(jQuery(this).parent('td').find('.up-file').length);
            jQuery(this).parent('td').find('.up-image');
            */
            console.log(jQuery(this).parents());
            alert(jQuery(this).parents('td').find('.up-file').length);
            jQuery(this).parents('td').find('.up-file').val('');
            jQuery(this).parents('td').find('.upload-file-container').html('');
        });
    });
</script>