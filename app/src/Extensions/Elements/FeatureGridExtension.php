<?php

declare(strict_types=1);

namespace App\Extensions\Elements;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use YouWillLikeIT\Elements\Elements\ElementFeatureGrid;

/**
 * Feature grid presentation:
 * - numbered: cards with icon + number "01" (services), whole card links when the entry has a link;
 * - icon-list: icon left, title + text right (scope of a service);
 * - columns-rule: columns with a top rule, text only (benefits);
 * - steps: numbered circles joined by a line (process).
 * Columns apply from the large breakpoint. HeaderAlign "split" puts the buttons next to the heading.
 *
 * @property string $Variant
 * @property string $Columns
 * @property string $HeaderAlign
 * @extends Extension<ElementFeatureGrid>
 */
class FeatureGridExtension extends Extension
{
    private static array $db = [
        'Variant' => "Enum('numbered,icon-list,columns-rule,steps','numbered')",
        'Columns' => "Enum('2,3,4','3')",
        'HeaderAlign' => "Enum('start,split','start')",
    ];

    private static array $defaults = [
        'Variant' => 'numbered',
        'Columns' => '3',
        'HeaderAlign' => 'start',
    ];

    protected function updateFieldLabels(array &$labels): void
    {
        $labels['Variant'] = _t(self::class . '.Variant', 'Darstellung');
        $labels['Columns'] = _t(self::class . '.Columns', 'Spalten (Desktop)');
        $labels['HeaderAlign'] = _t(self::class . '.HeaderAlign', 'Kopfbereich');
    }

    protected function updateCMSFields(FieldList $fields): void
    {
        $fields->removeByName(['Variant', 'Columns', 'HeaderAlign']);
        $fields->addFieldsToTab('Root.Settings', [
            DropdownField::create('Variant', _t(self::class . '.Variant', 'Darstellung'), [
                'numbered' => _t(self::class . '.Numbered', 'Nummerierte Karten mit Symbol (Leistungen)'),
                'icon-list' => _t(self::class . '.IconList', 'Symbol-Liste (Leistungsumfang)'),
                'columns-rule' => _t(self::class . '.ColumnsRule', 'Spalten mit Linie (Vorteile)'),
                'steps' => _t(self::class . '.Steps', 'Nummerierte Schritte (Ablauf)'),
            ]),
            DropdownField::create('Columns', _t(self::class . '.Columns', 'Spalten (Desktop)'), ['2' => '2', '3' => '3', '4' => '4']),
            DropdownField::create('HeaderAlign', _t(self::class . '.HeaderAlign', 'Kopfbereich'), [
                'start' => _t(self::class . '.AlignStart', 'Überschrift, Buttons unter der Liste'),
                'split' => _t(self::class . '.AlignSplit', 'Überschrift links, Buttons rechts daneben'),
            ]),
        ]);
    }

    public function getColumnClass(): string
    {
        return match ((string) $this->getOwner()->Columns) {
            '2' => 'row-cols-1 row-cols-md-2',
            '4' => 'row-cols-1 row-cols-sm-2 row-cols-lg-4',
            default => 'row-cols-1 row-cols-md-2 row-cols-lg-3',
        };
    }
}
