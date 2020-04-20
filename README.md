# Silverstripe shorturl

Provides a shorter path to URLs using your domain.

## Installation
Composer is the recommended way of installing SilverStripe modules.
```
composer require gorriecoe/silverstripe-shorturl
```

## Requirements

- silverstripe/framework ^4.0
- [gorriecoe/silverstripe link](https://github.com/gorriecoe/silverstripe-link) ^1.3

## Maintainers

- [Gorrie Coe](https://github.com/gorriecoe)

## Config

```yml
gorriecoe\ShortURL\Models\ShortURL:
  url_length: 5
  internal_types:
    - File
    - SiteTree
```
