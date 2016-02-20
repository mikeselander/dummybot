# Test Content Suite

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/oldtownmedia/test-content-suite/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/oldtownmedia/test-content-suite/?branch=master)

A test content creator plugin for WordPress posts.

An offshoot of our Evans library, this library is used just for quickly and easily spinning up test content in WordPress. Currently the only metadata supported is CMB2-created types. This will expand as we grow the library.

#### This is an incomplete plugin currently used for internal purposes. Breaking changes might be made. 

We are still working on adding more functionality and would love input or PRs for a particular feature.

## Currently support metabox libraries
* [CMB2](https://github.com/WebDevStudios/CMB2)
* [Custom-metaboxes and fields](https://github.com/WebDevStudios/Custom-Metaboxes-and-Fields-for-WordPress)

## Planned to support in future
* ACF
* taxonomies/terms
* individual metadata

### General Notes
* Uses namespace `testContent`
* Currently only supports whole posts/cpts on an automatic level


### Test Data

You can spin up test data in a variety of formats using the `testContent\TestContent` class. There are a variety of formats and all methods are static only because you only need each one one at a time. Methods inclue: 

```php
title()
paragraphs()
plain_text()
image()
date()
phone()
email()
```