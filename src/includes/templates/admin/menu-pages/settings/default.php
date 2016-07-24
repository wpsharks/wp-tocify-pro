<?php
declare (strict_types = 1);
namespace WebSharks\WpSharks\WpTocify\Pro\Classes\Utils;

use WebSharks\WpSharks\WpTocify\Pro\Classes;
use WebSharks\WpSharks\WpTocify\Pro\Interfaces;
use WebSharks\WpSharks\WpTocify\Pro\Traits;
#
use WebSharks\WpSharks\WpTocify\Pro\Classes\AppFacades as a;
use WebSharks\WpSharks\WpTocify\Pro\Classes\SCoreFacades as s;
use WebSharks\WpSharks\WpTocify\Pro\Classes\CoreFacades as c;
#
use WebSharks\WpSharks\Core\Classes as SCoreClasses;
use WebSharks\WpSharks\Core\Interfaces as SCoreInterfaces;
use WebSharks\WpSharks\Core\Traits as SCoreTraits;
#
use WebSharks\Core\WpSharksCore\Classes as CoreClasses;
use WebSharks\Core\WpSharksCore\Classes\Core\Base\Exception;
use WebSharks\Core\WpSharksCore\Interfaces as CoreInterfaces;
use WebSharks\Core\WpSharksCore\Traits as CoreTraits;
#
use function assert as debug;
use function get_defined_vars as vars;

extract($this->current_vars); // Template vars.
$Form = $this->s::MenuPageForm('§save-options');
?>
<?= $Form->openTag(); ?>
    <?= $Form->openTable(
        __('General Options', 'wp-tocify'),
        sprintf(__('Browse the <a href="%1$s" target="_blank">knowledge base</a> to learn more about these options.', 'wp-tocify'), esc_url(s::brandUrl('/kb')))
    ); ?>

        <?= $Form->selectRow([
            'label' => __('Default Meta Box Option', 'wp-tocify'),
            'tip'   => sprintf(__('When %1$s is applicable (based on your Post Type options), what should be the default setting for a new Post?', 'wp-tocify'), esc_html($this->App->Config->©brand['©name'])),

            'name'    => 'meta_box_default_enable',
            'value'   => s::getOption('meta_box_default_enable'),
            'options' => [
                '0' => sprintf(__('Disable %1$s', 'wp-tocify'), esc_html($this->App->Config->©brand['©name'])),
                '1' => sprintf(__('Enable %1$s', 'wp-tocify'), esc_html($this->App->Config->©brand['©name'])),
            ],
        ]); ?>

        <?= $Form->inputRow([
            'label' => __('JS/CSS Content Selector', 'wp-tocify'),
            'tip'   => __('This must be a valid CSS selector. It\'s used by jQuery to find the \'content\' portion of an article in the DOM.<hr />Many WordPress themes use <code>.entry-content</code>, but this is not univeral. You may need to customize this depending on the theme you\'re using.<hr />Only the first matching selector applies; i.e., this comma-delimited list is in order of priority.', 'wp-tocify'),

            'name'  => 'context',
            'value' => s::getOption('context'),
        ]); ?>

    <?= $Form->closeTable(); ?>

    <hr />

    <?= $Form->openTable(
        __('Post Type Options', 'wp-tocify'),
        sprintf(__('These two settings give you the flexibility to include (or exclude) specific Post Types.', 'wp-tocify'), esc_url(s::brandUrl('/kb')))
    ); ?>

        <?= $Form->selectRow([
            'label' => __('Include Post Types', 'wp-tocify'),
            'tip'   => sprintf(__('This enables the %1$s meta box (and TOC-related functionality) for specific Post Types. If nothing selected, all are included by default.', 'wp-tocify'), esc_html($this->App->Config->©brand['©name'])),

            'multiple' => true,
            'name'     => 'include_post_types',
            'value'    => s::getOption('include_post_types'),
            'options'  => s::postTypeSelectOptions([
                'filters'            => [],
                'allow_empty'        => false,
                'allow_arbitrary'    => false,
                'current_post_types' => s::getOption('include_post_types'),
            ]),
        ]); ?>

        <?= $Form->selectRow([
            'label' => __('Exclude Post Types', 'wp-tocify'),
            'tip'   => sprintf(__('This disables the %1$s meta box (and TOC-related functionality) for specific Post Types that you exclude.', 'wp-tocify'), esc_html($this->App->Config->©brand['©name'])),

            'multiple' => true,
            'name'     => 'exclude_post_types',
            'value'    => s::getOption('exclude_post_types'),
            'options'  => s::postTypeSelectOptions([
                'filters'            => [],
                'allow_empty'        => false,
                'allow_arbitrary'    => false,
                'current_post_types' => s::getOption('exclude_post_types'),
            ]),
        ]); ?>

    <?= $Form->closeTable(); ?>

    <hr />

    <?= $Form->openTable(
        __('CSS / Style Options', 'wp-tocify'),
        sprintf(__('These allows you to style the anchors used by %1$s.', 'wp-tocify'), esc_html($this->App->Config->©brand['©name']))
    ); ?>

        <?= $Form->inputRow([
            'label' => __('Custom Anchor Symbol', 'wp-tocify'),
            'tip'   => __('This controls the marker used to indicate a hashed location.', 'wp-tocify'),
            'note'  => __('Copy/paste alternatives: <span style="font-size:1.5em;">¶ § 🔗 #</span><br />', 'wp-tocify').
                       __('You can also set this to a URL; e.g., a custom SVG icon.', 'wp-tocify'),

            'name'  => 'anchor_symbol',
            'value' => s::getOption('anchor_symbol'),
        ]); ?>

        <?= $Form->inputRow([
            'label' => __('Custom TOC Symbol', 'wp-tocify'),
            'tip'   => __('This controls the marker used to indicate a hashed location in the TOC.', 'wp-tocify'),
            'note'  => __('Copy/paste alternatives: <span style="font-size:1.5em;">¶ § 🔗 #</span><br />', 'wp-tocify').
                       __('You can also set this to a URL; e.g., a custom SVG icon.', 'wp-tocify'),

            'name'  => 'toc_symbol',
            'value' => s::getOption('toc_symbol'),
        ]); ?>

        <?= $Form->textareaRow([
            'label' => __('Custom Anchor Styles', 'wp-tocify'),
            'tip'   => __('e.g., Add lines that customize style attributes such as <code>width:</code>, <code>color:</code>, etc.', 'wp-tocify'),

            'name'  => 'custom_styles',
            'value' => s::getOption('custom_styles'),
        ]); ?>

    <?= $Form->closeTable(); ?>
    <?= $Form->submitButton(); ?>
<?= $Form->closeTag(); ?>
