function setNewsletterCookie() {
    jQuery.cookie("newsletter_popup", "dontshowitagain")
}

function portoAlert(a) {
    jQuery('<div class="note-msg container alert" style="display:none;position:fixed;top:30px;margin-left:-30px;z-index:9999;">' + a + "</div>").appendTo("div.main"), jQuery(".alert").fadeIn(500), setTimeout(function() {
        jQuery(".alert").fadeOut(500), setTimeout(function() {
            jQuery(".alert").remove()
        }, 500)
    }, 3e3)
}
jQuery.fn.extend({
    scrollToMe: function() {
        if (jQuery(this).length) {
            var a = jQuery(this).offset().top - 100;
            jQuery("html,body").animate({
                scrollTop: a
            }, 500)
        }
    },
    scrollToJustMe: function() {
        if (jQuery(this).length) {
            var a = jQuery(this).offset().top;
            jQuery("html,body").animate({
                scrollTop: a
            }, 500)
        }
    }
}), jQuery(function(a) {
    // a("div.product-view p.no-rating a, div.product-view .rating-links a").click(function() {
    a("div.product-view p.no-rating abc, div.product-view .rating-links abc").click(function() {
        return a(".product-tabs ul li").removeClass("active"), a("#tab_review_tabbed").addClass("active"), a(".product-tabs .tab-content").hide(), a("#tab_review_tabbed_contents").show(), a("#tab_review_tabbed").scrollToMe(), !1
    }), a(".word-rotate").each(function() {
        var b = a(this),
            c = a(this).find(".word-rotate-items"),
            d = c.find("> span"),
            e = d.eq(0),
            f = e.clone(),
            g = 0,
            h = 1,
            i = 0;
        g = e.height(), c.append(f), b.height(g).addClass("active"), setInterval(function() {
            i = h * g, c.animate({
                top: -i + "px"
            }, 300, function() {
                h++, h > d.length && (c.css("top", 0), h = 1)
            })
        }, 2e3)
    }), a(window).stellar({
        responsive: !0,
        scrollProperty: "scroll",
        parallaxElements: !1,
        horizontalScrolling: !1,
        horizontalOffset: 0,
        verticalOffset: 0
    });
    var b = a(window).innerWidth(),
        c = a(window).innerHeight(),
        d = b / c,
        e = 320,
        f = 240,
        g = e / f;
    a(".full-screen-slider div.item").css("position", "relative"), a(".full-screen-slider div.item").css("overflow", "hidden"), a(".full-screen-slider div.item").width(b), a(".full-screen-slider div.item").height(c), a(".full-screen-slider div.item > video").css("position", "absolute"), a(".full-screen-slider div.item > video").bind("loadedmetadata", function() {
        e = this.videoWidth, f = this.videoHeight, g = e / f, d >= g ? (a(this).width(b), a(this).height(""), a(this).css("left", "0px"), a(this).css("top", (c - b / e * f) / 2 + "px")) : (a(this).width(""), a(this).height(c), a(this).css("left", (b - c / f * e) / 2 + "px"), a(this).css("top", "0px")), a(this).get(0).play()
    }), a(".header-container.type10 .dropdown-menu .menu-container>a").click(function() {
        a("body").hasClass("cms-index-index") && !a(".header-container.type10").hasClass("sticky-header") || (a(this).next().hasClass("show") ? a(this).next().removeClass("show") : a(this).next().addClass("show")), a(window).width() <= 991 && (a(".mobile-nav.side-block").hasClass("show") ? (a(".mobile-nav.side-block").removeClass("show"), a(".mobile-nav.side-block").slideUp()) : (a(".mobile-nav.side-block").addClass("show"), a(".mobile-nav.side-block").slideDown()))
    }), a(window).width() >= 992 && a(".cms-index-index .header-container.type10+.top-container .slider-wrapper").css("min-height", a(".header-container.type10 .menu.side-menu").height() + 20 + "px"), a(window).resize(function() {
        a(window).width() >= 992 ? a(".cms-index-index .header-container.type10+.top-container .slider-wrapper").css("min-height", a(".header-container.type10 .menu.side-menu").height() + 20 + "px") : a(".cms-index-index .header-container.type10+.top-container .slider-wrapper").css("min-height", ""), b = a(window).innerWidth(), c = a(window).innerHeight(), d = b / c, a(".full-screen-slider div.item").width(b), a(".full-screen-slider div.item").height(c), a(".full-screen-slider div.item > video").each(function() {
            d >= g ? (a(this).width(b), a(this).height(""), a(this).css("left", "0px"), a(this).css("top", (c - b / e * f) / 2 + "px")) : (a(this).width(""), a(this).height(c), a(this).css("left", (b - c / f * e) / 2 + "px"), a(this).css("top", "0px"))
        })
    }), a(".top-links-icon").click(function(b) {
        a("a.search-icon").parent().children("#search_mini_form").removeClass("show"), a(this).parent().children("ul.links").hasClass("show") ? a(this).parent().children("ul.links").removeClass("show") : a(this).parent().children("ul.links").addClass("show"), b.stopPropagation()
    }), a("a.search-icon").click(function(b) {
        a(".top-links-icon").parent().children("ul.links").removeClass("show"), a(this).parent().children("#search_mini_form").hasClass("show") ? a(this).parent().children("#search_mini_form").removeClass("show") : a(this).parent().children("#search_mini_form").addClass("show"), b.stopPropagation()
    }), a("a.search-icon").parent().click(function(a) {
        a.stopPropagation()
    }), a("html,body").click(function() {
        a(".top-links-icon").parent().children("ul.links").removeClass("show"), a("a.search-icon").parent().children("#search_mini_form").removeClass("show")
    }), a("a.product-image img.defaultImage").each(function() {
        var b = a(this).attr("src");
        b || (b = a(this).attr("data-src"));
        var c = a(this).parent().children("img.hoverImage").attr("src");
        c || (c = a(this).parent().children("img.hoverImage").attr("data-src")), b && b.replace("/small_image/", "/thumbnail/") == c && (a(this).parent().children("img.hoverImage").remove(), a(this).removeClass("defaultImage"))
    }), a(".table_qty_inc").unbind("click").click(function() {
        a(this).parent().children(".qty").is(":enabled") && a(this).parent().children(".qty").val(+a(this).parent().children(".qty").val() + 1 || 0)
    }), a(".table_qty_dec").unbind("click").click(function() {
        a(this).parent().children(".qty").is(":enabled") && a(this).parent().children(".qty").val(a(this).parent().children(".qty").val() - 1 > 0 ? a(this).parent().children(".qty").val() - 1 : 0)
    }), a(".qty_inc").unbind("click").click(function() {
        a(this).parent().parent().children("input.qty").is(":enabled") && (a(this).parent().parent().children("input.qty").val(+a(this).parent().parent().children("input.qty").val() + 1 || 0), a(this).parent().parent().children("input.qty").focus(), a(this).focus())
    }), a(".qty_dec").unbind("click").click(function() {
        a(this).parent().parent().children("input.qty").is(":enabled") && (a(this).parent().parent().children("input.qty").val(a(this).parent().parent().children("input.qty").val() - 1 > 0 ? a(this).parent().parent().children("input.qty").val() - 1 : 0), a(this).parent().parent().children("input.qty").focus(), a(this).focus())
    }), a(".move-action .item .details-area .actions").each(function() {
        a(this).parent().parent().children(".product-image-area").append(a(this))
    })
}), jQuery(function(a) {
    var b = 0;
    a(window).scroll(function() {
        if (!a("body").hasClass("cms-index-index")) {
            var c = a(".header-container.type12").innerHeight(),
                d = a(window).height();
            c - d < a(window).scrollTop() && (a(".header-container.type12").hasClass("fixed-bottom") || a(".header-container.type12").addClass("fixed-bottom")), c - d >= a(window).scrollTop() && a(".header-container.type12").hasClass("fixed-bottom") && a(".header-container.type12").removeClass("fixed-bottom")
        }
        a("body.side-header .top-container > .breadcrumbs").length && (a("body.side-header .top-container > .breadcrumbs").hasClass("fixed-position") ? b >= a(window).scrollTop() && a("body.side-header .top-container > .breadcrumbs").removeClass("fixed-position") : (b = a("body.side-header .top-container > .breadcrumbs").offset().top, a("body.side-header .top-container > .breadcrumbs").offset().top < a(window).scrollTop() && a("body.side-header .top-container > .breadcrumbs").addClass("fixed-position")))
    })
});