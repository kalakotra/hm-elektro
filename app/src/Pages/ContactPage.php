<?php

declare(strict_types=1);

namespace App\Pages;

use Page;

/**
 * Contact: blocks only (hero + form block with contact column from the settings).
 * The class marks the page that header button, action bar and service pages link to.
 */
class ContactPage extends Page
{
    private static string $table_name = 'App_ContactPage';

    private static string $cms_icon_class = 'font-icon-p-mail';

    private static string $singular_name = 'Kontaktseite';

    private static string $plural_name = 'Kontaktseiten';

    private static string $class_description = 'Anfrageseite: Ziel von „Angebot anfragen“ in Kopfzeile und Schnellzugriff';

    private static $allowed_children = 'none';
}
