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
import {RichText} from '@wordpress/block-editor';
import {addFilter, applyFilters} from '@wordpress/hooks';

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
    const autocompleters = [{
        name: 'codemascot/fnugg',
        triggerPrefix: '~',
        options: (search) => {
            if (! search || 'undefined' === search) {
                return [];
            }

            let uri = applyFilters(
                'fnugg_autocompleter_remote_api_options_uri',
                ['codemascot/v1/autocomplete/?q=', search]
            );

            return apiFetch({path: uri[0] + uri[1]}).then(
                (resp) => {
                    return resp;
                }
            );
        },
        isDebounced: true,
        getOptionLabel: (item) => {
            let label = applyFilters(
                'fnugg_autocompleter_option_label',
                [
                    <span>{item.name} <small>{item.site_path}</small></span>,
                    item
                ]
            );

            return label[0];
        },
        // Declares that options should be matched by their name
        getOptionKeywords: item => [item.name, item.site_path],
        // completions should be removed, but then spawn setPost
        getOptionCompletion: (item) => {
            let params = applyFilters(
                'fnugg_autocompleter_options_set_attributes',
                [
                    'replace',
                    item
                ]
            );

            return {
                action: params[0],
                value: props.setAttributes({
                    name: params[1].name,
                    site_path: params[1].site_path,
                }),
            };
        },
    }];

    // Tried to use the Autocomplete component first,
    // but faced issues with it.
    // @see https://github.com/WordPress/gutenberg/issues/10542
    // Therefore used the RichText block and replaced it's
    // `autocompleter` with `editor.Autocomplete.completers` hook.
    addFilter(
        'editor.Autocomplete.completers',
        'codemascot',
        (completers, blockName) => {
            return (blockName === 'codemascot/fnugg') ? autocompleters : completers;
        }
    );

    return (
        <p { ...useBlockProps() }>
            <h3>{__('Fnugg: Please select resort...', 'fnugg')}</h3>
            <RichText
                tagName="p"
                placeholder={__('Use tilda(~) to trigger the autocomplete...', 'fnugg')}
                withoutInteractiveFormatting
                onChange={(value) => {}}
                value={props.attributes.name}
                aria-autocomplete="list"
            />
            <small>{props.attributes.site_path}</small>
        </p>
    );
}
