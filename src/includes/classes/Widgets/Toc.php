<?php
/**
 * TOC widget.
 *
 * @author @jaswsinc
 * @copyright WP Sharks™
 */
declare (strict_types = 1);
namespace WebSharks\WpSharks\WpTocify\Pro\Classes\Widgets;

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
 * TOC widget.
 *
 * @since 160826 TOC widget.
 */
class Toc extends SCoreClasses\SCore\Base\Widget
{
    /**
     * Class constructor.
     *
     * @since 160826 TOC widget.
     */
    public function __construct()
    {
        $App  = c::app();
        $args = [
            'slug'        => 'toc',
            'name'        => sprintf(__('%1$s: TOC', 'wp-tocify'), esc_html($App->Config->©brand['©name'])),
            'description' => __('Table of Contents via sidebar widget.', 'wp-tocify'),
        ];
        $default_options = [
            // No default options yet.
        ];
        parent::__construct($App, $args, $default_options);
    }

    /**
     * Outputs the options form on admin.
     *
     * @since 160826 TOC widget.
     *
     * @param SCoreClasses\SCore\WidgetForm $Form    Instance.
     * @param array                         $options Options.
     *
     * @return string Form content markup.
     */
    protected function formContent(SCoreClasses\SCore\WidgetForm $Form, array $options): string
    {
        return $markup = ''; // Nothing here for now.
    }

    /**
     * Widget content markup.
     *
     * @since 160826 TOC widget.
     *
     * @param array $options Options.
     *
     * @return string Widget content markup.
     */
    protected function widgetContent(array $options): string
    {
        if (!a::isApplicable()) {
            return $markup = ''; // Not applicable.
        }
        return $markup = '<div class="-toc" style="display:none;"></div>';
    }
}
