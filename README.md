# Ydee Intl Region

A PHP library for filtering localized country lists by UN M49 continent or subregion codes, built on top of `symfony/intl`.

## Features

- **UN M49 Standard Compliance**: Uses official UN M49 codes for continents and subregions
- **ISO Continent Code Support**: Also supports ISO continent codes (AFR, EUR, AMR, ASI, OCE)
- **Localization Support**: Integrates with `symfony/intl` for localized country names
- **Error Handling**: Graceful handling of missing translations with logging
- **Symfony Bundle**: Easy integration with Symfony applications
- **CLI Command**: Command-line tool for listing countries by region
- **JSON-based Mappings**: Maintainable data structure using JSON files
- **Data Validation**: Scripts to validate mappings against official UN data

## Installation

```bash
composer require yaovicoder/intl-region
```

## Quick Start

### Basic Usage

```php
use Ydee\IntlRegion\RegionProvider;

$provider = new RegionProvider('en');

// Get all countries in Africa (using UN M49 code)
$africanCountries = $provider->getCountriesByContinent('002');

// Get all countries in Africa (using ISO code)
$africanCountries = $provider->getCountriesByContinent('AFR');

// Get all countries in Europe with French names
$europeanCountries = $provider->getCountriesByContinent('EUR', 'fr');

// Get all countries in Eastern Africa (using UN M49 code)
$easternAfricanCountries = $provider->getCountriesBySubregion('014');

// Get all countries in Western Europe (using ISO code)
$westernEuropeanCountries = $provider->getCountriesBySubregion('WEU');

// Get all countries in North America (using ISO code)
$northAmericanCountries = $provider->getCountriesBySubregion('NAM');

// Get continent information
$africaInfo = $provider->getContinentInfo('AFR');
```

### Symfony Integration

```yaml
# config/packages/ydee_intl_region.yaml
ydee_intl_region:
    default_locale: 'en'
```

```php
// In your service
public function __construct(
    private RegionProvider $regionProvider
) {}

public function getAfricanCountries(): array
{
    return $this->regionProvider->getCountriesByContinent('002');
}
```

### CLI Usage

```bash
# List all countries in Africa (using UN M49 code)
php bin/console intl-region:list continent 002

# List all countries in Europe (using ISO code)
php bin/console intl-region:list continent EUR

# List all countries in Eastern Africa with French names
php bin/console intl-region:list subregion 014 --locale=fr

# Export as JSON
php bin/console intl-region:list continent 002 --format=json

# Export as CSV
php bin/console intl-region:list continent 002 --format=csv
```

### Error Handling and Logging

The library includes robust error handling for missing translations and obsolete country codes:

```php
use Psr\Log\LoggerInterface;
use Ydee\IntlRegion\RegionProvider;

// With custom logger
$logger = $this->get(LoggerInterface::class);
$provider = new RegionProvider('en', $logger);

// Missing translations are logged and fallback to English or country code
$countries = $provider->getCountriesByContinent('EUR', 'fr');
// If a country has no French translation, it falls back to English
// If no English translation exists, it uses the country code
// All issues are logged for monitoring
```

**Supported Continent Codes:**

| UN M49 Code | ISO Code | Continent |
|-------------|----------|-----------|
| 002         | AFR      | Africa    |
| 019         | AMR      | Americas  |
| 142         | ASI      | Asia      |
| 150         | EUR      | Europe    |
| 009         | OCE      | Oceania   |
| 010         | ANT      | Antarctica|

**Supported Subregion Codes (Sample):**

| UN M49 Code | ISO Code | Subregion |
|-------------|----------|-----------|
| 155         | WEU      | Western Europe |
| 021         | NAM      | Northern America |
| 014         | EAF      | Eastern Africa |
| 029         | CAR      | Caribbean |
| 053         | ANZ      | Australia and New Zealand |

## Data Management

This package uses JSON mapping files to store continent and subregion data:

- `data/mapping/continent.json` - Maps ISO 3166-1 alpha-2 country codes to UN M49 continent codes
- `data/mapping/subregion.json` - Maps ISO 3166-1 alpha-2 country codes to UN M49 subregion codes

### Data Validation

The package includes automated validation to ensure our local mappings are complete and accurate compared to official UN M49 data:

#### 1. Download UN Data
First, download the latest UN M49 data:

```bash
php scripts/download-un-m49-data.php
```

This script:
- Downloads data from the official UN M49 website
- Saves raw HTML to `tests/_output/un-m49-overview.html`
- Extracts and saves structured data to `tests/_output/un-m49-data.json`
- Provides a summary of extracted data

#### 2. Run Validation Tests
Then run the validation tests:

```bash
vendor/bin/phpunit tests/UNM49DataValidationTest.php
```

The validation tests:
- Load the downloaded UN data from `tests/_output/`
- Compare it with our local JSON mappings
- Report any missing countries or regions
- Ensure data accuracy and completeness

### Data Sources

The mapping data is based on the official UN M49 standard for geographic regions. The validation compares against data from:
- [UN M49 Overview](https://unstats.un.org/unsd/methodology/m49/overview/)

### Updating Data

To update the mapping data:

1. Run the download script to get latest UN data: `php scripts/download-un-m49-data.php`
2. Run the update script to add missing countries: `php scripts/update-mappings.php`
3. Run the validation tests to ensure completeness: `vendor/bin/phpunit tests/UNM49DataValidationTest.php`
4. Run the full test suite to verify functionality: `vendor/bin/phpunit`

## UN M49 Codes

### Continents
- `002`: Africa
- `019`: Americas
- `142`: Asia
- `150`: Europe
- `009`: Oceania

### Subregions (Examples)
- `014`: Eastern Africa
- `017`: Middle Africa
- `015`: Northern Africa
- `018`: Southern Africa
- `011`: Western Africa
- `005`: South America
- `013`: Central America
- `021`: Northern America
- `029`: Caribbean

For a complete list, see the [UN M49 Standard](https://unstats.un.org/unsd/methodology/m49/).

## API Reference

### RegionProvider

#### Methods

- `getCountriesByContinent(string $continentCode, ?string $locale = null): array`
- `getCountriesBySubregion(string $subregionCode, ?string $locale = null): array`
- `getContinentInfo(string $continentCode, ?string $locale = null): ?array`
- `getSubregionInfo(string $subregionCode, ?string $locale = null): ?array`
- `getAvailableContinentCodes(): array`
- `getAvailableSubregionCodes(): array`
- `getAvailableCountryCodes(): array`
- `hasCountryCode(string $countryCode): bool`
- `hasContinentCode(string $continentCode): bool`
- `hasSubregionCode(string $subregionCode): bool`

### Mapping Classes

- `ContinentMapping`: Static methods for continent operations
- `SubregionMapping`: Static methods for subregion operations

## Testing

```bash
# Run all tests
vendor/bin/phpunit

# Run with coverage
vendor/bin/phpunit --coverage-html coverage/
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Run the test suite
6. Submit a pull request

## License

This library is open-sourced software licensed under the [MIT license](LICENSE).

## Data Sources

- [UN M49 Standard](https://unstats.un.org/unsd/methodology/m49/): Official UN geographic codes
- [Symfony Intl](https://github.com/symfony/intl): Localization and country data
