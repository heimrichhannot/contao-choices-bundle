<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\ChoicesBundle\Manager;

use Contao\Controller;
use Symfony\Contracts\Translation\TranslatorInterface;

class ChoicesManager
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function getOptionsFromDca(string $table, string $field): array
    {
        Controller::loadDataContainer($table);
        $options = [];
        $dca = &$GLOBALS['TL_DCA'][$table];

        if (isset($dca['fields'][$field]['eval']['choicesOptions']) && \is_array($dca['fields'][$field]['eval']['choicesOptions'])) {
            $options = $dca['fields'][$field]['eval']['choicesOptions'];
        }

        return $options;
    }

    public function getOptionsAsArray(array $customOptions = [], string $table = '', string $field = ''): array
    {
        $options = $this->getDefaultOptions();

        if (!empty($table) && empty($field)) {
            $options = array_merge($options, $this->getOptionsFromDca($table, $field));
        }
        $options = array_merge($options, $customOptions);

        return $options;
    }

    public function getDefaultOptions(): array
    {
        return [
            'loadingText' => $this->translator->trans('MSC.choices\.js.loadingText', [], 'contao_default'),
            'noResultsText' => $this->translator->trans('MSC.choices\.js.noResultsText', [], 'contao_default'),
            'noChoicesText' => $this->translator->trans('MSC.choices\.js.noChoicesText', [], 'contao_default'),
            'itemSelectText' => $this->translator->trans('MSC.choices\.js.itemSelectText', [], 'contao_default'),
            'addItemTextString' => $this->translator->trans('MSC.choices\.js.addItemText', [], 'contao_default'),
            'maxItemTextString' => $this->translator->trans('MSC.choices\.js.maxItemText', [], 'contao_default'),
            'shouldSort' => false,
        ];
    }
}
