<?php
/**
 * Scripts/styles.
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
 * Scripts/styles.
 *
 * @since 160724.1960 Initial release.
 */
class ScriptsStyles extends SCoreClasses\SCore\Base\Core
{
    /**
     * On body classes.
     *
     * @since 160724.1960 Initial release.
     *
     * @param array $classes Body classes.
     *
     * @return array Filtered body classes.
     */
    public function onBodyClass(array $classes): array
    {
        if (!$this->isApplicable()) {
            return $classes; // Not applicable.
        }
        $classes[]      = $this->App->Config->©brand['©slug'];
        return $classes = array_unique($classes);
    }

    /**
     * Scripts/styles.
     *
     * @since 160724.1960 Initial release.
     */
    public function onWpEnqueueScripts()
    {
        if (!($settings = $this->isApplicable())) {
            return; // Not applicable.
        }
        $brand_slug    = $this->App->Config->©brand['©slug'];
        $inline_styles = '.'.$brand_slug.'-anchor::after { content: '.$this->formatSymbol(s::getOption('anchor_symbol')).' !important; }'."\n".
            '.'.$brand_slug.'-toc ul > li::before { content: '.$this->formatSymbol(s::getOption('toc_symbol')).' !important; }'."\n".
            s::getOption('custom_styles'); // Any other custom styles.

        wp_enqueue_style($this->App->Config->©brand['©slug'], c::appUrl('/client-s/css/site/toc.min.css'), [], $this->App::VERSION);
        wp_add_inline_style($this->App->Config->©brand['©slug'], $inline_styles);

        wp_enqueue_script($this->App->Config->©brand['©slug'], c::appUrl('/client-s/js/site/toc.min.js'), ['jquery'], $this->App::VERSION, true);
        wp_localize_script($this->App->Config->©brand['©slug'], 'sQVXAaHbXuTCEnBDLBHQpNkxWYfJdfmVData', [
            'brand' => [
                'slug' => $this->App->Config->©brand['©slug'],
                'var'  => $this->App->Config->©brand['©var'],
            ],
            'settings' => $settings, // Via `isApplicable()`.

            'i18n' => [
                'tocHeading' => __('Table of Contents', 'wp-tocify'),
            ],
        ]);
    }

    /**
     * Is applicable?
     *
     * @since 160724.1960 Initial release.
     *
     * @return array Settings if applicable.
     */
    protected function isApplicable(): array
    {
        if (($is = &$this->cacheKey(__FUNCTION__)) !== null) {
            return $is; // Cached this already.
        } elseif (!is_singular(/* post type. */)) {
            return $is = []; // Not applicable.
        }
        $context        = s::getOption('context'); // e.g., `.entry-content`, etc.
        $anchors_enable = s::getPostMeta(null, '_anchors_enable', s::getOption('default_anchors_enable'));

        if (!$context || !$anchors_enable) {
            return $is = []; // Not applicable.
        }
        $current_post_type  = get_post_type();
        $include_post_types = s::getOption('include_post_types');
        $exclude_post_types = s::getOption('exclude_post_types');

        if ($include_post_types && !in_array($current_post_type, $include_post_types, true)) {
            return $is = []; // Not applicable.
        } elseif ($exclude_post_types && in_array($current_post_type, $exclude_post_types, true)) {
            return $is = []; // Not applicable.
        }
        $toc_enable = s::getPostMeta(null, '_toc_enable', s::getOption('default_toc_enable'));

        return $is = ['context' => $context, 'anchorsEnable' => $anchors_enable, 'tocEnable' => $toc_enable];
    }

    /**
     * Format symbol for CSS `content:` usage.
     *
     * @since 160724.1960 Initial release.
     *
     * @param string $symbol Symbol to format.
     *
     * @return string Formatted symbol; for CSS `content:` usage.
     */
    protected function formatSymbol(string $symbol): string
    {
        if (preg_match('/\.[^\/.]+$/ui', $symbol)) {
            return 'url('.c::sQuote($symbol).')';
        }
        return c::sQuote($symbol);
    }
}
