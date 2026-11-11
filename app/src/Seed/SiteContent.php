<?php

declare(strict_types=1);

namespace App\Seed;

use App\Pages\ContactPage;
use App\Pages\HomePage;
use App\Pages\ServiceHolderPage;
use App\Pages\ServicePage;
use DNADesign\ElementalUserForms\Model\ElementForm;
use Page;
use SilverStripe\CMS\Model\RedirectorPage;
use SilverStripe\UserForms\Model\EditableFormField\EditableCheckbox;
use SilverStripe\UserForms\Model\EditableFormField\EditableDropdown;
use SilverStripe\UserForms\Model\EditableFormField\EditableEmailField;
use SilverStripe\UserForms\Model\EditableFormField\EditableTextField;
use YouWillLikeIT\Elements\Elements\ElementCallToAction;
use YouWillLikeIT\Elements\Elements\ElementFaq;
use YouWillLikeIT\Elements\Elements\ElementFeatureGrid;
use YouWillLikeIT\Elements\Elements\ElementHero;
use YouWillLikeIT\Elements\Elements\ElementTextMedia;

/**
 * Seed content for hm-elektro.com (de_DE).
 * Source: no old site (hm-elektro.com = "coming soon": e-mail + mobile number only). Copy is new, written for the
 * keyword map in claude/sites/hmelektro/brief.md (Project "Webdesign"). Services, service area, office hours and
 * all trust statements are assumptions until the client confirms them (see README "Offen beim Kunden").
 * Deliberately NOT claimed: Meisterbetrieb, Innung/E-CHECK mark, emergency service, prices, reviews.
 */
final class SiteContent
{
    public const PHONE = '+49 172 8719048';

    public const PHONE_E164 = '+491728719048';

    public const EMAIL = 'info@hm-elektro.com';

    /**
     * @return array<string, mixed>
     */
    public static function siteConfig(): array
    {
        return [
            'fields' => [
                'Title' => 'HM Elektro',
                'Tagline' => 'Elektriker in München und im Landkreis München',
                'OwnerName' => '',
                'Street' => '',
                'PostalCode' => '',
                'City' => 'München',
                'Region' => 'Bayern',
                'CountryCode' => 'DE',
                'Phone' => self::PHONE,
                'Email' => self::EMAIL,
                'ServiceAreaShort' => 'München & Landkreis München',
                'ServiceArea' => implode("\n", self::serviceArea()),
                'FooterText' => 'Elektrofachbetrieb für München und den Landkreis – Installation, Sanierung, E-Mobilität, Photovoltaik und Prüfung.',
                'BusinessType' => 'Electrician',
            ],
            'schema' => [
                'SchemaOrganisationName' => 'HM Elektro',
                'SchemaOrganisationLegalName' => 'HM Elektro GmbH',
                'SchemaOrganisationPhone' => self::PHONE_E164,
                'SchemaOrganisationEmail' => self::EMAIL,
                'SchemaOrganisationLocality' => 'München',
                'SchemaOrganisationRegion' => 'Bayern',
                'SchemaOrganisationCountry' => 'DE',
            ],
            'openingHours' => [
                ['Label' => 'Mo–Fr', 'Days' => 'Monday,Tuesday,Wednesday,Thursday,Friday', 'Opens' => '07:30:00', 'Closes' => '17:00:00', 'Closed' => false],
            ],
        ];
    }

    /**
     * Service area (assumption, confirm with client).
     *
     * @return array<int, string>
     */
    public static function serviceArea(): array
    {
        return ['München', 'Unterschleißheim', 'Garching bei München', 'Ismaning', 'Haar', 'Ottobrunn', 'Unterhaching', 'Grünwald', 'Pullach im Isartal', 'Dachau', 'Freising', 'Erding', 'Fürstenfeldbruck', 'Starnberg'];
    }

    /**
     * Page tree. Order = menu order. Keys are referenced by links and blocks.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function pages(): array
    {
        return [
            'home' => [
                'sort' => 1,
                'class' => HomePage::class,
                'segment' => 'home',
                'fields' => [
                    'Title' => 'Startseite',
                    'MenuTitle' => 'Startseite',
                    'ShowInMenus' => false,
                    'MetaTitle' => 'Elektriker München – HM Elektro | Elektrofachbetrieb',
                    'MetaDescription' => 'Elektriker in München und im Landkreis: Elektroinstallation, Zählerschrank, Wallbox, Photovoltaik, Smart Home und DGUV-V3-Prüfung. Jetzt Angebot anfragen.',
                ],
            ],
            'leistungen' => [
                'sort' => 2,
                'class' => ServiceHolderPage::class,
                'segment' => 'leistungen',
                'fields' => [
                    'Title' => 'Leistungen',
                    'ListEyebrow' => 'Unsere Leistungen',
                    'ListTitle' => 'Was wir für Sie übernehmen',
                    'MetaTitle' => 'Elektroarbeiten München – Leistungen von HM Elektro',
                    'MetaDescription' => 'Elektroarbeiten in München für Privat, Hausverwaltung und Gewerbe: Installation, Sanierung, Wallbox, PV-Anschluss, Smart Home und Prüfung nach DGUV V3.',
                ],
            ],
            'elektroinstallation' => self::service(1, 'elektroinstallation', 'Elektroinstallation', 'Elektroinstallation', 'bolt', 'Elektroinstallation', [
                'Teaser' => 'Neubau, Umbau, Erweiterung: Leitungen, Steckdosen, Beleuchtung und Unterverteilung – fachgerecht nach VDE.',
                'MetaTitle' => 'Elektroinstallation München – Neubau & Umbau | HM Elektro',
                'MetaDescription' => 'Elektroinstallation in München: Neubau, Umbau und Erweiterung mit Unterverteilung, Steckdosen, Licht und Datenleitungen – geprüft nach DIN VDE 0100-600.',
            ]),
            'sanierung' => self::service(2, 'sanierung-zaehlerschrank', 'Sanierung & Zählerschrank', 'Zählerschrank', 'meter', 'Elektrosanierung und Zählerschrank-Erneuerung', [
                'Teaser' => 'Altbau-Elektrik modernisieren, Zählerplatz nach aktueller TAB erneuern, Fehlerstrom-Schutzschalter nachrüsten.',
                'MetaTitle' => 'Zählerschrank erneuern München – Sanierung | HM Elektro',
                'MetaDescription' => 'Zählerschrank erneuern in München: neuer Zählerplatz nach TAB, Elektrosanierung im Altbau, FI-Schutzschalter nachrüsten – abgestimmt mit dem Netzbetreiber.',
            ]),
            'wallbox' => self::service(3, 'wallbox-e-mobilitaet', 'Wallbox & E-Mobilität', 'Wallbox', 'charger', 'Wallbox-Installation', [
                'Teaser' => 'Wallbox in Garage oder Tiefgarage – inklusive Anmeldung beim Netzbetreiber und auf Wunsch Lastmanagement.',
                'MetaTitle' => 'Wallbox Installation München – Elektriker HM Elektro',
                'MetaDescription' => 'Wallbox Installation in München: Check von Hausanschluss und Zählerschrank, Anmeldung beim Netzbetreiber, Tiefgarage und Lastmanagement – mit Prüfprotokoll.',
            ]),
            'photovoltaik' => self::service(4, 'photovoltaik-speicher', 'Photovoltaik & Speicher', 'Photovoltaik', 'solar', 'Elektrischer Anschluss von Photovoltaikanlagen und Batteriespeichern', [
                'Teaser' => 'Elektrischer Anschluss von PV-Anlage und Batteriespeicher, Vorbereitung des Zählerschranks und Anmeldung.',
                'MetaTitle' => 'Photovoltaik Elektriker München – Anschluss & Speicher',
                'MetaDescription' => 'Photovoltaik-Elektriker in München: Anschluss von PV-Anlage und Batteriespeicher, Zählerschrank, Anmeldung beim Netzbetreiber und im Marktstammdatenregister.',
            ]),
            'smarthome' => self::service(5, 'smart-home-knx', 'Smart Home & KNX', 'Smart Home', 'smarthome', 'Smart-Home- und KNX-Installation', [
                'Teaser' => 'Licht, Rollläden und Heizung zentral steuern – als KNX-Installation im Neubau oder per Funk nachgerüstet.',
                'MetaTitle' => 'Smart Home Elektriker München – KNX & Funk | HM Elektro',
                'MetaDescription' => 'Smart-Home-Elektriker in München: KNX-Installation im Neubau, Funk-Nachrüstung im Bestand, Licht-, Rollladen- und Heizungssteuerung – geplant und eingerichtet.',
            ]),
            'pruefung' => self::service(6, 'e-check-dguv-v3', 'Elektro-Check & DGUV V3', 'Elektro-Check', 'clipboard', 'Prüfung elektrischer Anlagen und Geräte nach DGUV Vorschrift 3', [
                'Teaser' => 'Prüfung von Anlagen und Geräten mit Prüfprotokoll – für Vermieter, Hausverwaltungen, Büros und Betriebe.',
                'MetaTitle' => 'DGUV V3 Prüfung München – Elektro-Check | HM Elektro',
                'MetaDescription' => 'DGUV V3 Prüfung in München: ortsfeste Anlagen und ortsveränderliche Geräte mit Prüfplakette und Protokoll. Elektro-Check für Vermieter und Hausverwaltungen.',
            ]),
            'gewerbe' => [
                'sort' => 3,
                'class' => RedirectorPage::class,
                'segment' => 'gewerbe',
                'linkTo' => 'pruefung',
                'fields' => [
                    'Title' => 'Gewerbe',
                    'MenuTitle' => 'Gewerbe',
                    'RedirectionType' => 'Internal',
                    'ShowInSearch' => false,
                    'NoIndex' => true,
                ],
            ],
            'ueber-uns' => [
                'sort' => 4,
                'class' => Page::class,
                'segment' => 'ueber-uns',
                'fields' => [
                    'Title' => 'Über uns',
                    'MetaTitle' => 'Über uns – HM Elektro, Ihr Elektrobetrieb in München',
                    'MetaDescription' => 'HM Elektro GmbH: Elektrobetrieb aus München für Privatkunden, Hausverwaltungen und Gewerbe. Feste Ansprechpartner, schriftliche Angebote, geprüfte Übergabe.',
                ],
            ],
            'kontakt' => [
                'sort' => 5,
                'class' => ContactPage::class,
                'segment' => 'kontakt',
                'fields' => [
                    'Title' => 'Kontakt',
                    'MetaTitle' => 'Elektriker München: Angebot anfragen – HM Elektro GmbH',
                    'MetaDescription' => 'Elektriker in München anfragen: Formular ausfüllen oder anrufen unter +49 172 8719048. Wir melden uns mit einem Terminvorschlag für die Besichtigung.',
                ],
            ],
            'impressum' => [
                'sort' => 6,
                'class' => Page::class,
                'segment' => 'impressum',
                'draft' => true,
                'fields' => [
                    'Title' => 'Impressum',
                    'ShowInMenus' => false,
                    'NoIndex' => true,
                    'MetaDescription' => 'Impressum der HM Elektro GmbH, Elektrofachbetrieb in München: Anbieterkennzeichnung, Kontakt, Registereintrag und berufsrechtliche Angaben.',
                    'Content' => self::impressum(),
                ],
            ],
            'datenschutz' => [
                'sort' => 7,
                'class' => Page::class,
                'segment' => 'datenschutz',
                'draft' => true,
                'fields' => [
                    'Title' => 'Datenschutz',
                    'MenuTitle' => 'Datenschutz',
                    'ShowInMenus' => false,
                    'NoIndex' => true,
                    'MetaDescription' => 'Datenschutzerklärung der HM Elektro GmbH: welche Daten wir bei Besuch der Website und bei Anfragen über das Formular verarbeiten und welche Rechte Sie haben.',
                    'Content' => '<p>Die Datenschutzerklärung wird vor dem Livegang vom Kunden geliefert (Website, Anfrageformular, Hosting, E-Mail).</p>',
                ],
            ],
        ];
    }

    /**
     * Blocks per page and area. Area keys are has_one relation names (ElementalArea, BottomArea).
     *
     * @return array<string, array<string, array<int, array<string, mixed>>>>
     */
    public static function blocks(): array
    {
        $phoneLink = ['phone' => self::PHONE_E164, 'text' => self::PHONE];

        return [
            'home' => [
                'ElementalArea' => [
                    [
                        'type' => ElementHero::class,
                        'fields' => [
                            'Title' => 'Elektriker in München – sauber installiert,',
                            'TitleAccent' => 'sicher übergeben.',
                            'HeadingLevel' => 'h1',
                            'Eyebrow' => 'Elektrofachbetrieb · München',
                            'Lead' => 'HM Elektro ist Ihr Elektrobetrieb für München und den Landkreis: Elektroinstallation, Zählerschrank, Wallbox, Photovoltaik und Smart Home – für Privatkunden, Hausverwaltungen und Gewerbe.',
                            'Variant' => 'home',
                            'ShowBreadcrumbs' => false,
                            'TrustItems' => "Schriftliches Angebot vorab\nNach VDE, mit Prüfprotokoll\nPrivat · Hausverwaltung · Gewerbe\nMünchen & Landkreis",
                            'ShowPhoneBadge' => true,
                            'PhoneBadgeLabel' => 'Mobil erreichbar · Mo–Fr',
                            'ShowServiceLinks' => true,
                            'ServiceLinksLabel' => 'Direkt zur Leistung:',
                            'Spacing' => 'none',
                        ],
                        'images' => ['Image' => ['startseite-hero.jpg', 'Elektriker von HM Elektro verdrahtet eine Unterverteilung in einem Münchner Wohnhaus']],
                        'links' => [
                            ['page' => 'kontakt', 'text' => 'Angebot anfragen'],
                            $phoneLink,
                        ],
                    ],
                    [
                        'type' => ElementFeatureGrid::class,
                        'fields' => [
                            'Title' => 'Elektroarbeiten in München – vom Stromkreis bis zur Wallbox',
                            'Eyebrow' => 'Leistungen',
                            'Variant' => 'numbered',
                            'Columns' => '3',
                            'HeaderAlign' => 'split',
                        ],
                        'links' => [
                            ['page' => 'leistungen', 'text' => 'Alle Leistungen'],
                        ],
                        'items' => self::serviceCards(),
                    ],
                    self::benefits(),
                    [
                        'type' => ElementTextMedia::class,
                        'fields' => [
                            'Eyebrow' => 'Gewerbe & Hausverwaltung',
                            'Title' => 'DGUV V3 Prüfung und Elektro-Check in München',
                            'Content' => '<p>Wir prüfen ortsfeste Anlagen und ortsveränderliche Geräte in Büros, Praxen, Läden und Mietobjekten – termingerecht, mit Prüfprotokoll und Hinweisen zu Mängeln.</p>'
                                . '<ul class="list-check"><li>Prüfung ortsveränderlicher Geräte mit Prüfplaketten und Geräteliste</li><li>Elektro-Check für Vermieter, z. B. beim Mieterwechsel</li><li>Wiederkehrende Termine auf Wunsch, Mängel direkt behoben</li></ul>',
                            'ImagePosition' => 'left',
                            'Background' => 'dark',
                        ],
                        'images' => ['Image' => ['gewerbe-pruefung.jpg', 'Elektriker prüft mit einem Prüfgerät die Steckdosen in einem Büro']],
                        'links' => [
                            ['page' => 'kontakt', 'text' => 'Prüftermin anfragen'],
                            ['page' => 'pruefung', 'text' => 'Mehr zur DGUV V3'],
                        ],
                    ],
                    self::steps('So läuft es ab', 'In vier Schritten zur fertigen Anlage', [
                        ['Anfrage', 'Formular oder Anruf: Beschreiben Sie kurz, was gemacht werden soll – Fotos vom Zählerschrank helfen.'],
                        ['Vor-Ort-Termin', 'Wir sehen uns Anlage, Hausanschluss und Leitungswege an und klären Ihre Wünsche.'],
                        ['Schriftliches Angebot', 'Mit allen Positionen und einem verbindlichen Ausführungstermin.'],
                        ['Ausführung & Protokoll', 'Installation, Messung nach VDE, Übergabe mit Prüfprotokoll und Erklärung vor Ort.'],
                    ]),
                    [
                        'type' => ElementTextMedia::class,
                        'fields' => [
                            'Eyebrow' => 'Über uns',
                            'Title' => 'HM Elektro – Ihr Elektrobetrieb aus München',
                            'Content' => '<p>Die HM Elektro GmbH ist ein Münchner Elektrofachbetrieb für Privatkunden, Hausverwaltungen und Gewerbe. Wir planen, installieren und prüfen – und übergeben jede Anlage so, dass Sie wissen, was verbaut wurde.</p>'
                                . '<p>Kurze Wege, feste Ansprechpartner und Termine, die gehalten werden.</p>',
                            'ImagePosition' => 'right',
                            'Background' => 'light',
                        ],
                        'images' => ['Image' => ['ueber-uns-team.jpg', 'Das Team von HM Elektro vor dem Firmenfahrzeug']],
                        'links' => [
                            ['page' => 'ueber-uns', 'text' => 'Mehr über uns'],
                        ],
                    ],
                    [
                        'type' => ElementFaq::class,
                        'fields' => [
                            'Eyebrow' => 'Einsatzgebiet & FAQ',
                            'Title' => 'Ihr Elektriker für München und den Landkreis',
                            'Intro' => '<p>Wir sind in allen Münchner Stadtteilen im Einsatz und fahren regelmäßig in diese Orte:</p>',
                            'Layout' => 'intro-left',
                            'Chips' => "Unterschleißheim\nGarching\nIsmaning\nHaar\nOttobrunn\nUnterhaching\nGrünwald\nPullach\nDachau\nFreising\nErding\nFürstenfeldbruck\nStarnberg",
                        ],
                        'items' => [
                            ['Arbeiten Sie auch im Landkreis München?', 'Ja. Neben dem Stadtgebiet sind wir im gesamten Landkreis München und in den angrenzenden Landkreisen unterwegs. Fragen Sie einfach an – wir sagen Ihnen sofort, ob Ihr Ort dabei ist.'],
                            ['Was kostet ein Elektriker in München?', 'Das hängt vom Umfang ab. Nach dem Vor-Ort-Termin erhalten Sie ein schriftliches Angebot mit allen Positionen – bevor wir anfangen.'],
                            ['Muss eine Wallbox angemeldet werden?', 'Ja. Ladeeinrichtungen für Elektrofahrzeuge müssen beim Netzbetreiber angemeldet werden, ab mehr als 12 kVA ist zusätzlich seine Zustimmung nötig (§ 19 NAV). Die Anmeldung übernehmen wir für Sie.'],
                            ['Wann muss der Zählerschrank erneuert werden?', 'Spätestens wenn Wallbox, PV-Anlage oder Wärmepumpe dazukommen, muss der Zählerplatz den aktuellen technischen Anschlussbedingungen (TAB) des Netzbetreibers entsprechen. Ältere Zählerschränke erfüllen das oft nicht – wir prüfen das beim Vor-Ort-Termin.'],
                            ['Wie schnell bekomme ich einen Termin?', 'Schicken Sie uns kurz, worum es geht. Wir melden uns mit einem Vorschlag für den Vor-Ort-Termin.'],
                        ],
                    ],
                    self::form('Angebot anfragen', 'Beschreiben Sie Ihr Vorhaben – wir melden uns mit einem Terminvorschlag für die Besichtigung.', 'light'),
                ],
            ],
            'leistungen' => [
                'ElementalArea' => [
                    self::hero('Elektroarbeiten in München für Privat und Gewerbe', 'Leistungen', 'Vom einzelnen Stromkreis bis zur kompletten Neuinstallation: Wir planen, installieren und prüfen – und übergeben jede Anlage mit Prüfprotokoll.', ['leistungen-hero.jpg', 'Elektriker misst mit einem Installationstester an einer neuen Unterverteilung'], [['page' => 'kontakt', 'text' => 'Angebot anfragen'], $phoneLink]),
                    self::benefits(),
                    self::steps('So läuft es ab', 'In vier Schritten zur fertigen Anlage', [
                        ['Anfrage', 'Formular oder Anruf – Fotos vom Zählerschrank und vom Einbauort helfen bei der Einschätzung.'],
                        ['Vor-Ort-Termin', 'Wir sehen uns Anlage, Hausanschluss und Leitungswege an.'],
                        ['Schriftliches Angebot', 'Alle Positionen und ein fester Ausführungstermin.'],
                        ['Ausführung & Protokoll', 'Installation, Messung nach VDE, Übergabe mit Prüfprotokoll.'],
                    ]),
                    self::cta(),
                ],
            ],
            'elektroinstallation' => self::servicePage(
                'Elektroinstallation in München',
                'Leistung 01 · Elektroinstallation',
                'Ob Neubau, Umbau oder einzelne Erweiterung: Wir verlegen Leitungen, setzen Unterverteilungen und Steckdosen und nehmen jede Anlage mit Erstprüfung nach DIN VDE 0100-600 in Betrieb.',
                'elektroinstallation',
                ['Fachgerecht geplant, sauber verlegt', '<p>Eine gute Elektroinstallation fällt im Alltag nicht auf: genug Steckdosen an den richtigen Stellen, getrennte Stromkreise für große Verbraucher und eine Unterverteilung mit Reserve für später. Wir planen das mit Ihnen gemeinsam – im Neubau ebenso wie bei Umbau oder Erweiterung.</p>'
                    . '<ul class="list-check"><li>Unterverteilung mit Leitungsschutz und Fehlerstrom-Schutzschaltern</li><li>Steckdosen, Schalter und Beleuchtung nach Ihrem Raumplan</li><li>Netzwerk- und Datenleitungen für Homeoffice und WLAN</li><li>Erstprüfung nach DIN VDE 0100-600 mit Prüfprotokoll</li></ul>'],
                'Was zur Elektroinstallation gehört',
                [
                    ['Planung', 'Raumplan, Stromkreise, Steckdosen- und Lichtpositionen – abgestimmt mit Ihnen und dem Bauablauf.', 'document'],
                    ['Unterverteilung', 'Verteiler mit Leitungsschutz, Fehlerstrom-Schutz und Reserve für spätere Erweiterungen.', 'meter'],
                    ['Leitungen & Dosen', 'Unter Putz, auf Putz oder im Kanal – sauber verlegt und beschriftet.', 'bolt'],
                    ['Beleuchtung', 'Deckenauslässe, LED-Spots, Außenleuchten und Bewegungsmelder.', 'light'],
                    ['Netzwerk', 'Datenleitungen und Netzwerkdosen für stabiles Internet in jedem Raum.', 'chart'],
                    ['Prüfung', 'Messung und Erstprüfung nach DIN VDE 0100-600, Übergabe mit Protokoll.', 'clipboard'],
                ],
                [
                    ['Darf ich meine Elektroinstallation selbst erweitern?', 'Neue Stromkreise und Erweiterungen der Anlage dürfen nach § 13 NAV nur der Netzbetreiber oder ein in dessen Installateurverzeichnis eingetragenes Unternehmen ausführen. Auch kleinere Arbeiten gehören in die Hand einer Elektrofachkraft – für Ihre Sicherheit und Ihren Versicherungsschutz.'],
                    ['Wie lange dauert eine Elektroinstallation im Neubau?', 'Das hängt von Größe und Bauablauf ab. Wir stimmen Rohinstallation und Feininstallation mit den anderen Gewerken ab und nennen Ihnen die Termine im Angebot.'],
                    ['Können Sie eine bestehende Installation erweitern?', 'Ja. Wir prüfen vorher, ob Unterverteilung und Leitungen die zusätzliche Last tragen, und erweitern dann gezielt.'],
                ],
                ctaTitle: 'Elektroinstallation in München anfragen',
            ),
            'sanierung' => self::servicePage(
                'Zählerschrank erneuern und Elektrik sanieren in München',
                'Leistung 02 · Sanierung',
                'Alte Elektrik ist oft an der Grenze: zu wenige Stromkreise, kein Fehlerstrom-Schutz, ein Zählerschrank, der neue Verbraucher nicht mehr aufnehmen kann. Wir modernisieren Schritt für Schritt – im bewohnten Haus.',
                'sanierung',
                ['Sicher für heute – bereit für Wallbox und PV', '<p>Wer eine Wallbox, eine PV-Anlage oder eine Wärmepumpe plant, braucht einen Zählerplatz nach den aktuellen technischen Anschlussbedingungen (TAB) des Netzbetreibers und der VDE-AR-N 4100. Wir prüfen Ihren Zählerschrank, stimmen die Erneuerung mit dem Netzbetreiber ab – je nach Ort zum Beispiel SWM Infrastruktur oder Bayernwerk Netz – und bauen den neuen Zählerplatz ein.</p>'
                    . '<ul class="list-check"><li>Zählerschrank nach TAB und VDE-AR-N 4100</li><li>Neue Stromkreise und Unterverteilungen</li><li>Fehlerstrom-Schutzschalter (RCD) nachrüsten</li><li>Austausch alter Leitungen, z. B. zweiadriger Altbau-Leitungen</li></ul>'],
                'Was zur Sanierung gehört',
                [
                    ['Bestandsaufnahme', 'Wir messen die Anlage durch und zeigen Ihnen, was dringend ist und was warten kann.', 'search'],
                    ['Zählerplatz', 'Neuer Zählerschrank nach TAB, inklusive Abstimmung mit dem Netzbetreiber.', 'meter'],
                    ['Fehlerstrom-Schutz', 'RCDs schützen Menschen bei Isolationsfehlern – in neuen Stromkreisen vorgeschrieben, in Altanlagen empfohlen.', 'shield'],
                    ['Leitungen', 'Austausch alter oder überlasteter Leitungen, abschnittsweise und staubarm.', 'bolt'],
                    ['Im bewohnten Haus', 'Abschnittsweise Arbeit, damit Küche und Bad möglichst kurz ohne Strom sind.', 'home'],
                    ['Protokoll', 'Prüfung nach DIN VDE 0100-600 und Dokumentation der geänderten Stromkreise.', 'clipboard'],
                ],
                [
                    ['Muss ich meinen alten Zählerschrank austauschen?', 'Eine generelle Austauschpflicht gibt es nicht. Wird die Anlage aber wesentlich geändert – etwa für Wallbox, PV oder Wärmepumpe –, verlangt der Netzbetreiber einen Zählerplatz nach den aktuellen TAB.'],
                    ['Sind FI-Schutzschalter Pflicht?', 'Für neue oder geänderte Stromkreise mit Steckdosen bis 32 A schreibt die DIN VDE 0100-410 Fehlerstrom-Schutzschalter vor. Bestehende Anlagen genießen Bestandsschutz – die Nachrüstung empfehlen wir trotzdem.'],
                    ['Wer stimmt den Zählerwechsel mit dem Netzbetreiber ab?', 'Wir melden die Änderung beim Netzbetreiber an und stimmen den Zählerwechsel mit ihm bzw. dem Messstellenbetreiber ab.'],
                ],
                ctaTitle: 'Zählerschrank in München erneuern lassen',
            ),
            'wallbox' => self::servicePage(
                'Wallbox Installation in München',
                'Leistung 03 · E-Mobilität',
                'Wir installieren Ihre Wallbox in Garage, Carport oder Tiefgarage – mit Prüfung des Hausanschlusses, Anmeldung beim Netzbetreiber und Übergabe mit Prüfprotokoll.',
                'wallbox',
                ['Fertig angemeldet, richtig abgesichert', '<p>Eine Wallbox ist ein Dauerverbraucher mit hoher Leistung. Deshalb prüfen wir vor der Installation, ob Hausanschluss, Zählerschrank und Leitungsweg dafür ausgelegt sind – und sagen Ihnen offen, wenn vorher etwas angepasst werden muss.</p>'
                    . '<ul class="list-check"><li>Eigener Stromkreis mit passendem Fehlerstromschutz</li><li>Leitungsquerschnitt nach Ladeleistung und Leitungslänge</li><li>Anmeldung bzw. Zustimmung beim Netzbetreiber</li><li>Inbetriebnahme, Messung und Einweisung vor Ort</li></ul>'],
                'Was zur Wallbox-Installation dazugehört',
                [
                    ['Vor-Ort-Check', 'Hausanschluss, Zählerschrank und Leitungsweg prüfen, Montageort festlegen.', 'search'],
                    ['Anmeldung beim Netzbetreiber', 'Wir übernehmen die Anmeldung und – über 12 kVA – den Antrag auf Zustimmung.', 'document'],
                    ['Installation & Absicherung', 'Leitung verlegen, Schutzorgane setzen, Wallbox montieren und anschließen.', 'shield'],
                    ['Lastmanagement', 'Mehrere Ladepunkte teilen sich die verfügbare Leistung – ohne Überlast am Hausanschluss.', 'chart'],
                    ['Tiefgarage & Mehrparteienhaus', 'Lösungen für Eigentümergemeinschaften und Hausverwaltungen, auch mit Abrechnung je Stellplatz.', 'building'],
                    ['Laden mit PV-Strom', 'Mit kompatibler Wallbox und Energiemanager laden Sie bevorzugt mit eigenem Solarstrom.', 'sun'],
                ],
                [
                    ['11 kW oder 22 kW – was ist sinnvoll?', 'Für die meisten Fahrzeuge und den Alltag reichen 11 kW. Eine 22-kW-Wallbox braucht die Zustimmung des Netzbetreibers und lohnt sich nur, wenn Ihr Fahrzeug diese Leistung auch laden kann.'],
                    ['Muss die Wallbox angemeldet werden?', 'Ja. Jede Ladeeinrichtung wird beim Netzbetreiber angemeldet, über 12 kVA ist zusätzlich seine Zustimmung nötig (§ 19 NAV). Das erledigen wir für Sie.'],
                    ['Was bedeutet § 14a EnWG für meine Wallbox?', 'Neue Wallboxen mit mehr als 4,2 kW gelten als steuerbare Verbrauchseinrichtung: Der Netzbetreiber darf die Ladeleistung bei drohender Netzüberlastung zeitweise begrenzen, eine Mindestleistung bleibt garantiert. Im Gegenzug zahlen Sie reduzierte Netzentgelte.'],
                    ['Darf ich in der Tiefgarage einer WEG eine Wallbox installieren?', 'Wohnungseigentümer haben einen gesetzlichen Anspruch darauf, dass die Gemeinschaft den Einbau gestattet (§ 20 Abs. 2 WEG), Mieter gegenüber dem Vermieter nach § 554 BGB. Wie umgesetzt wird, beschließt die Eigentümerversammlung – wir liefern das technische Konzept.'],
                ],
                [
                    ['Anfrage mit Fotos', 'Zählerschrank, Montageort und gewünschte Wallbox – Fotos sparen oft einen Termin.'],
                    ['Check & Angebot', 'Vor-Ort-Check und schriftliches Angebot mit allen Positionen.'],
                    ['Anmeldung', 'Wir melden die Ladeeinrichtung beim zuständigen Netzbetreiber an.'],
                    ['Installation & Übergabe', 'Montage, Messung, Prüfprotokoll und Einweisung in die Wallbox.'],
                ],
                'So kommt die Wallbox an die Wand',
                ctaTitle: 'Wallbox in München installieren lassen',
            ),
            'photovoltaik' => self::servicePage(
                'Photovoltaik-Elektriker in München',
                'Leistung 04 · Photovoltaik',
                'Wir übernehmen die Elektroseite Ihrer Photovoltaikanlage: Wechselrichter und Batteriespeicher anschließen, Zählerschrank vorbereiten und die Anlage beim Netzbetreiber anmelden.',
                'photovoltaik',
                ['Vom Wechselrichter bis zur Anmeldung', '<p>Damit Ihr Solarstrom sicher ins Hausnetz und ins öffentliche Netz fließt, braucht es einen passenden Zählerplatz, die richtige Absicherung und eine saubere Anmeldung. Die Dachmontage der Module übernimmt Ihr Solarteur – wir sorgen für den elektrischen Anschluss und die Inbetriebnahme.</p>'
                    . '<ul class="list-check"><li>Anschluss von Wechselrichter und Batteriespeicher</li><li>Zählerschrank nach TAB, Zweirichtungszähler über den Messstellenbetreiber</li><li>Anmeldung beim Netzbetreiber</li><li>Hinweis zur Registrierung im Marktstammdatenregister</li></ul>'],
                'Was zum PV-Anschluss gehört',
                [
                    ['Wechselrichter', 'Anschluss und Absicherung auf der Wechselstromseite, Inbetriebnahme mit Ihrem Solarteur.', 'solar'],
                    ['Batteriespeicher', 'Einbindung des Speichers für mehr Eigenverbrauch am Abend.', 'bolt'],
                    ['Zählerschrank', 'Zählerplatz nach TAB – häufig die Voraussetzung für die Anmeldung.', 'meter'],
                    ['Anmeldung', 'Anmeldung beim Netzbetreiber und Unterlagen für das Marktstammdatenregister.', 'document'],
                    ['Wallbox & Wärmepumpe', 'Energiemanagement, damit Auto und Heizung den Solarstrom nutzen.', 'charger'],
                    ['Protokoll', 'Messung und Prüfprotokoll für Netzbetreiber und Versicherung.', 'clipboard'],
                ],
                [
                    ['Muss ich meine PV-Anlage registrieren?', 'Ja. Jede Anlage muss innerhalb eines Monats nach Inbetriebnahme im Marktstammdatenregister der Bundesnetzagentur eingetragen werden – zusätzlich zur Anmeldung beim Netzbetreiber.'],
                    ['Was gilt für ein Balkonkraftwerk?', 'Steckersolargeräte bis 800 W Wechselrichterleistung müssen seit dem Solarpaket I nur noch im Marktstammdatenregister angemeldet werden. Wir prüfen auf Wunsch, ob Ihr Stromkreis dafür geeignet ist.'],
                    ['Lohnt sich ein Batteriespeicher?', 'Ein Speicher erhöht den Eigenverbrauch, kostet aber zusätzlich. Ob er sich rechnet, hängt von Ihrem Verbrauch am Abend ab – wir besprechen das beim Vor-Ort-Termin.'],
                ],
                ctaTitle: 'PV-Anlage in München anschließen lassen',
            ),
            'smarthome' => self::servicePage(
                'Smart-Home-Elektriker in München',
                'Leistung 05 · Smart Home',
                'Licht, Rollläden, Heizung und Steckdosen zentral steuern: als kabelgebundene KNX-Installation im Neubau oder per Funk nachgerüstet im Bestand.',
                'smarthome',
                ['KNX im Neubau, Funk im Bestand', '<p>KNX ist ein offener Standard für die Gebäudesteuerung: Eine Busleitung verbindet Taster, Aktoren und Sensoren, und Funktionen lassen sich jederzeit neu programmieren. In bestehenden Wohnungen rüsten wir mit Funk-Systemen nach – ohne neue Leitungen in jedem Raum.</p>'
                    . '<ul class="list-check"><li>Planung der Funktionen mit Ihnen: Licht, Beschattung, Heizung, Szenen</li><li>KNX-Installation und Programmierung</li><li>Funk-Nachrüstung im Bestand</li><li>Einweisung und Dokumentation</li></ul>'],
                'Was wir für Ihr Smart Home übernehmen',
                [
                    ['Lichtsteuerung', 'Szenen, Dimmen und Zeitschaltungen – per Taster, App oder Bewegungsmelder.', 'light'],
                    ['Rollläden & Beschattung', 'Zentral, nach Zeit oder nach Sonnenstand gesteuert.', 'sun'],
                    ['Heizung', 'Einzelraumregelung für mehr Komfort und weniger Verbrauch.', 'home'],
                    ['KNX', 'Kabelgebundene Installation im Neubau, programmiert nach Ihren Wünschen.', 'smarthome'],
                    ['Funk-Nachrüstung', 'Smart-Home-Funktionen ohne neue Leitungen in jedem Raum.', 'tool'],
                    ['Energie', 'Verbrauch sichtbar machen und mit PV und Wallbox verbinden.', 'chart'],
                ],
                [
                    ['KNX oder Funk – was passt zu mir?', 'Im Neubau oder bei einer Komplettsanierung empfehlen wir KNX, weil es stabil und herstellerübergreifend ist. In bewohnten Räumen ist Funk meist die wirtschaftlichere Lösung.'],
                    ['Kann ich mein Smart Home später erweitern?', 'Ja. Bei KNX planen wir Reserven im Verteiler ein, Funk-Systeme lassen sich Raum für Raum ergänzen.'],
                    ['Funktioniert das auch ohne Internet?', 'Die Grundfunktionen von KNX laufen lokal ohne Internet. Für die Steuerung von unterwegs wird eine Verbindung benötigt.'],
                ],
                ctaTitle: 'Smart Home in München planen lassen',
            ),
            'pruefung' => self::servicePage(
                'DGUV V3 Prüfung und Elektro-Check in München',
                'Leistung 06 · Gewerbe & Vermieter',
                'Wir prüfen ortsfeste Anlagen und ortsveränderliche Geräte in Büros, Praxen, Läden und Mietobjekten – mit Prüfplaketten, Geräteliste und Prüfprotokoll.',
                'pruefung',
                ['Prüfpflicht erfüllt, Mängel behoben', '<p>Nach der DGUV Vorschrift 3 müssen Arbeitgeber elektrische Anlagen und Betriebsmittel regelmäßig prüfen lassen. Die Fristen legt der Unternehmer in der Gefährdungsbeurteilung fest; als Richtwerte nennt die DGUV zum Beispiel vier Jahre für ortsfeste Anlagen und – je nach Beanspruchung – sechs bis 24 Monate für ortsveränderliche Geräte in Büros.</p>'
                    . '<ul class="list-check"><li>Prüfung ortsveränderlicher Geräte nach DIN VDE 0701-0702</li><li>Prüfung ortsfester Anlagen nach DIN VDE 0105-100</li><li>Prüfplaketten, Geräteliste und Prüfprotokoll</li><li>Elektro-Check für Vermieter, z. B. beim Mieterwechsel</li></ul>'],
                'Was zur Prüfung gehört',
                [
                    ['Bestandsliste', 'Wir erfassen alle Geräte und Anlagenteile – oder übernehmen Ihre vorhandene Liste.', 'document'],
                    ['Geräteprüfung', 'Sichtprüfung, Messung und Funktionsprüfung, Prüfplakette mit nächstem Termin.', 'clipboard'],
                    ['Anlagenprüfung', 'Verteiler, Schutzmaßnahmen und Fehlerstrom-Schutzschalter werden gemessen.', 'meter'],
                    ['Mängel beheben', 'Kleine Mängel beheben wir direkt, größere bieten wir Ihnen an.', 'tool'],
                    ['Prüfprotokoll', 'Dokumentation für Berufsgenossenschaft und Versicherung.', 'shield'],
                    ['Wiederholung', 'Auf Wunsch erinnern wir Sie an den nächsten Prüftermin.', 'clock'],
                ],
                [
                    ['Wer muss nach DGUV V3 prüfen lassen?', 'Jeder Unternehmer, der Beschäftigte hat – auch Praxen, Kanzleien und kleine Büros. Die Prüfung muss eine Elektrofachkraft durchführen.'],
                    ['Wie oft muss geprüft werden?', 'Die Fristen ergeben sich aus der Gefährdungsbeurteilung. Richtwerte der DGUV: vier Jahre für ortsfeste Anlagen, für ortsveränderliche Geräte sechs Monate, in Büros mit geringer Fehlerquote bis zu 24 Monate.'],
                    ['Brauchen Vermieter einen Elektro-Check?', 'Eine gesetzliche Prüfpflicht für private Vermieter gibt es nicht. Eine Prüfung beim Mieterwechsel dokumentiert aber den sicheren Zustand der Anlage – das hilft bei der Verkehrssicherungspflicht und gegenüber der Versicherung.'],
                ],
                ctaTitle: 'DGUV-V3-Prüftermin in München anfragen',
            ),
            'ueber-uns' => [
                'ElementalArea' => [
                    self::hero('HM Elektro – Ihr Elektrobetrieb aus München', 'Über uns', 'Wir sind ein Elektrofachbetrieb für Privatkunden, Hausverwaltungen und Gewerbe in München und im Landkreis.', ['ueber-uns-hero.jpg', 'Das Team von HM Elektro in der Werkstatt'], [['page' => 'kontakt', 'text' => 'Angebot anfragen'], $phoneLink]),
                    [
                        'type' => ElementTextMedia::class,
                        'fields' => [
                            'Eyebrow' => 'Wer wir sind',
                            'Title' => 'Ein Ansprechpartner von der Besichtigung bis zur Abnahme',
                            'Content' => '<p>Die HM Elektro GmbH plant, installiert und prüft elektrische Anlagen in München und Umgebung. Bei uns sprechen Sie vom ersten Anruf bis zur Übergabe mit denselben Menschen – ohne Weiterreichen und ohne Überraschungen auf der Rechnung.</p>'
                                . '<p>Jede Anlage messen wir nach VDE und übergeben sie mit Prüfprotokoll. Das ist wichtig für den Netzbetreiber, für Ihre Versicherung und für alle, die später an der Anlage arbeiten.</p>',
                            'ImagePosition' => 'left',
                        ],
                        'images' => ['Image' => ['ueber-uns-arbeit.jpg', 'Elektriker von HM Elektro beschriftet die Stromkreise in einem Verteiler']],
                    ],
                    self::benefits(),
                    self::cta(),
                ],
            ],
            'kontakt' => [
                'ElementalArea' => [
                    self::hero('Elektriker in München anfragen', 'Kontakt', 'Beschreiben Sie kurz Ihr Vorhaben oder rufen Sie an – wir melden uns mit einem Terminvorschlag für die Besichtigung.', null, []),
                    self::form('Ihre Anfrage', 'Pflichtfelder sind mit * markiert. Fotos vom Zählerschrank oder Einbauort können Sie uns nach unserer Rückmeldung per E-Mail schicken.', 'none'),
                    [
                        'type' => ElementFaq::class,
                        'fields' => [
                            'Title' => 'Was nach Ihrer Anfrage passiert',
                            'Layout' => 'stacked',
                            'Background' => 'light',
                        ],
                        'items' => [
                            ['Wann melden Sie sich?', 'Wir melden uns innerhalb unserer Bürozeiten mit einem Vorschlag für den Vor-Ort-Termin.'],
                            ['Kostet die Besichtigung etwas?', 'Das klären wir vorab am Telefon – abhängig von Umfang und Anfahrt.'],
                            ['Welche Unterlagen helfen?', 'Fotos vom Zählerschrank, vom Einbauort und – falls vorhanden – Grundrisse oder Pläne.'],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Userforms definitions. Fields with 'field--half' sit side by side from md up.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function forms(): array
    {
        return [
            'contact' => [
                'subject' => 'Neue Anfrage über hm-elektro.com',
                'from' => 'noreply@hm-elektro.com',
                'submit' => 'Anfrage senden',
                'complete' => '<p><strong>Vielen Dank für Ihre Anfrage.</strong> Wir melden uns mit einem Terminvorschlag. Eilt es? Rufen Sie uns an: ' . self::PHONE . '.</p>',
                'fields' => [
                    ['key' => 'topic', 'class' => EditableDropdown::class, 'data' => ['Name' => 'Thema', 'Title' => 'Worum geht es?', 'Required' => true, 'UseEmptyString' => true, 'EmptyString' => 'Bitte wählen', 'CustomErrorMessage' => 'Bitte wählen Sie ein Thema.'], 'options' => ['Elektroinstallation', 'Sanierung / Zählerschrank', 'Wallbox / E-Mobilität', 'Photovoltaik / Speicher', 'Smart Home / KNX', 'Elektro-Check / DGUV V3', 'Sonstiges']],
                    ['key' => 'name', 'class' => EditableTextField::class, 'data' => ['Name' => 'Name', 'Title' => 'Name', 'Required' => true, 'Autocomplete' => 'name', 'ExtraClass' => 'field--half', 'CustomErrorMessage' => 'Bitte geben Sie Ihren Namen ein.']],
                    ['key' => 'phone', 'class' => EditableTextField::class, 'data' => ['Name' => 'Telefon', 'Title' => 'Telefon', 'Required' => true, 'Autocomplete' => 'tel', 'ExtraClass' => 'field--half', 'CustomErrorMessage' => 'Bitte geben Sie eine Telefonnummer für den Rückruf ein.']],
                    ['key' => 'email', 'class' => EditableEmailField::class, 'data' => ['Name' => 'Email', 'Title' => 'E-Mail', 'Required' => true, 'ExtraClass' => 'field--half', 'CustomErrorMessage' => 'Bitte geben Sie eine gültige E-Mail-Adresse ein.']],
                    ['key' => 'place', 'class' => EditableTextField::class, 'data' => ['Name' => 'Ort', 'Title' => 'PLZ / Ort', 'Required' => false, 'Autocomplete' => 'postal-code', 'ExtraClass' => 'field--half']],
                    ['key' => 'message', 'class' => EditableTextField::class, 'data' => ['Name' => 'Nachricht', 'Title' => 'Ihre Nachricht', 'Required' => false, 'Rows' => 5, 'Placeholder' => 'Was soll gemacht werden? Haus, Wohnung oder Gewerbe?']],
                    ['key' => 'privacy', 'class' => EditableCheckbox::class, 'data' => ['Name' => 'Datenschutz', 'Title' => 'Ich bin einverstanden, dass meine Angaben zur Bearbeitung der Anfrage gespeichert werden. Hinweise in der Datenschutzerklärung.', 'Required' => true, 'CustomErrorMessage' => 'Bitte bestätigen Sie die Datenschutzhinweise.']],
                ],
            ],
        ];
    }

    // ---------------------------------------------------------------------
    // Builders
    // ---------------------------------------------------------------------

    /**
     * @param array<string, mixed> $fields
     * @return array<string, mixed>
     */
    private static function service(int $sort, string $segment, string $title, string $short, string $icon, string $serviceType, array $fields): array
    {
        return [
            'sort' => $sort,
            'parent' => 'leistungen',
            'class' => ServicePage::class,
            'segment' => $segment,
            'fields' => [
                'Title' => $title,
                'ShortTitle' => $short,
                'Icon' => $icon,
                'ServiceType' => $serviceType,
                'RelatedTitle' => 'Passt oft zusammen',
            ] + $fields,
        ];
    }

    /**
     * Related services per page key (manual choice; empty = first siblings).
     *
     * @return array<string, array<int, string>>
     */
    public static function related(): array
    {
        return [
            'elektroinstallation' => ['sanierung', 'smarthome', 'pruefung'],
            'sanierung' => ['wallbox', 'photovoltaik', 'elektroinstallation'],
            'wallbox' => ['sanierung', 'photovoltaik', 'smarthome'],
            'photovoltaik' => ['wallbox', 'sanierung', 'smarthome'],
            'smarthome' => ['elektroinstallation', 'photovoltaik', 'wallbox'],
            'pruefung' => ['sanierung', 'elektroinstallation', 'wallbox'],
        ];
    }

    /**
     * Home service cards (manual FeatureGrid entries linking to the service pages).
     *
     * @return array<int, array<string, mixed>>
     */
    private static function serviceCards(): array
    {
        $cards = [];
        foreach (['elektroinstallation', 'sanierung', 'wallbox', 'photovoltaik', 'smarthome', 'pruefung'] as $key) {
            $page = self::pages()[$key]['fields'];
            $cards[] = [
                'title' => $page['Title'],
                'text' => $page['Teaser'],
                'icon' => $page['Icon'],
                'link' => ['page' => $key, 'text' => 'Mehr erfahren'],
            ];
        }

        return $cards;
    }

    /**
     * @param array{0: string, 1: string}|null $image
     * @param array<int, array<string, mixed>> $links
     * @return array<string, mixed>
     */
    private static function hero(string $title, string $eyebrow, string $lead, ?array $image, array $links): array
    {
        $block = [
            'type' => ElementHero::class,
            'fields' => [
                'Title' => $title,
                'HeadingLevel' => 'h1',
                'Eyebrow' => $eyebrow,
                'Lead' => $lead,
                'Variant' => 'compact',
                'ShowBreadcrumbs' => true,
                'Spacing' => 'none',
            ],
            'links' => $links,
        ];
        if ($image) {
            $block['images'] = ['Image' => $image];
        }

        return $block;
    }

    /**
     * @return array<string, mixed>
     */
    private static function benefits(): array
    {
        return [
            'type' => ElementFeatureGrid::class,
            'fields' => [
                'Eyebrow' => 'Warum HM Elektro',
                'Title' => 'Klare Absprachen, saubere Arbeit, geprüfte Übergabe',
                'Variant' => 'columns-rule',
                'Columns' => '4',
                'Background' => 'light',
            ],
            'items' => [
                ['title' => 'Ein Ansprechpartner', 'text' => 'Von der Besichtigung bis zur Abnahme sprechen Sie mit derselben Person – ohne Weiterreichen.'],
                ['title' => 'Angebot vor Arbeitsbeginn', 'text' => 'Sie erhalten ein schriftliches Angebot mit nachvollziehbaren Positionen, bevor wir anfangen.'],
                ['title' => 'Geprüft und dokumentiert', 'text' => 'Jede Anlage wird nach VDE gemessen und mit Prüfprotokoll übergeben – wichtig für Netzbetreiber und Versicherung.'],
                ['title' => 'Sauber hinterlassen', 'text' => 'Staubschutz, Abdeckung und Aufräumen gehören zum Auftrag – auch in bewohnten Räumen.'],
            ],
        ];
    }

    /**
     * @param array<int, array{0: string, 1: string}> $steps
     * @return array<string, mixed>
     */
    private static function steps(string $eyebrow, string $title, array $steps): array
    {
        return [
            'type' => ElementFeatureGrid::class,
            'fields' => [
                'Eyebrow' => $eyebrow,
                'Title' => $title,
                'Variant' => 'steps',
                'Columns' => '4',
            ],
            'items' => array_map(static fn (array $s): array => ['title' => $s[0], 'text' => $s[1]], $steps),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function cta(string $title = 'Elektriker in München gesucht?', string $text = 'Beschreiben Sie kurz Ihr Vorhaben – wir melden uns mit einem Terminvorschlag für die Besichtigung.'): array
    {
        return [
            'type' => ElementCallToAction::class,
            'fields' => [
                'Title' => $title,
                'Content' => $text,
                'Variant' => 'split',
                'Background' => 'primary',
                'Spacing' => 'md',
            ],
            'links' => [
                ['page' => 'kontakt', 'text' => 'Angebot anfragen'],
                ['phone' => self::PHONE_E164, 'text' => self::PHONE],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function form(string $title, string $intro, string $background): array
    {
        return [
            'type' => ElementForm::class,
            'form' => 'contact',
            'fields' => [
                'Title' => $title,
                'ShowTitle' => true,
                'Eyebrow' => 'Anfrage',
                'Intro' => $intro,
                'ShowContactColumn' => true,
                'Background' => $background,
            ],
        ];
    }

    /**
     * Service page: compact hero, text + image, scope (icon list), process (steps), FAQ; related cards are automatic;
     * CTA band in the bottom area.
     *
     * @param array{0: string, 1: string} $text
     * @param array<int, array{0: string, 1: string, 2: string}> $scope
     * @param array<int, array{0: string, 1: string}> $faq
     * @param array<int, array{0: string, 1: string}>|null $steps
     * @return array<string, array<int, array<string, mixed>>>
     */
    private static function servicePage(string $h1, string $eyebrow, string $lead, string $key, array $text, string $scopeTitle, array $scope, array $faq, ?array $steps = null, string $stepsTitle = 'So läuft Ihr Auftrag ab', ?string $ctaTitle = null): array
    {
        $images = self::serviceImages()[$key];

        return [
            'ElementalArea' => [
                self::hero($h1, $eyebrow, $lead, $images['hero'], [
                    ['page' => 'kontakt', 'text' => 'Angebot anfragen'],
                    ['phone' => self::PHONE_E164, 'text' => self::PHONE],
                ]),
                [
                    'type' => ElementTextMedia::class,
                    'fields' => [
                        'Eyebrow' => 'Im Überblick',
                        'Title' => $text[0],
                        'Content' => $text[1],
                        'ImagePosition' => 'left',
                    ],
                    'images' => ['Image' => $images['detail']],
                ],
                [
                    'type' => ElementFeatureGrid::class,
                    'fields' => [
                        'Eyebrow' => 'Leistungsumfang',
                        'Title' => $scopeTitle,
                        'Variant' => 'icon-list',
                        'Columns' => '3',
                        'Background' => 'light',
                    ],
                    'items' => array_map(static fn (array $f): array => ['title' => $f[0], 'text' => $f[1], 'icon' => $f[2]], $scope),
                ],
                self::steps('Ablauf', $stepsTitle, $steps ?? [
                    ['Anfrage', 'Formular oder Anruf – Fotos vom Einbauort und vom Zählerschrank helfen.'],
                    ['Vor-Ort-Termin', 'Wir sehen uns die Anlage an und klären Ihre Wünsche.'],
                    ['Schriftliches Angebot', 'Alle Positionen und ein fester Ausführungstermin.'],
                    ['Ausführung & Protokoll', 'Installation, Messung nach VDE und Übergabe mit Prüfprotokoll.'],
                ]),
                [
                    'type' => ElementFaq::class,
                    'fields' => [
                        'Eyebrow' => 'FAQ',
                        'Title' => 'Häufige Fragen',
                        'Intro' => '<p>Ihre Frage ist nicht dabei? Rufen Sie an: <a href="tel:' . self::PHONE_E164 . '">' . self::PHONE . '</a></p>',
                        'Layout' => 'intro-left',
                        'Background' => 'light',
                    ],
                    'items' => $faq,
                ],
            ],
            'BottomArea' => [
                $ctaTitle ? self::cta($ctaTitle, 'Schicken Sie uns kurz Ihr Vorhaben – Fotos vom Zählerschrank und Einbauort helfen. Wir melden uns mit einem Terminvorschlag.') : self::cta(),
            ],
        ];
    }

    /**
     * @return array<string, array<string, array{0: string, 1: string}>>
     */
    public static function serviceImages(): array
    {
        return [
            'elektroinstallation' => [
                'hero' => ['elektroinstallation-hero.jpg', 'Elektriker setzt Unterputzdosen in einer frisch verputzten Neubauwand'],
                'detail' => ['elektroinstallation-detail.jpg', 'Neu verdrahtete Unterverteilung mit beschrifteten Leitungsschutzschaltern'],
            ],
            'sanierung' => [
                'hero' => ['sanierung-hero.jpg', 'Neuer Zählerschrank mit Zählerplatz und Hauptschalter in einem Münchner Altbau-Keller'],
                'detail' => ['sanierung-detail.jpg', 'Elektriker baut Fehlerstrom-Schutzschalter in einen Verteiler ein'],
            ],
            'wallbox' => [
                'hero' => ['wallbox-hero.jpg', 'Wallbox an der Wand einer Garage, ein Elektroauto lädt'],
                'detail' => ['wallbox-detail.jpg', 'Absicherung der Wallbox mit Leitungsschutz und Fehlerstrom-Schutzschalter im Zählerschrank'],
            ],
            'photovoltaik' => [
                'hero' => ['photovoltaik-hero.jpg', 'Wechselrichter und Batteriespeicher an der Kellerwand eines Einfamilienhauses'],
                'detail' => ['photovoltaik-detail.jpg', 'Elektriker schließt einen PV-Wechselrichter an'],
            ],
            'smarthome' => [
                'hero' => ['smarthome-hero.jpg', 'Wohnzimmer mit Lichtszene, gesteuert über einen KNX-Taster an der Wand'],
                'detail' => ['smarthome-detail.jpg', 'KNX-Aktoren und Busleitung in einer Unterverteilung'],
            ],
            'pruefung' => [
                'hero' => ['pruefung-hero.jpg', 'Elektriker prüft ein Bürogerät mit einem Gerätetester'],
                'detail' => ['pruefung-detail.jpg', 'Prüfplakette mit nächstem Prüftermin auf einem Kaltgerätekabel'],
            ],
        ];
    }

    // ---------------------------------------------------------------------
    // Legal
    // ---------------------------------------------------------------------

    private static function impressum(): string
    {
        return '<h2>Angaben gemäß § 5 DDG</h2>'
            . '<p>HM Elektro GmbH<br>[Straße und Hausnummer]<br>[PLZ] München</p>'
            . '<p>Vertreten durch die Geschäftsführung: [Name]</p>'
            . '<h2>Kontakt</h2>'
            . '<p>Telefon: ' . self::PHONE . '<br>E-Mail: <a href="mailto:' . self::EMAIL . '">' . self::EMAIL . '</a></p>'
            . '<h2>Registereintrag</h2>'
            . '<p>Registergericht: Amtsgericht München<br>Registernummer: HRB [Nummer]</p>'
            . '<h2>Umsatzsteuer-Identifikationsnummer</h2>'
            . '<p>USt-IdNr. gemäß § 27a UStG: DE[Nummer]</p>'
            . '<h2>Berufsrechtliche Angaben</h2>'
            . '<p>Berufsbezeichnung: [Elektrotechnikermeister / Elektroinstallateur – bestätigen], verliehen in der Bundesrepublik Deutschland.<br>Zuständige Kammer: Handwerkskammer für München und Oberbayern.<br>Eintragung in die Handwerksrolle: [bestätigen].<br>Berufsrechtliche Regelung: Handwerksordnung (HwO), <a href="https://www.gesetze-im-internet.de/hwo/" rel="noopener">gesetze-im-internet.de/hwo</a>.</p>'
            . '<h2>Verantwortlich für den Inhalt nach § 18 Abs. 2 MStV</h2>'
            . '<p>[Name], Anschrift wie oben</p>'
            . '<h2>Verbraucherstreitbeilegung</h2>'
            . '<p>Wir sind nicht bereit und nicht verpflichtet, an Streitbeilegungsverfahren vor einer Verbraucherschlichtungsstelle teilzunehmen.</p>';
    }
}
