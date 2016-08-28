<?php
/**
 * VS upgrades.
 *
 * @author @jaswsinc
 * @copyright WP Sharks™
 */
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

/**
 * VS upgrades.
 *
 * @since $v Initial release.
 */
class VsUpgrades extends SCoreClasses\SCore\Base\Core
{
    /**
     * Version-specific upgrade.
     *
     * @since $v VS upgrade handler.
     */
    public function fromLt160827()
    {
        // Back compat. Strip leading `-` dashes.
        $default_toc_enable = s::getOption('default_toc_enable');
        $default_toc_enable = preg_replace(['/^\-/u', '/\s+\-/u'], ['', ' '], $default_toc_enable);
        s::updateOptions(compact('default_toc_enable'));
    }
}
