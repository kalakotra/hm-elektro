# hm-elektro.com

Website der **HM Elektro GmbH** (Elektrofachbetrieb, München) auf SilverStripe 6.3 mit Elemental, Bootstrap 5.3 und Vite.
Eine Sprache (de_DE), kein Fluent.

- Theme: `themes/hmelektro` (Templates + Bilder)
- Frontend-Quellen: `app/client/src` → Build nach `app/client/dist`
- Inhaltsblöcke: [`youwilllikeit/silverstripe-elements`](https://github.com/you-will-like-it/silverstripe-elements) 2.x
  + Projekt-Extensions in `app/src/Extensions/Elements`
- Strukturierte Daten: `dorsetdigital/silverstripe-schema-manager` (Organization, Electrician/LocalBusiness, WebSite,
  WebPage, BreadcrumbList, Service, FAQPage)
- Formulare: `dnadesign/silverstripe-elemental-userforms` (Block „Formular“ mit Kontaktspalte)

## Installation

```bash
composer install
composer vendor-expose
composer build-assets            # npm ci && npm run build
cp .env.example .env             # DB, Admin, MAILER_DSN anpassen
vendor/bin/sake db:build --flush
vendor/bin/sake tasks:seed-site  # Einstellungen, Seiten, Blöcke, Anfrageformulare
```

Bilder: in `app/seed/images` ablegen (Liste: `app/seed/images/README.md`), dann
`vendor/bin/sake tasks:seed-site --reset-blocks`.

Entwicklung mit Hot Reload: `npm run dev` (Vite auf :5173, DDEV-Origin wird automatisch gesetzt).

## Seitentypen

| Typ | Zweck |
|---|---|
| `HomePage` | Startseite, nur Blöcke |
| `ServiceHolderPage` / `ServicePage` | Leistungen; Übersicht listet die Unterseiten als nummerierte Karten, Leistungsseiten zeigen automatisch „Weitere Leistungen“ + unteren Blockbereich (CTA) |
| `ContactPage` | Anfrageseite (Ziel von „Angebot anfragen“ und Schnellzugriff) |
| `RedirectorPage` „Gewerbe“ | Menüpunkt → Leistung „Elektro-Check & DGUV V3“ |
| `Page` | Über uns, Impressum, Datenschutz |

Kontaktdaten, Bürozeiten, Einsatzgebiet und Schema-Typ pflegt man unter **Einstellungen**.

## Go-live-Checkliste

- [ ] `.env` (Produktion: `SS_ENVIRONMENT_TYPE="live"`, `SS_BASE_URL`, `MAILER_DSN`, Absender mit SPF), Admin-Passwort
- [ ] **Adresse** unter Einstellungen › Kontakt eintragen (sonst kein LocalBusiness-Schema, keine Adresse im Footer)
- [ ] **Impressum** (Name GF, HRB, USt-ID, Berufsbezeichnung/Handwerksrolle) ausfüllen und veröffentlichen – liegt als Entwurf an
- [ ] **Datenschutzerklärung** einfügen und veröffentlichen – liegt als Entwurf an
- [ ] Leistungen, Einsatzgebiet, Bürozeiten (Annahme Mo–Fr 07:30–17:00) und Vertrauensmerkmale vom Kunden bestätigen lassen
- [ ] „E-Check“-Marke nur verwenden, wenn Innungs-/E-Markenbetrieb (sonst „Elektro-Check“ lassen)
- [ ] Echte Fotos einspielen (`app/seed/images`), Logo als Original-Vektordatei ersetzen (`themes/hmelektro/images/logo*.svg`)
- [ ] Testmail beider Formulare (Startseite, Kontakt)
- [ ] Search Console: `https://www.hm-elektro.com/sitemap.xml`; Rich-Results-Test (Service, FAQ, LocalBusiness)
- [ ] Lighthouse + axe auf Staging (Vorschau: Mobil 99–100 / 100 / 100 / 100, axe 0 Verstöße)
