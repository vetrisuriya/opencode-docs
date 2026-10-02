# Team Leaders component

Click-to-expand team section: hover swaps the photo for a sketch, click opens a profile panel (bio and social links) under that row. Responsive, keyboard accessible, no jQuery.

## 1. Install

Copy the folder into the theme's components directory:

```
your-theme/
└── components/
    └── team-leaders/
        ├── team-leaders.php
        ├── team-leaders.css
        ├── team-leaders.js
        └── README.md
```

No `functions.php` changes are needed. The template registers and enqueues its own CSS and JS (child-theme safe, cache-busted with `filemtime`), and only on pages where the component is used.

If the client's components folder has a different name or path, add this to `functions.php`:

```php
add_filter( 'tl_leaders_dir', fn() => 'inc/components/team-leaders' );
```

## 2. Use it

### Option A: pass the data from PHP

```php
get_template_part( 'components/team-leaders/team-leaders', null, [
    'title'   => 'Meet our leaders',
    'leaders' => [
        [
            'name'   => 'Tom Lindell',
            'role'   => 'Managing Director',
            'bio'    => 'Short bio text.',
            'before' => 123, // attachment ID or image URL (photo)
            'after'  => 124, // attachment ID or image URL (sketch)
            'links'  => [
                [ 'type' => 'linkedin', 'url' => 'https://linkedin.com/in/username' ],
                [ 'type' => 'x',        'url' => 'https://x.com/username' ],
                [ 'type' => 'mail',     'url' => 'tom@example.com' ],
            ],
        ],
        // ...more leaders
    ],
] );
```

Link types: `linkedin`, `x`, `instagram`, `facebook`, `youtube`, `github`, `mail`, `web`. Add `'label' => 'Portfolio'` to override the button text.

### Option B: ACF repeater

Leave `leaders` out and the component reads an ACF repeater from the current post:

```php
get_template_part( 'components/team-leaders/team-leaders' );
```

Create a repeater named **`team_leaders`** with these sub fields:

| Field name     | Type                                              |
|----------------|---------------------------------------------------|
| `name`         | Text                                              |
| `role`         | Text                                              |
| `bio`          | Textarea                                          |
| `photo_before` | Image (ID, URL or array all work)                 |
| `photo_after`  | Image (sketch version)                            |
| `links`        | Repeater: `type` (Select), `url` (URL), `label` (Text, optional) |

For the `type` select, use the values listed above (`linkedin`, `x`, `mail`, ...).

To read from an options page or another post:

```php
get_template_part( 'components/team-leaders/team-leaders', null, [
    'acf_source' => 'option', // or a post ID
    'acf_field'  => 'team_leaders',
] );
```

## 3. Images

- Use the same aspect ratio for both images, about 800 x 840 px, with the person near the top (the crop is `object-position: center top`).
- Use a white background so the sketch blends into the page.
- If `photo_after` is empty, the photo is reused with a grayscale effect so the hover still works.

## 4. Customize

Colors and fonts are CSS variables on `.tl`. Override them from the theme stylesheet:

```css
.tl {
  --tl-accent: #0a7cff;
  --tl-ink: #222;
  --tl-panel: #eef3f8;
  --tl-font-head: "Poppins", sans-serif;
  --tl-font-body: "Inter", sans-serif;
}
```

If the theme already loads its own fonts, stop the Google Fonts request:

```php
add_filter( 'tl_leaders_load_google_fonts', '__return_false' );
```

## 5. Notes

- Every class is prefixed with `tl-` so it won't clash with the client's theme styles.
- Several sections can be used on the same page; each one works independently.
- Late-enqueued CSS prints in the footer, which works fine. To print it in `<head>` instead, enqueue it yourself on the pages that use it, with the same handle (the template then skips its Google Fonts request, so load the fonts from the theme):

```php
add_action( 'wp_enqueue_scripts', function () {
    if ( is_page( 'about' ) ) {
        wp_enqueue_style( 'tl-leaders', get_theme_file_uri( 'components/team-leaders/team-leaders.css' ), [], null );
    }
} );
```

- The bio allows only `a`, `strong`, `em` and `br`. Edit `$bio_ok` in the template to allow more.
- Make sure the theme `<head>` has `<meta name="viewport" content="width=device-width, initial-scale=1">`, otherwise phones show the desktop layout.
