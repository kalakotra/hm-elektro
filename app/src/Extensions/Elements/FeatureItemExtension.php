<?php

declare(strict_types=1);

namespace App\Extensions\Elements;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use YouWillLikeIT\Elements\Models\FeatureItem;

/**
 * Optional icon per entry (from the theme's icon sprite, Includes/IconSprite.ss) and the entry number ("01").
 *
 * @property string $Icon
 * @extends Extension<FeatureItem>
 */
class FeatureItemExtension extends Extension
{
    /**
     * Icon keys available in the theme sprite → CMS labels.
     */
    public const ICONS = [
        'bolt' => 'Blitz / Elektroinstallation',
        'meter' => 'Zählerschrank / Verteiler',
        'charger' => 'Wallbox / Ladestation',
        'solar' => 'Photovoltaik',
        'smarthome' => 'Smart Home',
        'clipboard' => 'Prüfung / Protokoll',
        'search' => 'Prüfen / Vor-Ort-Check',
        'document' => 'Dokument / Antrag',
        'shield' => 'Sicherheit / Absicherung',
        'chart' => 'Lastmanagement / Leistung',
        'building' => 'Gebäude / Tiefgarage',
        'sun' => 'Sonne / Solarstrom',
        'light' => 'Licht / Beleuchtung',
        'user' => 'Ansprechpartner',
        'clock' => 'Termin / Zeit',
        'check' => 'Häkchen',
        'tool' => 'Werkzeug / Reparatur',
        'home' => 'Zuhause',
    ];

    private static array $db = [
        'Icon' => 'Varchar(30)',
    ];

    /**
     * @return array<string, string> translated icon labels for dropdowns
     */
    public static function iconOptions(): array
    {
        $options = [];
        foreach (self::ICONS as $key => $label) {
            $options[$key] = _t(self::class . '.Icon_' . $key, $label);
        }

        return $options;
    }

    protected function updateFieldLabels(array &$labels): void
    {
        $labels['Icon'] = _t(self::class . '.Icon', 'Symbol');
    }

    protected function updateCMSFields(FieldList $fields): void
    {
        $fields->removeByName('Icon');
        $fields->insertAfter(
            'Text',
            DropdownField::create('Icon', _t(self::class . '.Icon', 'Symbol'), self::iconOptions())
                ->setEmptyString(_t(self::class . '.NoIcon', '– kein Symbol –'))
                ->setDescription(_t(self::class . '.IconHint', 'Wird in den Darstellungen „Nummerierte Karten“ und „Symbol-Liste“ genutzt.'))
        );
    }

    /**
     * Position within the list as "01", "02", …
     */
    public function getNumberLabel(): string
    {
        $owner = $this->getOwner();
        $ids = array_map('intval', FeatureItem::get()->filter('ParentID', (int) $owner->ParentID)->sort('Sort')->column('ID'));
        $index = array_search((int) $owner->ID, $ids, true);

        return sprintf('%02d', $index === false ? 0 : $index + 1);
    }
}
