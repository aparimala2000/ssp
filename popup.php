<!-- popup box1 start -->

<div class="popup-box1-blk generic" id="global_popup">
    <div class="bg-layer1"></div>
    <div class="popup-box1">
        <div class="d-flex justify-content-between align-items-center popup-header">
            <div><img src="<?php echo get_option('popup_icon'); ?>"></div>
            <a href="javascript:void(0);" class="popup-close1 d-block text-right gobal_popup_content"><i class="las la-times"></i></a>
        </div>
        <div class=" popup-box1-con text-center d-flex justify-content-center align-items-center">
            <div>
                <?php $popup_content = get_option('popup_message');
                echo wpautop($popup_content); ?>
            </div>
        </div>
    </div>
</div>
<!-- popup box1 end -->