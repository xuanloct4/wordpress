/**
 * WordPress components that create the necessary UI elements for the block
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-components/
 */
import { TextControl } from '@wordpress/components';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps } from '@wordpress/block-editor';

import apiFetch from '@wordpress/api-fetch';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @param {Object}   props               Properties passed to the function.
 * @param {Object}   props.attributes    Available block attributes.
 * @param {Function} props.setAttributes Function that updates individual attributes.
 *
 * @return {WPElement} Element to render.
 */
export default function Edit( { attributes, setAttributes } ) {
	const blockProps = useBlockProps();

	if(!attributes.categories) {
	
		apiFetch(
			{
				url: "https://jsonplaceholder.typicode.com/albums/1/photos"
			}
		).then (categories => {
				setAttributes({
					categories: categories
				})
		})
	}
	if(!attributes.categories) {
		return 'Loading ...';
	};
	if(attributes.categories && attributes.categories === 0) {
		return 'No category';
	};
	console.log(attributes.categories);


	function updateCategory(e) {
		setAttributes( {
			selectedCategory: e.target.value
		});
	}
	return (
		// <div { ...blockProps }>
		// 	<label>Text Control</label>
		// 	<TextControl
		// 		value={ attributes.message }
		// 		onChange={ ( val ) => setAttributes( { message: val } ) }
		// 	/>
		// 	<label>Text Control</label>
		// </div>

		<div>
			<select onChange={updateCategory} value={attributes.selectedCategory}>
{
	attributes.categories.map (
		category => {
			return (
				<option value= {category.id} key= {category.id}>
					{category.title}
				</option>
			)
		}
	)
}
			</select>
			<input type="text" />
		</div>
	);
}
