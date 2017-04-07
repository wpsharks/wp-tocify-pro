## $v

- **Bug Fix:** Invalid TOC heading levels under some scenarios. Fixed in this release.
- **Bug Fix:** Admin bar offset detection being attempted when the admin bar was not present.
- **Bug Fix:** Regression. Enhancing the custom inline style system by avoiding inline styles altogether whenever the inline styles wouldn't actually change anything.
- **Enhancement:** Automatically stripe `table, input, button, label, details` from heading before adding the heading text to the TOC. This avoids extraneous text in the TOC next to each heading.

## v170329.47339

- Bumping minimum required version of the WP Sharks Core.
- Updating scripts/styles to bring them inline with core standards.
- Enhancing security by removing `basename(__FILE__)` from direct access notices.
- Updating `Restore Default Options`. Now set as a meta link instead of as a tab.
- Enhancing `via-widget` display option by hiding the widget by default, and only displaying the widget when applicable. As opposed to the old behavior; i.e., showing the widget by default, and then hiding it when not applicable. In short, hiding by default avoids a flash of the TOC when not even applicable on a given page.
- Removing unnecessary lite build variation from `.build.props`.
- Enhancing scroll adjustment configuration option. Now _prepending_ anchor to heading instead of _appending_, which does a better job of positioning the user, particularly on multiline headings. The base calculation is also automatically increased when the admin bar is present on any given page. In addition, the TOC scroll adjustments, if enabled, will now adjust `#toc-` hashes only, and nothing else. This avoids a few edge cases where themes include their own hash adjusters. Of course, you can also disable this feature completely if your theme already deals with hash offsets/adjustments.
- Enhancing the custom inline style system by avoiding inline styles altogether whenever the inline styles wouldn't actually change anything.
- Bug fix. Properly adding widget class to list of single-instance classes.
- Bug fix. Improved handling of the initial hash location jump in some browsers.

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
