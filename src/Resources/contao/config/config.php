<?php

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['getAttributesFromDca']['choices'] = ['huh.choices.event_listener.hook_listener', 'getAttributesFromDca'];

/**
 * JS
 */
if (System::getContainer()->get('huh.utils.container')->isFrontend()) {
    $GLOBALS['TL_JAVASCRIPT']['huh_components_choices'] = 'assets/Choices/dist/public/assets/scripts/choices.min.js|static';
    $GLOBALS['TL_JAVASCRIPT']['contao-choices-bundle']  = 'bundles/heimrichhannotcontaochoices/js/contao-choices-bundle.js|static';
}

/**
 * CSS
 */
if (System::getContainer()->get('huh.utils.container')->isFrontend()) {
    $GLOBALS['TL_CSS']['huh_components_choices'] = 'assets/Choices/dist/public/assets/styles/choices.min.css|static';
}