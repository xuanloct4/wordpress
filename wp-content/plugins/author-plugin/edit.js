/**
 * External dependencies
 */
 import { forEach } from 'lodash';

/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */
import { 
	useBlockProps,
	InspectorControls
} from '@wordpress/block-editor';

import {
	PanelBody,
	PanelRow,
	QueryControls,
	SelectControl,
	ToggleControl,
	RangeControl
} from '@wordpress/components';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

import { useSelect } from '@wordpress/data';

import { RawHTML } from '@wordpress/element';

import { dateI18n, format, __experimentalGetSettings } from '@wordpress/date';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit( { attributes, setAttributes } ) {

	const { 
		numberOfItems, 
		columns, 
		displayExcerpt, 
		displayDate, 
		displayThumbnail, 
		displayAuthorInfo, 
		showAvatar, 
		avatarSize, 
		showBio 
	} = attributes;

	const { authorDetails, posts } = useSelect(
		( select ) => {

			const _authorId = select( 'core/editor' ).getCurrentPostAttribute( 'author' );

			const authorDetails = _authorId ? select( 'core' ).getUser( _authorId ) : null;
		
			const posts = select( 'core' ).getEntityRecords( 'postType', 'post', {
				'author': _authorId,
				'per_page': numberOfItems,
				'_embed': true
			});

			return { 
				authorDetails: authorDetails,
				posts: posts
			};
		},
		[ numberOfItems ]
	);

	const avatarSizes = [];
	if ( authorDetails ) {
		forEach( authorDetails.avatar_urls, ( url, size ) => {
			avatarSizes.push( {
				value: size,
				label: `${ size } x ${ size }`,
			} );
		} );
	}
	
	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Author Info', 'author-plugin' ) }>
					<PanelRow>
						<ToggleControl
							label={ __( 'Display Author Info', 'author-plugin' ) }
							checked={ displayAuthorInfo }
							onChange={ () =>
								setAttributes( { displayAuthorInfo: ! displayAuthorInfo } )
							}
						/>
					</PanelRow>
					{ displayAuthorInfo && (
						<>
							<PanelRow>
								<ToggleControl
									label={ __( 'Show avatar' ) }
									checked={ showAvatar }
									onChange={ () =>
										setAttributes( { showAvatar: ! showAvatar } )
									}
								/>
								{ showAvatar && (
									<SelectControl
										label={ __( 'Avatar size' ) }
										value={ avatarSize }
										options={ avatarSizes }
										onChange={ ( size ) => {
											setAttributes( {
												avatarSize: Number( size ),
											} );
										} }
									/>
								) }
							</PanelRow>
							<PanelRow>
								<ToggleControl
									label={ __( 'Show Bio', 'author-plugin' ) }
									checked={ showBio }
									onChange={ () =>
										setAttributes( { showBio: ! showBio } )
									}
								/>
							</PanelRow>
						</>
					) }
				</PanelBody>
				<PanelBody title={ __( 'Content Settings', 'author-plugin' ) }>
					<PanelRow>
						<QueryControls 
							numberOfItems={ numberOfItems }
							onNumberOfItemsChange={ ( value ) =>
								setAttributes( { numberOfItems: value } )
							}
							minItems={ 1 }
							maxItems={ 10 }
						/>
					</PanelRow>
					<PanelRow>
						<RangeControl
							label={ __( 'Number of Columns', 'author-plugin' ) }
							value={ columns }
							onChange={ ( value ) =>
								setAttributes( { columns: value } )
							}
							min={ 1 }
							max={ 4 }
							required
						/>
					</PanelRow>
					<PanelRow>
						<ToggleControl
							label={ __( 'Show Featured Image', 'author-plugin' ) }
							checked={ displayThumbnail }
							onChange={ () =>
								setAttributes( { displayThumbnail: ! displayThumbnail } )
							}
						/>
					</PanelRow>
					<PanelRow>
						<ToggleControl
							label={ __( 'Show Date', 'author-plugin' ) }
							checked={ displayDate }
							onChange={ () =>
								setAttributes( { displayDate: ! displayDate } )
							}
						/>
					</PanelRow>
					<PanelRow>
						<ToggleControl
							label={ __( 'Display Excerpt', 'author-plugin' ) }
							checked={ displayExcerpt }
							onChange={ () =>
								setAttributes( { displayExcerpt: ! displayExcerpt } )
							}
						/>
					</PanelRow>
				</PanelBody>
			</InspectorControls>
			<div { ...useBlockProps() }>
				{ displayAuthorInfo  && authorDetails && (
					<div className="wp-block-author-box-author-plugin__author">
						{ showAvatar && (
							<div className="wp-block-author-box-author-plugin__avatar">
								<img
									width={ avatarSize }
									src={
										authorDetails.avatar_urls[
											avatarSize
										]
									}
									alt={ authorDetails.name }
								/>
							</div>
						) }
						<div className='wp-block-author-box-author-plugin__author-content'>
							<div className='wp-block-author-box-author-plugin__name'>
								{ authorDetails.name }
							</div>
							{ showBio &&
								// https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Operators/Optional_chaining
								authorDetails?.description &&
								authorDetails.description.length > 0 && (
								<p className='wp-block-author-box-author-plugin__description'>{ authorDetails.description }</p>
							) }
						</div>
					</div>
				)}
				{/* https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Template_literals */}
				<ul className={ `wp-block-author-box-author-plugin__post-items columns-${ columns }` }>
					{ posts && posts.map( ( post ) => {					
						return (
							<li key={ post.id }>
								{
									displayThumbnail && 
									post._embedded && 
									post._embedded['wp:featuredmedia'] &&
									post._embedded['wp:featuredmedia'][0] &&
									<img 
									className='wp-block-author-box-author-plugin__post-thumbnail'
										src={ post._embedded['wp:featuredmedia'][0].media_details.sizes.large.source_url }
										alt={ post._embedded['wp:featuredmedia'][0].alt_text }
									/>
								}
								<h5
									className='wp-block-author-box-author-plugin__post-title'
								>
									<a href={ post.link }>
										{ post.title.rendered ? (
											<RawHTML>
												{ post.title.rendered }
											</RawHTML>
										) : (
											__( 'Default title', 'author-plugin' )
										)}
									</a>
								</h5>
								{ 
									displayDate && (
										<time
											className='wp-block-author-box-author-plugin__post-date'
											dateTime={ format( 'c', post.date_gmt ) }
										>
											{ dateI18n(
												__experimentalGetSettings().formats.date, 
												post.date_gmt
											)}
										</time>
									) 
								}
								{ 
									displayExcerpt &&
									// https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Operators/Optional_chaining
									post.excerpt?.rendered && (
										<div className='wp-block-author-box-author-plugin__post-excerpt'>
											<RawHTML>
												{ post.excerpt.rendered }
											</RawHTML>
										</div>
									)
								}
							</li>
						)
					})}
				</ul>
			</div>
		</>
	);
}
