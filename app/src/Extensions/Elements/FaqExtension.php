<?php

declare(strict_types=1);

namespace App\Extensions\Elements;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Model\ArrayData;
use SilverStripe\Model\List\ArrayList;
use YouWillLikeIT\Elements\Elements\ElementFaq;

/**
 * FAQ layout: stacked (centered) or intro column on the left, optionally with tags (e.g. towns of the service area).
 *
 * @property string $Layout
 * @property string $Chips
 * @extends Extension<ElementFaq>
 */
class FaqExtension extends Extension
{
    private static array $db = [
        'Layout' => "Enum('stacked,intro-left','stacked')",
        'Chips' => 'Text',
    ];

    private static array $defaults = [
        'Layout' => 'stacked',
    ];

    protected function updateFieldLabels(array &$labels): void
    {
        $labels['Layout'] = _t(self::class . '.Layout', 'Aufteilung');
        $labels['Chips'] = _t(self::class . '.Chips', 'Schlagworte in der Einleitung');
    }

    protected function updateCMSFields(FieldList $fields): void
    {
        $fields->removeByName(['Layout', 'Chips']);
        $fields->addFieldToTab(
            'Root.Main',
            TextareaField::create('Chips', _t(self::class . '.Chips', 'Schlagworte in der Einleitung'))
                ->setRows(4)
                ->setDescription(_t(self::class . '.ChipsHint', 'Eins pro Zeile, z. B. Orte im Einsatzgebiet. Nur bei „Einleitung links“.'))
        );
        $fields->addFieldToTab('Root.Settings', DropdownField::create('Layout', _t(self::class . '.Layout', 'Aufteilung'), [
            'stacked' => _t(self::class . '.Stacked', 'Untereinander (zentriert)'),
            'intro-left' => _t(self::class . '.IntroLeft', 'Einleitung links, Fragen rechts'),
        ]));
    }

    public function getChipList(): ArrayList
    {
        $lines = array_values(array_filter(array_map('trim', preg_split('/\R/', (string) $this->getOwner()->Chips) ?: [])));

        return ArrayList::create(array_map(static fn (string $t): ArrayData => ArrayData::create(['Text' => $t]), $lines));
    }
}
