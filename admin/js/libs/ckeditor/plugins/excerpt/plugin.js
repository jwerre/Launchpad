
(function(){

	var inserted = false;

	function createFakeElement( editor, realElement )
	{
		return editor.createFakeParserElement( realElement, null, 'comment', true );
	}

	CKEDITOR.plugins.add( 'excerpt',
	{
		init: function( editor ) {
			editor.addCommand( 'insertExcerpt',
			{
				exec : function( editor )
				{    
					if(!inserted){
						editor.insertHtml( '<!--excerpt-->' );
						inserted = true;
					}else{
						var removed = editor.getData().replace('<!--excerpt-->','', 'gm');
						editor.setData(removed);
						inserted = false;
					}
				}
			});

			editor.ui.addButton( 'Excerpt',
			{
				label: 'Insert excerpt',
				command: 'insertExcerpt',
				icon: this.path + 'images/icn_excerpt.png'
			});
		},

	});

})();
