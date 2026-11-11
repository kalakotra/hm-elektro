<?php

declare(strict_types=1);

namespace App\Pages;

use App\Extensions\Elements\FeatureItemExtension;
use DNADesign\Elemental\Models\ElementalArea;
use Page;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\ListboxField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\TextField;
use SilverStripe\Model\List\ArrayList;
use SilverStripe\ORM\ManyManyList;

/**
 * Single service. Layout: blocks (hero, text, scope, process, FAQ), automatic "related services" cards,
 * bottom area (CTA band). Provides a schema.org Service (PageSchemaExtension).
 *
 * @property string $Teaser
 * @property string $ShortTitle
 * @property string $Icon
 * @property string $ServiceType
 * @property string $RelatedTitle
 * @method ElementalArea BottomArea()
 * @method ManyManyList<ServicePage> RelatedServices()
 */
class ServicePage extends Page
{
    private static string $table_name = 'App_ServicePage';

    private static string $cms_icon_class = 'font-icon-p-document';

    private static string $singular_name = 'Leistung';

    private static string $plural_name = 'Leistungen';

    private static string $class_description = 'Detailseite einer Leistung (Schema.org Service)';

    private static bool $can_be_root = false;

    private static $allowed_children = 'none';

    /**
     * Number of related service cards when none are chosen manually.
     */
    private static int $related_limit = 3;

    private static array $db = [
        'Teaser' => 'Varchar(200)',
        'ShortTitle' => 'Varchar(40)',
        'Icon' => 'Varchar(30)',
        'ServiceType' => 'Varchar(120)',
        'RelatedTitle' => 'Varchar(120)',
    ];

    private static array $has_one = [
        'BottomArea' => ElementalArea::class,
    ];

    private static array $many_many = [
        'RelatedServices' => ServicePage::class,
    ];

    private static array $many_many_extraFields = [
        'RelatedServices' => [
            'SortOrder' => 'Int',
        ],
    ];

    private static array $owns = [
        'BottomArea',
    ];

    private static array $cascade_duplicates = [
        'BottomArea',
    ];

    private static array $defaults = [
        'RelatedTitle' => 'Passt oft zusammen',
    ];

    public function fieldLabels($includerelations = true): array
    {
        $labels = parent::fieldLabels($includerelations);
        $labels['Teaser'] = _t(self::class . '.Teaser', 'Kurzbeschreibung');
        $labels['ShortTitle'] = _t(self::class . '.ShortTitle', 'Kurzname');
        $labels['Icon'] = _t(self::class . '.Icon', 'Symbol');
        $labels['ServiceType'] = _t(self::class . '.ServiceType', 'Leistungsart (Schema.org serviceType)');
        $labels['RelatedTitle'] = _t(self::class . '.RelatedTitle', 'Überschrift „Weitere Leistungen“');
        $labels['RelatedServices'] = _t(self::class . '.RelatedServices', 'Weitere Leistungen');

        return $labels;
    }

    public function getCMSFields(): FieldList
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields): void {
            $fields->removeByName(['RelatedServices']);
            $fields->addFieldsToTab('Root.Karte', [
                TextareaField::create('Teaser', $this->fieldLabel('Teaser'))
                    ->setRows(2)
                    ->setDescription(_t(self::class . '.TeaserHint', 'Erscheint auf den Leistungskarten (max. 200 Zeichen).')),
                TextField::create('ShortTitle', $this->fieldLabel('ShortTitle'))
                    ->setDescription(_t(self::class . '.ShortTitleHint', 'Für die Schnellauswahl im Seitenkopf, z. B. „Wallbox“. Leer = Navigationstitel.')),
                DropdownField::create('Icon', $this->fieldLabel('Icon'), FeatureItemExtension::iconOptions())
                    ->setEmptyString(_t(self::class . '.NoIcon', '– kein Symbol –')),
                TextField::create('ServiceType', $this->fieldLabel('ServiceType'))
                    ->setDescription(_t(self::class . '.ServiceTypeHint', 'z. B. „Wallbox-Installation“. Leer = Seitentitel.')),
            ]);
            $fields->addFieldsToTab('Root.WeitereLeistungen', [
                TextField::create('RelatedTitle', $this->fieldLabel('RelatedTitle')),
                ListboxField::create(
                    'RelatedServices',
                    $this->fieldLabel('RelatedServices'),
                    ServicePage::get()->exclude('ID', (int) $this->ID)->sort('Sort')->map('ID', 'MenuTitle')
                )->setDescription(_t(self::class . '.RelatedHint', 'Leer = die ersten {count} anderen Leistungen.', ['count' => (int) static::config()->get('related_limit')])),
            ]);
        });

        return parent::getCMSFields();
    }

    /**
     * Position among the sibling services as "01", "02", …
     */
    public function getNumberLabel(): string
    {
        $ids = array_map('intval', ServicePage::get()->filter('ParentID', $this->ParentID)->sort('Sort')->column('ID'));
        $index = array_search((int) $this->ID, $ids, true);

        return sprintf('%02d', $index === false ? 0 : $index + 1);
    }

    public function getQuickLinkTitle(): string
    {
        return (string) ($this->ShortTitle ?: $this->MenuTitle);
    }

    /**
     * Manually chosen related services, otherwise the first siblings (published, in menus).
     */
    public function getRelatedList(): ArrayList
    {
        $chosen = $this->RelatedServices()->filter('ShowInMenus', true)->sort(['SortOrder' => 'ASC', 'Sort' => 'ASC']);
        if ($chosen->exists()) {
            return ArrayList::create($chosen->toArray());
        }

        return ArrayList::create(
            ServicePage::get()
                ->filter(['ParentID' => $this->ParentID, 'ShowInMenus' => true])
                ->exclude('ID', (int) $this->ID)
                ->sort('Sort')
                ->limit((int) static::config()->get('related_limit'))
                ->toArray()
        );
    }
}
