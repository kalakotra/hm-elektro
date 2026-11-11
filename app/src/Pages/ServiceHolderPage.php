<?php

declare(strict_types=1);

namespace App\Pages;

use Page;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataList;

/**
 * Overview of services. Child ServicePages are listed automatically as numbered cards
 * between the header hero and the remaining blocks.
 *
 * @property string $ListEyebrow
 * @property string $ListTitle
 */
class ServiceHolderPage extends Page
{
    private static string $table_name = 'App_ServiceHolderPage';

    private static string $cms_icon_class = 'font-icon-p-list';

    private static string $singular_name = 'Leistungsübersicht';

    private static string $plural_name = 'Leistungsübersichten';

    private static string $class_description = 'Listet alle Leistungsseiten als nummerierte Karten auf';

    private static array $allowed_children = [
        ServicePage::class,
    ];

    private static string $default_child = ServicePage::class;

    private static array $db = [
        'ListEyebrow' => 'Varchar(80)',
        'ListTitle' => 'Varchar(160)',
    ];

    public function fieldLabels($includerelations = true): array
    {
        $labels = parent::fieldLabels($includerelations);
        $labels['ListEyebrow'] = _t(self::class . '.ListEyebrow', 'Dachzeile der Leistungsliste');
        $labels['ListTitle'] = _t(self::class . '.ListTitle', 'Überschrift der Leistungsliste');

        return $labels;
    }

    public function getCMSFields(): FieldList
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields): void {
            $fields->addFieldsToTab('Root.Leistungsliste', [
                TextField::create('ListEyebrow', $this->fieldLabel('ListEyebrow')),
                TextField::create('ListTitle', $this->fieldLabel('ListTitle'))
                    ->setDescription(_t(self::class . '.ListTitleHint', 'Leer = Überschrift nur für Screenreader. Die Karten kommen automatisch aus den Unterseiten.')),
            ]);
        });

        return parent::getCMSFields();
    }

    public function getServices(): DataList
    {
        return ServicePage::get()->filter('ParentID', $this->ID)->sort('Sort');
    }
}
