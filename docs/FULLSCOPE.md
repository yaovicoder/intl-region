# 📄 Full Scope: Ydee Intl Region

This file defines exactly **what this package delivers** for v1.0.0.

---

## ✅ Objective

A PHP library to:
- Filter countries by continent or subregion.
- Localize names using `symfony/intl`.
- Expose a Symfony-friendly service.
- Provide a simple CLI tool.
- **NEW**: Support only sovereign countries (196 total).
- **NEW**: Support ISO codes for easy integration.
- **NEW**: Provide comprehensive error handling and logging.

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
| **NEW**: JSON-based mapping system | ✅ Required |
| **NEW**: ISO code support | ✅ Required |
| **NEW**: Error handling with PSR-3 logging | ✅ Required |
| **NEW**: UN M49 data validation | ✅ Required |
| **NEW**: Comprehensive documentation | ✅ Required |

---

## ✅ Core API

### `RegionProvider`

| Method | Description |
|--------|--------------|
| `getCountriesByContinent(string $continentCode, ?string $locale = null): array` | Return countries by continent |
| `getCountriesBySubregion(string $subregionCode, ?string $locale = null): array` | Return countries by subregion |
| `getAvailableContinentCodes(bool $asIsoCodes = false): array` | List all continent codes |
| `getAvailableSubregionCodes(bool $asIsoCodes = false): array` | List all subregion codes |
| `getAvailableCountryCodes(): array` | List all 196 sovereign country codes |
| `hasCountryCode(string $countryCode): bool` | Check if country exists |
| `getContinentCode(string $countryCode, bool $asIsoCode = false): ?string` | Get continent for country |
| `getSubregionCode(string $countryCode): ?string` | Get subregion for country |
| `getContinentInfo(string $continentCode, ?string $locale = null): ?array` | Get continent information |
| `getSubregionInfo(string $subregionCode, ?string $locale = null): ?array` | Get subregion information |
| `getIsoContinentCode(string $m49Code): ?string` | Convert M49 to ISO |
| `getM49ContinentCode(string $isoCode): ?string` | Convert ISO to M49 |
| `getIsoSubregionCode(string $m49Code): ?string` | Convert M49 to ISO |
| `getM49SubregionCode(string $isoCode): ?string` | Convert ISO to M49 |

---

## ✅ Symfony Integration

- YAML config for default locale.
- Auto-wired `RegionProvider` service.
- CLI command:
  ```bash
  php bin/console intl-region:list continent EUR --locale=fr
  php bin/console intl-region:list subregion 155 --format=json
  ```

---

## ✅ Tests

- Validate mappings (68 tests, 100% coverage).
- Validate country ISO codes exist in `symfony/intl`.
- Validate locale fallback.
- Validate Symfony CLI output.
- **NEW**: Validate against UN M49 official data.
- **NEW**: Validate only sovereign countries.

---

## ✅ License

MIT License.

---

## ✅ Constraints

- PHP >= 8.1.
- Symfony 6.x or 7.x.
- PSR-4 autoload: `Ydee\IntlRegion`.
- **NEW**: Only sovereign countries (196 total).
- **NEW**: No territories or dependencies.

---

## ✅ Data Accuracy

- **196 sovereign countries** only
- **UN M49 standard** compliance
- **ISO code support** (EUR, AFR, ASI, AMR, OCE)
- **Full internationalization** via Symfony Intl
- **Graceful error handling** with logging

---

## ✅ Success = This scope is fully implemented and tagged v1.0.0.

**Status: ✅ COMPLETED - Version 1.0.0 is production-ready!**
