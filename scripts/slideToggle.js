jQuery.fn.rotate = function(degrees) {
    $(this).css({'transform' : 'rotate('+ degrees +'deg)'});
};

var rotation = 0;
$(document).ready(function(){
    $("h2").click(function(){
		if ($(this).parent().find(".folddiv").is(':visible')) {
			$(this).find(".material-icons").rotate(180);
		} else {
			$(this).find(".material-icons").rotate(0);
		}
		$(this).parent().find(".folddiv").slideToggle('1000','swing');
    });
});

$(document).ready(function(){
    $("h2:has(.material-icons)").css("cursor", "pointer");
	$("h2:has(.material-icons)").hover(function(){
		$(this).css("color", "black")
	}, function(){
		$(this).css("color", "")
	});
});