/*---------------------------------------------------------------------*/
;(function($){

/*================= Global Variable Start =================*/		   
var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
var IEbellow9 = !$.support.leadingWhitespace;
var iPhoneAndiPad = /iPhone|iPod/i.test(navigator.userAgent);
var isIE = navigator.userAgent.indexOf('MSIE') !== -1 || navigator.appVersion.indexOf('Trident/') > 0;
function isIEver () {
  var myNav = navigator.userAgent.toLowerCase();
  return (myNav.indexOf('msie') != -1) ? parseInt(myNav.split('msie')[1]) : false;
}
//if (isIEver () == 8) {}
		   
var ww = document.body.clientWidth, wh = document.body.clientHeight;
var mobilePort = 800, ipadView = 1024, wideScreen = 1600;

/*================= Global Variable End =================*/	


/*================= On Document Load Start =================*/	
$(document).ready( function(){
	$('body').removeClass('noJS').addClass("hasJS");
	$(this).scrollTop(0);
	getWidth();
	menuMove();
	homePhotoSlider();
	homeVideoSlider();
	mediaCarasoulSlider();
	
	setTimeout(function(){
		$('body').addClass('loaded');
	}, 1500);
	
	//Custome Select Dropdown
	if( $(".customSelect").length){
		$('.customSelect').customSelect();
	}

	
	//Home Slider
	if($(".homeBanner").length){
		var menu = ['Slide 22', 'Slide 2', 'Slide 3', 'Slide 4', 'Slide 5', 'Slide 6']
		var homeSlider = new Swiper('.homeBanner .swiper-container', {
			spaceBetween: 0,
			pagination: ".swiper-pagination",
			paginationClickable: true,
			speed: 1100,
			loop: true,
			keyboard: true,
			//effect: 'fade',
			//parallax:true,
			autoplay: {
				delay: 3000,
				disableOnInteraction: false,
			},
			pagination: {
      el: '.swiper-pagination',
			clickable: true,
        renderBullet: function (index, className) {
          return '<span class="' + className + '">' + (menu[index]) + '</span>';
        },
    },
			navigation: {
                    nextEl: '.home-slider-next',
                    prevEl: '.home-slider-prev',
                },
		});
	}
	
	if($(".homeBanner").length){
		$(".homeBannerImgWrap").each(function(){
			var imagePath = $(this).find("img").attr("src");
			$(this).css("background-image","url( "+ imagePath +" )");
		});
	}
	


	
	
	if ($(".product4ColSlider").length) {
		var product4ColSlider = new Swiper('.product4ColSlider .swiper-container', {
			infiniteSlides: false,
			speed:800,
			slidesPerView: 4,
			autoplay: {
				delay: 8000,
				disableOnInteraction: false,
			},
			pagination: {
				el: '.swiper-pagination',
				clickable: false,
			},
			navigation: {
				nextEl: '.product4ColSlider-next',
				prevEl: '.product4ColSlider-prev',
			},
			breakpoints: {
				1023: {
					slidesPerView: 1,
					spaceBetween: 0
				}
			},
		
		});
	}
	
	
	if ($(".product5ColSlider").length) {
		var product5ColSlider = new Swiper('.product5ColSlider .swiper-container', {
			infiniteSlides: false,
			speed:800,
			slidesPerView: 5,
			autoplay: {
				delay: 8000,
				disableOnInteraction: false,
			},
			pagination: {
				el: '.swiper-pagination',
				clickable: false,
			},
			navigation: {
				nextEl: '.product5ColSlider-next',
				prevEl: '.product5ColSlider-prev',
			},
			breakpoints: {
				1023: {
					slidesPerView: 1,
					spaceBetween: 0
				}
			},
		
		});
	}
	
	//top selling commodities 2 row slider 
	if ($(".twoRows4colSlider").length) {
		var twoRows4colSlider = new Swiper('.twoRows4colSlider .swiper-container', {
			infiniteSlides: false,
			speed:800,
			slidesPerView: 4,
			slidesPerColumn: 2,
			autoplay: {
				delay: 8000,
				disableOnInteraction: false,
			},
			pagination: {
				el: '.swiper-pagination',
				clickable: false,
			},
			navigation: {
				nextEl: '.twoRows4colSlider-next',
				prevEl: '.twoRows4colSlider-prev',
			},
			breakpoints: {
				1023: {
					slidesPerView: 1,
					spaceBetween: 0
				}
			},
		
		});
	}
	
	if ($(".product2ColSlider").length) {
		var product2ColSlider = new Swiper('.product2ColSlider .swiper-container', {
			infiniteSlides: false,
			speed:900,
			slidesPerView: 2,
			spaceBetween: 19,
			autoplay: {
				delay: 6000,
				disableOnInteraction: false,
			},
			pagination: {
				el: '.swiper-pagination',
				clickable: false,
			},
			navigation: {
				nextEl: '.product2ColSlider-next',
				prevEl: '.product2ColSlider-prev',
			},
			breakpoints: {
				1023: {
					slidesPerView: 1,
					spaceBetween: 0
				}
			},
		
		});
	}	
	
	if ($(".product2ColSlidernew").length) {
		var product2ColSlidernew = new Swiper('.product2ColSlidernew .swiper-container', {
			infiniteSlides: false,
			speed:900,
			slidesPerView: 2,
			spaceBetween: 19,
			autoplay: false,
			
			pagination: {
				el: '.swiper-pagination',
				clickable: false,
			},
			navigation: {
				nextEl: '.product2ColSlidernew-next',
				prevEl: '.product2ColSlidernew-prev',
			},
			breakpoints: {
				1023: {
					slidesPerView: 1,
					spaceBetween: 0
				}
			},
		
		});
	}
	
	
	
	
	if ($(".product2pColSlider").length) {
		var product2pColSlider = new Swiper('.product2pColSlider .swiper-container', {
			infiniteSlides: false,
			speed:900,
			slidesPerView: 2,
			spaceBetween: 19,
			autoplay: {
				delay: 6000,
				disableOnInteraction: false,
			},
			pagination: {
				el: '.swiper-pagination',
				clickable: false,
			},
			navigation: {
				nextEl: '.product2pColSlider-next',
				prevEl: '.product2pColSlider-prev',
			},
			breakpoints: {
				1023: {
					slidesPerView: 1,
					spaceBetween: 0
				}
			},
		
		});
	}
	
	if ($(".product2p1ColSlider").length) {
		var product2p1ColSlider = new Swiper('.product2p1ColSlider .swiper-container', {
			infiniteSlides: false,
			speed:900,
			slidesPerView: 2,
			spaceBetween: 19,
			autoplay: {
				delay: 6000,
				disableOnInteraction: false,
			},
			pagination: {
				el: '.swiper-pagination',
				clickable: false,
			},
			navigation: {
				nextEl: '.product2p1ColSlider-next',
				prevEl: '.product2p1ColSlider-prev',
			},
			breakpoints: {
				1023: {
					slidesPerView: 1,
					spaceBetween: 0
				}
			},
		
		});
	}
	
	
	
	
	
	
	
	if ($(".product21ColSlider1").length) {
		var product21ColSlider1 = new Swiper('.product21ColSlider1 .swiper-container', {
			infiniteSlides: false,
			speed:5000,
			direction: 'vertical',
			slidesPerView: 3,
			spaceBetween: 19,
			autoplay: {
				delay: 6000,
				disableOnInteraction: false,
			},
			pagination: {
				el: '.swiper-pagination',
				clickable: false,
			},
			navigation: {
				nextEl: '.product21ColSlider1-next',
				prevEl: '.product21ColSlider1-prev',
			},
			breakpoints: {
				1023: {
					slidesPerView: 3,
					spaceBetween: 0
				}
			},
		
		});
	}
	
	if ($(".product21ColSlider2").length) {
		var product21ColSlider2 = new Swiper('.product21ColSlider2 .swiper-container', {
			infiniteSlides: false,
			 direction: 'vertical',
			speed:5000,
			slidesPerView: 3,
			loop:true,
			parallax: true,
			spaceBetween: 19,
			autoplay: {
				delay: 1000,
				disableOnInteraction: false,
			},
			pagination: {
				el: '.swiper-pagination',
				clickable: false,
			},
			navigation: {
				nextEl: '.product21ColSlider2-next',
				prevEl: '.product21ColSlider2-prev',
			},
			breakpoints: {
				1023: {
					slidesPerView: 3,
					spaceBetween: 0
				}
			},
		
		});
	}
	
	
	if ($(".featuredProductsSlider").length) {
		var featuredProductsSlider = new Swiper('.featuredProductsSlider .swiper-container', {
			infiniteSlides: false,
			speed:800,
			slidesPerView: 4,
			autoplay: {
				delay: 8000,
				disableOnInteraction: false,
			},
			pagination: {
				el: '.swiper-pagination',
				clickable: false,
			},
			navigation: {
				nextEl: '.featuredProductsSlider-next',
				prevEl: '.featuredProductsSlider-prev',
			},
			breakpoints: {
				1023: {
					slidesPerView: 1,
					spaceBetween: 0
				}
			},
		
		});
	}
	
	if ($(".product3ColSlider").length) {
		var product3ColSlider = new Swiper('.product3ColSlider .swiper-container', {
			infiniteSlides: false,
			speed:900,
			slidesPerView: 3,
			spaceBetween: 0,
			simulateTouch:false,
			allowTouchMove:false,
			autoplay: {
				delay: 6000,
				disableOnInteraction: false,
			},
			pagination: {
				el: '.swiper-pagination',
				clickable: false,
			},
			navigation: {
				nextEl: '.product3ColSlider-next',
				prevEl: '.product3ColSlider-prev',
			},
			breakpoints: {
				1023: {
					slidesPerView: 1,
					spaceBetween: 0
				}
			},
		
		});
	}
	
	if ($(".product1ColSlider").length) {
		var product1ColSlider = new Swiper('.product1ColSlider .swiper-container', {
			infiniteSlides: false,
			speed:900,
			slidesPerView: 1,
			spaceBetween: 0,
			simulateTouch:false,
			allowTouchMove:false,
			//autoplay: {
			//	delay: 6000,
			///	disableOnInteraction: false,
			//},
			pagination: {
				el: '.swiper-pagination',
				clickable: false,
			},
			navigation: {
				nextEl: '.product1ColSlider-next',
				prevEl: '.product1ColSlider-prev',
			},
			breakpoints: {
				1023: {
					slidesPerView: 1,
					spaceBetween: 0
				}
			},
		
		});
	}
	
	$('.best-seller .nav-tabs a').click(function(){
	setTimeout(function(){
		if ($(".product3ColSlider").length) {
		var product3ColSlider = new Swiper('.product3ColSlider .swiper-container', {
			infiniteSlides: false,
			speed:900,
			slidesPerView: 3,
			spaceBetween: 0,
			simulateTouch:false,
			allowTouchMove:false,
			autoplay: {
				delay: 6000,
				disableOnInteraction: false,
			},
			pagination: {
				el: '.swiper-pagination',
				clickable: false,
			},
			navigation: {
				nextEl: '.product3ColSlider-next',
				prevEl: '.product3ColSlider-prev',
			},
			breakpoints: {
				1023: {
					slidesPerView: 1,
					spaceBetween: 0
				}
			},
		
		});
	}
	}, 200);
	});
	
	if ($(".footer-logo-slider").length) {
		var footerLogoSlider = new Swiper('.footer-logo-slider .swiper-container', {
			loop: false,
			speed:800,
			slidesPerView: 5,
			autoplay:false,
			
			pagination: {
				el: '.swiper-pagination',
				clickable: false,
			},
			navigation: {
				nextEl: '.footer-logo-slider-next',
				prevEl: '.footer-logo-slider-prev',
			},
			breakpoints: {
				568: {
					slidesPerView: 1,
					spaceBetween: 10
				},
				1023: {
					slidesPerView: 5,
					spaceBetween: 10
				}
			},
		
		});
	}
	
	
	
	

	//Countdown
	// var countDownDate = new Date("April 25, 2020 15:20:40").getTime();
	
	// var x = setInterval(function() {
	//   var now = new Date().getTime();
	//   var distance = countDownDate - now;
		
	//   var days = Math.floor(distance / (1000 * 60 * 60 * 24));
	//   var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
	//   var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
	//   var seconds = Math.floor((distance % (1000 * 60)) / 1000);
		
	//   document.getElementById("countDown").innerHTML = '<ul>' + '<li>' + days + "<span>Days</span>" + '</li><li>' + hours + "<span>Hours</span>" + '</li><li>' + minutes + "<span>Mins</span>" + '</li><li>' + seconds + "<span>Secs</span>" +'</li>' + '</ul>';
		
	//   if (distance < 0) {
	// 	clearInterval(x);
	// 	document.getElementById("countDown").innerHTML = "";
	//   }
	// }, 1000);
	
			

	
	// Page Scrolling
	$('a[href="#content"]').click(function(){
		skipTo = $(this).attr('href');
		skipTo = $(skipTo).offset().top - 10;
		$('html, body').animate({scrollTop:skipTo}, '1000');
		//$(this).fadeOut('fast');
		//$('.slideArrow2').fadeIn('fast');
		return false;
	});
	
	if($(".innerBanner").length){
		$(".pageBannerImg").each(function(){
			  var imagePath=$(this).find("img").attr("src");
			  $(this).css("background-image","url( "+imagePath+" )");
		});
	}
	
	if( $(".marqueeScrolling li").length > 1){
		var $mq = $('.marquee').marquee({
			 speed: 25000
			,gap: 0
			,duplicated: true
			,pauseOnHover: true
			});
		$(".btnMPause").toggle(function(){
			$(this).addClass('play');
			$(this).text('Play');
			$mq.marquee('pause');
		},function(){
			$(this).removeClass('play');
			$(this).text('Pause');
			$mq.marquee('resume');
			return false;
		});
	};
	
	// Multiple Ticker	
	if( $(".ticker").length){
		$('.ticker').each(function(i){
			$(this).addClass('tickerDiv'+i).attr('id', 'ticker'+i);
			$('#ticker'+i).find('.tickerDivBlock').first().addClass('newsTikker'+i).attr('id', 'newsTikker'+i);
			$('#ticker'+i).find('a.playPause').attr('id', 'stopNews'+i)
			$('#newsTikker'+i).vTicker({ speed: 1E3, pause: 4E3, animation: "fade", mousePause: false, showItems: 3, height: 150, direction: 'up' })
			$("#stopNews"+i).click(function () {
				if($(this).hasClass('stop')){
					$(this).removeClass("stop").addClass("play").text("Play").attr('title', 'Play');
				}else{
					$(this).removeClass("play").addClass("stop").text("Pause").attr('title', 'pause');
				}
				return false;
			});
		});
	};
	
	
	
	// Responsive Tabing Script
	if( $(".resTab").length) {
		$('.resTab').responsiveTabs({
			 rotate: false
			,startCollapsed: 'tab' //accordion
			,collapsible: 'accordion' //accordion
			,scrollToAccordion: true
			,scrollToAccordionOnLoad:false
		});
	};
				
	if( $(".accordion").length){
	   $('.accordion .accordDetail').hide();
	   $(".accordion .accordDetail:first").show(); 
	   $(".accordion .accTrigger:first").addClass("active");	
	   $('.accordion .accTrigger').click(function(){
		  if ($(this).hasClass('active')) {
			   $(this).removeClass('active');
			   $(this).next().slideUp();
		  } else {
			  if ($('body').hasClass('desktop')) {
			   $('.accordion .accTrigger').removeClass('active');
			   $('.accordion .accordDetail').slideUp();
			  }
			   $(this).addClass('active');			   
			   $(this).next().slideDown();
		  }
		  return false;
	   });
	};
	
	if( $(".tableData").length > 0){
		$('.tableData').each(function(){
			$(this).wrap('<div class="tableOut"></div>');
			$(this).find('tr').each(function(){
			$(this).find('td:first').addClass('firstTd');
			$(this).find('th:first').addClass('firstTh');
			$(this).find('th:last').addClass('lastTh');
			});
			$(this).find('tr:last').addClass('lastTr');
			$(this).find('tr:even').addClass('evenRow');
			$(this).find('tr:nth-child(2)').find('th:first').removeClass('firstTh');
		});	
	};
	
	// Responsive Table
	if( $(".responsiveTable").length){
		$(".responsiveTable").each(function(){		
		$(this).find('td').removeAttr('width');
		//$(this).find('td').removeAttr('align');
		var head_col_count =  $(this).find('tr th').size();
		// loop which replaces td
		for ( i=0; i <= head_col_count; i++ )  {
			// head column label extraction
			var head_col_label = $(this).find('tr th:nth-child('+ i +')').text();
			// replaces td with <div class="column" data-label="label">
			$(this).find('tr td:nth-child('+ i +')').attr("data-label", head_col_label);
		}
		});
	};
	
	// Responsive Table
	if( $(".tableScroll").length){
		$(".tableScroll").each(function(){
			$(this).wrap('<div class="tableOut"></div>');
		});
	};
	
	//product color changes scripts
	if( $(".prodColSelect").length){
			
			$( "li .proColorIcon.red" ).hover(function() {
			     	$(this).parents(".card-body").siblings().children(".red").show();
					$(this).parents(".card-body").siblings().children(".red").siblings().hide();
			});
						$( "li .proColorIcon.yellow" ).hover(function() {
			     	$(this).parents(".card-body").siblings().children(".yellow").show();
					$(this).parents(".card-body").siblings().children(".yellow").siblings().hide();
			});
						$( "li .proColorIcon.blue" ).hover(function() {
			     	$(this).parents(".card-body").siblings().children(".blue").show();
					$(this).parents(".card-body").siblings().children(".blue").siblings().hide();
			});
						$( "li .proColorIcon.white" ).hover(function() {
			     	$(this).parents(".card-body").siblings().children(".white").show();
					$(this).parents(".card-body").siblings().children(".white").siblings().hide();
			});
						
						$( "li .proColorIcon.brown" ).hover(function() {
			     	$(this).parents(".card-body").siblings().children(".brown").show();
					$(this).parents(".card-body").siblings().children(".brown").siblings().hide();
			});
						$( "li .proColorIcon.green" ).hover(function() {
			     	$(this).parents(".card-body").siblings().children(".green").show();
					$(this).parents(".card-body").siblings().children(".green").siblings().hide();
			});
			
				$( "li .proColorIcon.multicolor" ).hover(function() {
			     	$(this).parents(".card-body").siblings().children(".multicolor").show();
					$(this).parents(".card-body").siblings().children(".multicolor").siblings().hide();
			});
			
			

	};
	
	//product details color changes scripts
	if( $(".productDetail").length){
			
			$( ".productDetailsInfo .proColorIcon.red" ).hover(function() {
			     	$(this).parents(".productDetail").children().find(".red").show();
					$(this).parents(".productDetail").children().find(".red").siblings().hide().removeClass(zoom);
			});
			
			$( ".productDetailsInfo .proColorIcon.yellow" ).hover(function() {
			     	$(this).parents(".productDetail").children().find(".yellow").show();
					$(this).parents(".productDetail").children().find(".yellow").siblings().hide().removeClass(zoom);
			});
			
			$( ".productDetailsInfo .proColorIcon.blue" ).hover(function() {
			     	$(this).parents(".productDetail").children().find(".blue").show();
					$(this).parents(".productDetail").children().find(".blue").siblings().hide().removeClass(zoom);
			});
			
			$( ".productDetailsInfo .proColorIcon.white" ).hover(function() {
			     	$(this).parents(".productDetail").children().find(".white").show();
					$(this).parents(".productDetail").children().find(".white").siblings().hide().removeClass(zoom);
			});
			$( ".productDetailsInfo .proColorIcon.brown" ).hover(function() {
			     	$(this).parents(".productDetail").children().find(".brown").show();
					$(this).parents(".productDetail").children().find(".brown").siblings().hide().removeClass(zoom);
			});
			$( ".productDetailsInfo .proColorIcon.green" ).hover(function() {
			     	$(this).parents(".productDetail").children().find(".green").show();
					$(this).parents(".productDetail").children().find(".green").siblings().hide().removeClass(zoom);
			});
			$( ".productDetailsInfo .proColorIcon.multicolor" ).hover(function() {
			     	$(this).parents(".productDetail").children().find(".multicolor").show();
					$(this).parents(".productDetail").children().find(".multicolor").siblings().hide().removeClass(zoom);
			});
			
			
			/*$( ".productDetailsInfo .proColorIcon.red" ).hover(function() {
			     	$(this).parents(".card-body").siblings().children(".red").show();
					$(this).parents(".card-body").siblings().children(".red").siblings().hide();
			});
						$( ".productDetailsInfo .proColorIcon.yellow" ).hover(function() {
			     	$(this).parents(".card-body").siblings().children(".yellow").show();
					$(this).parents(".card-body").siblings().children(".yellow").siblings().hide();
			});
						$( ".productDetailsInfo .proColorIcon.blue" ).hover(function() {
			     	$(this).parents(".card-body").siblings().children(".blue").show();
					$(this).parents(".card-body").siblings().children(".blue").siblings().hide();
			});
						$( ".productDetailsInfo .proColorIcon.white" ).hover(function() {
			     	$(this).parents(".card-body").siblings().children(".white").show();
					$(this).parents(".card-body").siblings().children(".white").siblings().hide();
			});*/
			
			

	};
	
	
	// Back to Top function
	if( $("#backtotop").length){
		$(window).scroll(function(){
			if ($(window).scrollTop()>120){
			$('#backtotop').fadeIn('250').css('display','block');}
			else {
			$('#backtotop').fadeOut('250');}
		});
		$('#backtotop').click(function(){
			$('html, body').animate({scrollTop:0}, '200');
			return false;
		});
	};
	
	// Get Focus Inputbox
	if( $(".getFocus").length){
			$(".getFocus").each(function(){
			$(this).on("focus", function(){
			if ($(this).val() == $(this)[0].defaultValue) { $(this).val("");};
		  }).on("blur", function(){
			  if ($(this).val() == "") {$(this).val($(this)[0].defaultValue);};
		  });								  
		});
	};
	
	// For device checking
	if (isMobile == false) {
	
	};
	
	// Video JS
	if( $(".videoplayer").length){	
		$(".videoplayer").each(function(){
			var $this = $(this);
			var poster = $this.children("a").find("img").attr("src");
			var title = $this.children("a").find("img").attr("alt");	
			var videotype = $this.children("a").attr("rel");
			var video = $this.children("a").attr("href");
			$this.children("a").remove();
			
			projekktor($this, {
			 poster: poster
			,title: title
			,playerFlashMP4: '../videoplayer/jarisplayer.swf'
			,playerFlashMP3: '../videoplayer/jarisplayer.swf'
			,width: 1366
			,height: 524
			,fullscreen:true
			,playlist: [
				{0: {src: video, type: videotype}}
			],
			plugin_display: {
				logoImage: '',
				logoDelay: 1
			}
			}, function(player) {} // on ready 
			);
		})
	};
	
	
	if( $(".litebox").length){	
		$('.litebox').liteBox();
	};	
	
	
	setTimeout (function(){
		if( $(".fixedErrorMsg").length){					 
			$(".fixedErrorMsg").slideDown("slow");				 
			setTimeout( function(){$('.fixedErrorMsg').slideUp();},5000 );
		}
		if( $(".fixedSuccessMsg").length){					 
			$(".fixedSuccessMsg").slideDown("slow");				 
			setTimeout( function(){$('.fixedSuccessMsg').slideUp();},5000 );
		}
	},500);
	
	/*================= On Document Load and Resize Start =================*/
	$(window).on('resize', function () {
									 
		ww = document.body.clientWidth; 
		wh = document.body.clientHeight;		
		
		$('.vCenter').each(function () {$(this).verticalAlign();});	
		
		if($("body").hasClass("mobilePort")){
			$("body").removeClass("wob");
		}
		
		//$('.container').resize(function(){});
		
    }).trigger('resize');
	/*================= On Document Load and Resize End =================*/
	
	//Navigation
	// if( $("#navMob").length) {
		// if($(".toggleMenu").length == 0){
			// $("#mainNav").prepend('<div class="menuBar"><a href="#" class="toggleMenu"><span class="mobileMenu">Menu</span><span class="iconBar homeSprite"></span></a></div>');	
		// }
		// $(".toggleMenu").click(function() {
			// $(this).toggleClass("active");
			
			// $("body").addClass("activeMobNav");
			// return false;
		// });
		// $("#navMob li a, #mainNav > ul > li a").each(function() {
			// if ($(this).next().length) {
				// $(this).parent().addClass("parent");
			// };
		// })
		// $("#mainNav > ul > li > a").each(function() {
			// if ($(this).next().hasClass("megaMenu")) {
				// $(this).parent().addClass("megaMenuWrap");
			// };
		// })
		// $("#navMob li.parent").each(function () {
			// if ($(this).has(".menuIcon").length <= 0) $(this).append('<i class="menuIcon">&nbsp;</i>')
		// });
		// dropdown('nav', 'hover', 1);
		// adjustMenu();
		
	// };


	
	// Message on Cookie Disabled
	$.cookie('cookieWorked', 'yes', { path: '/' });
	if ($.cookie('cookieWorked') == 'yes') {
		}
	else{
		if( $("div.jsRequired").length == 0){
			$("body").prepend(
				'<div class="jsRequired">Cookies are not enabled on your browser. Need to adjust this in your browser security preferences. Please enable cookies for better user experience.</div>'
			);	
		}
	}

	
	$('a.pageScroll').bind('click', function(event) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: ($($anchor.attr('href')).offset().top - 50)
        }, 1200);
        event.preventDefault();
    });
	
	if( $(".iuacFacilitiesList").length) {
		$(".iuacFacilitiesList li").each(function(i) {
     		$(this).find(".icon").addClass("icon"+(i+1));
    	});
	}
	
	//On scroll Header Fixed
	$(window).scroll(function(){
		if ($(window).scrollTop() >= 5) {
			$('#header').addClass('headerFixed');
		}
		else {
			$('#header').removeClass('headerFixed');
		}
	});
	
	
	
});
/*================= On Document Load End =================*/

/*================= On Window Resize Start =================*/	
$(window).bind('resize orientationchange', function() {
	getWidth();	
	adjustMenu();
	menuMove();
});

/*================= On Window Resize End =================*/	

/*================= On Window Load Start =================*/
$(window).load(function() {
						
});
/*================= On Document Load End =================*/


function getWidth() {
	ww = document.body.clientWidth;
	if(ww>wideScreen){$('body').removeClass('device').addClass('desktop widerDesktop');}
	if(ww>mobilePort && ww<=wideScreen){	$('body').removeClass('device widerDesktop').addClass('desktop');}
	if(ww<=mobilePort) {$('body').removeClass('desktop widerDesktop').addClass('device');}
	if(ww > 767 && ww < 1025){$('body').addClass('ipad');}
	else {$('body').removeClass('ipad');}
}

})(jQuery);


function validate() {
    return false;
};


function menuMove(){
	if($(".mobileNav").length == 0){
	var navigation= $('#nav').clone();
	$(navigation).appendTo("body").wrap("<div class='mobileNav'></div>");
		if($(".mobileNav #navMob").length == 0){
			$(".mobileNav #nav").attr("id", "navMob");
			$(".mobileNav").append("<span class='close'></span>");
			$(".mobileNav").append("<span class='navigationText'>Navigation</span>");
			$(".mobileNav").append("<span class='logoText'><span class='logoIcon homeSprite'></span></span>");
			$(".mobileNav .close").click(function() {
				$("body").removeClass("activeMobNav");
			});
		}
	}
}



function homePhotoSlider(){
if($(".photoWidgetSlider").length){
		var photoWidgetSlider = new Swiper('.photoWidgetSlider .swiper-container', {
			spaceBetween: 5,
			speed: 1100,
			loop: false,
			simulateTouch: false,
			effect: 'fade',
			//parallax:true,
			autoplay: {
				delay: 8000,
				disableOnInteraction: false,
			},
			navigation: {
                    nextEl: '.photoWidgetSlider-next',
                    prevEl: '.photoWidgetSlider-prev',
            }
		});
	}
	}
function homeVideoSlider(){	
	if($(".videoWidgetSlider").length){
		var videoWidgetSlider = new Swiper('.videoWidgetSlider .swiper-container', {
			spaceBetween: 5,
			speed: 1100,
			loop: false,
			simulateTouch: false,
			effect: 'fade',
			//parallax:true,
			autoplay: {
				delay: 8000,
				disableOnInteraction: false,
			},
			navigation: {
                    nextEl: '.videoWidgetSlider-next',
                    prevEl: '.videoWidgetSlider-prev',
            }
		});
	}
}

function mediaCarasoulSlider(){	
	if($(".mediaCarasoul").length){
		var mediaCarasoul = new Swiper('.mediaCarasoul .swiper-container', {
		speed: 1100,
    	spaceBetween: 20,
    	initialSlide: 0,
    	direction: 'horizontal',
		loop:true,
	    //loop: true,
    	autoplay:{
			delay: 1500,
			disableOnInteraction: false,
		},	
    	navigation: {
                    nextEl: '.mediaNext',
                    prevEl: '.mediaPrev',
            },
		effect: 'slide',
		slidesPerView: 2,
		centeredSlides: false,
		slidesOffsetBefore: 0,
		grabCursor: true,
		});
		
		$(".mediaPause").click(function(){
			if($(".mediaPause").children().hasClass("fa-pause")){										
                mediaCarasoul.autoplay.stop();
				$(".mediaPause").children().removeClass("fa-pause").addClass("fa-play");
			}
			else{
				mediaCarasoul.autoplay.start();
				$(".mediaPause").children().removeClass("fa-play").addClass("fa-pause");
			}
        });
	}
}

function videoEnded(video) {
    video.load();
};


// Custom File Upload 

$('#chooseFile').bind('change', function () {
  var filename = $("#chooseFile").val();
  if (/^\s*$/.test(filename)) {
    $(".file-upload").removeClass('active');
    $("#noFile").text("No file chosen..."); 
  }
  else {
    $(".file-upload").addClass('active');
    $("#noFile").text(filename.replace("C:\\fakepath\\", "")); 
  }
});













	$(document).ready(function(){
			$('.zoom').zoom();
			
		});