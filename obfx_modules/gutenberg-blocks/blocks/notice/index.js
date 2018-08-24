/**
 * WordPress dependencies...
 */
const { __ } = wp.i18n;

const { registerBlockType } = wp.blocks;

const { RichText } = wp.editor;

const { Notice } = wp.components;

/**
 * Internal dependencies
 */
import './style.scss';
import './editor.scss';

registerBlockType( 'orbitfox/notice', {
	title: __( 'Notice' ),
	description: __( 'Provide contextual feedback messages for typical user actions with the handful of available and flexible alert messages.' ),
	icon: 'info',
	category: 'orbitfox',
	keywords: [
		'notice',
		'info'
	],
	attributes: {
		title: {
			source: 'text',
			type: 'string',
			selector: '.obfx-block-notice__title',
			default: 'Info',
		},
		content: {
			type: 'array',
			source: 'children',
			selector: '.obfx-block-notice__content',
		},
	},

	styles: [
		{ name: 'sucess', label: __( 'Success' ), isDefault: true },
		{ name: 'info', label: __( 'Info' ) },
		{ name: 'warning', label: __( 'Warning' ) },
		{ name: 'error', label: __( 'Error' ) },
	],

	edit: ( props, { className } ) => {
		let status = "success";
		if ( props.attributes.className && props.attributes.className.includes( 'is-style-info') ) {
			status = "";
		} else if ( props.attributes.className && props.attributes.className.includes( 'is-style-warning') ) {
			status = "warning";
		} else if ( props.attributes.className && props.attributes.className.includes( 'is-style-error') ) {
			status = "error";
		}
		return (
			<Notice
				className={ className }
				isDismissible={ false }
				status={ status }
			>
				<RichText
					tagName="p"
					placeholder={ __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.' ) }
					value={ props.attributes.content }
					className="components-notice__content"
					onChange={ content => props.setAttributes( { content } ) }
					keepPlaceholderOnFocus="true"
				/>
			</Notice>
		)
	},
	save: props => {
		let status = "success";
		if ( props.attributes.className && props.attributes.className.includes( 'is-style-info') ) {
			status = "";
		} else if ( props.attributes.className && props.attributes.className.includes( 'is-style-warning') ) {
			status = "warning";
		} else if ( props.attributes.className && props.attributes.className.includes( 'is-style-error') ) {
			status = "error";
		}
		return (
			<Notice
				className="obfx-block-notice"
				isDismissible={ false }
				status={ status }
			>
				<RichText.Content
					tagName="p"
					className="components-notice__content"
					value={ props.attributes.content }
				/>
			</Notice>
		)
	},
});