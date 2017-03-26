<?php
/**
 * Template.
 *
 * @author @jaswsinc
 * @copyright WP Sharks™
 */
declare(strict_types=1);
namespace WebSharks\WpSharks\WpTocify\Pro;

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

if (!defined('WPINC')) {
    exit('Do NOT access this file directly.');
}
extract($this->vars); // Template variables.
$Form = $this->s::postMetaBoxForm('settings');
?>
<?= $Form->openTable(); ?>

    <?= $Form->selectRow([
        'label' => __('Enable Heading Anchors?', 'wp-tocify'),
        'tip'   => __('This adds anchors to each of your headings automatically.', 'wp-tocify'),

        'name'    => '_anchors_enable',
        'value'   => s::getPostMeta($post_id, '_anchors_enable', s::getOption('default_anchors_enable')),
        'options' => [
            '0' => __('No', 'wp-tocify'),
            '1' => __('Yes', 'wp-tocify'),
        ],
    ]); ?>

    <?= $Form->selectRow([
        'if' => '_anchors_enable',

        'label' => __('Show Table of Contents?', 'wp-tocify'),
        'tip'   => __('If enabled, this will show a Table of Contents (TOC).', 'wp-tocify'),

        'name'    => '_toc_enable',
        'value'   => s::getPostMeta($post_id, '_toc_enable', s::getOption('default_toc_enable')),
        'options' => [
            '0'                         => __('No', 'wp-tocify'),
            'float-left style-default'  => __('Yes (float left)', 'wp-tocify'),
            'float-right style-default' => __('Yes (float right)', 'wp-tocify'),
            'via-widget'                => __('Yes (via sidebar widget)', 'wp-tocify'),
            'via-shortcode'             => __('Yes (via [toc] shortcode)', 'wp-tocify'),
        ],
    ]); ?>

    <?= $Form->selectRow([
        'if' => '_toc_enable!=0|<disabled>',

        'label' => __('TOC (Max Heading Size)', 'wp-tocify'),
        'tip'   => __('Maximum heading size that will be displayed in the TOC.<hr />For instance, setting this to <code>h3</code> means that <code>h4</code>, <code>h5</code>, <code>h6</code> headings will not be shown in the TOC.', 'wp-tocify'),

        'name'    => '_toc_max_heading_size',
        'value'   => s::getPostMeta($post_id, '_toc_max_heading_size', s::getOption('default_toc_max_heading_size')),
        'options' => [
            '0' => __('Show All Headings', 'wp-tocify'),
            '1' => __('<h1>', 'wp-tocify'),
            '2' => __('<h2>', 'wp-tocify'),
            '3' => __('<h3>', 'wp-tocify'),
            '4' => __('<h4>', 'wp-tocify'),
            '5' => __('<h5>', 'wp-tocify'),
            '6' => __('<h6>', 'wp-tocify'),
        ],
    ]); ?>

<?= $Form->closeTable(); ?>
