
( function() {
	var __ = wp.i18n.__;
	var el = wp.element.createElement;
	var Editable = wp.blocks.Editable;
	var children = wp.blocks.source.children;
	var registerBlockType = wp.blocks.registerBlockType;

	registerBlockType( 'blocks-for-gutenberg/sidenote', {
		title: __( 'Sidenote', 'blocks-for-gutenberg' ),
		icon: 'media-default',
		category: 'common',

		attributes: {
			content: children( 'div' ),
		},

		edit: function( props ) {
			var content = props.attributes.content;
			var focus   = props.focus;

			function onChangeContent( new_content ) {
				props.setAttributes( { content: new_content } );
			}

			return el(
				Editable,
				{
					tagName: 'div',
					className: props.className,
					onChange: onChangeContent,
					value: content,
					focus: focus,
					onFocus: props.setFocus,
				}
			);
		},

		save: function( props ) {

			var attrs = {
				className: props.className,
			};

			return el( 'div', attrs, props.attributes.content );

		},
	} );
} )();
