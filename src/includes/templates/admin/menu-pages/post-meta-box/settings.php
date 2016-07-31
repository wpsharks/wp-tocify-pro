<?php
/**
 * Template.
 *
 * @author @jaswsinc
 * @copyright WP Sharks™
 */
declare (strict_types = 1);
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
        'label' => __('Show Table of Contents?', 'wp-tocify'),
        'tip'   => __('If enabled, this will show a Table of Contents.', 'wp-tocify'),

        'name'       => '_toc_enable',
        'depends_on' => ['_anchors_enable'],
        'value'      => s::getPostMeta($post_id, '_toc_enable', s::getOption('default_toc_enable')),
        'options'    => [
            '0'                           => __('No', 'wp-tocify'),
            '-float-right -style-default' => __('Yes (float right)', 'wp-tocify'),
            '-float-left -style-default'  => __('Yes (float left)', 'wp-tocify'),
            'via-shortcode'               => __('Yes (via [toc] shortcode)', 'wp-tocify'),
        ],
    ]); ?>

<?= $Form->closeTable(); ?>
