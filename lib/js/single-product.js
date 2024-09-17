$(document).ready(function () {
	    console.log("megha");
	    var conceptName = $('.country_select').find(":selected").attr('data-code');
        console.log(conceptName);
        if (conceptName==""){
	    var country_code = "US";
	        $.ajax({
	            type: "POST",
	            url: blogUri + "/wp-admin/admin-ajax.php",
	            data: {
	                action: 'country_shipping',
	                country_code: country_code,
	            },
	            success: function (data) {
	                console.log(data);
	                // location.reload();
	            }
	        });
	    }
	});
