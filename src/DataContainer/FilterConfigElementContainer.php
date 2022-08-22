<?php

/*
 * Copyright (c) 2022 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\ChoicesBundle\DataContainer;

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use HeimrichHannot\FilterBundle\Filter\Type\TextConcatType;
use HeimrichHannot\FilterBundle\Filter\Type\TextType;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FilterConfigElementContainer
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function addChoicesFieldToTypePalettes(array &$dca)
    {
        $config = $this->container->getParameter('huh.filter');

        if (!isset($config['filter']['types'])) {
            return;
        }

        $filterType = null;
        $choiceFields = [];

        foreach ($config['filter']['types'] as $type) {
            if ('choice' === $type['type']) {
                $choiceFields[] = $type['name'];
            }
        }

        foreach ($choiceFields as $choiceField) {
            if (empty($dca['palettes'][$choiceField])) {
                continue;
            }

            PaletteManipulator::create()
                ->addField('skipChoicesSupport', 'config_legend', PaletteManipulator::POSITION_APPEND)
                ->applyToPalette($choiceField, 'tl_filter_config_element');
        }

        $fields = [
            TextConcatType::TYPE,
            TextType::TYPE,
        ];

        foreach ($fields as $field) {
            if (empty($dca['palettes'][$field])) {
                continue;
            }

            PaletteManipulator::create()
                ->addField('addChoicesSupport', 'config_legend', PaletteManipulator::POSITION_APPEND)
                ->applyToPalette($field, 'tl_filter_config_element');
        }
    }
}
