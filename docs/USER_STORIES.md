# 📋 User Stories: Ydee Intl Region v1.0.0

This document contains user stories (tickets) for the initial release, following the ITR-000X format.

---

## ITR-0001: UN M49 Country → Continent Mapping
**As a** developer
**I want** a static mapping of ISO 3166-1 country codes to UN M49 continent codes
**So that** I can filter countries by continent without manual mapping.

---

## ITR-0002: Subregion Mapping
**As a** developer
**I want** a static mapping of country codes to UN M49 subregion codes
**So that** I can filter countries by subregion.

---

## ITR-0003: RegionProvider Core Class
**As a** developer
**I want** a `RegionProvider` class with methods to get countries by continent, subregion, and to list available codes
**So that** I can use a simple API for region-based country filtering.

---

## ITR-0004: Localized Country Names
**As a** user
**I want** country names to be localized using `symfony/intl`
**So that** I see country names in my preferred language.

---

## ITR-0005: Symfony Bundle Integration
**As a** Symfony developer
**I want** a bundle that auto-wires the `RegionProvider` and allows configuration of a default locale
**So that** I can use the service easily in my Symfony app.

---

## ITR-0006: Symfony Console Command
**As a** CLI user
**I want** a command `intl-region:list` to list countries by region from the command line
**So that** I can access region data without writing code.

---

## ITR-0007: Unit Tests
**As a** maintainer
**I want** comprehensive PHPUnit tests for all mappings, API methods, and CLI output
**So that** I can ensure the package works as expected.

---

## ITR-0008: Composer and Documentation
**As a** user
**I want** a `composer.json`, `README.md`, and license file
**So that** I can install, understand, and use the package easily.

---

## ITR-0009: Release v1.0.0
**As a** maintainer
**I want** the package tagged as v1.0.0 and published to Packagist
**So that** it is available for public use. 