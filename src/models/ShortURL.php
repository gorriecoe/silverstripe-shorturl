<?php

namespace gorriecoe\ShortURL\Models;

use gorriecoe\Link\Models\Link;
use SilverStripe\Forms\Tab;
use SilverStripe\Forms\TabSet;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\LiteralField;
use SilverStripe\GraphQL\Scaffolding\Interfaces\ScaffoldingProvider;
use SilverStripe\GraphQL\Scaffolding\Scaffolders\SchemaScaffolder;
use SilverStripe\Control\Controller;
use SilverStripe\Control\Director;
use SilverStripe\View\Requirements;

/**
 * ShortURL
 *
 * @package silverstripe-shorturl
 */
class ShortURL extends Link implements
    ScaffoldingProvider
{
    /**
     * Defines the database table name
     * @var string
     */
    private static $table_name = 'ShortURL';

    /**
     * Singular name for CMS
     * @var string
     */
    private static $singular_name = 'Short URL';

    /**
     * Plural name for CMS
     * @var string
     */
    private static $plural_name = 'Short URLS';

    /**
     * Database fields
     * @var array
     */
    private static $db = [
        'ShortURL' => 'Varchar'
    ];

    /**
     * Default sort ordering
     * @var array
     */
    private static $default_sort = ['Created' => 'DESC'];

    /**
     * Defines the max length of the urls generated.
     * @var int
     */
    private static $url_length = 5;

    /**
     * Defines internal link types
     * This will then prepend the domain and protocol to the LinkURL.
     *
     * @var array
     */
    private static $internal_types = [];

    /**
     * CMS Fields
     * @return FieldList
     */
    public function getCMSFields()
    {
        Requirements::css('gorriecoe/silverstripe-shorturl: client/admin.css');

        $fields = FieldList::create(
            TabSet::create(
                'Root',
                Tab::create('Main')
            )
            ->setTitle(_t('SiteTree.TABMAIN', 'Main'))
        );

        $fields->addFieldsToTab(
            'Root.Main',
            $this->getCMSMainFields()
        );

        $fields->addFieldToTab(
            'Root.Main',
            LiteralField::create(
                'ShortURLPreview',
                $this->Me()
            ),
            'Title'
        );

        $this->extend('updateCMSFields', $fields);

        $fields->removeByName([
            'OpenInNewWindow',
            'IDCustomValue'
        ]);

        return $fields;
    }

    /**
     * Get the default summary fields for this object.
     *
     * @return array
     */
    public function summaryFields()
    {
        $fields = [
            'Title' => 'Title',
            'AbsoluteLink' => 'Short URL',
            'LinkURL' => 'Destination URL'
        ];

        // Localize fields (if possible)
        foreach ($this->fieldLabels(false) as $name => $label) {
            // only attempt to localize if the label definition is the same as the field name.
            // this will preserve any custom labels set in the summary_fields configuration
            if (isset($fields[$name]) && $name === $fields[$name]) {
                $fields[$name] = $label;
            }
        }

        return $fields;
    }

    /**
     * Event handler called before writing to the database.
     */
    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
        if (!$this->ShortURL) {
            $this->ShortURL = $this->shortenURL($this->LinkURL);
        }
    }

    /**
     * Creates a shortenURLed url path.
     *
     * @return String $shortURL
     */
    public function shortenURL($url) {
        $url_length = $this->config()->get('url_length');
        $shortURL = str_replace(
            ['+','/','='],
            '',
            base64_encode($url) . time()
        );
        $shortURL = substr($shortURL, 0, $url_length);
        if(self::get_by_shortURL($shortURL)) {
            // found another record with this URL try again.
            $shortURL = $this->shortenURL($shortURL);
        }
        return $shortURL;
    }

    /**
     * Return the first ShortURL matching the given shortURL.
     *
     * @return gorriecoe\ShortURL\Models\ShortURL|Null
     */
    public static function get_by_shortURL($url)
    {
        $object = self::get()->find('ShortURL', $url);
        if ($object && $object->LinkURL) {
            return $object;
        }
    }

    /**
     * Get the absolute URL for this page, including protocol and host.
     *
     * @return String
     */
    public function AbsoluteLink()
    {
        return Controller::join_links([
            Director::absoluteBaseURL(),
            'z',
            $this->ShortURL
        ]);
    }

    /**
     * Prepend the domain and protoocol to the LinkURL if its internal.
     * @return string
     */
    public function getLinkURL()
    {
        $linkURL = parent::getLinkURL();

        $internalTypes = array_merge(
            $this->config()->get('internal_types'),
            ['File','SiteTree']
        );

        if (in_array($this->Type, $internalTypes)) {
            $linkURL = Controller::join_links([
                Director::absoluteBaseURL(),
                $linkURL
            ]);
        }

        return $linkURL;
    }

    public function provideGraphQLScaffolding(SchemaScaffolder $scaffolder)
    {
        $scaffolder->type(ShortURL::class)
            ->addAllFields()
            ->addFields(['LinkURL'])
            ->operation(SchemaScaffolder::READ)
                ->setName('readShortURLs')
                ->setUsePagination(false)
                ->end()
            ->operation(SchemaScaffolder::READ_ONE)
                ->setName('readOneShortURL')
                ->end()
            ->operation(SchemaScaffolder::CREATE)
                ->setName('createShortURL')
                ->end()
            ->operation(SchemaScaffolder::UPDATE)
                ->setName('updateShortURL')
                ->end()
            ->operation(SchemaScaffolder::DELETE)
                ->setName('deleteShortURL')
                ->end()
            ->end();
        return $scaffolder;
    }
}
