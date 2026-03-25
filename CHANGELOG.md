# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- icon for the dashboard link in the landing page when logged in
- rasa chat widget

### Changed

-  

### Removed

- 


## [1.1.2] - 2025-12-15

### Added

- 

### Changed

-  landing page navigation renders link to dashboard instead of Login link if user is logged in
-  formatted some pages as per vue standard

### Removed

- 

## [1.0.2] - 2025-12-11

### Added

-  

### Fixed

-  jsPermissions issue that casued a syntax error when user object is not found 

### Changed

-  filter stores used by sentiments dashboard to have default dates if not provided by user
-  temporarily disabled strict mode on mysql to allow certain quaries in the sentiments dashboard to run
-  sentiments dashboard to only load unique sentiments instead of all of them 
-  load all sentiments records if the date range is empty

### Removed

-  

## [1.0.1] - 2025-12-04

### Added

- CHANGELOG.md to keep track of changes learn more here [Keep a Changelog](https://keepachangelog.com/en/1.1.0/)
- added a new favicon

### Fixed

-  Added conditional render for senTalk like icon for null values
-  Improved error handling and messages for the printReport function in sentiments and predictive dashboards
-  Corrected spelling mistakes on the sentiment dashboard, capitilisation and social links on the landing page 

### Changed

-  Downgraded vite from "^7.1.12" to "^4.0.0"
-  Downgraded @vitejs/plugin-vue from "^6.0.1" to "^4.0.0"
-  Downgraded laravel-vite-plugin from "^2.0.1" to "^0.7.2"
-  Downgraded tailwindcss from "^4.1.16" to "^3.4.18"
-  Downgraded jsvectormap from "^1.7.0" to "1.5.3"
-  Downgraded vuevectormap from "^2.1.1" to "2.0.1"
-  Downgraded @vuepic/vue-datepicker "^12.0.0" to "^6.0.2"
-  Undid vite.config.js fixes for vuevectormap:2.1.1 and jsvectormap:1.7.0 errors
-  Downgraded puppeteer from "24.28.0" to "^17.1.3" to fix the "could not find Chrome error" when generating pdf report in production

### Removed

- @tailwindcss/postcss not needed in tailwindcss@3


