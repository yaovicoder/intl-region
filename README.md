# Ydee IntlRegion

A PHP library for filtering localized country lists by UN M49 continent or subregion codes, providing **196 sovereign countries only** with comprehensive internationalization support.

## Features

- 🌍 **196 Sovereign Countries** - Clean data with no territories or dependencies
- 🌐 **Full Internationalization** - Country names in any locale via Symfony Intl
- 🗺️ **UN M49 Standard** - Official United Nations geographical codes
- 🔄 **ISO Code Support** - Use ISO codes (EUR, AFR, ASI) or UN M49 codes (150, 002, 142)
- 📦 **Symfony Bundle** - Easy integration with Symfony applications
- 🖥️ **CLI Command** - Command-line interface for listing regions
- ✅ **Comprehensive Testing** - 100% test coverage with validation against UN data

## Installation

### Via Composer

```bash
composer require yaovicoder/intl-region
```

### Manual Installation

1. Clone the repository:
```bash
git clone https://github.com/yaovicoder/intl-region.git
cd intl-region
```

2. Install dependencies:
```bash
composer install
```

## Quick Start

### Basic Usage

```php
<?php

use Ydee\IntlRegion\RegionProvider;

// Create provider instance
$provider = new RegionProvider();

// Get all available countries (196 sovereign countries)
$allCountries = $provider->getAvailableCountryCodes();

// Get European countries using ISO code
$europeanCountries = $provider->getCountriesByContinent('EUR');

// Get African countries in French
$africanCountries = $provider->getCountriesByContinent('AFR', 'fr');

// Get Asian countries using UN M49 code
$asianCountries = $provider->getCountriesByContinent('142');
```

### Symfony Integration

1. **Enable the bundle** in your `config/bundles.php`:

```php
<?php

return [
    // ... other bundles
    Ydee\IntlRegion\IntlRegionBundle::class => ['all' => true],
];
```

2. **Configure the bundle** in `config/packages/ydee_intl_region.yaml`:

```yaml
ydee_intl_region:
    default_locale: 'en'  # Default locale for country names
```

3. **Use in your controllers**:

```php
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Ydee\IntlRegion\RegionProvider;

#[Route('/api/regions')]
class RegionController extends AbstractController
{
    private RegionProvider $regionProvider;

    public function __construct(RegionProvider $regionProvider)
    {
        $this->regionProvider = $regionProvider;
    }

    #[Route('/countries', name: 'app_regions_countries', methods: ['GET'])]
    public function getAllCountries(): JsonResponse
    {
        $countryCodes = $this->regionProvider->getAvailableCountryCodes();
        $countries = [];
        
        foreach ($countryCodes as $code) {
            $countries[$code] = \Locale::getDisplayRegion('-' . $code, 'en');
        }
        
        return $this->json([
            'success' => true,
            'data' => $countries,
            'count' => count($countries)
        ]);
    }

    #[Route('/countries/europe', name: 'app_regions_european_countries', methods: ['GET'])]
    public function getEuropeanCountries(): JsonResponse
    {
        $europeanCountries = $this->regionProvider->getCountriesByContinent('EUR');
        
        return $this->json([
            'success' => true,
            'data' => $europeanCountries,
            'count' => count($europeanCountries)
        ]);
    }

    #[Route('/countries/america/french', name: 'app_regions_american_countries_french', methods: ['GET'])]
    public function getAmericanCountriesInFrench(): JsonResponse
    {
        $americanCountries = $this->regionProvider->getCountriesByContinent('AMR', 'fr');
        
        return $this->json([
            'success' => true,
            'data' => $americanCountries,
            'count' => count($americanCountries)
        ]);
    }

    #[Route('/filter', name: 'app_regions_filter', methods: ['GET'])]
    public function getCountries(Request $request): JsonResponse
    {
        $continent = $request->query->get('continent'); // e.g. "EUR", "AFR"
        $lang = $request->query->get('lang', 'en'); // default language

        try {
            if ($continent) {
                $countries = $this->regionProvider->getCountriesByContinent($continent, $lang);
            } else {
                $countryCodes = $this->regionProvider->getAvailableCountryCodes();
                $countries = [];
                
                foreach ($countryCodes as $code) {
                    $countries[$code] = \Locale::getDisplayRegion('-' . $code, $lang);
                }
            }
        } catch (\InvalidArgumentException $e) {
            return $this->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        return $this->json([
            'success' => true,
            'data' => $countries,
            'count' => count($countries),
        ]);
    }
}
```

## API Reference

### RegionProvider Class

#### Constructor

```php
public function __construct(string $defaultLocale = 'en', ?LoggerInterface $logger = null)
```

- `$defaultLocale`: Default locale for country names (default: 'en')
- `$logger`: PSR-3 logger for error handling (optional)

#### Core Methods

##### Get Countries by Continent

```php
public function getCountriesByContinent(string $continentCode, ?string $locale = null): array
```

**Parameters:**
- `$continentCode`: Continent code (ISO or UN M49)
  - ISO codes: `'EUR'`, `'AFR'`, `'ASI'`, `'AMR'`, `'OCE'`
  - UN M49 codes: `'150'`, `'002'`, `'142'`, `'019'`, `'009'`
- `$locale`: Locale for country names (optional, uses default if not provided)

**Returns:** Array of country codes mapped to localized names

**Examples:**
```php
// Get European countries in English
$europeanCountries = $provider->getCountriesByContinent('EUR');
// Returns: ['FR' => 'France', 'DE' => 'Germany', ...]

// Get African countries in French
$africanCountries = $provider->getCountriesByContinent('AFR', 'fr');
// Returns: ['DZ' => 'Algérie', 'EG' => 'Égypte', ...]

// Get Asian countries using UN M49 code
$asianCountries = $provider->getCountriesByContinent('142');
// Returns: ['CN' => 'China', 'JP' => 'Japan', ...]
```

##### Get Countries by Subregion

```php
public function getCountriesBySubregion(string $subregionCode, ?string $locale = null): array
```

**Parameters:**
- `$subregionCode`: Subregion code (UN M49)
- `$locale`: Locale for country names (optional)

**Examples:**
```php
// Get Western European countries
$westernEurope = $provider->getCountriesBySubregion('155');

// Get Eastern African countries
$easternAfrica = $provider->getCountriesBySubregion('014');
```

##### Get All Available Countries

```php
public function getAvailableCountryCodes(): array
```

**Returns:** Array of all 196 sovereign country codes

**Example:**
```php
$allCountries = $provider->getAvailableCountryCodes();
// Returns: ['DZ', 'AO', 'BJ', 'BW', ...] (196 countries)
```

##### Check Country Existence

```php
public function hasCountryCode(string $countryCode): bool
```

**Example:**
```php
if ($provider->hasCountryCode('FR')) {
    echo "France is a sovereign country";
}
```

##### Get Continent Code for Country

```php
public function getContinentCode(string $countryCode, bool $asIsoCode = false): ?string
```

**Parameters:**
- `$countryCode`: ISO 3166-1 alpha-2 country code
- `$asIsoCode`: Return ISO code instead of UN M49 code (default: false)

**Examples:**
```php
$continentCode = $provider->getContinentCode('FR'); // Returns: '150'
$isoCode = $provider->getContinentCode('FR', true); // Returns: 'EUR'
```

#### ISO Code Conversion Methods

```php
// Convert UN M49 to ISO codes
$isoCode = $provider->getIsoContinentCode('150'); // Returns: 'EUR'
$isoCode = $provider->getIsoSubregionCode('155'); // Returns: 'WEU'

// Convert ISO to UN M49 codes
$m49Code = $provider->getM49ContinentCode('EUR'); // Returns: '150'
$m49Code = $provider->getM49SubregionCode('WEU'); // Returns: '155'
```

#### Available Codes Methods

```php
// Get all available continent codes
$continentCodes = $provider->getAvailableContinentCodes(); // UN M49 codes
$isoContinentCodes = $provider->getAvailableContinentCodes(true); // ISO codes

// Get all available subregion codes
$subregionCodes = $provider->getAvailableSubregionCodes(); // UN M49 codes
$isoSubregionCodes = $provider->getAvailableSubregionCodes(true); // ISO codes
```

## Supported Codes

### Continent Codes

| ISO Code | UN M49 Code | Name |
|----------|-------------|------|
| AFR | 002 | Africa |
| AMR | 019 | Americas |
| ASI | 142 | Asia |
| EUR | 150 | Europe |
| OCE | 009 | Oceania |

### Subregion Codes

| UN M49 Code | ISO Code | Name |
|-------------|----------|------|
| 014 | EAF | Eastern Africa |
| 015 | NAF | Northern Africa |
| 017 | MAF | Middle Africa |
| 018 | SAF | Southern Africa |
| 011 | WAF | Western Africa |
| 021 | NAM | Northern America |
| 013 | CAM | Central America |
| 029 | CAR | Caribbean |
| 005 | SAM | South America |
| 030 | EAS | Eastern Asia |
| 034 | SAS | Southern Asia |
| 035 | SEA | South-Eastern Asia |
| 143 | CAS | Central Asia |
| 145 | WAS | Western Asia |
| 151 | EEU | Eastern Europe |
| 154 | NEU | Northern Europe |
| 039 | SEU | Southern Europe |
| 155 | WEU | Western Europe |
| 053 | ANZ | Australia and New Zealand |
| 054 | MEL | Melanesia |
| 057 | MIC | Micronesia |
| 061 | POL | Polynesia |

## Country Counts

| Continent | Countries |
|-----------|-----------|
| Africa | 54 |
| Asia | 48 |
| Europe | 45 |
| North America | 23 |
| South America | 12 |
| Oceania | 14 |
| **Total** | **196** |

## CLI Usage

The library includes a Symfony console command for listing regions:

```bash
# List all countries
php bin/console intl-region:list

# List European countries
php bin/console intl-region:list continent EUR

# List African countries in French
php bin/console intl-region:list continent AFR --locale=fr

# List Western European countries
php bin/console intl-region:list subregion 155

# Output as JSON
php bin/console intl-region:list continent EUR --format=json

# Output as CSV
php bin/console intl-region:list continent EUR --format=csv
```

## Error Handling

The library provides graceful error handling with logging:

```php
use Psr\Log\LoggerInterface;

// Create provider with custom logger
use Psr\Log\NullLogger;

// Create provider with custom logger
$logger = new NullLogger();
$provider = new RegionProvider('en', $logger);

// Missing translations are logged and fallback to English or country code
$countries = $provider->getCountriesByContinent('EUR', 'xx');
// Logs warning for missing translations, falls back gracefully
```

## Testing

Run the test suite:

```bash
# Run all tests
vendor/bin/phpunit

# Run with coverage
vendor/bin/phpunit --coverage-html coverage/

# Run specific test
vendor/bin/phpunit tests/RegionProviderTest.php
```

## Data Validation

The library includes validation against official UN M49 data:

```bash
# Download and validate against UN data
php scripts/download-un-m49-data.php
vendor/bin/phpunit tests/UNM49DataValidationTest.php
```

## Contributing

Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct and the process for submitting pull requests.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for a list of changes and version history.

## Data Sources

- [UN M49 Standard](https://unstats.un.org/unsd/methodology/m49/): Official UN geographic codes
- [Symfony Intl](https://github.com/symfony/intl): Localization and country data