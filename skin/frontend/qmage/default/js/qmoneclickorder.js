Validation.add('validate-phone380', 'Please enter a valid telephone number. For example +38(099)999-99-99', function(v) {
    return Validation.get('IsEmpty').test(v) || /^(\+\d{2}\(\d{3}\)\d{3}-\d{2}-\d{2})$/i.test(v)
});
/**
 * OneClick Order form
 */
QMOneclickOrder = (function($){
    /**
     * Public methods
     */
    var methods = {};

    /**
     * Global fancybox options
     */
    var fancyboxOptions = {
        href: null,
        content: null,
        helpers: {
            overlay: {
                locked: false
            }
        },
        afterClose: null
    };

    var createOverlay = function(){
        return $("<div/>").addClass("oneclickorder-overlay");
    };

    var replacePlaceholders = function(str, data){
        $.each(data, function(key, val){
            str = str.replace("{{"+key+"}}", val);
        });
        return str;
    }

    var showConfirmationDialog = function(data, popup){
        var content = data.confirmation;
        if (data.customer) {
            content = replacePlaceholders(content, data.customer);
        }
        popup.replaceContent(content, function(){
            this.holder.find(".btn-close").on("click", function(e){
                e.preventDefault();
                popup.close();
            });
        });
    }

    var fxPopup = {
        init: function(options){
            var a = this, insertBox = [];
            if (options.insertSelector) {
                insertBox = $(options.insertSelector).eq(0);
            }
            if (!insertBox.length) { // Mage 1.7 - 1.9
                insertBox = $("#product-addtocart-button").closest(".add-to-box");
            }
            if (!insertBox.length) { // Mage 1.9.1
                insertBox = $(".add-to-cart").find("button").eq(0);
            }
            if (!insertBox.length) { // other
                insertBox = $(".add-to-cart").eq(0);
            }
            this.button = $("<button/>").append("<span><span>"+options.placeButtonText+"</span></span>")
                .addClass("oneclick-order button btn-cart")
                .on("click", function(e){
                    e.preventDefault();
                    var popup = a.openPopup(options);
                    if (popup.content) {
                        popup.content.find('input[type="text"]:eq(0)').trigger("focus");
                    }
                });
            $("<div/>").addClass('add-to-box').insertAfter(insertBox).append(this.button).css({position:"relative", clear:"both"});
            return this;
        },
        openPopup: function(options){
            var options = this.options = $.extend({
                delay:400,
                distance:15,
                easing: "easeOutCubic"
            }, options);
            if (this._opened) {
                return this.closePopup();
            }
            var top = 0,
                right = 0,
                bottom = 0,
                left = 0,
                animate = {}
                a = this;
            var popupHolder = $("<div/>").addClass("fx-popup").insertAfter(this.button);
            var popupInner = $("<div/>").addClass("fx-popup-inner").appendTo(popupHolder);
            var content = $("#"+options.holder);
            if (content.length) {
                content.appendTo(popupInner).show();
            } else {
                popupInner.html("Content not defined.");
            }
            popupInner.width(content.width());
            switch (options.direction) {
                case "bottom":
                    top = this.button.outerHeight() + options.distance;
                    left = left - ((popupHolder.outerWidth() / 2) - (this.button.outerWidth() / 2));
                    popupHolder.css({top:top,left:left});
                    popupHolder.show().animate({top:"-="+options.distance+"px",opacity:1},options.delay,options.easing, function(){
                        a._opened = true;
                    });
                    this._closeMethod = function(){
                        this.holder.animate({top:"+="+options.distance+"px",opacity:0},options.delay,options.easing,function(){
                            popupHolder.hide();
                            content.hide().appendTo("body");
                            popupHolder.remove();
                            if (typeof options.afterClose == "function") {
                                options.afterClose.call(this);
                            }
                        });
                        this._opened = false;
                    };
                    break;
            }

            this.content = content;
            this.close   = this.closePopup;
            this.holder  = popupHolder;
            this.replaceContent = function(cnt,clb){
                content.hide().appendTo("body");
                popupInner.html(cnt);
                a.content = cnt;
                if(typeof clb=="function"){clb.call(this)};
            };

            $('html').off("click.fxpopup").on("click.fxpopup", function(e){
                if (!$(e.target).closest(popupHolder).length && a._opened) {
                    a.closePopup();
                }
            });

            return this;
        },
        closePopup: function(){
            if (typeof this._closeMethod == "function") {
                $('html').off("click.fxpopup");
                this._closeMethod.call(this);
            }
            return this;
        }
    };

    var feedbackForm = function(options){
        var varienForm = new VarienForm(options.form);
        var options = $.extend({
            isAjax: false,
            holder: null,
            form: null,
            placeButtonText: null,
            afterClose: function(){
                varienForm.validator.reset();
                varienForm.form.reset();
            },
            direction:"bottom",
            insertSelector: null
        }, options);

        var popup = fxPopup.init(options);

        if (options.isAjax) {
            var isMaskActive = false;
            $("#"+options.form).on("submit", function(e){
                e.preventDefault();
                var form = $(this),
                    messageBlock = form.find(".message").hide(),
                    fields = form.find('input');
                if(!fields.hasClass('validation-failed')){
                    var overlay = createOverlay().appendTo(form.parent());
                    $.post(form.attr("action")+"isAjax/1/",form.serialize(),function(data){
                        if (data.r === "success") {
                            if (data.confirmation) {
                                showConfirmationDialog(data, popup);
                            } else {
                                popup.close();
                            }
                            form.trigger("reset");
                        } else {
                            messageBlock.fadeIn().html(data.error);
                        }
                    },'json')
                    .complete(function(){overlay.remove()});
                }
            })
        }
    };

    /**
     * Initialize public methods
     */
    methods.initOrder = feedbackForm;

    /**
     * Return public methods
     */
    return methods;
})(jQuery);