# Test Content Generator

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mikeselander/dummybot/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mikeselander/dummybot/?branch=master)

A test content creator plugin for WordPress posts, pages, CPTs, and terms from a convenient admin page.

An offshoot of our Evans library, this library is used for quickly and easily spinning up test content in WordPress. All data is random in length, return, format, etc. so that you can quickly and accurately test your site for edge cases.

The panel to spin up content can be found under `Tools->Test Content`.


## Currently supported metabox libraries
* [ACF](https://www.advancedcustomfields.com/)
* [CMB2](https://github.com/WebDevStudios/CMB2)
* [Custom-metaboxes and fields](https://github.com/WebDevStudios/Custom-Metaboxes-and-Fields-for-WordPress)
* [Custom Meta Boxes (Human Made)](https://github.com/humanmade/Custom-Meta-Boxes)


### General Notes
* Uses namespace `DummyPress`
* Plugin will only create and delete 'test' data - meaning that it will never delete your real information.


### Admin Panel
![Admin Panel Example](https://mikeselander.com/wp-content/uploads/2016/05/screenshot1.png)


### Test Data

You can spin up test data in a variety of formats using the `DummyPress\TestContent` class. There are a variety of formats and all methods are static because you only need one at a time. This class can be easily used stand-alone.

**Available methods:**

```php
title()			// Random-length Lorem Ipsum title.
paragraphs()	// TinyMCE-compatible paragraphs with random content suchas tables, images, quotes, etc.
plain_text()	// Paragraphs of plain text.
image()			// Fetch a random image, make sure it is formatted right, download it, and put it in the media library.
date()			// Date in the future (up to 60 days out) in the format prescribed.
time()			// Time in various formats
timezone()		// Timezone from a subset of available options.
phone()			// Phone # in multiple international formats.
email()			// Email address in random lengths/formats.
link()			// URL in a completely random format.
oembed()		// oembed-compatible link.
video()			// (safe) Video link from YouTube or Vimeo
```

### Filter All the Things!

There are quite a few filters so that you can easily modify any of the content being created. Below is a list of the filters available, detailed documentation is coming.

```php
tc_{$type}_data
tc_{$type}_metabox - Modifies created value according to type
tc_{$id}_metabox
tc_{$slug}_post_content
tc_{$slug}_post_excerpt
tc_{$slug}_post_title
tc_{$slug}_post_arguments
tc_{$slug}_term_title
tc_{$slug}_term_arguments
```


#### This is in active development - breaking changes might be made.

You will most likely not be affected by any since this plugin has no update mechanism. We will not remove any features, but might change HOW they are implemented without any notice. Soon, we will add filters & actions for you to be able to extend particular pieces but will not do this until we are satisfied that a section can be extended without breaking changes.

We are still working on adding more functionality and would love input, PRs, or requests for a particular feature.
