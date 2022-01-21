# Changelog

All notable changes to this project will be documented in this file.

## [1.2.0] - 2022-01-21
- Changed: added support for formhybrid_ajax_complete event of formhybrid-compatibility-bundle

## [1.1.1] - 2021-12-20
- Fixed: assets not automatically added on dca usage
- Fixed: assets added to early in on filter bundle usage

## [1.1.0] - 2021-12-02
- Changed: show placeholder for multiple select filter fields
- Changed: enhances readme

## [1.0.0] - 2021-08-05
- Changed: refactored FrontendAsset
- Removed: npm-package
- Removed: deprecated huh.choices.manager.choices_manager service alias
- Fixed: added missing symfony/dependency-injection dependency

## [0.4.9] - 2021-08-05
- Changed: optimized service definitions
- Changed: refactored AdjustFilterOptionsEventListener due coding standards
- Changed: increased Utils Bundles dependency
- Fixed: choices always disabled on filter bundle fields

## [0.4.8] - 2021-07-21
- fixed UndefinedMethodError in class AdjustFilterOptionsEventListener

## [0.4.7] - 2021-07-21

- added option do disable each field with dca configuration(#1)

## [0.4.6] - 2021-06-08

- fixed type hint

## [0.4.5] - 2021-04-08

- added support for formhybrid formhybrid_ajax_complete event
- added contao 4.9 rootfallback palette support
- refactored FrontendAsset to use modern coding standards
- fixed encore bundle versions lower than 1.5 allowed

## [0.4.4] - 2020-08-27

- fixed customization of choices-options

## [0.4.3] - 2020-08-27

- added js event listener for `formhybridToggleSubpaletteComplete`

## [0.4.2] - 2020-08-24

- updated encore bundle integration

## [0.4.1] - 2020-01-29

- fixed type errors if getAttributesFromDca hook is not called with an DataContainer or null (like in
  ModuleRegistration)

## [0.4.0] - 2020-01-23

- updated library to version 9.0.1
- library now bundle with this bundle instead of loading an external library
- added two javascript events
- renamed asset entries

## [0.3.0] - 2020-01-21

- added CustomizeChoicesOptionsEvent
- updated encore bundle config for encore bundle >= 1.5

## [0.2.2] - 2019-11-08

- fixed current page weren't taken in account when checking if choices is activated for current page

## [0.2.1] - 2019-11-04

- fixed copy and paste error :)

## [0.2.0] - 2019-11-04

- assets now only added when needed
- moved filter bundle specific dca code to loadDataContainer hook
- updated english translations
- some refactoring due internal coding standards and symfony 4 preparation
- fixed install error

## [0.1.4] - 2019-06-20

- fixed event calling also in generated legacy code

## [0.1.3] - 2019-06-03

- fixed check if objPage is not null when trying to get properties from page

## [0.1.2] - 2019-04-03

- fixed filter bundle support
- fixed missing service

## [0.1.1] - 2019-04-03

- updated dependencies
- updated legacy assets
- updated README

## [0.1.0] - 2019-04-03

- initial Version
