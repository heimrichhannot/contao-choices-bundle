<?php

/*
 * Copyright (c) 2023 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\ChoicesBundle\Asset;

use HeimrichHannot\EncoreContracts\PageAssetsTrait;
use HeimrichHannot\UtilsBundle\Util\Utils;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class FrontendAsset implements ServiceSubscriberInterface
{
    use PageAssetsTrait;

    protected Utils $utils;

    /**
     * FrontendAsset constructor.
     */
    public function __construct(Utils $utils)
    {
        $this->utils = $utils;
    }

    public function addFrontendAssets(): void
    {
        if ($this->utils->container()->isFrontend()) {
            $this->addPageEntrypoint('contao-choices-bundle', [
                'TL_JAVASCRIPT' => [
                    'contao-choices-bundle--library' => 'bundles/heimrichhannotcontaochoices/assets/choices.js|static',
                    'contao-choices-bundle' => 'bundles/heimrichhannotcontaochoices/assets/contao-choices-bundle.js|static',
                ],
            ]);
            $this->addPageEntrypoint('contao-choices-bundle-theme', [
                'TL_CSS' => [
                    'contao-choices-bundle' => 'bundles/heimrichhannotcontaochoices/assets/choices.css|static',
                ],
            ]);
        }
    }
}
