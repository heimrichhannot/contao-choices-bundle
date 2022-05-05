<?php

/*
 * Copyright (c) 2022 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\ChoicesBundle\Event;

use Contao\DataContainer;
use HeimrichHannot\FilterBundle\Event\AdjustFilterOptionsEvent;
use Symfony\Contracts\EventDispatcher\Event;

class CustomizeChoicesOptionsEvent extends Event
{
    const NAME = 'huh.choices.customize_choices_options';
    /**
     * @var AdjustFilterOptionsEvent
     */
    protected $adjustFilterOptionsEvent = null;
    /**
     * @var array
     */
    private $choicesOptions;
    /**
     * @var array
     */
    private $fieldAttributes;
    /**
     * @var DataContainer|null
     */
    private $dc;
    /**
     * @var bool
     */
    private $enabled = false;

    /**
     * CustomizeChoicesOptionsEvent constructor.
     *
     * @param DataContainer|null $dc
     */
    public function __construct(array $choicesOptions, array $fieldAttributes, $dc)
    {
        $this->choicesOptions = $choicesOptions;
        $this->applyOptions($choicesOptions);

        $this->fieldAttributes = $fieldAttributes;
        $this->dc = $dc;
    }

    public function setAdjustFilterOptionsEvent(AdjustFilterOptionsEvent $adjustFilterOptionsEvent)
    {
        $this->adjustFilterOptionsEvent = $adjustFilterOptionsEvent;
    }

    /**
     * @return AdjustFilterOptionsEvent
     */
    public function getAdjustFilterOptionsEvent(): ?AdjustFilterOptionsEvent
    {
        return $this->adjustFilterOptionsEvent;
    }

    /**
     * Return if field is a filter bundle field or not.
     */
    public function isFilterField(): bool
    {
        return null === $this->adjustFilterOptionsEvent;
    }

    public function getChoicesOptions(): array
    {
        return $this->choicesOptions;
    }

    public function getFieldAttributes(): array
    {
        return $this->fieldAttributes;
    }

    /**
     * Return the optional datacontainer object from the getAttributesFromDca hook.
     *
     * In some legacy code (like ModuleRegistration, as of version 4.4.46), this could also be a module object.
     *
     * @return DataContainer
     */
    public function getDc()
    {
        return $this->dc;
    }

    public function setChoicesOptions(array $choicesOptions): void
    {
        $this->choicesOptions = $choicesOptions;
    }

    public function isChoicesEnabled(): bool
    {
        return $this->enabled;
    }

    public function disableChoices(): void
    {
        $this->enabled = false;
    }

    public function enableChoices(): void
    {
        $this->enabled = true;
    }

    private function applyOptions(array $options): void
    {
        if (isset($options['enable']) && $options['enable']) {
            $this->enableChoices();
        }
    }
}
