<?php

declare(strict_types=1);

namespace App\Extensions\Elements;

use DNADesign\ElementalUserForms\Model\ElementForm;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Core\Extension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\TextField;

/**
 * Form block (dnadesign/silverstripe-elemental-userforms) with a kicker line, intro text and an optional
 * contact column (phone, e-mail, office hours, address from the settings) to the left of the form.
 *
 * @property string $Eyebrow
 * @property string $Intro
 * @property bool $ShowContactColumn
 * @extends Extension<ElementForm>
 */
class ElementFormExtension extends Extension
{
    private static array $db = [
        'Eyebrow' => 'Varchar(80)',
        'Intro' => 'Text',
        'ShowContactColumn' => 'Boolean',
    ];

    private static array $defaults = [
        'ShowContactColumn' => true,
    ];

    protected function updateFieldLabels(array &$labels): void
    {
        $labels['Eyebrow'] = _t(self::class . '.Eyebrow', 'Dachzeile');
        $labels['Intro'] = _t(self::class . '.Intro', 'Einleitung');
        $labels['ShowContactColumn'] = _t(self::class . '.ShowContactColumn', 'Kontaktdaten links neben dem Formular anzeigen');
    }

    protected function updateCMSFields(FieldList $fields): void
    {
        $fields->removeByName(['Eyebrow', 'Intro', 'ShowContactColumn']);
        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Eyebrow', _t(self::class . '.Eyebrow', 'Dachzeile'))
                ->setDescription(_t(self::class . '.EyebrowHint', 'Kurzes Stichwort über der Überschrift, z. B. „Anfrage“. Optional.')),
            TextareaField::create('Intro', _t(self::class . '.Intro', 'Einleitung'))->setRows(3),
            CheckboxField::create('ShowContactColumn', _t(self::class . '.ShowContactColumn', 'Kontaktdaten links neben dem Formular anzeigen'))
                ->setDescription(_t(self::class . '.ShowContactColumnHint', 'Telefon, E-Mail, Bürozeiten und Adresse aus Einstellungen › Kontakt.')),
        ]);
    }
}
