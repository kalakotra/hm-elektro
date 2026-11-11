<?php

declare(strict_types=1);

namespace App\Extensions;

use App\Pages\ServicePage;
use DorsetDigital\SchemaManager\Control\SchemaIDs;
use DorsetDigital\SchemaManager\Model\Schema\ServiceSchema;
use DorsetDigital\SchemaManager\Model\Schema\WebPageSchema;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Core\Extension;
use SilverStripe\i18n\i18n;
use SilverStripe\SiteConfig\SiteConfig;

/**
 * Page-level structured data, added to the schema-manager graph:
 * - WebPage: inLanguage from the current locale;
 * - ServicePage: Service (main entity of the page), provided by the LocalBusiness when an address exists,
 *   otherwise by the organisation; areaServed from the settings.
 *
 * @extends Extension<SiteTree>
 */
class PageSchemaExtension extends Extension
{
    /**
     * @param array<int, mixed> $entities
     */
    protected function updateSchemaManagerEntities(array &$entities): void
    {
        $page = $this->getOwner();
        $language = str_replace('_', '-', (string) i18n::get_locale());

        foreach ($entities as $entity) {
            if ($entity instanceof WebPageSchema) {
                $entity->update(['inLanguage' => $language]);
            }
        }

        if (!$page instanceof ServicePage) {
            return;
        }

        $config = SiteConfig::current_site_config();
        $url = $page->AbsoluteLink();
        $provider = $config->getHasAddress() ? SiteConfigExtension::localBusinessID() : SchemaIDs::organisation();

        $service = ServiceSchema::create('page-' . $page->ID, (string) $page->Title, $page->MetaDescription ?: ($page->Teaser ?: null))
            ->setURL($url)
            ->setProvider($provider)
            ->setAreaServed($config->getSchemaAreaServed())
            ->setMainEntityOfPage($url);

        $service->update(['serviceType' => (string) ($page->ServiceType ?: $page->Title)]);

        $entities[] = $service;
    }
}
