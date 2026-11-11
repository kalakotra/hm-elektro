<?php

declare(strict_types=1);

namespace App\Tasks;

use App\Models\OpeningHoursEntry;
use App\Pages\ServicePage;
use App\Seed\SiteContent;
use DNADesign\Elemental\Models\BaseElement;
use DNADesign\Elemental\Models\ElementalArea;
use DNADesign\ElementalUserForms\Model\ElementForm;
use SilverStripe\Assets\Folder;
use SilverStripe\Assets\Image;
use SilverStripe\CMS\Model\RedirectorPage;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Control\Director;
use SilverStripe\Dev\BuildTask;
use SilverStripe\LinkField\Models\ExternalLink;
use SilverStripe\LinkField\Models\Link;
use SilverStripe\LinkField\Models\PhoneLink;
use SilverStripe\LinkField\Models\SiteTreeLink;
use SilverStripe\ORM\DataObject;
use SilverStripe\PolyExecution\PolyOutput;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\UserForms\Model\EditableFormField;
use SilverStripe\UserForms\Model\EditableFormField\EditableEmailField;
use SilverStripe\UserForms\Model\EditableFormField\EditableMultipleOptionField;
use SilverStripe\UserForms\Model\EditableFormField\EditableOption;
use SilverStripe\UserForms\Model\Recipient\EmailRecipient;
use SilverStripe\Versioned\Versioned;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use YouWillLikeIT\Elements\Elements\ElementBase;
use YouWillLikeIT\Elements\Elements\ElementFaq;
use YouWillLikeIT\Elements\Elements\ElementFeatureGrid;
use YouWillLikeIT\Elements\Models\FaqItem;
use YouWillLikeIT\Elements\Models\FeatureItem;

/**
 * Creates settings, page tree, blocks and the enquiry forms for hm-elektro.com.
 * Idempotent: pages are matched by URL segment + parent; blocks are only created for empty areas
 * (use --reset-blocks to rebuild them). Images are imported from app/seed/images when present.
 *
 * Run: vendor/bin/sake tasks:seed-site
 */
class SeedSiteTask extends BuildTask
{
    protected static string $commandName = 'seed-site';

    protected string $title = 'HM Elektro: Inhalte anlegen';

    protected static string $description = 'Legt Einstellungen, Seiten, Blöcke und Anfrageformulare für hm-elektro.com an.';

    private const IMAGE_SOURCE = 'app/seed/images';

    private PolyOutput $output;

    private bool $resetBlocks = false;

    /** @var array<string, SiteTree> */
    private array $pages = [];

    /** @var array<string, string> */
    private array $missingImages = [];

    public function getOptions(): array
    {
        return [
            new InputOption('reset-blocks', null, InputOption::VALUE_NONE, 'Vorhandene Blöcke der Seed-Seiten löschen und neu anlegen'),
        ];
    }

    protected function execute(InputInterface $input, PolyOutput $output): int
    {
        $this->output = $output;
        $this->resetBlocks = (bool) $input->getOption('reset-blocks');

        Versioned::withVersionedMode(function (): void {
            Versioned::set_stage(Versioned::DRAFT);
            $this->seedSiteConfig();
            $this->seedPages();
            $this->seedPageRelations();
            $this->seedBlocks();
            $this->publishAll();
        });

        if ($this->missingImages) {
            $output->writeln('');
            $output->writeln('<comment>Fehlende Bilder (in ' . self::IMAGE_SOURCE . ' ablegen, dann Task erneut mit --reset-blocks ausführen):</comment>');
            foreach ($this->missingImages as $file => $title) {
                $output->writeln('  - ' . $file . ' (' . $title . ')');
            }
        }

        $output->writeln('<info>Fertig.</info>');

        return Command::SUCCESS;
    }

    // ---------------------------------------------------------------------
    // Settings
    // ---------------------------------------------------------------------

    private function seedSiteConfig(): void
    {
        $config = SiteConfig::current_site_config();
        $data = SiteContent::siteConfig();
        $config->update($data['fields']);

        $themeImages = Director::baseFolder() . '/themes/hmelektro/images/';
        $logo = $this->image('logo-512.png', 'Site', 'HM Elektro Logo', $themeImages . 'icon-512.png');
        $og = $this->image('og-default.jpg', 'SEO', 'HM Elektro – Elektriker in München', $themeImages . 'og-default.jpg');

        if ($og) {
            $config->DefaultOGImageID = $og->ID;
        }

        // dorsetdigital/silverstripe-schema-manager organisation fields
        foreach ($data['schema'] as $field => $value) {
            if ($config->hasField($field)) {
                $config->setField($field, $value);
            }
        }
        if ($logo && $config->hasField('SchemaOrganisationLogoID')) {
            $config->SchemaOrganisationLogoID = $logo->ID;
        }

        $config->write();

        if (!$config->OpeningHours()->exists()) {
            foreach ($data['openingHours'] as $i => $row) {
                $entry = OpeningHoursEntry::create($row + ['Sort' => $i + 1]);
                $entry->SiteConfigID = $config->ID;
                $entry->write();
            }
        }

        $this->output->writeln('Einstellungen gespeichert.');
    }

    // ---------------------------------------------------------------------
    // Pages
    // ---------------------------------------------------------------------

    private function seedPages(): void
    {
        foreach (SiteContent::pages() as $key => $def) {
            $parent = isset($def['parent']) ? $this->pages[$def['parent']] : null;
            $page = $this->findOrCreatePage($def['class'], $def['segment'], $parent);
            $page->update($def['fields']);
            $page->Sort = $def['sort'];

            $page->write();
            $this->pages[$key] = $page;
        }

        $this->output->writeln(count($this->pages) . ' Seiten angelegt/aktualisiert.');
    }

    /**
     * @param class-string<SiteTree> $class
     */
    private function findOrCreatePage(string $class, string $segment, ?SiteTree $parent): SiteTree
    {
        $parentID = $parent?->ID ?? 0;
        $existing = SiteTree::get()->filter(['URLSegment' => $segment, 'ParentID' => $parentID])->first();

        if ($existing && $existing->ClassName !== $class) {
            $existing = $existing->newClassInstance($class);
        }

        $page = $existing ?? $class::create();
        $page->URLSegment = $segment;
        $page->ParentID = $parentID;

        return $page;
    }

    /**
     * Redirector targets (menu item "Gewerbe") and manually chosen related services.
     */
    private function seedPageRelations(): void
    {
        foreach (SiteContent::pages() as $key => $def) {
            $page = $this->pages[$key];
            if ($page instanceof RedirectorPage && isset($def['linkTo'])) {
                $page->LinkToID = $this->pages[$def['linkTo']]->ID;
                $page->write();
            }
        }

        foreach (SiteContent::related() as $key => $relatedKeys) {
            $page = $this->pages[$key] ?? null;
            if (!$page instanceof ServicePage) {
                continue;
            }
            $page->RelatedServices()->removeAll();
            foreach ($relatedKeys as $sort => $relatedKey) {
                $page->RelatedServices()->add($this->pages[$relatedKey], ['SortOrder' => $sort + 1]);
            }
        }
    }

    // ---------------------------------------------------------------------
    // Blocks
    // ---------------------------------------------------------------------

    private function seedBlocks(): void
    {
        foreach (SiteContent::blocks() as $pageKey => $areas) {
            $page = $this->pages[$pageKey] ?? null;
            if (!$page) {
                continue;
            }

            foreach ($areas as $relation => $blocks) {
                $area = $this->area($page, $relation);
                if ($area->Elements()->exists()) {
                    if (!$this->resetBlocks) {
                        continue;
                    }
                    foreach ($area->Elements() as $element) {
                        $element->doArchive();
                    }
                }

                foreach ($blocks as $i => $block) {
                    $this->createBlock($area, $block, $i + 1);
                }
            }
        }

        $this->output->writeln('Blöcke angelegt.');
    }

    private function area(SiteTree $page, string $relation): ElementalArea
    {
        $area = $page->getComponent($relation);
        if (!$area || !$area->exists()) {
            $area = ElementalArea::create();
            $area->OwnerClassName = $page->ClassName;
            $area->write();
            $page->setField($relation . 'ID', $area->ID);
            $page->write();
        }

        return $area;
    }

    /**
     * @param array<string, mixed> $block
     */
    private function createBlock(ElementalArea $area, array $block, int $sort): BaseElement
    {
        $type = $block['type'];
        /** @var BaseElement $element */
        $element = $type::create();
        $element->ParentID = $area->ID;
        $element->Sort = $sort;
        $element->update($block['fields'] ?? []);

        foreach (($block['images'] ?? []) as $field => [$file, $alt]) {
            $img = $this->image($file, 'Elements', $alt);
            $element->setField($field . 'ID', $img?->ID ?? 0);
            $element->setField($field . 'Alt', $alt);
        }

        $element->write();

        if ($element instanceof ElementBase) {
            foreach (($block['links'] ?? []) as $i => $linkDef) {
                $link = $this->link($linkDef);
                $link->OwnerID = $element->ID;
                $link->OwnerClass = $element->ClassName;
                $link->OwnerRelation = 'CTALinks';
                $link->Sort = $i + 1;
                $link->write();
            }
        }

        match (true) {
            $element instanceof ElementFeatureGrid => $this->featureItems($element, $block['items'] ?? []),
            $element instanceof ElementFaq => $this->faqItems($element, $block['items'] ?? []),
            $element instanceof ElementForm => $this->form($element, (string) ($block['form'] ?? 'contact')),
            default => null,
        };

        return $element;
    }

    /**
     * @param array<int, array<string, mixed>> $items
     */
    private function featureItems(ElementFeatureGrid $grid, array $items): void
    {
        foreach ($items as $i => $def) {
            $item = FeatureItem::create([
                'Title' => $def['title'],
                'Text' => $def['text'] ?? '',
                'Icon' => $def['icon'] ?? '',
                'Sort' => $i + 1,
            ]);
            $item->ParentID = $grid->ID;
            if (isset($def['image'])) {
                $item->ImageID = $this->image($def['image'][0], 'Elements', $def['image'][1])?->ID ?? 0;
                $item->ImageAlt = $def['image'][1];
            }
            $item->write();

            if (isset($def['link'])) {
                $this->attachHasOne($item, 'Link', $this->link($def['link']));
                $item->write();
            }
        }
    }

    /**
     * @param array<int, array{0: string, 1: string}> $items
     */
    private function faqItems(ElementFaq $faq, array $items): void
    {
        foreach ($items as $i => [$question, $answer]) {
            $item = FaqItem::create(['Question' => $question, 'Answer' => '<p>' . $answer . '</p>', 'Sort' => $i + 1]);
            $item->ParentID = $faq->ID;
            $item->write();
        }
    }

    private function form(ElementForm $form, string $key): void
    {
        $config = SiteConfig::current_site_config();
        $def = SiteContent::forms()[$key];

        /** @var array<string, EditableFormField> $fields */
        $fields = [];
        foreach ($def['fields'] as $i => $fieldDef) {
            /** @var EditableFormField $field */
            $field = $fieldDef['class']::create($fieldDef['data'] + ['Sort' => $i + 2]);
            $field->ParentID = $form->ID;
            $field->ParentClass = $form->ClassName;
            $field->write();

            if ($field instanceof EditableMultipleOptionField) {
                foreach ($fieldDef['options'] ?? [] as $o => $option) {
                    $opt = EditableOption::create(['Title' => $option, 'Value' => $option, 'Sort' => $o + 1]);
                    $opt->ParentID = $field->ID;
                    $opt->write();
                }
            }
            $fields[$fieldDef['key']] = $field;
        }

        // Adds the initial EditableFormStep (userforms onAfterWrite).
        $form->SubmitButtonText = $def['submit'];
        $form->OnCompleteMessage = $def['complete'];
        $form->write();

        $recipient = EmailRecipient::create([
            'EmailAddress' => $config->Email,
            'EmailSubject' => $def['subject'],
            'EmailFrom' => $def['from'],
            'EmailBodyHtml' => '<p>' . $def['subject'] . '</p>',
        ]);
        $recipient->FormID = $form->ID;
        $recipient->FormClass = $form->ClassName;
        if (isset($fields['email']) && $fields['email'] instanceof EditableEmailField) {
            $recipient->SendEmailFromFieldID = $fields['email']->ID;
        }
        $recipient->write();
    }

    // ---------------------------------------------------------------------
    // Publish
    // ---------------------------------------------------------------------

    private function publishAll(): void
    {
        SiteConfig::current_site_config()->publishRecursive();

        foreach (SiteContent::pages() as $key => $def) {
            if (!empty($def['draft'])) {
                continue;
            }
            $this->pages[$key]->publishRecursive();
        }

        $this->output->writeln('Seiten veröffentlicht (Impressum und Datenschutz bleiben Entwurf, bis die Angaben des Kunden vorliegen).');
    }

    // ---------------------------------------------------------------------
    // Helpers
    // ---------------------------------------------------------------------

    /**
     * @param array<string, mixed> $def ['page' => key, 'anchor' => ?, 'text' => ...] | ['phone' => ..] | ['url' => ..]
     */
    private function link(array $def): Link
    {
        if (isset($def['page'])) {
            $link = SiteTreeLink::create();
            $link->PageID = $this->pages[$def['page']]->ID;
            if (isset($def['anchor'])) {
                $link->Anchor = $def['anchor'];
            }
        } elseif (isset($def['phone'])) {
            $link = PhoneLink::create();
            $link->Phone = $def['phone'];
        } else {
            $link = ExternalLink::create();
            $link->ExternalUrl = $def['url'];
            $link->OpenInNew = (bool) ($def['newWindow'] ?? true);
        }
        $link->LinkText = $def['text'];
        $link->write();

        return $link;
    }

    private function attachHasOne(DataObject $owner, string $relation, Link $link): void
    {
        $link->OwnerID = $owner->ID;
        $link->OwnerClass = $owner->ClassName;
        $link->OwnerRelation = $relation;
        $link->write();
        $owner->setField($relation . 'ID', $link->ID);
    }

    /**
     * Imports an image once (by file name). Returns null and records it when the source is missing.
     */
    private function image(string $fileName, string $folder, string $title, ?string $sourcePath = null): ?Image
    {
        $target = $folder . '/' . $fileName;
        $existing = Image::get()->filter('FileFilename', $target)->first();
        if ($existing) {
            return $existing;
        }

        $source = $sourcePath ?? Director::baseFolder() . '/' . self::IMAGE_SOURCE . '/' . $fileName;
        if (!is_file($source)) {
            $this->missingImages[$fileName] = $title;
            return null;
        }

        Folder::find_or_make($folder);
        $image = Image::create();
        $image->setFromLocalFile($source, $target);
        $image->Title = $title;
        $image->write();
        $image->publishSingle();

        return $image;
    }
}
