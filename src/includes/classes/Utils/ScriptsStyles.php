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

/**
 * Scripts/styles.
 *
 * @since 16xxxx Initial release.
 */
class ScriptsStyles extends SCoreClasses\SCore\Base\Core
{
    /**
     * On body classes.
     *
     * @since 16xxxx Initial release.
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
        $classes[] = $this->App->Config->©brand['©slug'].'-enable';
        return $classes; // With attribute to enable.
    }

    /**
     * Scripts/styles.
     *
     * @since 16xxxx Initial release.
     */
    public function onWpEnqueueScripts()
    {
        if (!$this->isApplicable()) {
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
            'options' => [
                'context' => s::getOption('context'),
            ],
            'i18n' => [
                'tocHeading' => __('Table of Contents', 'wp-tocify'),
            ],
        ]);
    }

    /**
     * Format symbol for CSS `content:` usage.
     *
     * @since 16xxxx Initial release.
     *
     * @param string $symbol Symbol to format.
     *
     * @return string Formatted symbol; for CSS `content:` usage.
     */
    protected function formatSymbol(string $symbol): string
    {
        if (mb_stripos($symbol, 'http') === 0 || mb_strpos($symbol, '/') === 0) {
            return 'url('.c::sQuote($symbol).')';
        }
        return c::sQuote($symbol);
    }

    /**
     * Is applicable?
     *
     * @since 16xxxx Initial release.
     *
     * @return bool True if applicable.
     */
    protected function isApplicable(): bool
    {
        if (($is = &$this->cacheKey(__FUNCTION__)) !== null) {
            return $is; // Cached this already.
        }
        if (!is_singular() || !s::getPostMeta(null, '_enable')) {
            return $is = false; // Not applicable.
        }
        $include_post_types = s::getOption('include_post_types');
        $exclude_post_types = s::getOption('exclude_post_types');

        if ($include_post_types && !in_array(get_post_type(), $include_post_types, true)) {
            return $is = false; // Not applicable.
        } elseif ($exclude_post_types && in_array(get_post_type(), $exclude_post_types, true)) {
            return $is = false; // Not applicable.
        }
        return $is = true; // Applicable; i.e., nothing excluded it above.
    }
}
