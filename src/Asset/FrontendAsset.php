<?php

/*
 * Copyright (c) 2022 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\ChoicesBundle\Asset;

use HeimrichHannot\EncoreBundle\Asset\FrontendAsset as EncoreFrontendAsset;
use HeimrichHannot\UtilsBundle\Util\Utils;
use Psr\Container\ContainerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class FrontendAsset implements ServiceSubscriberInterface
{
    protected Utils $utils;
    private ContainerInterface $container;

    /**
     * FrontendAsset constructor.
     */
    public function __construct(ContainerInterface $container, Utils $utils)
    {
        $this->container = $container;
        $this->utils = $utils;
    }

    public function addFrontendAssets(): void
    {
        if (!$this->utils->container()->isFrontend()) {
            return;
        }

        if ($this->container->has("HeimrichHannot\EncoreBundle\Asset\FrontendAsset")) {
            $this->container->get(EncoreFrontendAsset::class)->addActiveEntrypoint('contao-choices-bundle');
            $this->container->get(EncoreFrontendAsset::class)->addActiveEntrypoint('contao-choices-bundle-theme');
        }

        $GLOBALS['TL_CSS']['contao-choices-bundle'] = 'bundles/heimrichhannotcontaochoices/assets/choices.css|static';
        $GLOBALS['TL_JAVASCRIPT']['contao-choices-bundle--library'] = 'bundles/heimrichhannotcontaochoices/assets/choices.js|static';
        $GLOBALS['TL_JAVASCRIPT']['contao-choices-bundle'] = 'bundles/heimrichhannotcontaochoices/assets/contao-choices-bundle.js|static';
    }

    public static function getSubscribedServices()
    {
        return [
            '?HeimrichHannot\EncoreBundle\Asset\FrontendAsset',
        ];
    }
}
