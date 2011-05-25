
(function($){

 
	$("#contact_form").validate();
			
	$("ul.worship_wheel li").hoverIntent({
		over: showTooltip, 
		timeout: 100, 
		out: hideTooltip
	});

	function showTooltip(){
		$(this).children('div.tooltip').animate({bottom: '125', opacity:"show"}); 
	}
	function hideTooltip(){ 
		$(this).children('div.tooltip').animate({bottom: '150', opacity:"hide"}); 
	}
		
    $(document).ready(function() {
	
    	$('#slider').loopedSlider({
			containerClick: false,
		});
	
		$('.sermon_button').click( function(){
			
			var audio = $(this).attr('rel');
			
			$('#media_player').show('slow');
			
			jwplayer('media_player').setup({
			    'flashplayer': '../js/jwplayer/player.swf',
			    'width': '590',
			    'height': '24',
			    'controlbar': 'bottom',
			    'file': audio,
		  	});
	
		})
    });	

})(this.jQuery);




window.log = function(){
  log.history = log.history || [];   
  log.history.push(arguments);
  if(this.console){
    console.log( Array.prototype.slice.call(arguments) );
  }
};

(function(doc){
  var write = doc.write;
  doc.write = function(q){ 
    log('document.write(): ',arguments); 
    if (/docwriteregexwhitelist/.test(q)) write.apply(doc,arguments);  
  };
})(document);


