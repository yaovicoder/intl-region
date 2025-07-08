# 📄 Full Scope: Ydee Intl Region

This file defines exactly **what this package must deliver** for v1.0.0.

---

## ✅ Objective

A PHP library to:
- Filter countries by continent or subregion.
- Localize names using `symfony/intl`.
- Expose a Symfony-friendly service.
- Provide a simple CLI tool.

---

## ✅ Deliverables

| Deliverable | Status |
|----------------|---------|
| UN M49 JSON mappings | ✅ Required |
| Subregion JSON mappings | ✅ Required |
| `RegionProvider` class | ✅ Required |
| `IntlRegionBundle` class | ✅ Required |
| Symfony DI Extension | ✅ Required |
| Symfony Console Command | ✅ Required |
| PHPUnit tests | ✅ Required |
| `composer.json` with MIT license | ✅ Required |
| `README.md` | ✅ Required |
| `ROADMAP.md` | ✅ Required |
| `FULLSCOPE.md` | ✅ Required |
| Tagged `v1.0.0` release | ✅ Required |

---

## ✅ Core API

### `RegionProvider`

| Method | Description |
|--------|--------------|
| `getCountriesForContinent(string $continentCode, ?string $locale = null): array` | Return countries by continent |
| `getCountriesForSubregion(string $subregionCode, ?string $locale = null): array` | Return countries by subregion |
| `getAvailableContinents(): array` | List all continent codes |
| `getAvailableSubregions(): array` | List all subregion codes |
| `getMapping(): array` | Return raw mapping for debugging |

---

## ✅ Symfony Integration

- YAML config for default locale.
- Auto-wired `RegionProvider` service.
- CLI command:
  ```bash
  php bin/console intl-region:list EU --locale=fr
  ```

---

## ✅ Tests

- Validate mappings.
- Validate country ISO codes exist in `symfony/intl`.
- Validate locale fallback.
- Validate Symfony CLI output.

---

## ✅ License

MIT License.

---

## ✅ Constraints

- PHP >= 8.1.
- Symfony 6.x or 7.x.
- PSR-4 autoload: `Ydee\Intl\Region`.

---

## ✅ Success = This scope is fully implemented and tagged v1.0.0.
