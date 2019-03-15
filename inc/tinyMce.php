<?php

/**
 * Cleans up TinyMCE Buttons to show all relevant buttons on the first bar. Adds an easy to configure way to change these defaults.
 *
 *
 * ## Updating global TinyMce settings using the JSON config
 *
 * By updating the function `getConfig` you can easily add new **Block Formats**, **Style Formats** and **Toolbars** for all Wysiwyg editors in your project.
 *
 * ## Editor Toolbars
 *
 * The MCE Buttons that show up by default are specified by the `toolbars` section in the `getConfig` function.
 * You can modify the settings for all Wysiwyg toolbars (all over your project).
 */
namespace Flynt\TinyMce;

// First Toolbar
add_filter('mce_buttons', function ($buttons) {
    $config = getConfig();
    if ($config && isset($config['toolbars'])) {
        $toolbars = $config['toolbars'];
        if (isset($toolbars['default']) && isset($toolbars['default'][0])) {
            return $toolbars['default'][0];
        }
    }
    return $buttons;
});

// Second Toolbar
add_filter('mce_buttons_2', function ($buttons) {
    return [];
});

add_filter('tiny_mce_before_init', function ($init) {
    $config = getConfig();
    if ($config) {
        if (isset($config['blockformats'])) {
            $init['block_formats'] = getBlockFormats($config['blockformats']);
        }

        if (isset($config['styleformats'])) {
            // Send it to style_formats as true js array
            $init['style_formats'] = json_encode($config['styleformats']);
        }
    }
    return $init;
});

add_filter('acf/fields/wysiwyg/toolbars', function ($toolbars) {
    // Load Toolbars and parse them into TinyMCE
    $config = getConfig();
    if ($config && !empty($config['toolbars'])) {
        $toolbars = array_map(function ($toolbar) {
            array_unshift($toolbar, []);
            return $toolbar;
        }, $config['toolbars']);
    }
    return $toolbars;
});

function getBlockFormats($blockFormats)
{
    if (!empty($blockFormats)) {
        $blockFormatStrings = array_map(function ($tag, $label) {
            return "${label}=${tag}";
        }, $blockFormats, array_keys($blockFormats));
        return implode(';', $blockFormatStrings);
    }
    return '';
}

function getConfig()
{
    return [
        'blockformats' => [
            'Paragraph' => 'p',
            'Heading 1' => 'h1',
            'Heading 2' => 'h2',
            'Heading 3' => 'h3',
            'Heading 4' => 'h4',
            'Heading 5' => 'h5',
            'Heading 6' => 'h6'
        ],
        'styleformats' => [
            [
                'title' => 'Buttons',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Button Primary',
                        'classes' => 'btn btn--primary',
                        'selector' => 'a'
                    ],
                    [
                        'title' => 'Button Primary Block',
                        'classes' => 'btn btn--primary btn--block',
                        'selector' => 'a'
                    ]
                ]
            ]
        ],
        'toolbars' => [
            'default' => [
                [
                    'formatselect',
                    'styleselect',
                    'bold',
                    'italic',
                    'underline',
                    'strikethrough',
                    '|',
                    'bullist',
                    'numlist',
                    '|',
                    'link',
                    'unlink',
                    '|',
                    'wp_more',
                    'pastetext',
                    'removeformat',
                    '|',
                    'undo',
                    'redo',
                    'fullscreen'
                ]
            ],
            'full' => [
                [
                    'formatselect',
                    'styleselect',
                    'bold',
                    'italic',
                    'underline',
                    'blockquote',
                    '|',
                    'bullist',
                    'numlist',
                    '|',
                    'link',
                    'unlink',
                    '|',
                    'pastetext',
                    'removeformat',
                    '|',
                    'undo',
                    'redo',
                    'fullscreen'
                ]
            ],
            'basic' => [
                [
                    'bold',
                    'italic',
                    'underline',
                    'blockquote',
                    'bullist',
                    'numlist',
                    'alignleft',
                    'aligncenter',
                    'alignright',
                    'undo',
                    'redo',
                    'link',
                    'unlink',
                    'fullscreen'
                ]
            ]
        ]
    ];
}