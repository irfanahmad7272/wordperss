// scroll and children effect

if ($(window).width() <= 767) {
            $(".menu-item-has-children > a").click(function(){
				$('.menu-item-has-children > a').not(this).parent("li").children("ul.custom_menu").slideUp(500);
				$(this).parent("li").children("ul.custom_menu").slideToggle(500);
			});
        }
		else{
			$(".custom_navigation > li").hover(function () {
        $(this).children(".custom_menu").stop(true, false, true).slideToggle(500);
    });
    
    $(".menu-item-has-children a").focusin(function () {
            
         $(this).parent('li').children(".custom_menu").stop(true).slideToggle(500);
        
    });
    
    $(".menu-item-has-children ul li:last-of-type a").focusout(function () {
            
         $(this).parent('li').parent().stop(true).slideUp(500);
        
    });
		}
