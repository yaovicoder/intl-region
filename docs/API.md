# API Reference

Complete API documentation for the Ydee IntlRegion library.

## Table of Contents

1. [RegionProvider Class](#regionprovider-class)
2. [Constructor](#constructor)
3. [Core Methods](#core-methods)
4. [Information Methods](#information-methods)
5. [ISO Code Conversion](#iso-code-conversion)
6. [Available Codes Methods](#available-codes-methods)
7. [Utility Methods](#utility-methods)
8. [Data Structures](#data-structures)
9. [Error Handling](#error-handling)

## RegionProvider Class

The main class for interacting with the IntlRegion library.

```php
use Ydee\IntlRegion\RegionProvider;

$provider = new RegionProvider();
```

## Constructor

```php
public function __construct(string $defaultLocale = 'en', ?LoggerInterface $logger = null)
```

**Parameters:**
- `$defaultLocale` (string): Default locale for country names (default: 'en')
- `$logger` (LoggerInterface|null): PSR-3 logger for error handling (optional)

**Example:**
```php
use Ydee\IntlRegion\RegionProvider;
use Psr\Log\LoggerInterface;

// Basic usage
$provider = new RegionProvider();

// With custom locale
$provider = new RegionProvider('fr');

// With logger
$logger = new CustomLogger();
$provider = new RegionProvider('en', $logger);
```

## Core Methods

### getCountriesByContinent()

Get countries by continent with localized names.

```php
public function getCountriesByContinent(string $continentCode, ?string $locale = null): array
```

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

// Get American countries in Spanish
$americanCountries = $provider->getCountriesByContinent('AMR', 'es');
// Returns: ['US' => 'Estados Unidos', 'CA' => 'Canadá', 'MX' => 'México', ...]
```

### getCountriesBySubregion()

Get countries by subregion with localized names.

```php
public function getCountriesBySubregion(string $subregionCode, ?string $locale = null): array
```

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

// Get Northern African countries in Arabic
$northernAfrica = $provider->getCountriesBySubregion('015', 'ar');
// Returns: ['DZ' => 'الجزائر', 'EG' => 'مصر', 'LY' => 'ليبيا', ...]
```

### getAvailableCountryCodes()

Get all available sovereign country codes.

```php
public function getAvailableCountryCodes(): array
```

**Returns:** `array<string>` Array of all 196 sovereign country codes

**Example:**
```php
$allCountries = $provider->getAvailableCountryCodes();
// Returns: ['DZ', 'AO', 'BJ', 'BW', 'BF', 'BI', 'CV', 'CM', ...] (196 countries)

echo "Total sovereign countries: " . count($allCountries); // 196
```

### hasCountryCode()

Check if a country code exists in the mapping.

```php
public function hasCountryCode(string $countryCode): bool
```

**Parameters:**
- `$countryCode` (string): ISO 3166-1 alpha-2 country code

**Returns:** `bool` True if the country code exists

**Examples:**
```php
// Check sovereign countries
$provider->hasCountryCode('FR'); // true
$provider->hasCountryCode('DE'); // true
$provider->hasCountryCode('US'); // true

// Check non-sovereign territories (should return false)
$provider->hasCountryCode('RE'); // false (French overseas department)
$provider->hasCountryCode('HK'); // false (Special Administrative Region)
$provider->hasCountryCode('XX'); // false (invalid code)
```

### getContinentCode()

Get the continent code for a country.

```php
public function getContinentCode(string $countryCode, bool $asIsoCode = false): ?string
```

**Parameters:**
- `$countryCode` (string): ISO 3166-1 alpha-2 country code
- `$asIsoCode` (bool): Return ISO code instead of UN M49 code (default: false)

**Returns:** `string|null` Continent code or null if not found

**Examples:**
```php
// Get UN M49 continent codes
$provider->getContinentCode('FR'); // '150'
$provider->getContinentCode('DZ'); // '002'
$provider->getContinentCode('CN'); // '142'
$provider->getContinentCode('US'); // '019'
$provider->getContinentCode('AU'); // '009'

// Get ISO continent codes
$provider->getContinentCode('FR', true); // 'EUR'
$provider->getContinentCode('DZ', true); // 'AFR'
$provider->getContinentCode('CN', true); // 'ASI'
$provider->getContinentCode('US', true); // 'AMR'
$provider->getContinentCode('AU', true); // 'OCE'

// Invalid country codes
$provider->getContinentCode('XX'); // null
$provider->getContinentCode('XX', true); // null
```

### getSubregionCode()

Get the subregion code for a country.

```php
public function getSubregionCode(string $countryCode): ?string
```

**Parameters:**
- `$countryCode` (string): ISO 3166-1 alpha-2 country code

**Returns:** `string|null` Subregion code or null if not found

**Examples:**
```php
// Get subregion codes
$provider->getSubregionCode('FR'); // '155' (Western Europe)
$provider->getSubregionCode('DZ'); // '015' (Northern Africa)
$provider->getSubregionCode('CN'); // '030' (Eastern Asia)
$provider->getSubregionCode('US'); // '021' (Northern America)
$provider->getSubregionCode('AU'); // '053' (Australia and New Zealand)

// Invalid country codes
$provider->getSubregionCode('XX'); // null
```

## Information Methods

### getContinentInfo()

Get continent information including name and available countries.

```php
public function getContinentInfo(string $continentCode, ?string $locale = null): ?array
```

**Parameters:**
- `$continentCode` (string): Continent code (ISO or UN M49)
- `$locale` (string|null): Locale for country names (optional)

**Returns:** `array|null` Continent information or null if not found

**Return Structure:**
```php
[
    'code' => string,      // Continent code
    'name' => string,      // Continent name
    'countries' => array   // Array of country codes mapped to localized names
]
```

**Examples:**
```php
// Get Europe information
$info = $provider->getContinentInfo('EUR', 'en');
/*
Returns:
[
    'code' => 'EUR',
    'name' => 'Europe',
    'countries' => [
        'FR' => 'France',
        'DE' => 'Germany',
        'IT' => 'Italy',
        // ... 45 countries total
    ]
]
*/

// Get Africa information in French
$info = $provider->getContinentInfo('AFR', 'fr');
/*
Returns:
[
    'code' => 'AFR',
    'name' => 'Afrique',
    'countries' => [
        'DZ' => 'Algérie',
        'EG' => 'Égypte',
        'MA' => 'Maroc',
        // ... 54 countries total
    ]
]
*/

// Invalid continent code
$info = $provider->getContinentInfo('XXX'); // null
```

### getSubregionInfo()

Get subregion information including name and available countries.

```php
public function getSubregionInfo(string $subregionCode, ?string $locale = null): ?array
```

**Parameters:**
- `$subregionCode` (string): Subregion code (UN M49)
- `$locale` (string|null): Locale for country names (optional)

**Returns:** `array|null` Subregion information or null if not found

**Return Structure:**
```php
[
    'code' => string,      // Subregion code
    'name' => string,      // Subregion name
    'countries' => array   // Array of country codes mapped to localized names
]
```

**Examples:**
```php
// Get Western Europe information
$info = $provider->getSubregionInfo('155', 'en');
/*
Returns:
[
    'code' => '155',
    'name' => 'Western Europe',
    'countries' => [
        'AT' => 'Austria',
        'BE' => 'Belgium',
        'FR' => 'France',
        // ... more countries
    ]
]
*/

// Get Northern Africa information in Arabic
$info = $provider->getSubregionInfo('015', 'ar');
/*
Returns:
[
    'code' => '015',
    'name' => 'شمال أفريقيا',
    'countries' => [
        'DZ' => 'الجزائر',
        'EG' => 'مصر',
        'LY' => 'ليبيا',
        // ... more countries
    ]
]
*/

// Invalid subregion code
$info = $provider->getSubregionInfo('999'); // null
```

## ISO Code Conversion

### getIsoContinentCode()

Convert UN M49 continent code to ISO code.

```php
public function getIsoContinentCode(string $m49Code): ?string
```

**Parameters:**
- `$m49Code` (string): UN M49 continent code

**Returns:** `string|null` ISO continent code or null if not found

**Examples:**
```php
$provider->getIsoContinentCode('150'); // 'EUR'
$provider->getIsoContinentCode('002'); // 'AFR'
$provider->getIsoContinentCode('142'); // 'ASI'
$provider->getIsoContinentCode('019'); // 'AMR'
$provider->getIsoContinentCode('009'); // 'OCE'

// Invalid codes
$provider->getIsoContinentCode('999'); // null
```

### getM49ContinentCode()

Convert ISO continent code to UN M49 code.

```php
public function getM49ContinentCode(string $isoCode): ?string
```

**Parameters:**
- `$isoCode` (string): ISO continent code

**Returns:** `string|null` UN M49 continent code or null if not found

**Examples:**
```php
$provider->getM49ContinentCode('EUR'); // '150'
$provider->getM49ContinentCode('AFR'); // '002'
$provider->getM49ContinentCode('ASI'); // '142'
$provider->getM49ContinentCode('AMR'); // '019'
$provider->getM49ContinentCode('OCE'); // '009'

// Invalid codes
$provider->getM49ContinentCode('XXX'); // null
```

### getIsoSubregionCode()

Convert UN M49 subregion code to ISO code.

```php
public function getIsoSubregionCode(string $m49Code): ?string
```

**Parameters:**
- `$m49Code` (string): UN M49 subregion code

**Returns:** `string|null` ISO subregion code or null if not found

**Examples:**
```php
$provider->getIsoSubregionCode('155'); // 'WEU'
$provider->getIsoSubregionCode('015'); // 'NAF'
$provider->getIsoSubregionCode('030'); // 'EAS'
$provider->getIsoSubregionCode('021'); // 'NAM'
$provider->getIsoSubregionCode('053'); // 'ANZ'

// Invalid codes
$provider->getIsoSubregionCode('999'); // null
```

### getM49SubregionCode()

Convert ISO subregion code to UN M49 code.

```php
public function getM49SubregionCode(string $isoCode): ?string
```

**Parameters:**
- `$isoCode` (string): ISO subregion code

**Returns:** `string|null` UN M49 subregion code or null if not found

**Examples:**
```php
$provider->getM49SubregionCode('WEU'); // '155'
$provider->getM49SubregionCode('NAF'); // '015'
$provider->getM49SubregionCode('EAS'); // '030'
$provider->getM49SubregionCode('NAM'); // '021'
$provider->getM49SubregionCode('ANZ'); // '053'

// Invalid codes
$provider->getM49SubregionCode('XXX'); // null
```

## Available Codes Methods

### getAvailableContinentCodes()

Get all available continent codes.

```php
public function getAvailableContinentCodes(bool $asIsoCodes = false): array
```

**Parameters:**
- `$asIsoCodes` (bool): Return ISO codes instead of UN M49 codes (default: false)

**Returns:** `array<string>` Array of continent codes

**Examples:**
```php
// Get UN M49 continent codes
$m49Codes = $provider->getAvailableContinentCodes();
// Returns: ['002', '019', '142', '150', '009']

// Get ISO continent codes
$isoCodes = $provider->getAvailableContinentCodes(true);
// Returns: ['AFR', 'AMR', 'ASI', 'EUR', 'OCE']

// Use in validation
$validContinents = $provider->getAvailableContinentCodes(true);
if (in_array('EUR', $validContinents)) {
    echo "EUR is a valid continent code";
}
```

### getAvailableSubregionCodes()

Get all available subregion codes.

```php
public function getAvailableSubregionCodes(bool $asIsoCodes = false): array
```

**Parameters:**
- `$asIsoCodes` (bool): Return ISO codes instead of UN M49 codes (default: false)

**Returns:** `array<string>` Array of subregion codes

**Examples:**
```php
// Get UN M49 subregion codes
$m49Codes = $provider->getAvailableSubregionCodes();
// Returns: ['014', '015', '017', '018', '011', '021', '013', '029', '005', ...]

// Get ISO subregion codes
$isoCodes = $provider->getAvailableSubregionCodes(true);
// Returns: ['EAF', 'NAF', 'MAF', 'SAF', 'WAF', 'NAM', 'CAM', 'CAR', 'SAM', ...]

// Use in validation
$validSubregions = $provider->getAvailableSubregionCodes(true);
if (in_array('WEU', $validSubregions)) {
    echo "WEU is a valid subregion code";
}
```

## Utility Methods

### hasContinentCode()

Check if a continent code exists in the mapping.

```php
public function hasContinentCode(string $continentCode): bool
```

**Parameters:**
- `$continentCode` (string): Continent code (ISO or UN M49)

**Returns:** `bool` True if the continent code exists

**Examples:**
```php
// Valid continent codes
$provider->hasContinentCode('EUR'); // true
$provider->hasContinentCode('150'); // true
$provider->hasContinentCode('AFR'); // true
$provider->hasContinentCode('002'); // true

// Invalid continent codes
$provider->hasContinentCode('XXX'); // false
$provider->hasContinentCode('999'); // false
```

### hasSubregionCode()

Check if a subregion code exists in the mapping.

```php
public function hasSubregionCode(string $subregionCode): bool
```

**Parameters:**
- `$subregionCode` (string): Subregion code (UN M49)

**Returns:** `bool` True if the subregion code exists

**Examples:**
```php
// Valid subregion codes
$provider->hasSubregionCode('155'); // true (Western Europe)
$provider->hasSubregionCode('015'); // true (Northern Africa)
$provider->hasSubregionCode('030'); // true (Eastern Asia)

// Invalid subregion codes
$provider->hasSubregionCode('999'); // false
$provider->hasSubregionCode('XXX'); // false
```

## Data Structures

### Country Array Format

All methods that return country data use this format:

```php
[
    'FR' => 'France',
    'DE' => 'Germany',
    'IT' => 'Italy',
    // ... more countries
]
```

### Continent Information Format

```php
[
    'code' => 'EUR',
    'name' => 'Europe',
    'countries' => [
        'FR' => 'France',
        'DE' => 'Germany',
        // ... more countries
    ]
]
```

### Subregion Information Format

```php
[
    'code' => '155',
    'name' => 'Western Europe',
    'countries' => [
        'AT' => 'Austria',
        'BE' => 'Belgium',
        // ... more countries
    ]
]
```

## Error Handling

### Graceful Fallbacks

The library provides graceful error handling:

1. **Missing Translations**: Falls back to English, then to country code
2. **Invalid Country Codes**: Returns null or empty arrays
3. **Invalid Continent Codes**: Returns null or empty arrays
4. **Logging**: All errors are logged if a logger is provided

### Example Error Handling

```php
use Ydee\IntlRegion\RegionProvider;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

class CustomLogger extends AbstractLogger
{
    public function log($level, \Stringable|string $message, array $context = []): void
    {
        error_log("[$level] $message: " . json_encode($context));
    }
}

// Create provider with logger
$logger = new CustomLogger();
$provider = new RegionProvider('en', $logger);

// This will log warnings for missing translations
$countries = $provider->getCountriesByContinent('EUR', 'xx');
// Falls back gracefully to English or country code
```

### Common Error Scenarios

```php
// Invalid continent code
$countries = $provider->getCountriesByContinent('XXX');
// Returns: []

// Invalid country code
$continentCode = $provider->getContinentCode('XX');
// Returns: null

// Missing translation (logs warning, falls back)
$countries = $provider->getCountriesByContinent('EUR', 'xx');
// Falls back to English, then to country code

// Invalid subregion code
$countries = $provider->getCountriesBySubregion('999');
// Returns: []
```

## Complete Example

```php
<?php

require_once 'vendor/autoload.php';

use Ydee\IntlRegion\RegionProvider;

// Create provider
$provider = new RegionProvider('en');

// Example: Build a complete country selector
function buildCountrySelector(RegionProvider $provider, string $continent = null, string $locale = 'en'): array
{
    if ($continent) {
        // Validate continent code
        if (!$provider->hasContinentCode($continent)) {
            throw new \InvalidArgumentException("Invalid continent code: $continent");
        }
        
        return $provider->getCountriesByContinent($continent, $locale);
    }
    
    // Return all countries
    $countryCodes = $provider->getAvailableCountryCodes();
    $countries = [];
    
    foreach ($countryCodes as $code) {
        $countries[$code] = \Locale::getDisplayRegion('-' . $code, $locale);
    }
    
    return $countries;
}

// Usage examples
try {
    // Get all countries
    $allCountries = buildCountrySelector($provider);
    echo "All countries: " . count($allCountries) . "\n";
    
    // Get European countries in French
    $europeanCountries = buildCountrySelector($provider, 'EUR', 'fr');
    echo "European countries in French: " . count($europeanCountries) . "\n";
    
    // Get African countries
    $africanCountries = buildCountrySelector($provider, 'AFR');
    echo "African countries: " . count($africanCountries) . "\n";
    
} catch (\InvalidArgumentException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
``` 