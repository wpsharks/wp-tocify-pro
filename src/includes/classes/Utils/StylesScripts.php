<?php
/**
 * Styles/scripts.
 *
 * @author @jaswsinc
 * @copyright WP Sharks™
 */
declare(strict_types=1);
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
        $brand_slug = $this->App->Config->©brand['©slug'];

        $anchor_symbol = $this->formatSymbol(s::getOption('anchor_symbol'));
        $toc_symbol    = $this->formatSymbol(s::getOption('toc_symbol'));

        $custom_styles         = s::getOption('custom_styles');
        $default_custom_styles = s::getDefaultOption('custom_styles');
        $inline_styles         = ''; // Initialize.

        if ($anchor_symbol !== '#' || $toc_symbol !== '#' || ($custom_styles && $custom_styles !== $default_custom_styles)) {
            $inline_styles = '.'.$brand_slug.'-anchor::after { content: '.$anchor_symbol.' !important; }'."\n".
                '.'.$brand_slug.'-toc ul > li::before { content: '.$toc_symbol.' !important; }'."\n".
                $custom_styles; // Any other custom styles.
        }
        s::enqueueLibs(__METHOD__, [
            'styles' => [
                $this->App->Config->©brand['©slug'] => [
                    'ver'    => $this->App::VERSION,
                    'url'    => c::appUrl('/client-s/css/site/toc.min.css'),
                    'inline' => $inline_styles,
                ],
            ],
            'scripts' => [
                $this->App->Config->©brand['©slug'] => [
                    'deps'     => ['jquery'],
                    'ver'      => $this->App::VERSION,
                    'url'      => c::appUrl('/client-s/js/site/toc.min.js'),
                    'localize' => [
                        'key'  => 'mzytpzuu784a54qu8dcwzuhvz623vhdsData',
                        'data' => [
                            'brand' => [
                                'slug' => $this->App->Config->©brand['©slug'],
                                'var'  => $this->App->Config->©brand['©var'],
                            ],
                            'settings' => $settings, // Filterable.
                            // See filter below in `applicableSettings()`.

                            'i18n' => [
                                'tocHeading' => __('Table of Contents', 'wp-tocify'),
                            ],
                        ],
                    ],
                ],
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
        }
        if (!is_singular()) { // Singulars only.
            return $settings = []; // Not applicable.
        }
        $is_applicable = null; // Initialize.

        $lazy_load        = s::getOption('lazy_load');
        $lazy_load_marker = '<!--'.$this->App->Config->©brand['©slug'].'-->';

        $context = s::getOption('context'); // Must have a context (always).

        $anchors_enable            = (bool) s::getPostMeta(null, '_anchors_enable', s::getOption('default_anchors_enable'));
        $anchors_adjust_scroll_pos = (bool) s::getOption('default_anchors_adjust_scroll_pos');

        $toc_enable           = (string) s::getPostMeta(null, '_toc_enable', s::getOption('default_toc_enable'));
        $toc_max_heading_size = (int) s::getPostMeta(null, '_toc_max_heading_size', s::getOption('default_toc_max_heading_size'));
        $toc_min_headings     = max(1, (int) s::getOption('default_toc_min_headings'));

        $current_post_type  = get_post_type();
        $include_post_types = s::getOption('include_post_types');
        $exclude_post_types = s::getOption('exclude_post_types');

        if (!$context) { // Must have a context.
            return $settings = []; // Not applicable.
        }
        if (!isset($is_applicable)) {
            if (!$anchors_enable) {
                $is_applicable = false; // Not applicable.
            } elseif ($include_post_types && !in_array($current_post_type, $include_post_types, true)) {
                $is_applicable = false; // Not applicable.
            } elseif ($exclude_post_types && in_array($current_post_type, $exclude_post_types, true)) {
                $is_applicable = false; // Not applicable.
            }
        }
        if (!isset($is_applicable) && $lazy_load) {
            if (!($WP_Post = get_post())) {
                $is_applicable = false;
            } elseif (mb_stripos($WP_Post->post_content, $lazy_load_marker) !== false) {
                $is_applicable = true; // Explicitly enabled by comment marker.
            } elseif (!preg_match('/\<h[1-6]/ui', $WP_Post->post_content)) {
                $is_applicable = false; // Nothing to tocify in this case.
            }
        }
        // Give filters a chance to override detections above.
        $is_applicable = s::applyFilters('is_applicable', $is_applicable);

        if ($is_applicable === false) {
            return $settings = []; // Not applicable.
        } else {
            $anchors_enable = true; // Always true, when applicable.
        }
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
