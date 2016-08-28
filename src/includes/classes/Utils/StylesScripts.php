<?php
/**
 * Styles/scripts.
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
 * Styles/scripts.
 *
 * @since 160826 Styles/scripts.
 */
class StylesScripts extends SCoreClasses\SCore\Base\Core
{
    /**
     * Is applicable?
     *
     * @since 160826 Styles/scripts.
     *
     * @return bool True if applicable.
     */
    public function isApplicable(): bool
    {
        return (bool) $this->applicableSettings();
    }

    /**
     * On body classes.
     *
     * @since 160826 Styles/scripts.
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
     * @since 160826 Styles/scripts.
     */
    public function onWpEnqueueScripts()
    {
        if (!$this->isApplicable()) {
            return; // Not applicable.
        } elseif (!($settings = $this->applicableSettings())) {
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
     * Applicable settings.
     *
     * @since 160826 Styles/scripts.
     *
     * @return array Settings if applicable.
     */
    protected function applicableSettings(): array
    {
        if (($settings = &$this->cacheKey(__FUNCTION__)) !== null) {
            return $settings; // Cached this already.
        } elseif (!is_singular()) {
            return $settings = [];
        }
        $is_applicable_filter = s::applyFilters('is_applicable', null);
        // NOTE: This can be used to force a `true` or `false` value.

        if ($is_applicable_filter === false) {
            return $settings = []; // Not applicable.
        }
        $context                   = s::getOption('context'); // e.g., `.entry-content`
        $anchors_enable            = (int) s::getPostMeta(null, '_anchors_enable', s::getOption('default_anchors_enable'));
        $anchors_enable            = !$anchors_enable && $is_applicable_filter === true ? 1 : $anchors_enable;
        $anchors_adjust_scroll_pos = s::getOption('default_anchors_adjust_scroll_pos');

        if (!$context || !$anchors_enable) {
            return $settings = []; // Not applicable.
        }
        if ($is_applicable_filter !== true) {
            $current_post_type  = get_post_type();
            $include_post_types = s::getOption('include_post_types');
            $exclude_post_types = s::getOption('exclude_post_types');

            if ($include_post_types && !in_array($current_post_type, $include_post_types, true)) {
                return $settings = []; // Not applicable.
            } elseif ($exclude_post_types && in_array($current_post_type, $exclude_post_types, true)) {
                return $settings = []; // Not applicable.
            }
        }
        // Back compat. Strip leading `-` dashes so meta value is handled properly.
        $toc_enable           = (string) s::getPostMeta(null, '_toc_enable', s::getOption('default_toc_enable'));
        $toc_enable           = preg_replace(['/^\-/u', '/\s+\-/u'], ['', ' '], $toc_enable); // Back compat. Strip `-` dashes.
        $toc_max_heading_size = (int) s::getPostMeta(null, '_toc_max_heading_size', s::getOption('default_toc_max_heading_size'));
        $toc_min_headings     = max(1, s::getOption('default_toc_min_headings'));

        return $settings = s::applyFilters('script_settings', [
            'context' => $context,

            'anchorsEnable'          => $anchors_enable,
            'anchorsAdjustScrollPos' => $anchors_adjust_scroll_pos,

            'tocEnable'         => $toc_enable,
            'tocMaxHeadingSize' => $toc_max_heading_size,
            'tocMinHeadings'    => $toc_min_headings,
        ]);
    }

    /**
     * Format symbol for CSS `content:` usage.
     *
     * @since 160826 Styles/scripts.
     *
     * @param string $symbol Symbol to format.
     *
     * @return string Formatted symbol; for CSS `content:` usage.
     */
    protected function formatSymbol(string $symbol): string
    {
        if (preg_match('/\.[^\/.]+$/u', $symbol)) {
            return 'url('.c::sQuote($symbol).')';
        }
        return c::sQuote($symbol);
    }
}
