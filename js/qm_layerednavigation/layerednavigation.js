function layerednavigation_start(){
    $$('.block-layered-nav .form-button').each(function (e){
        e.observe('click', layerednavigation_price_click_callback);
    });

    $$('.block-layered-nav .input-text').each(function (e){
        e.observe('focus', layerednavigation_price_focus_callback);
        e.observe('keypress', layerednavigation_price_click_callback);
    });

    $$('a.layerednavigation-less', 'a.layerednavigation-more').each(function (a){
        a.observe('click', layerednavigation_toggle);
    });

    $$('span.layerednavigation-plusminus').each(function (span){
        span.observe('click', layerednavigation_category_show);
    });

    $$('.block-layered-nav dt').each(function (dt){
        dt.observe('click', layerednavigation_filter_show);
    });

    $$('.block-layered-nav dt img').each(function (img){
        img.observe('mouseover', layerednavigation_tooltip_show);
        img.observe('mouseout', layerednavigation_tooltip_hide);
    });

    layerednavigation_slider();
}

function layerednavigation_price_click_callback(evt, decimal) {

    if (evt && evt.type == 'keypress' && 13 != evt.keyCode)
        return;

    var prefix = 'layerednavigation-price';
    // from slider
    if (typeof(evt) == 'string'){
        prefix = evt;
    }
    else {
        var el = Event.findElement(evt, 'input');
        if (!Object.isElement(el)){
            el = Event.findElement(evt, 'button');
        }
        prefix = el.name;
    }

    var a = prefix + '-from';
    var b = prefix + '-to';

    var numFrom = layerednavigation_price_format($(a).value, decimal);
    var numTo   = layerednavigation_price_format($(b).value, decimal);

    if ((numFrom < 0.01 && numTo < 0.01) || numFrom < 0 || numTo < 0) {
        return;
    }

    var url =  $(prefix +'-url').value.gsub(a, numFrom).gsub(b, numTo);
    layerednavigation_set_location(url);
}

function layerednavigation_price_focus_callback(evt){
    var el = Event.findElement(evt, 'input');
    if (isNaN(parseFloat(el.value))){
        el.value = '';
    }
}


function layerednavigation_price_format(num, decimal){
    num = parseFloat(num);

    if (isNaN(num))
        num = 0;

    if (decimal >= 0) {
        return num.toFixed(decimal);
    } else {
        return Math.round(num);
    }
}

function layerednavigation_slider() {
    jQuery(function ($) {
        var prefix = 'layerednavigation-price',
            slider = $('#' + prefix),
            fromInput = $('#layerednavigation-price-from'),
            toInput = $('#layerednavigation-price-to');

        slider.slider({
            min: slider.data('min'),
            max: slider.data('max'),
            values: [slider.data('from-value'), slider.data('to-value')],
            range: true,
            slide: function (event, ui) {
                fromInput.val(ui.values[0]);
                toInput.val(ui.values[1]);
            },
            change: function() {
                layerednavigation_price_click_callback(prefix, slider.data('prefix'));
            }
        });
    });
}

function layerednavigation_toggle(evt){
    var attr = Event.findElement(evt, 'a').id.substr(14);

    $$('.layerednavigation-attr-' + attr).invoke('toggle');

    $('layerednavigation-less-' + attr, 'layerednavigation-more-' + attr).invoke('toggle');

    Event.stop(evt);
    return false;
}

function layerednavigation_category_show(evt){
    var span = Event.findElement(evt, 'span');
    var id = span.id.substr(16);

    $$('.layerednavigation-cat-parentid-' + id).invoke('toggle');

    span.toggleClassName('minus');
    Event.stop(evt);

    return false;
}

function layerednavigation_filter_show(evt){
    var dt = Event.findElement(evt, 'dt');

    var content = dt.next('dd').down('ol');

    if (!content) { //theme module filter
        content = dt.next('dd').down('div');
    }
    content.toggle();
    dt.toggleClassName('layerednavigation-collapsed');

    Event.stop(evt);
    return false;
}

function layerednavigation_tooltip_show(evt){
    var img = Event.findElement(evt, 'img');
    var txt = img.alt;

    var tooltip = $(img.id + '-tooltip');
    if (!tooltip) {
        tooltip           = document.createElement('div');
        tooltip.className = 'layerednavigation-tooltip';
        tooltip.id        = img.id + '-tooltip';
        tooltip.innerHTML = img.alt;

        document.body.appendChild(tooltip);
    }

    var offset = Element.cumulativeOffset(img);
    tooltip.style.top  = offset[1] + 'px';
    tooltip.style.left = (offset[0] + 30) + 'px';
    tooltip.show();
}

function layerednavigation_tooltip_hide(evt){
    var img = Event.findElement(evt, 'img');
    var tooltip = $(img.id + '-tooltip');
    if (tooltip) {
        tooltip.hide();
    }
}

function layerednavigation_set_location(url){
    if (typeof layerednavigation_working != 'undefined'){
        return layerednavigation_ajax_request(url);
    }
    else {
        return setLocation(url);
    }
}

function layerednavigation_attr_highlight(li, str)
{
    /*
     * Remove previous highlight
     */
    layerednavigation_attr_unhighlight(li);

    var ch = li.childElements();
    if (ch.length >  0) {
        ch = ch[0];
        if (ch.tagName == 'A') {
            ch.innerHTML = ch.innerHTML.replace(new RegExp(str,'gi'), '<span class="layerednavigation-hightlighted">' + str + '</span>');
        }
    }
}

function layerednavigation_attr_unhighlight(li)
{
    var ch = li.childElements();
    if (ch.length > 0) {
        ch = ch[0];
        if (ch.tagName == 'A') {
            ch.innerHTML = li.readAttribute('rel');
        }
    }
}


function layerednavigation_attr_search(searchBox){
    var str = searchBox.value.toLowerCase();
    var all = searchBox.up(1).childElements();

    all.each(function(li) {
        val = li.readAttribute('rel').toLowerCase();
        if (!val || val == 'search' || val.indexOf(str) > -1) {
            if (str != '' && val.indexOf(str) > -1) {
                layerednavigation_attr_highlight(li, str);
            } else {
                layerednavigation_attr_unhighlight(li);
            }
            li.show();
        }
        else {
            layerednavigation_attr_unhighlight(li);
            li.hide();
        }
    });
}



document.observe("dom:loaded", function() { layerednavigation_start(); });