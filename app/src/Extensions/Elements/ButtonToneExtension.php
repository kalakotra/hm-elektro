<?php

declare(strict_types=1);

namespace App\Extensions\Elements;

use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Core\Extension;

/**
 * Template helpers derived from the section background (SectionStyleExtension):
 * - ButtonTone: light | dark | brand – wrapper modifier for Includes/CtaButtons.ss;
 * - IsDarkTheme: dark text-on-light is inverted (data-bs-theme="dark").
 * The brand blue carries ink text (white on #3CA0DC is only 2.9:1), so "primary" counts as light here,
 * although the module lists it under dark_backgrounds.
 *
 * @extends Extension<BaseElement>
 */
class ButtonToneExtension extends Extension
{
    public function getIsDarkTheme(): bool
    {
        return in_array((string) $this->getOwner()->getField('Background'), ['dark', 'image'], true);
    }

    public function getButtonTone(): string
    {
        return match ((string) $this->getOwner()->getField('Background')) {
            'dark', 'image' => 'dark',
            'primary' => 'brand',
            default => 'light',
        };
    }
}
