<?php

/*
 * Copyright (c) 2023 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\ChoicesBundle\EventListener;

use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\PageModel;
use HeimrichHannot\ChoicesBundle\Asset\FrontendAsset;
use HeimrichHannot\ChoicesBundle\Event\CustomizeChoicesOptionsEvent;
use HeimrichHannot\ChoicesBundle\Manager\ChoicesManager;
use HeimrichHannot\UtilsBundle\Dca\DcaUtil;
use HeimrichHannot\UtilsBundle\Model\ModelUtil;
use HeimrichHannot\UtilsBundle\Util\Utils;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class GetAttributesFromDcaListener
{
    /**
     * @var bool
     */
    protected $closed = false;

    /**
     * @var Utils
     */
    protected $utils;
    /**
     * @var ChoicesManager
     */
    protected $choicesManager;
    /**
     * @var DcaUtil
     */
    protected $dcaUtil;
    /**
     * @var ModelUtil
     */
    protected $modelUtil;

    private $pageParents = null;
    /**
     * @var FrontendAsset
     */
    private $frontendAsset;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * GetAttributesFromDcaListener constructor.
     *
     * @param null $pageParents
     */
    public function __construct(FrontendAsset $frontendAsset, EventDispatcherInterface $eventDispatcher, Utils $utils, ChoicesManager $choicesManager, DcaUtil $dcaUtil, ModelUtil $modelUtil)
    {
        $this->frontendAsset = $frontendAsset;
        $this->eventDispatcher = $eventDispatcher;
        $this->utils = $utils;
        $this->choicesManager = $choicesManager;
        $this->dcaUtil = $dcaUtil;
        $this->modelUtil = $modelUtil;
    }

    /**
     * @Hook("getAttributesFromDca")
     */
    public function __invoke(array $attributes, $context = null): array
    {
        if ($this->closed || !$this->utils->container()->isFrontend() || !\in_array($attributes['type'], ['select', 'text'])) {
            $this->open();

            return $attributes;
        }

        $customOptions = [];

        if (isset($attributes['choicesOptions']) && \is_array($attributes['choicesOptions'])) {
            $customOptions = $attributes['choicesOptions'];
        }
        $customOptions = $this->choicesManager->getOptionsAsArray($customOptions);

        $this->getPageWithParents();

        if (null !== $this->pageParents) {
            if ('select' === $attributes['type']) {
                $property = $this->dcaUtil->getOverridableProperty('useChoicesForSelect', $this->pageParents);

                if (true === (bool) $property) {
                    $this->frontendAsset->addFrontendAssets();
                    $customOptions['enable'] = true;
                }
            }

            if ('text' === $attributes['type']) {
                $property = $this->dcaUtil->getOverridableProperty('useChoicesForText', $this->pageParents);

                if (true === (bool) $property) {
                    $this->frontendAsset->addFrontendAssets();
                    $customOptions['enable'] = true;
                }
            }
        }

        $event = $this->eventDispatcher->dispatch(
            new CustomizeChoicesOptionsEvent($customOptions, $attributes, $context),
            CustomizeChoicesOptionsEvent::NAME
        );

        if ($event->isChoicesEnabled()) {
            $this->frontendAsset->addFrontendAssets();
        }

        $attributes['data-choices'] = (int) $event->isChoicesEnabled();
        $attributes['data-choices-options'] = json_encode($event->getChoicesOptions());

        return $attributes;
    }

    public function close(): void
    {
        $this->closed = true;
    }

    public function open(): void
    {
        $this->closed = false;
    }

    protected function getPageWithParents()
    {
        /* @var PageModel $objPage */
        global $objPage;

        if (null === $this->pageParents && null !== $objPage) {
            $this->pageParents = $this->modelUtil->findParentsRecursively('pid', 'tl_page', $objPage);
            $this->pageParents[] = $objPage;
        }

        return $this->pageParents;
    }
}
