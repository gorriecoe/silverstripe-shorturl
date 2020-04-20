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

## Configuration

### URL length

By default `url_length` is set to 5

```yml
gorriecoe\ShortURL\Models\ShortURL:
  url_length: 7
```

### Internal link types

Depending on the link type you may or may not want to prepend the domain and protocol to the destination url.  `SiteTree` and `File` will automatically do this.  If you need to define additional internal link types you follow the example below:

```yml
gorriecoe\ShortURL\Models\ShortURL:
  internal_types:
    - Product
    - AnotherType
```
