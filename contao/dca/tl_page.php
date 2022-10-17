<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2019 Heimrich & Hannot GmbH
 *
 * @author  Thomas Körner <t.koerner@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

$table = 'tl_page';
$dca = &$GLOBALS['TL_DCA'][$table];

$dca['palettes']['root'] = str_replace('includeLayout', 'includeLayout,useChoicesForSelect,useChoicesForText', $dca['palettes']['root']);
$dca['palettes']['rootfallback'] = str_replace('includeLayout', 'includeLayout,useChoicesForSelect,useChoicesForText', $dca['palettes']['rootfallback']);
$dca['palettes']['regular'] = str_replace('includeLayout', 'includeLayout,overrideUseChoicesForSelect,overrideUseChoicesForText', $dca['palettes']['regular']);

$dca['fields']['useChoicesForSelect'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_page']['useChoicesForSelect'],
    'exclude'   => true,
    'inputType' => 'checkbox',
    'eval'      => ['tl_class' => 'w50'],
    'sql'       => "char(1) NOT NULL default ''",
];
$dca['fields']['useChoicesForText'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_page']['useChoicesForText'],
    'exclude'   => true,
    'inputType' => 'checkbox',
    'eval'      => ['tl_class' => 'w50'],
    'sql'       => "char(1) NOT NULL default ''",
];

\Contao\System::getContainer()->get('huh.utils.dca')->addOverridableFields(
    ['useChoicesForSelect', 'useChoicesForText'],
    $table,
    $table
);