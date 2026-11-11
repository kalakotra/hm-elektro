<?php

declare(strict_types=1);

namespace App\Extensions\Elements;

use App\Pages\ServiceHolderPage;
use App\Pages\ServicePage;
use SilverStripe\Core\Extension;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\TextField;
use SilverStripe\Model\ArrayData;
use SilverStripe\Model\List\ArrayList;
use SilverStripe\ORM\DataList;
use YouWillLikeIT\Elements\Elements\ElementHero;

/**
 * Hero options for this site:
 * - Variant: home (large, white, trust items, phone badge, service quick links) or compact (subpages, tint);
 * - TitleAccent: end of the heading in brand colour (part of the same heading);
 * - TrustItems: short facts under the buttons, one per line (home only);
 * - ShowPhoneBadge: phone number card over the image (number from the settings);
 * - ShowServiceLinks: row of links to all service pages under the hero;
 * - ShowBreadcrumbs: breadcrumbs above the heading on subpages.
 *
 * @property string $Variant
 * @property string $TitleAccent
 * @property string $TrustItems
 * @property bool $ShowBreadcrumbs
 * @property bool $ShowPhoneBadge
 * @property string $PhoneBadgeLabel
 * @property bool $ShowServiceLinks
 * @property string $ServiceLinksLabel
 * @extends Extension<ElementHero>
 */
class HeroExtension extends Extension
{
    private static array $db = [
        'Variant' => "Enum('home,compact','compact')",
        'TitleAccent' => 'Varchar(120)',
        'TrustItems' => 'Text',
        'ShowBreadcrumbs' => 'Boolean',
        'ShowPhoneBadge' => 'Boolean',
        'PhoneBadgeLabel' => 'Varchar(60)',
        'ShowServiceLinks' => 'Boolean',
        'ServiceLinksLabel' => 'Varchar(60)',
    ];

    private static array $defaults = [
        'Variant' => 'compact',
        'ShowBreadcrumbs' => true,
        'ShowPhoneBadge' => false,
        'ShowServiceLinks' => false,
    ];

    protected function updateFieldLabels(array &$labels): void
    {
        $labels['Variant'] = _t(self::class . '.Variant', 'Variante');
        $labels['TitleAccent'] = _t(self::class . '.TitleAccent', 'Überschrift – Ende in Markenfarbe');
        $labels['TrustItems'] = _t(self::class . '.TrustItems', 'Vertrauensmerkmale');
        $labels['ShowBreadcrumbs'] = _t(self::class . '.ShowBreadcrumbs', 'Brotkrumen-Navigation anzeigen');
        $labels['ShowPhoneBadge'] = _t(self::class . '.ShowPhoneBadge', 'Telefon-Karte über dem Bild anzeigen');
        $labels['PhoneBadgeLabel'] = _t(self::class . '.PhoneBadgeLabel', 'Text über der Telefonnummer');
        $labels['ShowServiceLinks'] = _t(self::class . '.ShowServiceLinks', 'Schnellauswahl der Leistungen anzeigen');
        $labels['ServiceLinksLabel'] = _t(self::class . '.ServiceLinksLabel', 'Text vor der Schnellauswahl');
    }

    protected function updateCMSFields(FieldList $fields): void
    {
        $owner = $this->getOwner();
        $fields->removeByName(['Variant', 'TitleAccent', 'TrustItems', 'ShowBreadcrumbs', 'ShowPhoneBadge', 'PhoneBadgeLabel', 'ShowServiceLinks', 'ServiceLinksLabel']);

        $fields->insertAfter(
            'Title',
            TextField::create('TitleAccent', $owner->fieldLabel('TitleAccent'))
                ->setDescription(_t(self::class . '.TitleAccentHint', 'Optional. Wird direkt an die Überschrift angehängt, z. B. „sicher übergeben.“')),
        );

        $fields->addFieldsToTab('Root.Main', [
            TextareaField::create('TrustItems', $owner->fieldLabel('TrustItems'))
                ->setRows(4)
                ->setDescription(_t(self::class . '.TrustItemsHint', 'Ein Merkmal pro Zeile, kurz und belegbar. Nur in der Variante „Startseite“.')),
        ]);

        $fields->addFieldsToTab('Root.Settings', [
            DropdownField::create('Variant', $owner->fieldLabel('Variant'), [
                'home' => _t(self::class . '.VariantHome', 'Startseite (groß, mit Merkmalen)'),
                'compact' => _t(self::class . '.VariantCompact', 'Unterseite (kompakt, hinterlegt)'),
            ]),
            CheckboxField::create('ShowBreadcrumbs', $owner->fieldLabel('ShowBreadcrumbs')),
            CheckboxField::create('ShowPhoneBadge', $owner->fieldLabel('ShowPhoneBadge'))
                ->setDescription(_t(self::class . '.ShowPhoneBadgeHint', 'Nummer aus Einstellungen › Kontakt. Nur mit Bild.')),
            TextField::create('PhoneBadgeLabel', $owner->fieldLabel('PhoneBadgeLabel')),
            CheckboxField::create('ShowServiceLinks', $owner->fieldLabel('ShowServiceLinks'))
                ->setDescription(_t(self::class . '.ShowServiceLinksHint', 'Verlinkt alle Leistungsseiten (Kurzname). Pflege unter Seiten › Leistungen.')),
            TextField::create('ServiceLinksLabel', $owner->fieldLabel('ServiceLinksLabel')),
        ]);
    }

    public function getTrustItemList(): ArrayList
    {
        $lines = array_values(array_filter(array_map('trim', preg_split('/\R/', (string) $this->getOwner()->TrustItems) ?: [])));

        return ArrayList::create(array_map(static fn (string $line): ArrayData => ArrayData::create(['Text' => $line]), $lines));
    }

    public function getServiceLinkList(): DataList
    {
        $holder = ServiceHolderPage::get()->first();
        $list = ServicePage::get()->filter('ShowInMenus', true)->sort('Sort');

        return $holder ? $list->filter('ParentID', $holder->ID) : $list;
    }
}
