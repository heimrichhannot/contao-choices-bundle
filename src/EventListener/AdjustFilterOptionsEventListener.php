<?php

/*
 * Copyright (c) 2023 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\ChoicesBundle\EventListener;

use Contao\Controller;
use HeimrichHannot\ChoicesBundle\Asset\FrontendAsset;
use HeimrichHannot\ChoicesBundle\Event\CustomizeChoicesOptionsEvent;
use HeimrichHannot\ChoicesBundle\Manager\ChoicesManager;
use HeimrichHannot\FilterBundle\Event\AdjustFilterOptionsEvent;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AdjustFilterOptionsEventListener implements EventSubscriberInterface
{
    private FrontendAsset                $frontendAsset;
    private ChoicesManager               $choicesManager;
    private EventDispatcherInterface     $eventDispatcher;
    private GetAttributesFromDcaListener $dcaListener;
    private ParameterBagInterface        $parameterBag;

    public function __construct(FrontendAsset $frontendAsset, ChoicesManager $choicesManager, EventDispatcherInterface $eventDispatcher, GetAttributesFromDcaListener $dcaListener, ParameterBagInterface $parameterBag)
    {
        $this->frontendAsset = $frontendAsset;
        $this->choicesManager = $choicesManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->dcaListener = $dcaListener;
        $this->parameterBag = $parameterBag;
    }

    public function onAdjustFilterOptions(AdjustFilterOptionsEvent $event)
    {
        $this->dcaListener->close();
        $filter = $event->getConfig()->getFilter();
        $table = $filter['dataContainer'];

        Controller::loadDataContainer($table);

        $element = $event->getElement();

        $config = $this->parameterBag->has('huh.filter') ? $this->parameterBag->get('huh.filter') : [];

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

        $options = $event->getOptions();

        $choicesOptions = $this->choicesManager->getOptionsAsArray([], $table, $element->field ?: '');
        $choicesOptions['enable'] = true;

        if ($event->getElement()->addPlaceholder) {
            if (($options['multiple'] ?? false) === true && ($options['expanded'] ?? false) === false) {
                $choices = $options['choices'];
                $choices = array_merge([$options['placeholder'] => ''], $choices);
                $options['choices'] = $choices;
            }

            $choicesOptions['placeholder'] = true;

            if (isset($options['placeholder'])) {
                $choicesOptions['placeholderValue'] = $options['placeholder'];
            }
        }

        $customizeChoicesOptionsEvent = new CustomizeChoicesOptionsEvent($choicesOptions, [], null);
        $customizeChoicesOptionsEvent->setAdjustFilterOptionsEvent(clone $event);
        $this->eventDispatcher->dispatch($customizeChoicesOptionsEvent, CustomizeChoicesOptionsEvent::NAME);

        if ($customizeChoicesOptionsEvent->isChoicesEnabled()) {
            $this->frontendAsset->addFrontendAssets();
        }

        $options['attr']['data-choices'] = (int) $customizeChoicesOptionsEvent->isChoicesEnabled();
        $options['attr']['data-choices-options'] = json_encode($customizeChoicesOptionsEvent->getChoicesOptions());

        $event->setOptions($options);
    }

    public static function getSubscribedEvents(): array
    {
        if (class_exists(AdjustFilterOptionsEvent::class)) {
            return [
                AdjustFilterOptionsEvent::NAME => 'onAdjustFilterOptions',
            ];
        }

        return [];
    }
}
