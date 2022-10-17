<?php

/*
 * Copyright (c) 2022 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\ChoicesBundle\Asset;

use HeimrichHannot\ChoicesBundle\HeimrichHannotContaoChoicesBundle;
use HeimrichHannot\EncoreContracts\EncoreEntry;
use HeimrichHannot\EncoreContracts\EncoreExtensionInterface;

class EncoreExtension implements EncoreExtensionInterface
{
    /**
     * {@inheritDoc}
     */
    public function getBundle(): string
    {
        return HeimrichHannotContaoChoicesBundle::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getEntries(): array
    {
        return [
            EncoreEntry::create('contao-choices-bundle', 'assets/js/contao-choices-bundle.js')
                ->addJsEntryToRemoveFromGlobals('contao-choices-bundle')
                ->addJsEntryToRemoveFromGlobals('contao-choices-bundle--library'),
            EncoreEntry::create('contao-choices-bundle-theme', 'assets/js/contao-choices-bundle-theme.js')
                ->setRequiresCss(true)
                ->addCssEntryToRemoveFromGlobals('contao-choices-bundle'),
        ];
    }
}
