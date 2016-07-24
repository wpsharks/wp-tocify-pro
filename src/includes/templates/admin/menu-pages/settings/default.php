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

        <?= $Form->inputRow([
            'label' => __('JS/CSS Content Selector', 'wp-tocify'),
            'tip'   => __('This must be a valid CSS selector. It\'s used by jQuery to find the DOM \'content\'.<hr />Many WordPress themes use <code>.entry-content</code>, but this is not univeral. You may need to customize this depending on the theme you\'re using.<hr />Only the first matching selector applies; i.e., this comma-delimited list is in order of priority.', 'wp-tocify'),
            'note'  => __('Widening the scope of this selector may pick up additional headings like an &lt;h1&gt; tag.', 'wp-tocify'),

            'name'  => 'context',
            'value' => s::getOption('context'),
        ]); ?>

        <?= $Form->selectRow([
            'label' => __('Enable Anchors by Default?', 'wp-tocify'),
            'tip'   => sprintf(__('When %1$s is applicable (based on your Post Type options), what should be the default setting for a Post?<hr />This controls h[1-6] headings being anchored in the article (by default), or not.', 'wp-tocify'), esc_html($this->App->Config->©brand['©name'])),
            'note'  => __('It is suggested that you enable this by default so that headings will always be anchored.', 'wp-tocify'),

            'name'    => 'default_anchors_enable',
            'value'   => s::getOption('default_anchors_enable'),
            'options' => [
                '0' => __('No', 'wp-tocify'),
                '1' => __('Yes', 'wp-tocify'),
            ],
        ]); ?>

        <?= $Form->selectRow([
            'label' => __('Enable TOC by Default?', 'wp-tocify'),
            'tip'   => sprintf(__('When %1$s is applicable (based on your Post Type options), what should be the default setting for a Post?<hr />This controls the Table of Contents being shown in the article (by default), or not.', 'wp-tocify'), esc_html($this->App->Config->©brand['©name'])),
            'note'  => __('Choosing <strong>via [toc] shortcode</strong> allows you to insert a TOC whenever and wherever you want.<pre style="margin:.5em 0 0 .5em;">[toc float="right|left|none" style="none|default"]'."\n".' Example: [toc float="right"] at the top of your article.'."\n".' Example: [toc style="none"] to get unstyled list items anywhere.</pre>'),

            'name'    => 'default_toc_enable',
            'value'   => s::getOption('default_toc_enable'),
            'options' => [
                '0'                           => __('No', 'wp-tocify'),
                '-float-right -style-default' => __('Yes (float right)', 'wp-tocify'),
                '-float-left -style-default'  => __('Yes (float left)', 'wp-tocify'),
                'via-shortcode'               => __('Yes (via [toc] shortcode)', 'wp-tocify'),
            ],
        ]); ?>

    <?= $Form->closeTable(); ?>

    <hr />

    <?= $Form->openTable(
        __('Post Type Options', 'wp-tocify'),
        sprintf(__('These two settings give you the flexibility to include (or exclude) specific Post Types. It\'s usually easier to forget about the list of exclusions and instead choose specific Post Types to include. The choice is yours. You can use one of these lists only, or both together at the same time.', 'wp-tocify'), esc_url(s::brandUrl('/kb')))
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
        sprintf(__('These options allow you to style the anchors used by %1$s.', 'wp-tocify'), esc_html($this->App->Config->©brand['©name']))
    ); ?>

        <?= $Form->inputRow([
            'label' => __('Custom Anchor Symbol', 'wp-tocify'),
            'tip'   => __('This controls the marker used to indicate a hashed location.', 'wp-tocify'),
            'note'  => __('Copy/paste alternatives: <span style="font-size:1.5em;">¶ § 🔗 #</span><br />', 'wp-tocify').
                       __('You can also set this to a URL or path; e.g., <code>/custom-icon.svg</code>', 'wp-tocify'),

            'name'  => 'anchor_symbol',
            'value' => s::getOption('anchor_symbol'),
        ]); ?>

        <?= $Form->inputRow([
            'label' => __('Custom TOC Symbol', 'wp-tocify'),
            'tip'   => __('This controls the marker used to indicate a hashed location in the TOC.', 'wp-tocify'),
            'note'  => __('Copy/paste alternatives: <span style="font-size:1.5em;">¶ § 🔗 #</span><br />', 'wp-tocify').
                       __('You can also set this to a URL or path; e.g., <code>/custom-icon.svg</code>', 'wp-tocify'),

            'name'  => 'toc_symbol',
            'value' => s::getOption('toc_symbol'),
        ]); ?>

        <?= $Form->textareaRow([
            'label' => __('Custom Anchor Styles', 'wp-tocify'),
            'tip'   => __('e.g., Add lines that customize style attributes such as <code>width:</code>, <code>color:</code>, etc.', 'wp-tocify'),
            'note'  => __('Use <a href="https://css-tricks.com/when-using-important-is-the-right-choice/" target="_blank"><code>!important</code></a> to override default structural styles.', 'wp-tocify'),

            'name'  => 'custom_styles',
            'value' => s::getOption('custom_styles'),
        ]); ?>

    <?= $Form->closeTable(); ?>
    <?= $Form->submitButton(); ?>
<?= $Form->closeTag(); ?>
