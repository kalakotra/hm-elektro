<?php

declare(strict_types=1);

namespace App\Extensions\Elements;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use YouWillLikeIT\Elements\Elements\ElementCallToAction;

/**
 * CTA layout: split (text left, buttons right) or centered. Colour comes from the section background.
 *
 * @property string $Variant
 * @extends Extension<ElementCallToAction>
 */
class CallToActionExtension extends Extension
{
    private static array $db = [
        'Variant' => "Enum('split,center','split')",
    ];

    private static array $defaults = [
        'Variant' => 'split',
    ];

    protected function updateFieldLabels(array &$labels): void
    {
        $labels['Variant'] = _t(self::class . '.Variant', 'Aufteilung');
    }

    protected function updateCMSFields(FieldList $fields): void
    {
        $fields->removeByName('Variant');
        $fields->addFieldToTab('Root.Settings', DropdownField::create('Variant', _t(self::class . '.Variant', 'Aufteilung'), [
            'split' => _t(self::class . '.Split', 'Text links, Buttons rechts'),
            'center' => _t(self::class . '.Center', 'Zentriert'),
        ])->setDescription(_t(self::class . '.VariantHint', 'Farbe über „Hintergrund“ (z. B. Markenfarbe).')));
    }
}
