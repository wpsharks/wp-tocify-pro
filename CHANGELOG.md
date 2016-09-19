## v160919.17597

- Remove old back compat. handlers for `-` prefixed classes in style option values.
- Use percentages for TOC margin instead of `em` units; making it easier to style surrounding elements.

## v160828.48363

- New configurable option: 'Auto-Adjust Scroll Position?'.
- New TOC widget and new TOC display option (via sidebar widget).
- New option that allows a minimum number of headings to be required for a TOC to be shown.
- New option that allows for control over the max heading size to be displayed in any given TOC.
- Slightly reduce the built-in default TOC heading font size so that it consumes less horizontal space.
- Removing `overflow: hidden` from headings whenever TOC is being displayed. Rely upon margin alone.
- Remove CSS `outline` from headings that are anchored to avoid a box when it's focused by an anchor.
- Normalize line-height in TOC to avoid cutting the bottom off of items in the list by mistake.
- Bumping minimum required WP Sharks Core dependency to v160828.25227.
- New Facade for developers: `a::isApplicable()`.
- New filter for developers: `wp_tocify_is_applicable`.

## v160731.37612

- Enhancing options page.
- A little refactoring to simplify menu pages.
- Tested against WordPress v4.6 for compatibility.

## v160727.5691

- Removing underline for titles when floating right.
- Updating to the latest WP Sharks Core.
- Enhancing margins when TOC is floated left or right of content.

## v160724.65065

- Enhancing options page.
- Improving CSS specificity with respect to the `[toc /]` shortcode, making this plugin more compatible with a variety of themes.
- Bug fix. The configuration option `default_enable`, should apply also to existing posts as expected.
- Bug fix. Incorrect `line-height:` in `[toc /]` shortcode output styles.
- Adding 'Restore Default Options' tab.

## v160724.1960

- Initial release.
