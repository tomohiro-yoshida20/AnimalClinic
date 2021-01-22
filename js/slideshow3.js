//jQuery(function ($) {
//	var now_id = 1;
//	var next_id = 2;
//	var max_image = $("#pickup_field ul li img").length;
//	function changeImage() {
//		if (now_id >= max_image) {
//			next_id = 1;
//		} else {
//			next_id = now_id + 1;
//		}
//		var point = $('#pickup_field ul li img[id="pickup'+ next_id +'"]').parent();
//		$('#pickup_field ul li img[id="pickup'+ now_id +'"]').parent().fadeOut(1000, function() {
//			$(this).removeClass('now');
//		});
//		$('#pickup_field ul li img[id="pickup'+ next_id +'"]').parent().fadeIn(1000, function() {
//			$(this).addClass("now");
//			$("#pickup_field ul li").removeClass("now");
//			point.addClass("now");
//			now_id = next_id;
//		});
//	}
//	if (max_image > 1) {
//		setInterval(changeImage, 4000);
//	}
//});


$(function () {
    $('#graphic ul').slick({
        autoplay: true,
        autoplaySpeed: 2000,
        speed:500,
        fade: true
    });


});
