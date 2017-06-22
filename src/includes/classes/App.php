<?php
/**
 * Application.
 *
 * @author @jaswsinc
 * @copyright WP Sharks™
 */
declare(strict_types=1);
namespace WebSharks\WpSharks\WpTocify\Pro\Classes;

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
 * Application.
 *
 * @since 160724.1960 Initial release.
 */
class App extends SCoreClasses\App
{
    /**
     * Version.
     *
     * @since 160724.1960
     *
     * @type string Version.
     */
    const VERSION = '170622.49279'; //v//

    /**
     * Constructor.
     *
     * @since 160724.1960 Initial release.
     *
     * @param array $instance Instance args.
     */
    public function __construct(array $instance = [])
    {
        $instance_base = [
            '©di' => [
                '©default_rule' => [
                    'new_instances' => [
                        Classes\Widgets\Toc::class,
                    ],
                ],
            ],

            '§specs' => [
                '§in_wp'           => false,
                '§is_network_wide' => false,

                '§type' => 'plugin',
                '§file' => dirname(__FILE__, 4).'/plugin.php',
            ],
            '©brand' => [
                '©acronym' => 'WPTOC',
                '©name'    => 'WP Tocify',

                '©slug' => 'wp-tocify',
                '©var'  => 'wp_tocify',

                '©short_slug' => 'wp-toc',
                '©short_var'  => 'wp_toc',

                '©text_domain' => 'wp-tocify',
            ],

            '§pro_option_keys' => [],
            '§default_options' => [
                'lazy_load' => true,

                'default_anchors_enable'            => true,
                'default_anchors_adjust_scroll_pos' => true,

                'default_toc_enable'           => '0',
                'default_toc_max_heading_size' => 3,
                'default_toc_min_headings'     => 3,

                'context' => '.entry-content, .hentry, #content',

                'anchor_symbol' => '#',
                'toc_symbol'    => '#',

                'custom_styles' => '.wp-tocify-heading {}'."\n".
                                   '.wp-tocify-anchor {}'."\n".
                                   '.wp-tocify-toc:not(.-style-none) {}',

                'include_post_types' => [
                    'post',
                    'page',
                    'kb_article',
                ],
                'exclude_post_types' => [],
            ],
        ];
        parent::__construct($instance_base, $instance);
    }

    /**
     * Early hook setup handler.
     *
     * @since 160828.48363 Install utilities.
     */
    protected function onSetupEarlyHooks()
    {
        parent::onSetupEarlyHooks();

        s::addAction('vs_upgrades', [$this->Utils->Installer, 'onVsUpgrades']);
    }

    /**
     * Other hook setup handler.
     *
     * @since 160724.1960 Initial release.
     */
    protected function onSetupOtherHooks()
    {
        parent::onSetupOtherHooks();

        if ($this->Wp->is_admin) {
            add_action('admin_menu', [$this->Utils->MenuPage, 'onAdminMenu']);
            add_action('admin_init', [$this->Utils->PostMetaBox, 'onAdminInit']);
        }
        add_filter('body_class', [$this->Utils->StylesScripts, 'onBodyClass']);
        add_action('wp_enqueue_scripts', [$this->Utils->StylesScripts, 'onWpEnqueueScripts']);

        add_action('widgets_init', function () {
            register_widget(Classes\Widgets\Toc::class);
        });
        add_shortcode('toc', [$this->Utils->Shortcode, 'onShortcode']);
    }
}
