<?php

namespace gorriecoe\ShortURL\Admin;

use gorriecoe\ShortURL\Models\ShortURL;
use SilverStripe\Admin\ModelAdmin;

/**
 * CMS Admin area to maintain shorturls
 *
 * @package silverstripe-shorturl
 */
class ShortURLAdmin extends ModelAdmin
{
    /**
     * Managed data objects for CMS
     * @var array
     */
    private static $managed_models = [
        ShortURL::class
    ];

    /**
     * URL Path for CMS
     * @var string
     */
    private static $url_segment = 'shorturls';

    /**
     * Menu title for Left and Main CMS
     * @var string
     */
    private static $menu_title = 'ShortURLS';

    /**
     * Menu icon for Left and Main CMS
     * @var string
     */
    private static $menu_icon_class = 'font-icon-link';

    /**
     * @var int
     */
    private static $menu_priority = -300;
}
