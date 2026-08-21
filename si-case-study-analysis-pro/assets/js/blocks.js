/**
 * SI Case Study Analysis Pro - Gutenberg Block Editor Script
 * 
 * @package SI_CSP
 * @since 1.0.0
 */

(function(wp) {
    'use strict';

    var el = wp.element.createElement;
    var registerBlockType = wp.blocks.registerBlockType;
    var ServerSideRender = wp.components.ServerSideRender;
    var TextControl = wp.components.TextControl;
    var SelectControl = wp.components.SelectControl;
    var PanelBody = wp.components.PanelBody;
    var InspectorControls = wp.editor.InspectorControls;

    // Register Case Study Card Block
    registerBlockType('si-csp/case-study-card', {
        title: 'Case Study Card',
        icon: 'admin-post',
        category: 'si-csp-blocks',
        keywords: ['case study', 'card', 'si csp'],
        attributes: {
            postId: {
                type: 'number',
                default: 0
            },
            displayStyle: {
                type: 'string',
                default: 'default'
            },
            showMeta: {
                type: 'boolean',
                default: true
            },
            showTags: {
                type: 'boolean',
                default: true
            }
        },

        edit: function(props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;

            return [
                el(InspectorControls, {key: 'inspector'},
                    el(PanelBody, {title: 'Case Study Settings', initialOpen: true},
                        el(SelectControl, {
                            label: 'Display Style',
                            value: attributes.displayStyle,
                            options: [
                                {label: 'Default', value: 'default'},
                                {label: 'Compact', value: 'compact'},
                                {label: 'Detailed', value: 'detailed'}
                            ],
                            onChange: function(value) {
                                setAttributes({displayStyle: value});
                            }
                        }),
                        el(SelectControl, {
                            label: 'Show Meta Information',
                            value: attributes.showMeta ? 'yes' : 'no',
                            options: [
                                {label: 'Yes', value: 'yes'},
                                {label: 'No', value: 'no'}
                            ],
                            onChange: function(value) {
                                setAttributes({showMeta: value === 'yes'});
                            }
                        }),
                        el(SelectControl, {
                            label: 'Show Tags',
                            value: attributes.showTags ? 'yes' : 'no',
                            options: [
                                {label: 'Yes', value: 'yes'},
                                {label: 'No', value: 'no'}
                            ],
                            onChange: function(value) {
                                setAttributes({showTags: value === 'yes'});
                            }
                        })
                    )
                ),
                el('div', {className: 'si-csp-block-placeholder'},
                    el('h4', {}, 'Case Study Card'),
                    el('p', {}, 'Select a case study to display.'),
                    el(SelectControl, {
                        label: 'Select Case Study',
                        value: attributes.postId,
                        options: [
                            {label: 'Choose...', value: 0}
                        ],
                        onChange: function(value) {
                            setAttributes({postId: parseInt(value)});
                        }
                    })
                )
            ];
        },

        save: function() {
            return null;
        }
    });

    // Register Blueprint Card Block
    registerBlockType('si-csp/blueprint-card', {
        title: 'Blueprint Card',
        icon: 'clipboard',
        category: 'si-csp-blocks',
        keywords: ['blueprint', 'card', 'si csp'],
        attributes: {
            postId: {
                type: 'number',
                default: 0
            },
            displayStyle: {
                type: 'string',
                default: 'default'
            }
        },

        edit: function(props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;

            return [
                el(InspectorControls, {key: 'inspector'},
                    el(PanelBody, {title: 'Blueprint Settings', initialOpen: true},
                        el(SelectControl, {
                            label: 'Display Style',
                            value: attributes.displayStyle,
                            options: [
                                {label: 'Default', value: 'default'},
                                {label: 'Compact', value: 'compact'}
                            ],
                            onChange: function(value) {
                                setAttributes({displayStyle: value});
                            }
                        })
                    )
                ),
                el('div', {className: 'si-csp-block-placeholder'},
                    el('h4', {}, 'Blueprint Card'),
                    el('p', {}, 'Select a blueprint to display.')
                )
            ];
        },

        save: function() {
            return null;
        }
    });

    // Register Audit Score Badge Block
    registerBlockType('si-csp/audit-badge', {
        title: 'Audit Score Badge',
        icon: 'awards',
        category: 'si-csp-blocks',
        keywords: ['audit', 'score', 'badge'],
        attributes: {
            postId: {
                type: 'number',
                default: 0
            },
            size: {
                type: 'string',
                default: 'medium'
            }
        },

        edit: function(props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;

            return el('div', {className: 'si-csp-block-placeholder'},
                el('h4', {}, 'Audit Score Badge'),
                el('p', {}, 'Displays the audit score for a case study.')
            );
        },

        save: function() {
            return null;
        }
    });

})(window.wp);
