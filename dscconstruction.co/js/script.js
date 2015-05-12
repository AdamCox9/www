var isSplash = true;

$(document).ready(function(){
	var MSIE8 = ($.browser.msie) && ($.browser.version == 8);
	$.fn.ajaxJSSwitch({
		topMargin:15,//mandatory property for decktop
		bottomMargin:340,//mandatory property for decktop
		topMarginMobileDevices:0,//mandatory property for mobile devices
		bottomMarginMobileDevices:0,//mandatory property for mobile devices
		bodyMinHeight:820,
		menuInit:function (classMenu, classSubMenu){
			classMenu.find(">li").each(function(){
				$(">a", this).append("<div class='openPart'></div>");
			})
		},
		buttonOver:function (item){
			if(MSIE8){
				item.css({"color":"#eb5368"});
				$(".openPart", item).css({"visibility":"visible"});
			}else{
				item.stop(true).animate({"color":"#eb5368"}, 200, "easeOutCubic");
				$(".openPart", item).stop(true).animate({"opacity":"1","bottom":"7px","height":"2px"}, 400, "easeOutCubic");
			}
		},
		buttonOut:function (item){
			if(MSIE8){
				item.css({"color":"#747178"});
				$(".openPart", item).css({"visibility":"hidden"});
			}else{
				item.stop(true).animate({"color":"#747178"}, 200, "easeOutCubic");
				$(".openPart", item).stop(true).animate({"opacity":"0","bottom":"17px","height":"0px"}, 400, "easeOutCubic");
			}
		},
		subMenuButtonOver:function (item){ 
		      /*item.stop().animate({"color":"#4bc1d4"}, 300, "easeOutCubic");*/
        },
		subMenuButtonOut:function (item){
		      /*item.stop().animate({"color":"#fff"}, 300, "easeOutCubic");*/
        },
		subMenuShow:function(subMenu){
            if(MSIE8){
				subMenu.css({"display":"block"});
			}else{
				subMenu.stop(true).css({"display":"block"}).animate({"opacity":"1"}, 400, "easeOutCubic");
			}
        },
		subMenuHide:function(subMenu){
            if(MSIE8){
				subMenu.css({"display":"none"});
			}else{
				subMenu.stop(true).delay(300).animate({"opacity":"0"}, 400, "easeOutCubic", function(){
					$(this).css({"display":"none"})
				});
      
			}
        },
		pageInit:function (pages){
		},
		currPageAnimate:function (page){
              page.css({left:'-1500px'}).stop(true).css({"top":"0"}).delay(100).animate({left:0}, 500, "easeInOutExpo");
              isSplash = false;
              $(window).trigger('resize');   
        },
		prevPageAnimate:function (page){
              page.stop(true).animate({"top":$(window).height()+20}, 500, "easeInSine");
              $("#wrapper>section>#content_part").css({"visibility":"visible"}).stop(true).animate({"top":0}, 100, "easeInOutCubic");
              $("#splash").stop(true).delay(0).animate({opacity:0,marginLeft:300}, 500, "easeInOutCubic");
              $("footer").stop(true).delay(0).animate({marginTop:72}, 500, "easeInOutCubic");
              $(".header_line").stop(true).delay(0).animate({height:10}, 500, "easeInOutCubic");
              /*$("#splash").stop(true).delay(0).css({background:"none"});*/
              /*$("footer").stop(true).delay(0).animate({marginTop:272}, 500, "easeInOutCubic");*/
        },
		backToSplash:function (){
		      isSplash = true;
              $("#wrapper>section>#content_part").stop(true).delay(500).animate({"top":$(window).height()+20}, 700, "easeInOutCubic", function(){$(this).css({"visibility":"hidden"})});
              $("#splash").stop(true).delay(0).animate({opacity:1,marginLeft:0}, 500, "easeInOutCubic");
              $("footer").stop(true).delay(0).animate({marginTop:32}, 500, "easeInOutCubic");
              $(".header_line").stop(true).delay(0).animate({height:0}, 500, "easeInOutCubic");
              /*$("#splash").stop(true).delay(0).css({background:"#f1f1f0"});*/
              /*$("footer").stop(true).delay(0).animate({marginTop:590}, 500, "easeInOutCubic");*/
              $(window).trigger('resize');        
        },
		pageLoadComplete:function (){
		},
	});
})
$(window).load(function(){	
	$("#webSiteLoader").delay(50).animate({opacity:0}, 800, "easeInCubic", function(){$("#webSiteLoader").remove()});
	$("footer").stop(true).delay(400).animate({marginLeft:0}, 1100, "easeInOutCubic");


	$('#prev_arr, #next_arr')
	.sprites({
		method:'simple',
		duration:400,
		easing:'easeOutQuint',
		hover:true
	})

	
	$('.social_icons > li').hoverSprite({onLoadWebSite:true}); 


	var ind = 0;
	var len = $('.nav_item').length;
	 //start slider2
	    if ($(".slider2").length) {
	        $('.slider2').cycle({
	            fx: 'scrollHorz',
	            speed: 600,
	            timeout: 9000,
	            next: '.next1',
	            prev: '.prev1',                
	            easing: 'easeInOutExpo',
	            cleartypeNoBg: true ,
	            rev:0,
	            startingSlide: 0,
	            wrap: true,
	            before: function(currSlideElement, nextSlideElement) {
	            	$('.nav_item').each(function(index,elem){
	            		if (index!=(ind)){$(this).removeClass('active');} else {$(this).addClass('active');}
	            	});
	            	ind++;
	            	if(ind>(len-1)) {ind=0;}
		        }
	        })
	    };
	    
	    $('.nav_item').bind('click',function(){
	        //ind = $(this).index()-1;
	        ind = $(this).index()-0;
	        $('.nav_item').each(function(index,elem){if (index!=(ind)){$(this).removeClass('active');}});
	        $(this).addClass('active');
	        $('.slider2').cycle(ind);
	    });
	    //end slider2


//-----Window resize------------------------------------------------------------------------------------------
	$(window).resize(
        function(){
            resize_function();
        }
    ).trigger('resize');

	function resize_function(){
	    var h_cont = $('header').height();
	    var wh = $(window).height();
		m_top = ~~(wh-h_cont)/2-100;
            if(isSplash){
                /*$("header").stop(true).delay(300).animate({"top":m_top}, 350, "easeOutSine");*/
                /*$("footer").stop(true).animate({"height":88}, 350, "easeOutSine");*/
            }else{
                /*$("header").stop(true).animate({"top":0}, 500, "easeOutCubic");*/
            }          
    }
    $(document).resize(
        function(){}
    ).trigger('resize');

    $(".fancybox").fancybox({}); 


});