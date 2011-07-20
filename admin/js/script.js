/* Author: Jonah Werre */

/*
 *
 *
 */
function monthIntToMonthName(month, long)
{
	if(typeof long == 'undefined'){
		long = false;
	}
	var shortNames=new Array( "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec" );
	var longNames=new Array( "January", "February", "March", "April", "May", "June",	"July", "August", "September", "October", "November", "December");
	return (long) ? longNames[month] : shortNames[month];
}

/*
* Test whether or not file is an image
*
* @param filename
* @return boolean
*/
function isImage(filename)
{
	var regex = /[.](jpg)|(jpeg)|(gif)|(png)$/i;
	return (regex.exec(filename)) ? true : false;
}

/*
* Gets the type of file base on extension
*
* @param filename
*/
function getFileType(filename)
{
	return (/[.]/.exec(filename)) ? /[^.]+$/.exec(filename) : undefined;
}

/*
* Clears placeholder text
*
* @param input
*/
function clearPlaceholderText( input )
{
	if (input.val() == input.attr('placeholder')) {
		input.val('');
		input.removeClass('placeholder');
	}
}

/*
* makes placeholder text visible to non-supported browsers
*
* @param input
*/
function initPlaceholderText( input )
{
	if (input.val() == '' || input.val() == input.attr('placeholder')) {
		input.addClass('placeholder');
		input.val(input.attr('placeholder'));
	}
}

/*
* breaks comma delimited list into array
*
* @param val string
* @return array
*/
function split( val ) {
	return val.split( /,\s*/ );
}

/*
* extracts the last in comma delimited string
*
* @param term string
* @return string
*/
function extractLast( term ) {
	return split( term ).pop();
}

/*
* breaks "meta[key][foo][value][bar]" into usable object
*
* @param string
* @return object
*/
function splitComplexId(id)
{
	var pattern=/[\[\]]/;	
	var data = id.split(pattern);
	// remove first
	var idName = data.shift();
	// remove all empty strings
	data.clean();
	
	var newObject = new Object;
	for (var i=0; i < data.length; i+=2) {
		var key=data[i];
		var value=data[i+1];
		if( key && value ){
			newObject[key] = value	
		}
	};
	return newObject;
}

function appendMessage(container, message, messageType, style )
{
    if (typeof style == "undefined") {
        style = "";
    }
    
	$(container).find('.'+messageType).remove();
	$(container).append('<div class="'+messageType+'" style="'+ style +'">'+message+'</div>')
	.find('.'+messageType).delay(3000).fadeOut( function(){$(this).remove()} );
}

function prependMessage(container, message, messageType, style )
{
    if (typeof style == "undefined") {
        style = "";
    }
    
	$(container).find('.'+messageType).remove();
	$(container).prepend('<div class="'+messageType+'" style="'+ style +'">'+message+'</div>')
	.find('.'+messageType).delay(3000).fadeOut( function(){$(this).remove()} );
}

function initMessage(message, messageType )
{
	$('#message').remove();
	$('header').after('<div id="message" class="'+messageType+'" style="display:block;" ><p>'+message+' <a href="#" class="close">close</a></p></div>' )
	.find('.'+messageType).delay(1000).slideDown();
}

/*
* removes specified values from array
*
* @param deleteValue = "" Value to be removed from array
* @return array
*/
Array.prototype.clean = function(deleteValue) {
	if(!deleteValue){
		deleteValue = "";
	}
	for (var i = 0; i < this.length; i++) {
		if (this[i] == deleteValue) {         
			this.splice(i, 1);
			i--;
		}
	}
	return this;
};


/*
* removes whitespace from string
*
* @return string
*/
String.prototype.trim = function() {
	return this.replace(/^\s+|\s+$/g,"");
}
/*
* removes whitespace from beginning of string
*
* @return string
*/
String.prototype.ltrim = function() {
	return this.replace(/^\s+/,"");
}
/*
* removes whitespace from end of string
*
* @return string
*/
String.prototype.rtrim = function() {
	return this.replace(/\s+$/,"");
}

