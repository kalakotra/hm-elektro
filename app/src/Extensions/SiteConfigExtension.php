<?php

declare(strict_types=1);

namespace App\Extensions;

use App\Models\OpeningHoursEntry;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Control\Director;
use SilverStripe\Core\Extension;
use SilverStripe\Forms\EmailField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\TextField;
use SilverStripe\LinkField\Form\LinkField;
use SilverStripe\LinkField\Models\Link;
use SilverStripe\Model\ArrayData;
use SilverStripe\Model\List\ArrayList;
use SilverStripe\ORM\HasManyList;
use SilverStripe\SiteConfig\SiteConfig;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;

/**
 * Contact data (NAP), office hours, service area, footer and social defaults.
 * NAP shown in topbar/footer/form blocks is the same data used for the LocalBusiness (Electrician) schema.
 *
 * @property string $Street
 * @property string $PostalCode
 * @property string $City
 * @property string $Region
 * @property string $CountryCode
 * @property string $Phone
 * @property string $Email
 * @property float $Latitude
 * @property float $Longitude
 * @property string $RouteURL
 * @property string $ServiceAreaShort
 * @property string $ServiceArea
 * @property string $FooterText
 * @property string $OwnerName
 * @property string $BusinessType
 * @method Image DefaultOGImage()
 * @method Image FooterSeal()
 * @method Link FooterSealLink()
 * @method HasManyList<OpeningHoursEntry> OpeningHours()
 *
 * @extends Extension<SiteConfig>
 */
class SiteConfigExtension extends Extension
{
    private static array $db = [
        'Street' => 'Varchar(150)',
        'PostalCode' => 'Varchar(10)',
        'City' => 'Varchar(100)',
        'Region' => 'Varchar(100)',
        'CountryCode' => 'Varchar(2)',
        'Phone' => 'Varchar(40)',
        'Email' => 'Varchar(150)',
        'Latitude' => 'Decimal(10,7)',
        'Longitude' => 'Decimal(10,7)',
        'RouteURL' => 'Varchar(1000)',
        'ServiceAreaShort' => 'Varchar(80)',
        'ServiceArea' => 'Text',
        'FooterText' => 'Text',
        'OwnerName' => 'Varchar(120)',
        'BusinessType' => 'Varchar(60)',
    ];

    private static array $has_one = [
        'DefaultOGImage' => Image::class,
        'FooterSeal' => Image::class,
        'FooterSealLink' => Link::class,
    ];

    private static array $has_many = [
        'OpeningHours' => OpeningHoursEntry::class,
    ];

    private static array $owns = [
        'DefaultOGImage',
        'FooterSeal',
        'FooterSealLink',
    ];

    private static array $defaults = [
        'CountryCode' => 'DE',
        'BusinessType' => 'Electrician',
    ];

    /**
     * International dialling code used to build tel: links from a local number.
     */
    private static string $phone_country_prefix = '+49';

    protected function updateCMSFields(FieldList $fields): void
    {
        $fields->addFieldsToTab('Root.Kontakt', [
            TextField::create('OwnerName', _t(self::class . '.OwnerName', 'Inhaber / Geschäftsführung')),
            TextField::create('Street', _t(self::class . '.Street', 'Straße und Hausnummer'))
                ->setDescription(_t(self::class . '.StreetHint', 'Pflicht für die Google-Firmendaten (LocalBusiness). Ohne Straße wird keine Adresse ausgegeben.')),
            TextField::create('PostalCode', _t(self::class . '.PostalCode', 'PLZ')),
            TextField::create('City', _t(self::class . '.City', 'Ort')),
            TextField::create('Region', _t(self::class . '.Region', 'Bundesland')),
            TextField::create('CountryCode', _t(self::class . '.CountryCode', 'Ländercode (ISO)'))->setAttribute('maxlength', '2'),
            TextField::create('Phone', _t(self::class . '.Phone', 'Telefon'))
                ->setDescription(_t(self::class . '.PhoneHint', 'Schreibweise wie angezeigt, z. B. „+49 172 8719048“.')),
            EmailField::create('Email', _t(self::class . '.Email', 'E-Mail')),
            NumericField::create('Latitude', _t(self::class . '.Latitude', 'Breitengrad'))->setScale(7),
            NumericField::create('Longitude', _t(self::class . '.Longitude', 'Längengrad'))->setScale(7),
            TextField::create('RouteURL', _t(self::class . '.RouteURL', 'Link „Route planen“ (Google Maps)')),
        ]);

        if ($this->getOwner()->isInDB()) {
            $config = GridFieldConfig_RecordEditor::create(20)->addComponent(GridFieldOrderableRows::create('Sort'));
            $fields->addFieldToTab(
                'Root.Kontakt',
                GridField::create('OpeningHours', _t(self::class . '.OpeningHours', 'Bürozeiten'), $this->getOwner()->OpeningHours(), $config)
            );
        }

        $fields->addFieldsToTab('Root.Einsatzgebiet', [
            TextField::create('ServiceAreaShort', _t(self::class . '.ServiceAreaShort', 'Kurztext (Kopfzeile)'))
                ->setDescription(_t(self::class . '.ServiceAreaShortHint', 'z. B. „München & Landkreis München“. Erscheint in der Kopfzeile nach „Einsatzgebiet:“.')),
            TextareaField::create('ServiceArea', _t(self::class . '.ServiceArea', 'Orte für Google (areaServed)'))
                ->setRows(6)
                ->setDescription(_t(self::class . '.ServiceAreaHint', 'Ein Ort pro Zeile. Fließt in die Firmendaten und in jede Leistung.')),
        ]);

        $fields->addFieldsToTab('Root.Footer', [
            TextareaField::create('FooterText', _t(self::class . '.FooterText', 'Kurztext im Footer'))->setRows(2),
            UploadField::create('FooterSeal', _t(self::class . '.FooterSeal', 'Siegel / Innungszeichen'))
                ->setFolderName('Site')
                ->setAllowedFileCategories('image/supported'),
            LinkField::create('FooterSealLink', _t(self::class . '.FooterSealLink', 'Link des Siegels')),
        ]);

        $fields->addFieldsToTab('Root.SEO', [
            UploadField::create('DefaultOGImage', _t(self::class . '.DefaultOGImage', 'Standard-Social-Media-Bild'))
                ->setFolderName('SEO')
                ->setAllowedFileCategories('image/supported')
                ->setDescription(_t(self::class . '.DefaultOGImageHint', '1200 × 630 px')),
            TextField::create('BusinessType', _t(self::class . '.BusinessType', 'Schema.org-Typ des Unternehmens'))
                ->setDescription(_t(self::class . '.BusinessTypeHint', 'Standard „Electrician“. Nur ändern, wenn Sie wissen, was Sie tun.')),
        ]);
    }

    public function getPhoneLink(): string
    {
        $digits = preg_replace('/[^\d+]/', '', (string) $this->getOwner()->Phone) ?? '';
        if ($digits === '') {
            return '';
        }
        if (str_starts_with($digits, '+')) {
            return 'tel:' . $digits;
        }
        if (str_starts_with($digits, '00')) {
            return 'tel:+' . substr($digits, 2);
        }

        return 'tel:' . $this->getOwner()->config()->get('phone_country_prefix') . ltrim($digits, '0');
    }

    public function getPhoneE164(): string
    {
        return substr($this->getPhoneLink(), 4);
    }

    public function getHasAddress(): bool
    {
        return trim((string) $this->getOwner()->Street) !== '' && trim((string) $this->getOwner()->City) !== '';
    }

    public function getFullAddress(): string
    {
        $owner = $this->getOwner();

        return trim(sprintf('%s, %s %s', $owner->Street, $owner->PostalCode, $owner->City), ', ');
    }

    /**
     * Office hours grouped by label: "Mo–Fr" => "07:30–17:00".
     */
    public function getOpeningHoursGrouped(): ArrayList
    {
        $groups = [];
        foreach ($this->getOwner()->OpeningHours()->sort('Sort') as $entry) {
            $label = (string) $entry->Label;
            $groups[$label] ??= [];
            $groups[$label][] = $entry->getTimeRange();
        }

        $list = ArrayList::create();
        foreach ($groups as $label => $ranges) {
            $list->push(ArrayData::create(['Label' => $label, 'Times' => implode(', ', array_unique($ranges))]));
        }

        return $list;
    }

    /**
     * @return array<int, string>
     */
    public function getServiceAreaNames(): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\R/', (string) $this->getOwner()->ServiceArea) ?: [])));
    }

    /**
     * schema.org areaServed: one City per line of the service area, else the business city.
     *
     * @return array<int, array<string, string>>|string|null
     */
    public function getSchemaAreaServed(): array|string|null
    {
        $names = $this->getServiceAreaNames();
        if ($names) {
            return array_map(static fn (string $name): array => ['@type' => 'City', 'name' => $name], $names);
        }

        return $this->getOwner()->City ?: null;
    }

    /**
     * LocalBusiness (Electrician) entity from the same NAP data, linked to the site organisation.
     * Called by dorsetdigital/silverstripe-schema-manager for site-wide entities.
     * Without street + city no LocalBusiness is emitted (Google requires a postal address).
     *
     * @param array<int, mixed> $entities
     */
    protected function updateSchemaManagerEntities(array &$entities): void
    {
        if (!class_exists(\DorsetDigital\SchemaManager\Model\Schema\LocalBusinessSchema::class)) {
            return;
        }

        $owner = $this->getOwner();
        if (!$this->getHasAddress()) {
            return;
        }

        $baseURL = rtrim(Director::absoluteBaseURL(), '/') . '/';
        $business = \DorsetDigital\SchemaManager\Model\Schema\LocalBusinessSchema::create('main', (string) $owner->Title)
            ->setURL($baseURL)
            ->setParentOrganization(\DorsetDigital\SchemaManager\Control\SchemaIDs::organisation())
            ->setTelephone($this->getPhoneE164() ?: null)
            ->setEmail($owner->Email ?: null)
            ->setDescription($owner->FooterText ?: null)
            ->setAddress(array_filter([
                'streetAddress' => (string) $owner->Street,
                'postalCode' => (string) $owner->PostalCode,
                'addressLocality' => (string) $owner->City,
                'addressRegion' => (string) $owner->Region,
                'addressCountry' => (string) ($owner->CountryCode ?: 'DE'),
            ]));

        $business->update(['@type' => (string) ($owner->BusinessType ?: 'Electrician')]);

        $area = $this->getSchemaAreaServed();
        if ($area) {
            $business->update(['areaServed' => $area]);
        }

        if ($owner->Latitude && $owner->Longitude) {
            $business->setGeo((float) $owner->Latitude, (float) $owner->Longitude);
        }

        if ($owner->DefaultOGImage()->exists()) {
            $business->setImage($owner->DefaultOGImage()->getAbsoluteURL());
        }

        $specs = [];
        foreach ($owner->OpeningHours()->sort('Sort') as $entry) {
            $spec = $entry->toSchemaSpecification();
            if ($spec !== null) {
                $specs[] = $spec;
            }
        }
        if ($specs) {
            $business->setOpeningHours($specs);
        }

        if ($owner->RouteURL) {
            $business->update(['hasMap' => (string) $owner->RouteURL]);
        }

        $entities[] = $business;
    }

    public static function localBusinessID(): string
    {
        return \DorsetDigital\SchemaManager\Control\SchemaIDs::forEntity('localbusiness', 'main');
    }
}
