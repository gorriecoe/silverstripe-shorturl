<?php

namespace gorriecoe\ShortURL\Controllers;

use SilverStripe\CMS\Controllers\ContentController;
use gorriecoe\ShortURL\Models\ShortURL;

/**
 * ShortURL
 *
 * @package silverstripe-shorturl
 */
class ShortURLController extends ContentController
{
    public function doInit()
    {
        parent::doInit();
        $shortURL = $this->getRequest()->param('ShortURL');
        $ShortURLObject = ShortURL::get_by_shortURL($shortURL);
        if ($ShortURLObject) {
            // Intentionally not using Controller::redirect as it prepends the domain
            header('Location:  ' . $ShortURLObject->LinkURL);
            die();
        }
        $this->httpError(404);
    }
}
