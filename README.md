# PressCount
WordPress Social Sharing Plugin -- **Gutenberg ready!!!**

For the most stable version, please use the `Master` branch. For testing purposes, please use the latest branch under the `release` branches.

## Features
* Display share counts from Facebook, Pinterest and LinkedIn on all of your posts with a simple shortcode `[share_count]`

* Works with or without the WordPress Loop! (pass the `id` or `url` parameter to the shortcode)

* Display the share count of **any** webpage with the `url` shortcode parameter. (`[share_count url="https://elexicon.com"]`)

* Developer hooks to customize the plugin output!

* Utilizes AJAX to reduce the API load on large pages.

* Advanced caching system for performance. Clear your cache at any time!

## Usage
1. Add the `presscount` plugin folder to your `plugins` directory.
2. Visit your plugin page and activate PressCount.
3. Add the `[share_count]` shortcode to any post via the WordPress WYSIWYG editor **or** you can call it via PHP like: `echo do_shortcode('[share_count]')`
  * If you are running the shortcode in the loop you **do not** need to add the `url` or `id` parameters.

  * If you are **not** running the shortcode in the loop, you must include **at least** one of those parameters (`url`, `id`) to tell the plugin which URL to grab share counts for. Otherwise it will gather the share count for the current page.

  * You can set the `text` parameter to `true` to display "Share(s)" after the numeric share count. For example; if 1 share was returned, the plugin will output "1 Share". If multiple shares are returned, the output will be "300 Shares".

  * You can return the share count for **any** URL using the `url` parameter. Just enter in the URL you wish to retrieve. The share count **will** be cached for 1 hour as standard.

## Hooks
### `presscount_share_base`:
Allows you to set a base share count for your posts. If your URL was changed and you know the old URL has shares, you can add them to the new URL through this hook. **The return from your callback function must be a whole integer!**

  i.e. `add_filter('presscount_share_base', 248)` -- will set the base share count for all posts to 248, then all counts returned from the API will be added on top of that. **Default:** 0

  **Example:**

  ```
  function my_share_base() {
    global $post;

    if( $post->ID === 125 ) {
      return 650;
    }
  }
  add_filter( 'presscount_share_base', 'my_share_base' );
  ```

### `presscount_single_share_text`:
Allows you to change the text displayed when the `text=true` parameter is set in the shortcode. **Note:** This hook is used only when a single share was returned. **Default:** "Share"

  **Example:**

  ```
  function my_single_share_text() {
    return "small share";
  }
  add_filter( 'presscount_single_share_text', 'my_single_share_text' );

  // Will return "1 small share"
  ```

### `presscount_multiple_share_text`:
Allows you to change the text displayed when the `text=true` parameter is set in the shortcode. **Note:** This hook is used when multiple (or 0) shares are returned. **Default:** "Shares"

  **Example:**

  ```
  function my_multiple_share_text() {
    return "awesome shares";
  }
  add_filter( 'presscount_multiple_share_text', 'my_multiple_share_text' );

  // Will return "200 awesome shares"
  ```

## To do
* Write official documentation
* Add ability to truncate long share numbers (i.e. 100k, 2.2m, 2.8k, etc...)
* Implement dashboard inputs for changing share count text.
* Add ability to change cache time via the dashboard.
* Implement analytic dashboard for viewing share counts from the PressCount admin pages. (PRO)
