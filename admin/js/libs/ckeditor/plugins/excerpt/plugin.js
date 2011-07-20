
(function(){

var inserted = false;
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
				console.log('CLICKED');
			}
		});

		editor.ui.addButton( 'Excerpt',
		{
			label: 'Insert excerpt',
			command: 'insertExcerpt',
			icon: this.path + 'images/icn_excerpt.png'
		});
	},

	afterInit : function( editor )
	{
		console.log('AFTER INIT');
		var dataProcessor = editor.dataProcessor,
			dataFilter = dataProcessor && dataProcessor.dataFilter;

		if ( dataFilter )
		{
			dataFilter.addRules(
				{
					elements :
					{
						'cke:object' : function( element )
						{
							var attributes = element.attributes,
								classId = attributes.classid && String( attributes.classid ).toLowerCase();

							return createFakeElement( editor, element );
						},

						'cke:embed' : function( element )
						{
							return createFakeElement( editor, element );
						}
					}
				},
				5);
		}
	}

});

function createFakeElement( editor, realElement )
{
	return editor.createFakeParserElement( realElement, 'excerpt', 'comment', true );
}

})();
