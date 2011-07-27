
(function($){

	var html5Audio = Modernizr.audio && BrowserDetect.browser != "Firefox" && BrowserDetect.browser != "Opera";
    $(document).ready(function() {
	
    	$('#slider').loopedSlider({
			containerClick: false,
			autoStart: 10000, //Set to positive number for true. This number will be the time between transitions.
			restart: 0, //Set to positive number for true. Sets time until autoStart is restarted.
			hoverPause: true, //Set to true to pause on hover, if autoStart is also true
			slidespeed: 300, //Speed of slide animation, 1000 = 1second.
			fadespeed: 200, //Speed of fade animation, 1000 = 1second.
			autoHeight: 0, //Set to positive number for true. This number will be the speed of the animation.
			addPagination: false //Add pagination links based on content? true/false
		});

		if( typeof nsc_events != 'undefiend')
		{
			$('#event_calendar').fullCalendar({
				header: {
					left: 'prev,next today',
					center: 'title',
					right: 'month,basicWeek,basicDay'
				},
				events: 'themes/northsound/json_events.php',
				eventRender: function(event, element) {
					element.qtip({
						content:{
							text: event.tip,	  
							prerender: true
						},
						name: 'dark',
						position: {
							target: 'mouse',
							adjust: {
								mouse: true,
								x: 10,
								y: 10
							}
						}
					});
				}
			});
		}

		if(!html5Audio && $("#feature_audio").length > 0){
			jwplayer('feature_audio').setup({
				'flashplayer': 'themes/northsound/js/jwplayer/player.swf',
				'width': '274',
				'height': '24',
				'controlbar': 'bottom',
				'autostart' : false
			});
		}
		
    });	

	$('.sermon_play_button').live( 'click', function(event){
		
		event.preventDefault();
		var id = $(this).attr('data-id');
		var audio = $(this).attr('rel');
		var $playerButton = $(this).toggleClass('playing');
		var $player = $('#audio_player_'+id);
		removeAllAudio();
		$('.sermon_play_button').each( function(index){
			if( audio != $(this).attr('rel') ){
				$(this).removeClass('playing');
			}
		})
		

		if( $playerButton.hasClass('playing') ){
			$player.show();
				if(html5Audio){
					var audioElement = document.createElement('audio');
					audioElement.setAttribute('src', audio);
					audioElement.setAttribute('controls', 'controls');
					audioElement.load();
					audioElement.play();
					$player.html(audioElement);
				}else{
					jwplayer('audio_player_'+id).setup({
						'flashplayer': 'themes/northsound/js/jwplayer/player.swf',
						'width': '630',
						'height': '24',
						'controlbar': 'bottom',
						'file': audio,
						'autostart' : true
					});
				}
		}else{
			removeAllAudio();
		}

		function removeAllAudio(){
			$('.sermon').each( function(index){
				var jwp = "audio_player_"+index;
				if(html5Audio){
					$("#"+jwp).empty();
				}else{
					if(typeof jwp != 'undefined'){
						jwplayer(jwp).remove();
					}
				}
			})
		}
	})
	
	
	$("#contact_form").validate();
			
	$(".worship_wheel li").hoverIntent({
		over: showTooltip, 
		timeout: 100, 
		out: hideTooltip
	});

	function showTooltip(){
		$(this).children('div.tooltip').animate({top: '75', opacity:"show"}); 
	}
	function hideTooltip(){ 
		$(this).children('div.tooltip').animate({top: '50', opacity:"hide"}); 
	}

   //$("a[title]").qtip();

})(this.jQuery);


window.log = function(){
  log.history = log.history || [];   
  log.history.push(arguments);
  if(this.console){
    console.log( Array.prototype.slice.call(arguments) );
  }
};
