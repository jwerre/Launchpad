(function($) {

/*
 * Scales an image proportionately and centers it in container
 *
 * @name     $.fn.scaleImage
 * @author   Jonah Werre
 * @example  $('.thumb img').scaleImage();
 *
 */

$.fn.scaleImage = function(options) {
	
	
	defaults = {
		maxWidth : 128,
		maxHeight : 128
	};
	
	options = $.extend(defaults, options);

	return this.each(function(){
		var image = this;
		image.style.display='none';
		var temp_img = new Image();		
		temp_img.onload = function() {
			
			var imgWidth = this.width;
			var imgHeight = this.height;
			
			var ratio = options.maxWidth / imgWidth;
		    var newHeight = Math.ceil(imgHeight * ratio);
		    var newWidth = Math.ceil(imgWidth * ratio);

			if(imgWidth < options.maxWidth && imgHeight < options.maxHeight){
				newHeight = imgHeight;
		        newWidth = imgWidth;

			} else if( imgWidth > imgHeight ) { 
				ratio = options.maxWidth / imgWidth;
		        newHeight = Math.ceil(imgHeight * ratio);
		        newWidth = Math.ceil(imgWidth * ratio);

			} else if( imgHeight > imgWidth ){
				ratio = options.maxHeight / imgHeight;
		        newHeight = Math.ceil(imgHeight * ratio);
		        newWidth = Math.ceil(imgWidth * ratio);
			}


			$(image).css({
				'height' : newHeight+'px',
				'width' : newWidth+'px',
				'position' : 'relative',
				'display' : 'block',
				'top' : '50%',
				'left' : '50%',
				'margin-top' : '-' + Math.round( newHeight/2 ) + 'px',
				'margin-left' : '-' + Math.round( newWidth/2 ) +'px'
			});
			
		}
		temp_img.src = $(this).attr('src');
		temp_img = null;
	});
};

})(jQuery);