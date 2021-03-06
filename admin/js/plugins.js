// LOG usage: log('inside coolFunc', this, arguments);
window.log = function(){
  log.history = log.history || [];   // store logs to an array for reference
  log.history.push(arguments);
  if(this.console) console.log( Array.prototype.slice.call(arguments) );
}; 

$(function() {
	// HANDLE COOKIES
	if( $.cookie('aside') != null ){
		$('body').addClass( $.cookie('aside') );
	}

    if($.cookie('layout') == null){
        $.cookie('layout', 'layout_100');
    }

	$('body').attr( 'id', $.cookie('layout') );

	$('#layout_settings a').each(function(index){
       if( $(this).data().layout == $.cookie('layout')){
           $(this).addClass('on');
       }
    });
	
    
    $("header nav li").hoverIntent({    
	    timeout: 500, // number = milliseconds delay before onMouseOut    
		over: function(){
			$(this).children("ul").first().toggleClass('top').slideDown('fast');
		},
	    out: function(){
			$(this).children("ul").first().toggleClass('top').slideUp('fast');
		}
	}).children("ul").first().toggleClass('top');

	$("header nav li#settings_nav").hoverIntent({    
	    timeout: 500, // number = milliseconds delay before onMouseOut    
		over: function(){
			$(this).children("div").first().toggleClass('top').slideDown('fast');
		},
	    out: function(){
			$(this).children("div").first().toggleClass('top').slideUp('fast');
		}
	});

    $('#layout_settings a').click( function(event){
        event.preventDefault();
        $(this).parent().siblings().children('a').removeClass('on')
        $(this).addClass('on');
        var data = $(this).data();
		$.cookie('layout', data.layout);
	    $('body').attr( 'id', $.cookie('layout') );
    });

    $('#color_settings a').click( function(event){
        $(this).parent().siblings().children('a').removeClass('on')
        $(this).addClass('on');
    });

	// LEFT COLUMN TOGGLE
	$('#toggle_aside').click( function(event){
        event.preventDefault();
		if ($('body').hasClass('no_aside')) {
			$('body').removeClass('no_aside')
			$.cookie('aside', null);
		} else {
			$('body').addClass('no_aside')
			$.cookie('aside', 'no_aside');
		}
		return false;
	});
	// REDEFINE USER ROLL
	// $( '.connected_sortable' ).sortable({
	// 	opacity: .8,
	// 	scroll: false,
	// 	tolerance: 'pointer',
	// 	placeholder: 'sort_placeholder',
	// 	cursor: 'move',
	// });
	
	
	
	// MEDIA
	$('.media_list li').mouseenter(function(){
		$(this).children('nav').show();
	});

	$('.media_list li').mouseleave(function(){
		$(this).children('nav').hide();
	});
	
	// $('#image .media_container img').scaleImage({maxWidth:120, maxHeight:200});
		
	$('.embed_btn').click(function(event){
		event.preventDefault();
		var $embed = $( '#embed_img_dialog' );
		var tag = $(this).attr('href');
		$embed.dialog({
			resizable: false,
			draggable: false,
			width: 500,
			height: 90,
			modal: true,
			open : function(event, ui){
				$(this).find('input').val(tag).select();
			}
			
		});
	});
	
	$('.img_delete').click(function(){
		var img = $(this).parents('li').last();
		var img_id = $(this).parents('li').last().attr('class');
		$( '#delete_img_dialog' ).dialog({
			resizable: false,
			draggable: false,
			height: 170,
			modal: true,
			buttons: [
			{
				text: "No, keep it",
				click: function(){ 
					$(this).dialog('close'); 
				}
			},
			{
				text: "Yes, delete it",
				click: function(){
					$(this).dialog('close'); 
					$.ajax({
						url: 'ajax/media_delete.php',
						type: 'POST',
						dataType: 'text',
						data: {id: img_id},
						success: function(data, textStatus, xhr) {
							if(data == 'true'){
								$(img).animate({ width: 0 }, 500, function(){
									$(img).remove();
								});
							}
						},
						error: function(xhr, textStatus, errorThrown) {
						}
					});
				}
			}
			]
		});
		return false;
	});

	// DELETE USER
    // delete content form list page
	$('a.user_list_delete').click(function(event){

		event.preventDefault;
		var container = $(this).parents('li').last();
		var content = this;
		var href = 'ajax/user_delete.php';
		var id = $(container).data('id');
		$( '#delete_content_dialog' ).dialog({
			resizable: false,
			draggable: false,
			height: 170,
			modal: true,
			buttons: [
			{
				text: "No, keep it",
				click: function(){ 
					$(this).dialog('close'); 
				}
			},
			{
				text: "Yes, delete it",
				click: function(){
					$(this).dialog('close'); 
					$.post( href, {'id':id}, function(data) {
						if(data == 'true'){
							$(container).slideUp( function(){
								$(container).remove();
							});
						}else{
							appendMessage( container, 'This could not be deleted', 'error_msg', 'margin-top:5px;' )
						}						
					})
					.error(function() {
						appendMessage( container, 'This could not be deleted', 'error_msg', 'margin-top:5px;' )
					});
				}
			}
			]
		});
		return false;
	});

	// DELETE CONTENT
	$('a#content_delete').click( function(event) {

		event.preventDefault;
		var content = this;
		var href = 'ajax/content_delete.php';
		var id = $('input#content_id').val();
		$( '#delete_content_dialog' ).dialog({
			resizable: false,
			draggable: false,
			height: 170,
			modal: true,
			buttons: [
			{
				text: "No, keep it",
				click: function(){ 
					$(this).dialog('close'); 
				}
			},
			{
				text: "Yes, delete it",
				click: function(){
					$(this).dialog('close'); 
					$.post( href, {'id':id}, function(data) {
						if(data == 'true'){
							window.location.href = 'content.php';
						}else{
							initMessage( content, 'This could not be deleted', 'error_msg' )
						}						
					})
					.error(function() {
						initMessage( content, 'This could not be deleted', 'error_msg' )
					});
				}
			}
			]
		});
		
		return false; // don't follow the link!
	})
   
    // delete content form list page
	$('a.content_list_delete').click(function( event ){
		event.preventDefault;
		var container = $(this).parents('li').last();
		var content = this;
		var href = 'ajax/content_delete.php';
		var id = (container).data('id');
		$( '#delete_content_dialog' ).dialog({
			resizable: false,
			draggable: false,
			height: 170,
			modal: true,
			buttons: [
			{
				text: "No, keep it",
				click: function(){ 
					$(this).dialog('close'); 
				}
			},
			{
				text: "Yes, delete it",
				click: function(){
					$(this).dialog('close'); 
					$.post( href, {'id':id}, function(data) {
						if(data == 'true'){
							$(container).slideUp( function(){
								$(container).remove();
							});
						}else{
							appendMessage( container, 'This could not be deleted', 'error_msg', 'margin-top:5px;' )
						}						
					})
					.error(function() {
						appendMessage( container, 'This could not be deleted', 'error_msg', 'margin-top:5px;' )
					});
				}
			}
			]
		});
		return false;
	});
    
    // SHOWS SNIPPETS AVAILABLE FOR PAGE TEMPLATE OR POST CATEGORY
    $('select#template').change( function(){showSnippets()} );
    $('select#category_id').change( function(){showSnippets()});
    $('#snippet_suggestions').click( function(event){
		event.preventDefault();
		showSnippets()
	});
    $('a#option_suggestions').click( function(event){
		event.preventDefault();
		$.ajax({
			type: "GET",
			url: window.themeDir+'/theme.xml',
			dataType: "xml",
			success: function(xml) {
				var nodes = $(xml).find('options').children('option');
                if(nodes.length > 0){
					$('#message').remove();
					var message = '<div id="message" class="info_msg"><p><strong>The following custom options are suggested for this theme:</strong><a href="" class="close">close</a></p>';
					message += '<ul>';
					nodes.each(function(index){
						message += '<li><strong style="">'+$(this).attr('name')+':</strong> '+$(this).text()+'</li>';
					});
					message += '</ul></div>';
				}else{
					var message = '<div id="message" class="info_msg"><p><strong>There are no custom options for this theme :</strong><a href="" class="close">close</a></p>';
				}
				$('#snippet').before(message);
			}
		}); 
	});
    $('a#suggested_categories').click(function(event){
		event.preventDefault();
		$.ajax({
			type: "GET",
			url: window.themeDir+'/theme.xml',
			dataType: "xml",
			success: function(xml) {
				$('#message').remove();
				var nodes = $(xml).find('categories').children('category');
                if( nodes.length > 0){
					var message = '<div id="message" class="info_msg"><p><strong>This theme rquires the following categories:</strong><a href="#" class="close">close</a></p>';
					message += '<ul>';
					nodes.each(function(index){
						message += '<li><strong>'+$(this).attr('name')+': </strong>'+$(this).children('description').first().text()+'</li>';
					});
					message += '</ul></div>';
				}else{
					var message = '<div id="message" class="info_msg"><p><strong>There are no category suggestions for this theme</strong><a href="#" class="close">close</a></p>';
				}
				$('h1').after(message);
			}
		}); 
	});

	if( $('#available_themes').length > 0 ){
		var themes = window.themeData;
		var container = $('#available_themes ul.themes li.theme').remove();
		$(themes).each(function(index){
			var themeXml = this+'/theme.xml';
			var themePreview = this+'/preview.png';
			var themeName = this.split('/').pop();
			$.ajax({
				type: "GET",
				url: themeXml,
				dataType: "xml",
				success: function(xml) {
					var newContainer = $(container).clone();
					$(newContainer).find('a.use_button').first().attr('href', '?theme='+themeName);
					
					themePreview = ( $(xml).find('preview').text().length > 1 ) ? $(xml).find('preview').text() : themePreview ;
					$(newContainer).find('img').first().attr('src', themePreview);

					themeName = ( $(xml).find('title').text().length > 1 ) ? $(xml).find('title').text().capitalize() : themeName.capitalize();
					$(newContainer).find('h4').first().text(themeName);
					
					themeDescription = $(xml).find('description').first().text();
					$(newContainer).find('.theme_description').first().text(themeDescription);
					$(newContainer).appendTo('#available_themes ul.themes').fadeIn('fast');
				}
			});
		});
	};
	if( $('#current_theme').length > 0 ){
		
		var themeXml = window.themeDir+'/theme.xml';
		var themePreview = window.themeDir+'/preview.png';
		var themeName = window.themeDir.split('/').pop();
		$.ajax({
			type: "GET",
			url: themeXml,
			dataType: "xml",
			success: function(xml) {
				themePreview = ( $(xml).find('preview').text().length > 1 ) ? $(xml).find('preview').text() : themePreview ;
				$('#current_theme').find('img').first().attr('src', themePreview);
				themeName = ( $(xml).find('title').text().length > 1 ) ? $(xml).find('title').text().capitalize() : themeName.capitalize();
				$('#current_theme').find('h4').first().text(themeName);
				themeDescription = $(xml).find('description').first().text();
				$('#current_theme').find('.theme_description').first().text(themeDescription);
			}
		});
	}

    // VIMEO
	if( $('#tutorials').length > 0 )
    {
		var playlistUrl = 'http://vimeo.com/api/v2/user7117833/all_videos.json';
		var vidWidth = 450;
		var vidHeight = 253;
		$.ajax({
			type: "POST",
			url: playlistUrl,
			dataType: "jsonp",
			success: function(data) {
				var tuts = '<ul class="vimeo_playlist">';
				$.each(data, function(key, val) {
					tuts += '<li><a class="tutorial_button" data-vid_id="'+val['id']+'" >' + val['title'] + '</a></li>';
				});
				tuts += '</ul>'
				$('#tutorials h3').after(tuts);
			}
		});
    }

    $('a.tutorial_button').live('click', function(event){
		event.preventDefault();
		var videoId = $(this).data('vid_id');
		var vidWidth = 450;
		var vidHeight = 253;
		$('.vimeo_player').empty();
		var output = '<iframe src="http://player.vimeo.com/video/'+videoId+'?autoplay=true" width="'+vidWidth+'" height="'+vidHeight+'" frameborder="0"></iframe>';
		$(output).appendTo('.vimeo_player');
	});

    // TOGGLE CATEGORY INPUT
    $('#toggle_category_input').click( function(event){
        event.preventDefault();
        $(this).text( $(this).text() == "create new category" ? "X" : "create new category");
        $('#new_category_box').toggleClass('hidden');
    });

    // CREATE CATEGORY
    $('a.create_category').click( function(event)  {
        event.preventDefault();
        var container = $("#category_box");
        var title = $("input#category_title").val();
        var description = $("textarea#category_description").val();

        $.post( 'ajax/category_create.php',{title: title, description:description}, function(response) {
			var data = $.parseJSON( response );
            if( typeof data['title'] != 'undefined'){
				$('input#category_title').val('');
				$('textarea#category_description').val('');
                $('select#category option:selected').removeAttr('selected');
                $('select#category').append('<option value="'+data['id']+'" selected="selected" >'+data['title']+'</option>');
            }else{
                appendMessage( container, 'Could not create category', 'error_msg', 'margin: 10px;' )
            }						
        })
        .error(function() {
            appendMessage( container, 'Could not create category', 'error_msg' , 'margin: 10px;')
        });
    });
	
    // DELETE CATEGORY from category page
	$('a.category_delete').click( function(event) {
        event.preventDefault();
		var container = $(this).parents('li').last();
        var category_id = $(container).attr('id');
		$( '#delete_category_dialog' ).dialog({
			resizable: false,
			draggable: false,
			height: 170,
			modal: true,
			buttons: [
			{
				text: "No, keep it",
				click: function(){ 
					$(this).dialog('close'); 
				}
			},
			{
				text: "Yes, delete it",
				click: function(){
					$(this).dialog('close'); 
					$.post( 'ajax/category_delete.php',{id: category_id}, function(data) {
						if(data == 'true'){
							$(container).slideUp( function(){
								$(container).remove();
							});
						}else{
							initMessage('This could not be deleted', 'error_msg' );
						}						
					})
					.error(function() {
						initMessage( 'This could not be deleted', 'error_msg' );
					});
				}
			}
			]
		});
		
		return false; // don't follow the link!
	})
	// IMAGE PREVIEW
    $("a[rel^='prettyPhoto']").prettyPhoto({
        theme: 'pp_default',
        slideshow:5000, 
        autoplay_slideshow:true,
        deeplinking: false,
        social_tools: false
    });
		


	// PAGE WIDTH
	// $('#page_width').change( function(){
	// 	$.cookie('layout', $(this).val());
	// 	$('body').attr( 'id', $(this).val() );	
	// })
	
	// NEW META DATA
	$('#new_snippet_save').click( function(event){

        event.preventDefault();    
		var nameInput = $('#new_snippet_name');
		var valueInput = $('#new_snippet_value');
		var snippetName = $(nameInput).val();
		var snippetValue = $(valueInput).val();
		var contentId = $('#snippet_content_id').val();
		var valid = snippetValue && snippetName && snippetValue != "Snippet Value" && snippetName != "Snippet Name";

		if(valid){
			$.ajax({
				url: 'ajax/snippet_new.php',
				type: 'POST',
				dataType: 'json',
				data: {content_id: contentId, snippet_name: snippetName, snippet_value: snippetValue},
				complete: function(xhr, textStatus) {
					$(nameInput).val('');
					$(valueInput).val('');
					initPlaceholderText( nameInput );
					initPlaceholderText( valueInput );
				},
				success: function(data, textStatus, xhr) {
					if( typeof data == "object"){
						var output = '<fieldset class="name_value">';
						output += '<p class="half left"><input type="text" name="snippet_name" value="'+snippetName+'" id="snippet_name"></p>';
						output += '<p class="half right"><textarea type="text" name="snippet_value" id="snippet_value">'+snippetValue+'</textarea></p>';
						output += '<input type="hidden" name="snippet_id" value="'+contentId+'" id="snippet_id">';
						output += '<nav class="left">';
						output += '<ul><li><button class="snippet_update" >update</button></li><li><button class="snippet_delete">delete</button></li></ul>';				
						output += '</nav>';
						output += '</fieldset>';
						var position = $("#snippet").children().length -2 ;
						$("#snippet").children().eq(position).after(output);
					}
				},
				error: function(xhr, textStatus, errorThrown) {
				//called when there is an error
				}
			});
		}else{
			var message; 
			if( !snippetName || snippetName == $(nameInput).attr('placeholder') )
			{
				message = 'Opps, don\'t forget to enter a value for the Snippet Name';
				nameInput.focus();
			}else{
				message = 'Opps, don\'t forget about the Snippet Value';
				valueInput.focus();
			}
			$('#new_snippet_fields').find('.info_msg').remove();
			$('#new_snippet_fields').prepend('<div id="message" class="info_msg"><p>'+message+'</p></div>')
			.find('.info_msg').hide().slideDown().delay(3000).slideUp( function(){ $(this).remove() } );
		}
		
	});

	// UPDATE SNIPPET
	$('#snippet button.snippet_update').click( function(event){
		
        event.preventDefault();
		var item = $(this);
        var parent = item.parents('.name_value');
		var snippetId = parent.find('input[type="hidden"]').val();
		var snippetName = parent.find('input#snippet_name').val();
		var snippetValue = parent.find('textarea#snippet_value').val();
		var isOption = ($('#snippet_content_id').val() == 0) ? true : false ;
		$.ajax({
		  url: 'ajax/snippet_update.php',
		  type: 'POST',
		  dataType: '',
		  data: {id: snippetId, name:snippetName, value:snippetValue, isOption: isOption},
		  success: function(data, textStatus, xhr) {
				if(data == 'true'){
                    parent.effect( 'highlight', {}, 500);
                }else{
					parent.find('.warning_msg').remove();					
					parent.prepend('<p class="warning_msg">Sorry, there was a problem deleting this snippet data</p>')
					.find('.warning_msg').delay(3000).fadeOut( function(){ $(this).remove() } );
				}
		  },
		  error: function(xhr, textStatus, errorThrown) {
		    //called when there is an error
		  }
		});
		
	});
	
	// DELETE SNIPPET
	$('#snippet button.snippet_delete').click( function(event){

        event.preventDefault();
		var item = $(this);
        var parent = item.parents('.name_value');
		var snippetId = parent.find('input[type="hidden"]').val();
		var isOption = ($('#snippet_content_id').val() == 0) ? true : false ;

		$.ajax({
		  url: 'ajax/snippet_delete.php',
		  type: 'POST',
		  dataType: 'text',
		  data: {id: snippetId, isOption: isOption},
		  success: function(data, textStatus, xhr) {
				if(data == 'true'){
					parent.hide('slow', function(){
						$(this).remove();
					});			
				}else{
					parent.find('.warning_msg').remove();					
					parent.prepend('<p class="warning_msg">Sorry, there was a problem deleting this snippet data</p>')
					.find('.warning_msg').delay(3000).fadeOut( function(){ $(this).remove() } );
				}
		  },
		  error: function(xhr, textStatus, errorThrown) {}
		});
	});
	
	// SNIPPET NAME AUTO COMPLETE		
	$( "#new_snippet_name").autocomplete({
		minLength: 1,
		source: function( request, response ) {
			// delegate back to autocomplete, but extract the last term
			response( $.ui.autocomplete.filter(
				$.ajax({
				  url: 'ajax/snippet_get.php',
				  type: 'GET',
				  dataType: 'json',
				  data: {'snippet': request},
				  complete: function(xhr, textStatus) { },
				  success: function(data, textStatus, xhr) {
				    response( $.map( data, function( item ) {
						return {
							label: item,
							value: item
						}
					}));
				  },
				  error: function(xhr, textStatus, errorThrown) {}
				})
			, extractLast( request.term ) ) );
		},
		focus: function() {
			// prevent value inserted on focus
			return false;
		},
		select: function( event, ui ) {
			this.value = ui.item.value;
            $('new_snippet_value').focus();
			return false;
		}
	});

	$('#toggle_date_input').click( function(event){
        event.preventDefault();
		var open = $(this).text() == "X";
        $(this).text( !open ? "X" : "change date");
		$('#change_date_inputs').toggleClass( 'hidden')	
        if(!open){
			var created = $('#created_string').text();
			var date = new Date(created);
			var day = monthIntToMonthName(date.getMonth())+" "+date.getDate()+", "+date.getFullYear();
			$('#created_date').val(day); 
			$('#created_hour').val(date.getHours()); 
			$('#created_minutes').val(date.getMinutes()); 
		}
    });

	$('#created_date').datepicker({ dateFormat: 'M d, yy' });
	
    // TAG CREATE
    $('.create_tags').bind('click', function(event) {
        event.preventDefault();    
        var content_id = $('#content_id').val();
        var tags = $('#tags_input').val().split(',').clean();
        var cleanTags = new Array();
		for (var i = 0; i < tags.length; i++) {
			tags[i] = tags[i].trim();
			if(tags[i]){
				cleanTags.push(tags[i])	
			}
		};

        if(cleanTags.length > 0 ){
            $.post( this.href, {'id':content_id, 'tags':cleanTags}, function(reply) {
                var result = reply;
                if( result.length > 0){
                    $('#tags_input').val("");
                    $('ul#tag_list').empty();
                    for (var i = 0; i < result.length; i++) {
                        $('ul#tag_list').append('<li> <a href="#" class="delete_tag_btn" id='+result[i].id+'>delete</a> <span>'+result[i].tag+'</span> </li>');
                    };
                }else{
                    appendMessage('#tag_box', 'Could not create the tag', 'error_msg', 'margin-bottom:5px;');
                }						
            }, 'json');
        }else{
            appendMessage('#tag_box', 'Enter a tag or tags', 'error_msg', 'margin-bottom:5px;');
        }
    });

    // TAG DELETE
    $('.delete_tag_btn').live('click', function(event){
        event.preventDefault();
        var target = this;
        var tag_id = $(target).data('id');
        var content_id = $('#content_id').val();
        $.post( 'ajax/tag_delete.php', {'content_id':content_id, 'tag_id':tag_id}, function(reply){
            if(reply == 'true'){
                $(target).parent('li').hide();                
            }else{
                appendMessage('#tag_box', 'Could not delete the tag', 'error_msg', 'margin-bottom:5px;');
            }
        }, 'text');
    });
    
	// TAGS AUTO COMPLETE
	$( "#tags_input" )
	// don't navigate away from the field on tab when selecting an item
	.bind( "keydown", function( event ) {
		if ( event.keyCode === $.ui.keyCode.TAB &&
				$( this ).data( "autocomplete" ).menu.active ) {
			event.preventDefault();
		}
	}).autocomplete({
		minLength: 1,
		source: function( request, response ) {
			// delegate back to autocomplete, but extract the last term
			response( $.ui.autocomplete.filter(
				$.ajax({
				  url: 'ajax/tags_get.php',
				  type: 'GET',
				  dataType: 'json',
				  data: {'tag': extractLast( request.term ) },
				  complete: function(xhr, textStatus) { },
				  success: function(data, textStatus, xhr) {
				    response( $.map( data, function( item ) {
						return {
							label: item,
							value: item
						}
					}));
				  },
				  error: function(xhr, textStatus, errorThrown) {}
				})
			    , extractLast( request.term )
            ) );
		},
		focus: function() {
			// prevent value inserted on focus
			return false;
		},
		select: function( event, ui ) {
			var terms = split( this.value );
			// remove the current input
			terms.pop();
			// add the selected item
			terms.push( ui.item.value );
			// add placeholder to get the comma-and-space at the end
			terms.push( "" );
			this.value = terms.join( ", " );
			return false;
		}
	});
		
	// FORM PLACER HOLDER FIX
	$('[placeholder]').focus(function() {
		clearPlaceholderText( $(this) );
	}).blur(function() {
		initPlaceholderText( $(this) );
	}).blur();
	
	// clear placeholder text pre-submit
	$('[placeholder]').parents('form').submit(function() {
	  $(this).find('[placeholder]').each(function() {
	    var input = $(this);
	    if (input.val() == input.attr('placeholder')) {
	      input.val('');
	    }
	  })
	});
	
   // IMAGE UPLOAD
	$('#file_upload').uploadify({
		'uploader' : 'js/libs/uploadify/uploadify.swf',
		'script' : 'ajax/media_upload.php',
		'cancelImg' : 'images/icn_close.png',
		'buttonImg' : 'images/btn_upload.png',
		'scriptData'  : {'author_id':window.user_id},
		'multi' : false,
		'auto' : true,
		'onError' : function (event,ID,fileObj,errorObj) {
			alert(errorObj.type + ' Error: ' + errorObj.info);
		},
		'onComplete' : function(event, ID, fileObj, response, data) {
			var filedata = jQuery.parseJSON( response );

			if(filedata.errors.length > 0){
				$('#file_upload').parent().prepend('<p class="error_msg">'+filedata.errors[0]+'</p>')
				$('p.error_msg').delay(3000).slideUp( function(){$(this).remove()} );
				return;
			}
						
			// if #img_width and #img_height inputs exist and have values crop the image
			var cropWidth = $("#profile_image_width").val();
			var cropHeight = $("#profile_image_height").val();
			var profileId = $('#user_profile_id').val();
			
			if( cropWidth && cropHeight && isImage(filedata.filename) )
			{
				$.ajax({
				  url: 'ajax/image_crop.php',
				  type: 'POST',
				  dataType: 'json',
				  data: {'image_id': filedata.id, 'width': cropWidth, 'height': cropHeight, 'user_id':profileId },
				  success: function(data, textStatus, xhr) {
					if( isImage(filedata.filename) ){
						$('#image_placholder').html('<img src="'+filedata.filename+'"/>');
					}		
				  },
				  error: function(xhr, textStatus, errorThrown) {
				  }
				});
				
			}else{
				if( isImage(filedata.filename) ){
					$('#image_placholder').append('<img src="'+filedata.filename+'"/>');
				}				
			}
			
			$('#media_upload').delay(500).slideUp('slow', function(){
				$('#media_info').slideDown('slow');				
				$('#caption').val( filedata.caption ).select();
				$('#media_url').val( filedata.filename );
			});
		}
	});
	// FLOT
    if ( typeof gaData != "undefined" ) {
        $.plot($("#visitor_graph"), gaData, {
            xaxis: { mode: "time", position: 'bottom', tickLength: 5 , tickLength:null, tickColor:"#F7F7F7", font: { size: 13, weight: "bold", family: "Arial"}, color:"#444444" },
            yaxis: { mode: null, tickLength: 5, tickLength:null, tickColor:'#EEEEEE', font: { size: 13, weight: "bold", family: "Arial"}, color:"#444444" },
            colors: ["#409AD4"],
            lable: ["Unique Visits"],
            series: {
                lines: { show: true, lineWidth: 5, fill: true, fillColor: "rgba(64,154,212,0.15)"}, 
                points: { show: true, lineWidth: 2, fill:true, fillColor: '#FFFFFF' }, 
                shadowSize: 0
            },
            grid: { show:true, color:null, axisMargin: 20, borderWidth:0, borderColor:'#EEEEEE' /*, backgroundColor: { colors: ["#FFFFFF", "#EEEEEE"]  }*/ },
            legend: { show: true, labelFormatter: null, labelBoxBorderColor: "blue", noColumns: 20, position: "ne", margin: 20, backgroundColor: "red", backgroundOpacity: 0, container: null },
        }); 
    };
	// MESSAGES
	$('#message a.close').live( 'click', function(event){
		event.preventDefault;
		$(this).parents('#message').slideUp('fast',function(){
			$(this).remove();
		});
		return false;
	});
	
	$('#message').hide().delay(1000).slideDown();
   	
    // FORM VALIDATION
	
    $(document).ready(function(){
	
		$('form').validate({
			rules: { 	
				password_confirm: {
					required: $("input[id$='password']").length > 0,
					equalTo: "#password"
				}
			}
		});

		// $('#user_edit').validate({
		// 	rules: {
		// 		username: {
		// 			required: true,
		// 			minLength: 2
		// 		},
		// 		 email: {
		// 		 	required: true,
		// 		 	email: true
		// 		 },
		// 		 password: {
		// 		 	required: true,//$('#password').attr('class') == 'required',
		// 		 	minLength: 2
		// 		 },
		// 		 password_confirm: {
		// 		 	required: $("input[id$='password']").length > 0,
		// 		 	equalTo: "#password"
		// 		 }
		// 	}
		// });

	} )
    // prevent form submission on enter
    $(window).keydown(function(event){
        if(event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });
 
});


//WYSIWIG EDITOR (tiny editor)
if( $('#content_body').length > 0 ){
	
	CKEDITOR.replace( 'content_body', {
		skin: 'launchpad',
        filebrowserImageBrowseUrl : 'file_browser.php?type=Images',
        filebrowserImageUploadUrl : 'ajax/media_editor_upload.php?type=Images',		
        filebrowserWindowWidth : '1024',
        filebrowserWindowHeight : '770',
		extraPlugins : 'excerpt',
		toolbar :
		[
			{ name: 'source', items : [ 'Source'] },
			{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','-','Undo','Redo','-','Find','SelectAll' ] },
			{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','-','Subscript','Superscript',] },
			{ name: 'paragraph', items : [ 'NumberedList','BulletedList','Blockquote','-','Outdent','Indent','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ] },
			{ name: 'links', items : [ 'Link','Unlink','Anchor'] },
			{ name: 'insert', items : [ 'Image','Flash','Table','HorizontalRule','SpecialChar','Iframe','Excerpt'] },
			{ name: 'colors', items : [ 'TextColor','BGColor' ] },
			{ name: 'tools', items : [ 'RemoveFormat', 'ShowBlocks', 'Maximize' ] },
			{ name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
		]
		
	});

	
}
