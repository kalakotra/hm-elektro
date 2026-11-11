# Bilder für den Seed (`app/seed/images`)

Der Seed-Task importiert Bilder aus diesem Ordner (Dateiname = Schlüssel). Fehlende Dateien werden beim Lauf aufgelistet;
nach dem Ablegen `vendor/bin/sake tasks:seed-site --reset-blocks` ausführen.

**Keine Bilder von Referenz-Websites hotlinken oder übernehmen.** Echte Fotos aus Projekten von HM Elektro sind immer besser
als Stockfotos; Personen nur mit Einverständnis.

| Datei | Motiv / Alt-Text | Format |
|---|---|---|
| `startseite-hero.jpg` | Elektriker von HM Elektro verdrahtet eine Unterverteilung in einem Münchner Wohnhaus | 1200 × 1400 (6:7), hochkant |
| `gewerbe-pruefung.jpg` | Elektriker prüft mit einem Prüfgerät die Steckdosen in einem Büro | 1300 × 1100 |
| `ueber-uns-team.jpg` | Das Team von HM Elektro vor dem Firmenfahrzeug | 1300 × 1100 |
| `leistungen-hero.jpg` | Elektriker misst mit einem Installationstester an einer neuen Unterverteilung | 1200 × 900 (4:3) |
| `ueber-uns-hero.jpg` | Das Team von HM Elektro in der Werkstatt | 1200 × 900 (4:3) |
| `ueber-uns-arbeit.jpg` | Elektriker von HM Elektro beschriftet die Stromkreise in einem Verteiler | 1300 × 1100 |
| `elektroinstallation-hero.jpg` / `-detail.jpg` | Unterputzdosen im Neubau / neu verdrahtete Unterverteilung | 1200 × 900 / 1300 × 1100 |
| `sanierung-hero.jpg` / `-detail.jpg` | Neuer Zählerschrank im Altbau-Keller / Einbau Fehlerstrom-Schutzschalter | 1200 × 900 / 1300 × 1100 |
| `wallbox-hero.jpg` / `-detail.jpg` | Wallbox in Garage, E-Auto lädt / Absicherung im Zählerschrank | 1200 × 900 / 1300 × 1100 |
| `photovoltaik-hero.jpg` / `-detail.jpg` | Wechselrichter + Speicher an Kellerwand / Anschluss Wechselrichter | 1200 × 900 / 1300 × 1100 |
| `smarthome-hero.jpg` / `-detail.jpg` | Lichtszene über KNX-Taster / KNX-Aktoren im Verteiler | 1200 × 900 / 1300 × 1100 |
| `pruefung-hero.jpg` / `-detail.jpg` | Gerätetest im Büro / Prüfplakette am Kaltgerätekabel | 1200 × 900 / 1300 × 1100 |

Exakte Alt-Texte stehen in `app/src/Seed/SiteContent.php`. JPG, Qualität ~80, max. 400 KB.
