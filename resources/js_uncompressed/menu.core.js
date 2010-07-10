/*
 * Droppy 0.1.2
 * (c) 2008 Jason Frame (jason@onehackoranother.com)
 */
$.fn.droppy = function() {
  
  this.each(function() {
    
    var root = this, zIndex = 1000;
    $(this).prepend("<span id='links_move'></span>");
    $('li:has(li) > a', this).append(" >");
    
    function getSubnav(ele) {
      if (ele.nodeName.toLowerCase() == 'li') {
        var subnav = $('> ul', ele);
        return subnav.length ? subnav[0] : null;
      } else {
        return ele;
      }
    }
    
    function getActuator(ele) {
      if (ele.nodeName.toLowerCase() == 'ul') {
        return $(ele).parents('li')[0];
      } else {
        return ele;
      }
    }
    
    function hide() {
      var subnav = getSubnav(this);
      if (!subnav) return;
      $.data(subnav, 'cancelHide', false);
      setTimeout(function() {
        if (!$.data(subnav, 'cancelHide')) {
          $(subnav).hide();
        }
      }, 500);
    }
  
    function show() {
      var subnav = getSubnav(this);
      $(subnav).stop(true, true);
      if (!subnav) return;
      $.data(subnav, 'cancelHide', true);
      $(subnav).css({zIndex: zIndex++}).show();
      if (this.nodeName.toLowerCase() == 'ul') {
        var li = getActuator(this);
      }
    }
    $('ul, li', this).hover(show, hide);
    $('li:not(li li)', this).hover(function() {
    	//OvEr
    	var that = this;
    	var offset = $(this).offset();
    	var offsetBody = $(root).offset(); //find 
    	$('#links_move').animate({
    		left: (offset.left - offsetBody.left),
    		height: $(that).height(),
    		width: $(that).width()
    	}, {duration: 250, queue: false});
    },
    function() {
    	//OuT
    	var that = this;
    	$('#links_move').stop(true).animate({
    		height: 0,
    		width: 0
    	}, {duration: 250});
    });
    
  });
  
};
