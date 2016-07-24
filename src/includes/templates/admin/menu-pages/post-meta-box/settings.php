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

extract($this->current_vars); // Template variables.
$Form = $this->s::postMetaBoxForm('settings');
?>
<?= $Form->openTable(); ?>

    <?= $Form->selectRow([
        'label' => __('Enable Heading Anchors?', 'wp-tocify'),
        'tip'   => __('This adds anchors to each of your headings automatically.<hr />If you also want to display a Table of Contents, use the <code>[toc]</code> shortcode in your content; i.e., put <code>[toc]</code> where you want it displayed.', 'wp-tocify'),

        'name'    => '_enable',
        'value'   => s::getPostMeta($post_id, '_enable', s::getOption('meta_box_default_enable')),
        'options' => [
            '0' => __('No', 'wp-tocify'),
            '1' => __('Yes', 'wp-tocify'),
        ],
    ]); ?>

<?= $Form->closeTable(); ?>
