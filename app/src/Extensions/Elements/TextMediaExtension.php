<?php

declare(strict_types=1);

namespace App\Extensions\Elements;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use YouWillLikeIT\Elements\Elements\ElementTextMedia;

/**
 * Text with image: image left or right of the text.
 * Check lists come from the editor (style „Häkchen-Liste“ = ul.list-check).
 *
 * @property string $ImagePosition
 * @extends Extension<ElementTextMedia>
 */
class TextMediaExtension extends Extension
{
    private static array $db = [
        'ImagePosition' => "Enum('left,right','right')",
    ];

    private static array $defaults = [
        'ImagePosition' => 'right',
    ];

    protected function updateFieldLabels(array &$labels): void
    {
        $labels['ImagePosition'] = _t(self::class . '.ImagePosition', 'Bildposition');
    }

    protected function updateCMSFields(FieldList $fields): void
    {
        $fields->removeByName('ImagePosition');
        $fields->addFieldToTab('Root.Settings', DropdownField::create('ImagePosition', _t(self::class . '.ImagePosition', 'Bildposition'), [
            'left' => _t(self::class . '.Left', 'Links'),
            'right' => _t(self::class . '.Right', 'Rechts'),
        ]));
    }
}
