var layerednavigation_working  = false;
var layerednavigation_blocks   = {};

function layerednavigation_ajax_init(){

    $$('div.block-layered-nav a', layerednavigation_toolbar_selector + ' a').
        each(function(e){
            var p = e.up();
            if (p.hasClassName('layerednavigation-cat') || p.hasClassName('layerednavigation-clearer')){
               return;
            }

            e.onclick = function(){
                if (this.hasClassName('checked')) {
                    this.removeClassName('checked');
                } else {
                    this.addClassName('checked');
                }
		        
                var s = this.href;
                if (s.indexOf('#') > 0){
                	s = s.substring(0, s.indexOf('#'))
                }
                layerednavigation_ajax_request(s);
                return false;
            };
        });
    
    $$('div.block-layered-nav select.layerednavigation-ajax-select', layerednavigation_toolbar_selector + ' select').
        each(function(e){
            e.onchange = 'return false';
            Event.observe(e, 'change', function(e){
                layerednavigation_ajax_request(this.value);
                Event.stop(e);
            });
        });
    if (typeof(layerednavigation_external) != 'undefined'){
        layerednavigation_external();
    }
}

function layerednavigation_get_created_container()
{
    var elements = document.getElementsByClassName('layerednavigation-page-container');
    return (elements.length > 0) ? elements[0] : null;
}

function layerednavigation_get_container()
{
	var createdElement = layerednavigation_get_created_container();
	if (!createdElement) {
		var container_element = null;
		
		var elements = $$('div.category-products');
		if (elements.length == 0) {
			container_element = layerednavigation_get_empty_container();
		} else {
			container_element = elements[0];
		}
		
		if (!container_element) {
            alert('Please add the <div class="layerednavigation-page-container"> to the list template as per installtion guide. Enable template hints to find the right file if needed.');
        }	
        
		container_element.wrap('div', { 'class': 'layerednavigation-page-container', 'id' : 'layerednavigation-page-container' });
		
		createdElement = layerednavigation_get_created_container();
		
		$(createdElement).insert({ bottom : '<div style="display:none" class="layerednavigation-overlay"><div></div></div>'});
	}
	return createdElement;
}

function layerednavigation_get_empty_container()
{
	var notes = document.getElementsByClassName('note-msg');
	if (notes.length == 1) {
		return notes[0];
	}
}

/*
 * Get location object from string 
 */
var layerednavigation_get_location_from_string = function(href) {
    var l = document.createElement("a");
    l.href = href;
    return l;
};



function layerednavigation_ajax_request(url){
    
	if (layerednavigation_use_hash) {

        layerednavigation_skip_hash_change = true;
		var url = url.replace(window.top.location.protocol + '//' + window.top.location.host, '');
		window.top.location.hash = layerednavigation_encodeUri(url);
	}
	
    var block = layerednavigation_get_container();
    
    if (block && layerednavigation_scroll_to_products) {
        block.scrollTo();
    }

    layerednavigation_working = true;
    
    $$('div.layerednavigation-overlay').each(function(e){
        e.show();
    });

    var request = new Ajax.Request(url,{
            method: 'get',
            parameters:{'is_ajax':1},
            onSuccess: function(response){
                data = response.responseText;
                if(!data.isJSON()){
                    setLocation(url);
                }
                
                data = data.evalJSON();
                if (!data.page || !data.blocks){
                    setLocation(url);
                }
                layerednavigation_ajax_update(data);
                layerednavigation_working = false;
                layerednavigation_skip_hash_change = false;
            },
            onFailure: function(){
                layerednavigation_working = false;
                setLocation(url);
            }
        }
    );
}

function layerednavigation_get_first_descendant(element) {
	
	var targetElement = element.firstChild;
	if(typeof element.firstDescendant != "undefined") {
		targetElement = element.firstDescendant();
	}
	return targetElement;
}

function layerednavigation_ajax_update(data){

    //update category (we need all category as some filters changes description)
    var tmp = document.createElement('div');
    tmp.innerHTML = data.page;
    
    
    
    var block = layerednavigation_get_container();
    if (block) {
    	var targetElement = layerednavigation_get_first_descendant(tmp);
    	
        /*
         * If returned element is not HTML tag
         */
    	if (targetElement == null) {    		
    		tmp.innerHTML = '<p class="note-msg">' + data.page + '</p>';
    		targetElement = layerednavigation_get_first_descendant(tmp);
    	}
    	block.parentNode.replaceChild(targetElement, block);
		if (typeof AmConfigurableData != 'undefined') {
			targetElement.innerHTML.evalScripts();
		}
    }


    var blocks = data.blocks;
    for (id in blocks){
    
        var html   = blocks[id];
        if (html){
            tmp.innerHTML = html;
        }
        
        block = $$('div.'+id)[0];
        if (html){
            if (!block){
                block = layerednavigation_blocks[id]; // the block WAS in the structure a few requests ago
                layerednavigation_blocks[id] = null;
            }
            if (block){
            	var targetElement = layerednavigation_get_first_descendant(tmp);
            	block.parentNode.replaceChild(targetElement, block);
            }
        }
        else { // no filters returned, need to remove
            if (block){
                var empty = document.createTextNode('');
                layerednavigation_blocks[id] = empty; // remember the block in the DOM structure
                block.parentNode.replaceChild(empty, block);        
            }
        }  
    }

    layerednavigation_start();
    layerednavigation_ajax_init();
     
}

function layerednavigation_request_required()
{
    if (layerednavigation_use_hash && window.top.location.hash) {
        return true;
    }
	return false;	
}


document.observe("dom:loaded", function(event) {

    layerednavigation_ajax_init();
	
	if (layerednavigation_request_required()) {
		var hash = layerednavigation_decodeUri(window.top.location.hash.substr(1));
		var url = window.top.location.protocol + '//' + window.top.location.host;
		
		url = url + hash;

        layerednavigation_ajax_request(url);
	} 
});

var layerednavigation_toolbar_selector = 'div.toolbar';
var layerednavigation_scroll_to_products = false;
var layerednavigation_use_hash = true;
var layerednavigation_encode_uri = false;
var layerednavigation_skip_hash_change = false;

var AnchorChecker = {
		initialize: function(){
			this.location = window.top.location.hash;
		    this.interval = setInterval(function(){
		    
			if (this.location != window.top.location.hash) 
		     {
		    	 if (this.location != '') { 
		    		 this.anchorAltered();
		    	 }
		    	 this.location = window.top.location.hash;
		     }
		   }.bind(this), 500); // check every half second
		 },
		 anchorAltered: function(){
			if (!layerednavigation_skip_hash_change) {
                layerednavigation_ajax_request(layerednavigation_decodeUri(window.top.location.hash.substr(1)));
			}
		 }
	};
if (layerednavigation_use_hash) {
		AnchorChecker.initialize();
}

function layerednavigation_encodeUri(str) {
    return layerednavigation_encode_uri ? encodeURIComponent(str) : str;
}

function layerednavigation_decodeUri(str) {
    return layerednavigation_encode_uri ? decodeURIComponent(str) : str;
}
		
function layerednavigation_external(){
	if (typeof layerednavigation_demo != 'undefined') {
        layerednavigation_demo();
	}
}