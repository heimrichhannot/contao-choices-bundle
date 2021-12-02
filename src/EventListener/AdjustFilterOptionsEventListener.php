<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\ChoicesBundle\EventListener;

use HeimrichHannot\ChoicesBundle\Asset\FrontendAsset;
use HeimrichHannot\ChoicesBundle\Event\CustomizeChoicesOptionsEvent;
use HeimrichHannot\ChoicesBundle\Manager\ChoicesManager;
use HeimrichHannot\FilterBundle\Event\AdjustFilterOptionsEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AdjustFilterOptionsEventListener
{
    /**
     * @var ContainerInterface
     */
    protected $container;
    /**
     * @var FrontendAsset
     */
    private $frontendAsset;
    /**
     * @var ChoicesManager
     */
    private $choicesManager;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(ContainerInterface $container, FrontendAsset $frontendAsset, ChoicesManager $choicesManager, EventDispatcherInterface $eventDispatcher)
    {
        $this->container = $container;
        $this->frontendAsset = $frontendAsset;
        $this->choicesManager = $choicesManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function onAdjustFilterOptions(AdjustFilterOptionsEvent $event)
    {
        $this->container->get(GetAttributesFromDcaListener::class)->close();
        $filter = $event->getConfig()->getFilter();
        $table = $filter['dataContainer'];

        $this->container->get('huh.utils.dca')->loadDc($table);

        $dca = &$GLOBALS['TL_DCA'][$table];

        $element = $event->getElement();

        $config = $this->container->getParameter('huh.filter');

        if (!isset($config['filter']['types'])) {
            return;
        }

        $filterType = null;

        foreach ($config['filter']['types'] as $type) {
            if ($type['name'] === $element->type) {
                $filterType = $type['type'];

                break;
            }
        }

        if (!$filterType) {
            return;
        }

        // select fields are choice'd by default, others not
        switch ($filterType) {
            case 'choice':
                if ($element->skipChoicesSupport) {
                    return;
                }

                break;

            default:
                if (!$element->addChoicesSupport) {
                    return;
                }

                break;
        }

        $this->frontendAsset->addFrontendAssets();

        $options = $event->getOptions();

        $choicesOptions = $this->choicesManager->getOptionsAsArray([], $table, $element->field ?: '');
        $choicesOptions['enable'] = true;

        if ($event->getElement()->addPlaceholder) {
            if (($options['multiple'] ?? false) === true && ($options['expanded'] ?? false) === false) {
                $choices = $options['choices'];
                $choices = array_merge([$options['placeholder'] => ""], $choices);
                $options['choices'] = $choices;
            }

            $choicesOptions['placeholder'] = true;
            if (isset($options['placeholder'])) {
                $choicesOptions['placeholderValue'] = $options['placeholder'];
            }
        }

        $customizeChoicesOptionsEvent = new CustomizeChoicesOptionsEvent($choicesOptions, [], null);
        $customizeChoicesOptionsEvent->setAdjustFilterOptionsEvent(clone $event);
        $this->eventDispatcher->dispatch(CustomizeChoicesOptionsEvent::NAME, $customizeChoicesOptionsEvent);

        $options['attr']['data-choices'] = (int) $customizeChoicesOptionsEvent->isChoicesEnabled();
        $options['attr']['data-choices-options'] = json_encode($customizeChoicesOptionsEvent->getChoicesOptions());

        $event->setOptions($options);
    }
}
