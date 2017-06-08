var NewPost = Class.create((function () {
    var methods = {},
        jQuery,
        holder;

    function renderAll() {
        jQuery.each(methods, function (name) {
            render(name);
        });
    }

    function updateAutocomplete(name) {
        methods[name].addressSelect.trigger('chosen:updated');
    }

    function fixAutocompleteStyle(element) {
        var parent = element.parent();
        element.show().css({
            'position': 'absolute',
            'left': '-99999px'
        }).detach().appendTo(parent);
    }

    function initChosen(method, selectName, autocompleteOptName) {
        method[selectName]['chosen']({allow_single_deselect: true});

        if (jQuery('#' + method[selectName].attr('id') + '_chosen').length < 1) {
            //if chosen doesn't created (it doesn't support iphones, ipads, etc.)
            return;
        }

        method[autocompleteOptName] = true;

        fixAutocompleteStyle(method[selectName]);
    }

    function renderCityAutocomplete(name) {
        if (!methods[name].autocompleteCityInit) {
            initChosen(methods[name], 'citySelect', 'autocompleteCityInit');
        }
    }

    function renderAddressAutocomplete(name) {
        if (!methods[name].autocompleteAddressInit) {
            initChosen(methods[name], 'addressSelect', 'autocompleteAddressInit');
        }
    }

    function render(name) {
        if (methods[name].radio.prop("checked")) {
            methods[name].wrapper.show();
            renderCityAutocomplete(name);

            renderAddress(name);
        } else {
            methods[name].wrapper.hide();
        }
    }

    function renderAddress(name) {
        if (methods[name].citySelect.prop("selectedIndex")) {
            methods[name].addressWrapper.show();

            renderAddressAutocomplete(name);
        } else {
            methods[name].addressWrapper.hide();
        }
    }

    function syncCity(name) {
        if (!methods[name].cityData) {
            return;
        }
        jQuery.each(methods[name].cityData.collection, function (index, data) {
            var option = jQuery('<option>').val(index).html(data);

            if (index == methods[name].cityData.selected) {
                option.prop("selected", "selected");
            }
            methods[name].citySelect.append(option);
        });
    }

    function syncAddress(name) {
        methods[name].addressSelect.children().not(':first').remove();
        if (!methods[name].addressData) {
            return;
        }

        jQuery.each(methods[name].addressData.collection, function (index, data) {
            var option = jQuery('<option>').val(index).html(data);

            if (index == methods[name].addressData.selected) {
                option.prop("selected", "selected");
            }
            methods[name].addressSelect.append(option);
        });
    }

    function registerMethod(params) {
        if (!methods[params.name]) {
            methods[params.name] = {};
        }

        var method = methods[params.name];

        method.radio = jQuery('#' + params.radioId);
        method.wrapper = jQuery('#' + params.wrapperId);

        method.citySelect = jQuery('#' + params.citySelectId);

        var cityDataRaw = jQuery('#' + params.cityDataId).html();
        method.cityData = cityDataRaw ? JSON.parse(cityDataRaw) : null;

        method.addressWrapper = jQuery('#' + params.addressWrapperId);
        method.addressSelect = jQuery('#' + params.addressSelectId);

        var addressDataRaw = jQuery('#' + params.addressDataId).html();
        method.addressData = addressDataRaw ? JSON.parse(jQuery('#' + params.addressDataId).html()) : null;
        method.addressDataId = params.addressDataId;

        syncCity(params.name);
        syncAddress(params.name);
    }

    function initialize(jquery, prototype) {
        jQuery = jquery;

        jQuery(document).ready(function () {
            holder = jQuery('.sp-methods-additional');

            jQuery.each(methods, function (name) {
                holder.append(methods[name].wrapper);
            });

            renderAll();

            jQuery("[id^='s_method_']").change(function () {
                renderAll();
            });
        });

        prototype(document).observe("fiCheckout:updated", function (e) {
            if (e.memo.params.shipping_method) {
                var methodName = e.memo.params.shipping_method;
                var name = methodName.substring(0, methodName.length / 2);

                if (methods.hasOwnProperty(name)) {
                    var addressDataRaw = jQuery(e.memo.response.shipping).find('#' + methods[name].addressDataId).html();
                    methods[name].addressData = addressDataRaw ? JSON.parse(addressDataRaw) : null;

                    syncAddress(name);
                    updateAutocomplete(name);
                    render(name);
                }
            }
        })
    }

    return {
        registerMethod: registerMethod,
        initialize: initialize
    };
})());

var newPost = new NewPost(jQuery, $);