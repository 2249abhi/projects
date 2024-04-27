//Skeep to main content
$('a[href="#content"]').click(function(){
    skipTo = $(this).attr('href');
    skipTo = $(skipTo).offset().top - 200;
    $('html, body').animate({scrollTop:skipTo}, '300');
    return false;
});


// skip to content issue regarding
$('a[href="#content"]').click(function(){
    skipContent = $(".skipContent");
    skipContent = $(skipContent).offset().top - 200;
    $('html, body').animate({scrollTop:skipContent}, '300');
    return false;
});

$(function() {
  $('.scrolldown a[href*="#"]').on('click', function(e) {
    e.preventDefault();
    $('html, body').animate({ scrollTop: $($(this).attr('href')).offset().top-100}, 500, 'linear');
  });
});


$(document).ready(function(){
    if (location.hash){
        setTimeout(function(){
            $('html, body').scrollTop(0).show();
            jump();
        }, 100);
    }else{
        $('html, body').show();
    }
    var jump = function(e){
        if (e){
            var target = $(this).attr("href").replace('/', '');
        } else {
            var target = location.hash;
        }
        $('html,body').animate({
            scrollTop: ($(target).offset().top) - 100
            },2000,function()
        {
        });
    }
});
// Access Script
var siteName = $("#hdnSiteName").val();
akCookie = {
    create: function(name, value, days) {
        var expires = "";
        if (days) {
            var expireDate = new Date;
            expireDate.setTime(expireDate.getTime() + days * 24 * 60 * 60 * 1E3);
            expires = "; expires=" + expireDate.toGMTString()
        }
        document.cookie = name + "=" + value + expires + "; path=/"
    },
    read: function(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(";");
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == " ") c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length)
        }
        return null
    },
    erase: function(name) {
        akCookie.create(name, "", -1)
    }
};
$(function() {
    $("#accessControl").show();
    akAccess = function() {
        var sizes={normal:"140.5%",large:"166.8%",smaller:"120.3%"};
        var contrasts = ["normal", "wob"];
        var chosenSize;
        var chosenContrast;
        var cookieValue = akCookie.read(siteName);
        if (cookieValue !== null) {
            settings = cookieValue.split(" ");
            _setFontSize(settings[0]);
            _setContrast(settings[1])
        } else {
            $("input.font-normal").addClass("current");
            $("input.contrast-normal").addClass("current")
        }

        function _setFontSize(size) {
            $("body").css("font-size", sizes[size]);
            for (key in sizes)
                if (size === key) chosenSize = key;
            if (!chosenSize) chosenSize = "normal";
            $(".fontScaler").removeClass("current");
            $(".font-" + chosenSize).addClass("current")
        }

        function _setContrast(contrast) {
            chosenContrast = contrast;
            if (!contrast in contrasts) contrast = "normal";
            if (contrast === "normal") $("body").removeClass(contrasts.join(" "));
            else $("body").addClass(contrast);
            $("input.contrastChanger").each(function() {
                $(this).removeClass("current")
            });
            $("input.contrast-" + chosenContrast).addClass("current")
        }

        function _save() {
            akCookie.create(siteName, chosenSize + " " + chosenContrast, 2)
        }
        return {
            handleFontSizeEvent: function(size) {
                var varFontSize = size.split("_")[size.split("_").length - 1];
                _setFontSize(varFontSize);
                _save();
                return false
            },
            handleContrastEvent: function(contrast) {
                var varContrastSize = contrast.split("_")[contrast.split("_").length - 1];
                _setContrast(varContrastSize);
                _save();
                return false
            }
        }
    }();
    $("#accessControl input.fontScaler").click(function() {
        akAccess.handleFontSizeEvent($(this).attr("id"));
        $("#accessControl input.fontScaler").each(function() {
            $(this).removeClass("current")
        });
        $(this).addClass("current");
        return false
    });
    $("#accessControl input.contrastChanger").click(function() {
        akAccess.handleContrastEvent($(this).attr("id"));
        return false
    })
});


jQuery(document).ready(function() {
    // Search Box
    if($(".zmdi-search").length) {
        $('.zmdi-search').click(function(e){
            e.preventDefault();
            if(!$(this).hasClass('active')){
                $(this).addClass('active');
                $(this).parent().find(".topnav").slideDown(300);
            } else {
                $(this).removeClass('active');
                $(this).parent().find(".topnav").slideUp(300);
            }
            return false;
        });
    }
    $('.search').click(function(e){
        e.stopPropagation();
    });    
    $(document).click(function(){
        $('.topnav').slideUp();
        $('.zmdi-search').removeClass('active');
    });
    
    $('#searchkeyword').keyup(function (e) {
        var yourInput = $(this).val();
        re = /[`~!@#$%^&*()_|+\-=?;:'",<>\{\}\[\]\\\/]/gi;
        var isSplChar = re.test(yourInput);
        if(isSplChar){
            var no_spl_char = yourInput.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
            alert('Special characters are not allowed.')
            $(this).val(no_spl_char);
        }
    });
});