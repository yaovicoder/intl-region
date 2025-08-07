# Usage Guide

This guide provides comprehensive examples and API documentation for the Ydee IntlRegion library.

## Table of Contents

1. [Basic Usage](#basic-usage)
2. [Symfony Integration](#symfony-integration)
3. [API Reference](#api-reference)
4. [Code Examples](#code-examples)
5. [Error Handling](#error-handling)
6. [CLI Usage](#cli-usage)
7. [Best Practices](#best-practices)

## Basic Usage

### Simple Example

```php
<?php

use Ydee\IntlRegion\RegionProvider;

// Create provider instance
$provider = new RegionProvider();

// Get all available countries (196 sovereign countries)
$allCountries = $provider->getAvailableCountryCodes();
echo "Total countries: " . count($allCountries); // 196

// Get European countries using ISO code
$europeanCountries = $provider->getCountriesByContinent('EUR');
echo "European countries: " . count($europeanCountries); // 45

// Get African countries in French
$africanCountries = $provider->getCountriesByContinent('AFR', 'fr');
// Returns: ['DZ' => 'Algérie', 'EG' => 'Égypte', ...]
```

### Advanced Example

```php
<?php

use Ydee\IntlRegion\RegionProvider;
use Psr\Log\LoggerInterface;

// Create provider with custom locale and logger
$logger = new CustomLogger();
$provider = new RegionProvider('fr', $logger);

// Get continent information
$continentInfo = $provider->getContinentInfo('EUR', 'en');
/*
Returns:
[
    'code' => 'EUR',
    'name' => 'Europe',
    'countries' => ['FR' => 'France', 'DE' => 'Germany', ...]
]
*/

// Check if country exists
if ($provider->hasCountryCode('FR')) {
    echo "France is a sovereign country";
}

// Get continent code for a country
$continentCode = $provider->getContinentCode('FR'); // Returns: '150'
$isoCode = $provider->getContinentCode('FR', true); // Returns: 'EUR'
```

## Symfony Integration

### 1. Bundle Configuration

**config/bundles.php:**
```php
<?php

return [
    // ... other bundles
    Ydee\IntlRegion\IntlRegionBundle::class => ['all' => true],
];
```

**config/packages/ydee_intl_region.yaml:**
```yaml
ydee_intl_region:
    default_locale: 'en'  # Default locale for country names
```

### 2. Controller Example

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
                    $countries[$code] = \Locale::getDisplayRegion('-' . $code, 'en');
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

### 3. Service Usage

```php
<?php

namespace App\Service;

use Ydee\IntlRegion\RegionProvider;

class CountryService
{
    private RegionProvider $regionProvider;

    public function __construct(RegionProvider $regionProvider)
    {
        $this->regionProvider = $regionProvider;
    }

    public function getCountriesByRegion(string $region, string $locale = 'en'): array
    {
        return $this->regionProvider->getCountriesByContinent($region, $locale);
    }

    public function getCountryCount(): int
    {
        return count($this->regionProvider->getAvailableCountryCodes());
    }

    public function isSovereignCountry(string $countryCode): bool
    {
        return $this->regionProvider->hasCountryCode($countryCode);
    }
}
```

## API Reference

### RegionProvider Class

#### Constructor

```php
public function __construct(string $defaultLocale = 'en', ?LoggerInterface $logger = null)
```

**Parameters:**
- `$defaultLocale` (string): Default locale for country names (default: 'en')
- `$logger` (LoggerInterface|null): PSR-3 logger for error handling (optional)

#### Core Methods

##### getCountriesByContinent()

```php
public function getCountriesByContinent(string $continentCode, ?string $locale = null): array
```

Get countries by continent with localized names.

**Parameters:**
- `$continentCode` (string): Continent code (ISO or UN M49)
  - ISO codes: `'EUR'`, `'AFR'`, `'ASI'`, `'AMR'`, `'OCE'`
  - UN M49 codes: `'150'`, `'002'`, `'142'`, `'019'`, `'009'`
- `$locale` (string|null): Locale for country names (optional)

**Returns:** `array<string, string>` Array of country codes mapped to localized names

**Examples:**
```php
// Get European countries in English
$europeanCountries = $provider->getCountriesByContinent('EUR');
// Returns: ['FR' => 'France', 'DE' => 'Germany', 'IT' => 'Italy', ...]

// Get African countries in French
$africanCountries = $provider->getCountriesByContinent('AFR', 'fr');
// Returns: ['DZ' => 'Algérie', 'EG' => 'Égypte', 'MA' => 'Maroc', ...]

// Get Asian countries using UN M49 code
$asianCountries = $provider->getCountriesByContinent('142');
// Returns: ['CN' => 'China', 'JP' => 'Japan', 'IN' => 'India', ...]
```

##### getCountriesBySubregion()

```php
public function getCountriesBySubregion(string $subregionCode, ?string $locale = null): array
```

Get countries by subregion with localized names.

**Parameters:**
- `$subregionCode` (string): Subregion code (UN M49)
- `$locale` (string|null): Locale for country names (optional)

**Returns:** `array<string, string>` Array of country codes mapped to localized names

**Examples:**
```php
// Get Western European countries
$westernEurope = $provider->getCountriesBySubregion('155');
// Returns: ['AT' => 'Austria', 'BE' => 'Belgium', 'FR' => 'France', ...]

// Get Eastern African countries
$easternAfrica = $provider->getCountriesBySubregion('014');
// Returns: ['BI' => 'Burundi', 'KM' => 'Comoros', 'DJ' => 'Djibouti', ...]
```

##### getAvailableCountryCodes()

```php
public function getAvailableCountryCodes(): array
```

Get all available sovereign country codes.

**Returns:** `array<string>` Array of all 196 sovereign country codes

**Example:**
```php
$allCountries = $provider->getAvailableCountryCodes();
// Returns: ['DZ', 'AO', 'BJ', 'BW', 'BF', 'BI', 'CV', 'CM', ...] (196 countries)
```

##### hasCountryCode()

```php
public function hasCountryCode(string $countryCode): bool
```

Check if a country code exists in the mapping.

**Parameters:**
- `$countryCode` (string): ISO 3166-1 alpha-2 country code

**Returns:** `bool` True if the country code exists

**Example:**
```php
if ($provider->hasCountryCode('FR')) {
    echo "France is a sovereign country";
}

if (!$provider->hasCountryCode('XX')) {
    echo "XX is not a valid sovereign country code";
}
```

##### getContinentCode()

```php
public function getContinentCode(string $countryCode, bool $asIsoCode = false): ?string
```

Get the continent code for a country.

**Parameters:**
- `$countryCode` (string): ISO 3166-1 alpha-2 country code
- `$asIsoCode` (bool): Return ISO code instead of UN M49 code (default: false)

**Returns:** `string|null` Continent code or null if not found

**Examples:**
```php
$continentCode = $provider->getContinentCode('FR'); // Returns: '150'
$isoCode = $provider->getContinentCode('FR', true); // Returns: 'EUR'

$continentCode = $provider->getContinentCode('DZ'); // Returns: '002'
$isoCode = $provider->getContinentCode('DZ', true); // Returns: 'AFR'
```

##### getSubregionCode()

```php
public function getSubregionCode(string $countryCode): ?string
```

Get the subregion code for a country.

**Parameters:**
- `$countryCode` (string): ISO 3166-1 alpha-2 country code

**Returns:** `string|null` Subregion code or null if not found

**Example:**
```php
$subregionCode = $provider->getSubregionCode('FR'); // Returns: '155'
$subregionCode = $provider->getSubregionCode('DZ'); // Returns: '015'
```

#### Information Methods

##### getContinentInfo()

```php
public function getContinentInfo(string $continentCode, ?string $locale = null): ?array
```

Get continent information including name and available countries.

**Parameters:**
- `$continentCode` (string): Continent code (ISO or UN M49)
- `$locale` (string|null): Locale for country names (optional)

**Returns:** `array|null` Continent information or null if not found

**Example:**
```php
$info = $provider->getContinentInfo('EUR', 'en');
/*
Returns:
[
    'code' => 'EUR',
    'name' => 'Europe',
    'countries' => ['FR' => 'France', 'DE' => 'Germany', ...]
]
*/
```

##### getSubregionInfo()

```php
public function getSubregionInfo(string $subregionCode, ?string $locale = null): ?array
```

Get subregion information including name and available countries.

**Parameters:**
- `$subregionCode` (string): Subregion code (UN M49)
- `$locale` (string|null): Locale for country names (optional)

**Returns:** `array|null` Subregion information or null if not found

**Example:**
```php
$info = $provider->getSubregionInfo('155', 'en');
/*
Returns:
[
    'code' => '155',
    'name' => 'Western Europe',
    'countries' => ['AT' => 'Austria', 'BE' => 'Belgium', ...]
]
*/
```

#### ISO Code Conversion Methods

##### getIsoContinentCode()

```php
public function getIsoContinentCode(string $m49Code): ?string
```

Convert UN M49 continent code to ISO code.

**Parameters:**
- `$m49Code` (string): UN M49 continent code

**Returns:** `string|null` ISO continent code or null if not found

**Examples:**
```php
$isoCode = $provider->getIsoContinentCode('150'); // Returns: 'EUR'
$isoCode = $provider->getIsoContinentCode('002'); // Returns: 'AFR'
```

##### getM49ContinentCode()

```php
public function getM49ContinentCode(string $isoCode): ?string
```

Convert ISO continent code to UN M49 code.

**Parameters:**
- `$isoCode` (string): ISO continent code

**Returns:** `string|null` UN M49 continent code or null if not found

**Examples:**
```php
$m49Code = $provider->getM49ContinentCode('EUR'); // Returns: '150'
$m49Code = $provider->getM49ContinentCode('AFR'); // Returns: '002'
```

##### getIsoSubregionCode()

```php
public function getIsoSubregionCode(string $m49Code): ?string
```

Convert UN M49 subregion code to ISO code.

**Parameters:**
- `$m49Code` (string): UN M49 subregion code

**Returns:** `string|null` ISO subregion code or null if not found

**Examples:**
```php
$isoCode = $provider->getIsoSubregionCode('155'); // Returns: 'WEU'
$isoCode = $provider->getIsoSubregionCode('015'); // Returns: 'NAF'
```

##### getM49SubregionCode()

```php
public function getM49SubregionCode(string $isoCode): ?string
```

Convert ISO subregion code to UN M49 code.

**Parameters:**
- `$isoCode` (string): ISO subregion code

**Returns:** `string|null` UN M49 subregion code or null if not found

**Examples:**
```php
$m49Code = $provider->getM49SubregionCode('WEU'); // Returns: '155'
$m49Code = $provider->getM49SubregionCode('NAF'); // Returns: '015'
```

#### Available Codes Methods

##### getAvailableContinentCodes()

```php
public function getAvailableContinentCodes(bool $asIsoCodes = false): array
```

Get all available continent codes.

**Parameters:**
- `$asIsoCodes` (bool): Return ISO codes instead of UN M49 codes (default: false)

**Returns:** `array<string>` Array of continent codes

**Examples:**
```php
$m49Codes = $provider->getAvailableContinentCodes();
// Returns: ['002', '019', '142', '150', '009']

$isoCodes = $provider->getAvailableContinentCodes(true);
// Returns: ['AFR', 'AMR', 'ASI', 'EUR', 'OCE']
```

##### getAvailableSubregionCodes()

```php
public function getAvailableSubregionCodes(bool $asIsoCodes = false): array
```

Get all available subregion codes.

**Parameters:**
- `$asIsoCodes` (bool): Return ISO codes instead of UN M49 codes (default: false)

**Returns:** `array<string>` Array of subregion codes

**Examples:**
```php
$m49Codes = $provider->getAvailableSubregionCodes();
// Returns: ['014', '015', '017', '018', '011', ...]

$isoCodes = $provider->getAvailableSubregionCodes(true);
// Returns: ['EAF', 'NAF', 'MAF', 'SAF', 'WAF', ...]
```

## Code Examples

### Complete Working Example

```php
<?php

require_once 'vendor/autoload.php';

use Ydee\IntlRegion\RegionProvider;

// Create provider
$provider = new RegionProvider('en');

// Display all continents
echo "Available Continents:\n";
$continentCodes = $provider->getAvailableContinentCodes(true);
foreach ($continentCodes as $code) {
    $info = $provider->getContinentInfo($code);
    if ($info) {
        echo "- {$code}: {$info['name']} ({$info['count']} countries)\n";
    }
}

echo "\n";

// Display countries by continent
foreach ($continentCodes as $continentCode) {
    $countries = $provider->getCountriesByContinent($continentCode);
    echo "Countries in {$continentCode}:\n";
    
    foreach ($countries as $code => $name) {
        echo "  {$code}: {$name}\n";
    }
    
    echo "\n";
}

// Example API responses
$apiResponses = [
    'all_countries' => [
        'success' => true,
        'data' => $provider->getAvailableCountryCodes(),
        'count' => count($provider->getAvailableCountryCodes())
    ],
    'european_countries' => [
        'success' => true,
        'data' => $provider->getCountriesByContinent('EUR'),
        'count' => count($provider->getCountriesByContinent('EUR'))
    ],
    'african_countries_french' => [
        'success' => true,
        'data' => $provider->getCountriesByContinent('AFR', 'fr'),
        'count' => count($provider->getCountriesByContinent('AFR', 'fr'))
    ]
];

echo "API Response Examples:\n";
echo json_encode($apiResponses, JSON_PRETTY_PRINT);
```

### Error Handling Example

```php
<?php

use Ydee\IntlRegion\RegionProvider;
use Psr\Log\LoggerInterface;

class CustomLogger implements LoggerInterface
{
    public function emergency($message, array $context = []): void
    {
        error_log("EMERGENCY: $message");
    }
    
    public function alert($message, array $context = []): void
    {
        error_log("ALERT: $message");
    }
    
    public function critical($message, array $context = []): void
    {
        error_log("CRITICAL: $message");
    }
    
    public function error($message, array $context = []): void
    {
        error_log("ERROR: $message");
    }
    
    public function warning($message, array $context = []): void
    {
        error_log("WARNING: $message");
    }
    
    public function notice($message, array $context = []): void
    {
        error_log("NOTICE: $message");
    }
    
    public function info($message, array $context = []): void
    {
        error_log("INFO: $message");
    }
    
    public function debug($message, array $context = []): void
    {
        error_log("DEBUG: $message");
    }
    
    public function log($level, $message, array $context = []): void
    {
        error_log("LOG [$level]: $message");
    }
}

// Create provider with custom logger
$logger = new CustomLogger();
$provider = new RegionProvider('en', $logger);

// This will log warnings for missing translations
$countries = $provider->getCountriesByContinent('EUR', 'xx');
```

## CLI Usage

The library includes a Symfony console command for listing regions:

### Basic Commands

```bash
# List all countries
php bin/console intl-region:list

# List European countries
php bin/console intl-region:list continent EUR

# List African countries in French
php bin/console intl-region:list continent AFR --locale=fr

# List Western European countries
php bin/console intl-region:list subregion 155

# List Eastern African countries
php bin/console intl-region:list subregion 014
```

### Output Formats

```bash
# Default table format
php bin/console intl-region:list continent EUR

# JSON format
php bin/console intl-region:list continent EUR --format=json

# CSV format
php bin/console intl-region:list continent EUR --format=csv
```

### Command Options

```bash
# Available options
php bin/console intl-region:list --help

# Options:
#   --locale=LOCALE     Locale for country names [default: "en"]
#   --format=FORMAT     Output format (table, json, csv) [default: "table"]
#   --help, -h          Display help for the given command
```

## Best Practices

### 1. Use ISO Codes for Readability

```php
// Good: Use ISO codes for better readability
$europeanCountries = $provider->getCountriesByContinent('EUR');

// Less readable: Using UN M49 codes
$europeanCountries = $provider->getCountriesByContinent('150');
```

### 2. Handle Missing Translations Gracefully

```php
// The library automatically handles missing translations
$countries = $provider->getCountriesByContinent('EUR', 'xx');
// Falls back to English, then to country code if needed
```

### 3. Validate Input Parameters

```php
public function getCountriesByRegion(string $region, string $locale = 'en'): array
{
    // Validate region parameter
    $validRegions = $provider->getAvailableContinentCodes(true);
    if (!in_array($region, $validRegions)) {
        throw new \InvalidArgumentException("Invalid region: $region");
    }
    
    return $provider->getCountriesByContinent($region, $locale);
}
```

### 4. Cache Results for Performance

```php
class CachedRegionProvider
{
    private RegionProvider $provider;
    private array $cache = [];
    
    public function __construct(RegionProvider $provider)
    {
        $this->provider = $provider;
    }
    
    public function getCountriesByContinent(string $continent, string $locale = 'en'): array
    {
        $key = "{$continent}_{$locale}";
        
        if (!isset($this->cache[$key])) {
            $this->cache[$key] = $this->provider->getCountriesByContinent($continent, $locale);
        }
        
        return $this->cache[$key];
    }
}
```

### 5. Use Type Hints and Return Types

```php
public function getEuropeanCountries(): array
{
    return $this->regionProvider->getCountriesByContinent('EUR');
}

public function isEuropeanCountry(string $countryCode): bool
{
    $continentCode = $this->regionProvider->getContinentCode($countryCode, true);
    return $continentCode === 'EUR';
}
```

## Troubleshooting

### Common Issues

1. **Missing Translations**: The library automatically falls back to English or country code
2. **Invalid Country Codes**: Use `hasCountryCode()` to validate before processing
3. **Invalid Continent Codes**: Use `getAvailableContinentCodes()` to get valid codes
4. **Performance Issues**: Consider caching results for frequently accessed data

### Debug Mode

```php
// Enable debug logging
$logger = new DebugLogger();
$provider = new RegionProvider('en', $logger);

// This will log all operations
$countries = $provider->getCountriesByContinent('EUR', 'fr');
``` 