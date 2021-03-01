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
import { useBlockProps } from '@wordpress/block-editor';

/**
 * WP Dependencies
 */
import apiFetch from '@wordpress/api-fetch';
import ServerSideRender from '@wordpress/server-side-render';
import {RichText} from '@wordpress/block-editor';
import {withState} from '@wordpress/compose';
import {addFilter, applyFilter} from '@wordpress/hooks';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit(props) {
    const autocompleter = {
        name: 'codemascot/fnugg',
        triggerPrefix: '~',
        options: (search) => {
            if (search) {
                return apiFetch({
                    path: 'codemascot/v1/autocomplete/?q=' + search
                });
            }
            return [];
        },
        isDebounced: true,
        getOptionLabel: (item) => {
            return <span>{item.name} <small>{item.site_path}</small></span>;
        },
        // Declares that options should be matched by their name
        getOptionKeywords: item => [item.name, item.site_path],
        // completions should be removed, but then spawn setPost
        getOptionCompletion: (item) => {
            return {
                action: 'replace',
                value: props.setAttributes({
					name: item.name,
					sitePath: item.site_path,
				}),
            };
        },
    };

    // Our filter function
    addFilter(
        'editor.Autocomplete.completers',
        'codemascot',
		(completers, blockName) => {
			return blockName === 'codemascot/fnugg'
				? [...completers, autocompleter]
				: completers;
		}
    );

    return (
		<p { ...useBlockProps() }>
			<p>{__('Please select resort...', 'fnugg')}</p>
			<RichText
				tagName="p"
				placeholder={__('Use tilda(~) to trigger the autocomplete...', 'fnugg')}
				withoutInteractiveFormatting
				onChange={(value) => {}}
				value={props.attributes.name}
				aria-autocomplete="list"
			/>
			<small>{props.attributes.sitePath}</small>
		</p>
    );
}
