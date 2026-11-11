<?php

declare(strict_types=1);

use App\Pages\ContactPage;
use App\Pages\ServiceHolderPage;
use App\Pages\ServicePage;
use DNADesign\Elemental\Controllers\ElementController;
use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\Model\List\ArrayList;
use SilverStripe\ORM\DataList;
use YouWillLikeIT\Elements\Elements\ElementHero;

/**
 * @template T of Page
 * @extends ContentController<T>
 */
class PageController extends ContentController
{
    /**
     * First block of the main area when it is a hero (rendered above automatic page content).
     */
    public function HeaderElement(): ?ElementController
    {
        $area = $this->data()->hasMethod('ElementalArea') ? $this->data()->ElementalArea() : null;
        if (!$area || !$area->exists()) {
            return null;
        }

        $first = $area->ElementControllers()->first();

        return $first && $first->getElement() instanceof ElementHero ? $first : null;
    }

    /**
     * Main-area blocks without the header hero.
     */
    public function MainElements(): ArrayList
    {
        $list = ArrayList::create();
        $area = $this->data()->hasMethod('ElementalArea') ? $this->data()->ElementalArea() : null;
        if (!$area || !$area->exists()) {
            return $list;
        }

        $header = $this->HeaderElement();
        $headerID = $header ? (int) $header->getElement()->ID : 0;

        foreach ($area->ElementControllers() as $controller) {
            if ((int) $controller->getElement()->ID !== $headerID) {
                $list->push($controller);
            }
        }

        return $list;
    }

    public function ContactPage(): ?ContactPage
    {
        return ContactPage::get()->first();
    }

    public function ServiceHolder(): ?ServiceHolderPage
    {
        return ServiceHolderPage::get()->first();
    }

    /**
     * Service pages of the (first) overview page, in menu order – footer, hero quick links.
     */
    public function ServicePages(): DataList
    {
        $holder = $this->ServiceHolder();

        return $holder
            ? ServicePage::get()->filter(['ParentID' => $holder->ID, 'ShowInMenus' => true])->sort('Sort')
            : ServicePage::get()->filter('ShowInMenus', true)->sort('Sort');
    }
}
