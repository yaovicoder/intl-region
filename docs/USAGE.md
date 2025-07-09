# 📖 Usage Guide: Ydee Intl Region

This document provides comprehensive usage examples and API documentation for the Ydee Intl Region package.

---

## 🚀 Quick Start

### Installation

```bash
composer require yaovicoder/intl-region
```

### Basic Usage

```php
use Ydee\IntlRegion\RegionProvider;

$provider = new RegionProvider();

// Get all African countries
$africanCountries = $provider->getCountriesByContinent('002');

// Get Eastern African countries
$easternAfricanCountries = $provider->getCountriesBySubregion('014');

// Get countries in French
$frenchCountries = $provider->getCountriesByContinent('002', 'fr');
```

---

## 🌍 Region Codes

### Continent Codes (UN M49)

| Code | Name | Description |
|------|------|-------------|
| `002` | Africa | African continent |
| `019` | Americas | North and South America |
| `142` | Asia | Asian continent |
| `150` | Europe | European continent |
| `009` | Oceania | Oceania and Pacific islands |

### Subregion Codes (UN M49)

#### Africa
- `014` - Eastern Africa
- `017` - Middle Africa
- `015` - Northern Africa
- `018` - Southern Africa
- `011` - Western Africa

#### Americas
- `005` - South America
- `013` - Central America
- `021` - Northern America
- `029` - Caribbean

#### Asia
- `030` - Eastern Asia
- `034` - Southern Asia
- `035` - South-Eastern Asia
- `143` - Central Asia
- `145` - Western Asia

#### Europe
- `151` - Eastern Europe
- `154` - Northern Europe
- `039` - Southern Europe
- `155` - Western Europe

#### Oceania
- `053` - Australia and New Zealand
- `054` - Melanesia
- `057` - Micronesia
- `061` - Polynesia

---

## 🔧 API Reference

### RegionProvider Class

#### Constructor

```php
public function __construct(string $defaultLocale = 'en')
```

**Parameters:**
- `$defaultLocale` - Default locale for country names (default: 'en')

#### Methods

##### getCountriesByContinent()

```php
public function getCountriesByContinent(string $continentCode, ?string $locale = null): array
```

Get all countries for a given continent.

**Parameters:**
- `$continentCode` - UN M49 continent code (e.g., '002' for Africa)
- `$locale` - Locale for country names (optional, uses default if not provided)

**Returns:** Array of country codes mapped to localized names

**Example:**
```php
$countries = $provider->getCountriesByContinent('002', 'fr');
// Returns: ['DZ' => 'Algérie', 'EG' => 'Égypte', ...]
```

##### getCountriesBySubregion()

```php
public function getCountriesBySubregion(string $subregionCode, ?string $locale = null): array
```

Get all countries for a given subregion.

**Parameters:**
- `$subregionCode` - UN M49 subregion code (e.g., '014' for Eastern Africa)
- `$locale` - Locale for country names (optional, uses default if not provided)

**Returns:** Array of country codes mapped to localized names

**Example:**
```php
$countries = $provider->getCountriesBySubregion('014', 'en');
// Returns: ['KE' => 'Kenya', 'TZ' => 'Tanzania', ...]
```

##### getContinentCode()

```php
public function getContinentCode(string $countryCode): ?string
```

Get the continent code for a given country.

**Parameters:**
- `$countryCode` - ISO 3166-1 alpha-2 country code

**Returns:** UN M49 continent code or null if not found

**Example:**
```php
$continentCode = $provider->getContinentCode('US');
// Returns: '019'
```

##### getSubregionCode()

```php
public function getSubregionCode(string $countryCode): ?string
```

Get the subregion code for a given country.

**Parameters:**
- `$countryCode` - ISO 3166-1 alpha-2 country code

**Returns:** UN M49 subregion code or null if not found

**Example:**
```php
$subregionCode = $provider->getSubregionCode('US');
// Returns: '021'
```

##### getContinentInfo()

```php
public function getContinentInfo(string $continentCode, ?string $locale = null): ?array
```

Get comprehensive information about a continent.

**Parameters:**
- `$continentCode` - UN M49 continent code
- `$locale` - Locale for country names (optional)

**Returns:** Array with 'code', 'name', and 'countries' keys, or null if not found

**Example:**
```php
$info = $provider->getContinentInfo('002', 'fr');
// Returns: [
//     'code' => '002',
//     'name' => 'Africa',
//     'countries' => ['DZ' => 'Algérie', 'EG' => 'Égypte', ...]
// ]
```

##### getSubregionInfo()

```php
public function getSubregionInfo(string $subregionCode, ?string $locale = null): ?array
```

Get comprehensive information about a subregion.

**Parameters:**
- `$subregionCode` - UN M49 subregion code
- `$locale` - Locale for country names (optional)

**Returns:** Array with 'code', 'name', and 'countries' keys, or null if not found

**Example:**
```php
$info = $provider->getSubregionInfo('014', 'en');
// Returns: [
//     'code' => '014',
//     'name' => 'Eastern Africa',
//     'countries' => ['KE' => 'Kenya', 'TZ' => 'Tanzania', ...]
// ]
```

##### Utility Methods

```php
// Get all available continent codes
$continentCodes = $provider->getAvailableContinentCodes();

// Get all available subregion codes
$subregionCodes = $provider->getAvailableSubregionCodes();

// Get all available country codes
$countryCodes = $provider->getAvailableCountryCodes();

// Check if a country code exists
$exists = $provider->hasCountryCode('US');

// Check if a continent code exists
$exists = $provider->hasContinentCode('002');

// Check if a subregion code exists
$exists = $provider->hasSubregionCode('014');
```

---

## 🎛️ Symfony Integration

### Bundle Configuration

```yaml
# config/packages/intl_region.yaml
ydee_intl_region:
    default_locale: fr
```

### Service Usage

```php
use Ydee\IntlRegion\RegionProvider;

class MyController
{
    public function __construct(
        private RegionProvider $regionProvider
    ) {}

    public function index(): Response
    {
        $africanCountries = $this->regionProvider->getCountriesByContinent('002');
        
        return $this->render('index.html.twig', [
            'countries' => $africanCountries
        ]);
    }
}
```

### Console Command

```bash
# List all African countries
php bin/console intl-region:list continent 002

# List Eastern African countries in French
php bin/console intl-region:list subregion 014 --locale=fr

# Output in JSON format
php bin/console intl-region:list continent 002 --format=json

# Output in CSV format
php bin/console intl-region:list continent 002 --format=csv
```

**Command Options:**
- `--locale, -l` - Locale for country names (default: 'en')
- `--format, -f` - Output format: 'table', 'json', or 'csv' (default: 'table')

---

## 🌐 Localization

The package uses Symfony's Intl component for country name localization. Supported locales include:

- `en` - English
- `fr` - French
- `de` - German
- `es` - Spanish
- `it` - Italian
- `pt` - Portuguese
- `ru` - Russian
- `ja` - Japanese
- `zh` - Chinese
- And many more...

**Example:**
```php
$provider = new RegionProvider('fr');

// Get countries in French
$frenchCountries = $provider->getCountriesByContinent('002');
// Returns: ['DZ' => 'Algérie', 'EG' => 'Égypte', ...]

// Override locale for specific call
$englishCountries = $provider->getCountriesByContinent('002', 'en');
// Returns: ['DZ' => 'Algeria', 'EG' => 'Egypt', ...]
```

---

## 🧪 Testing

### Running Tests

```bash
# Run all tests
vendor/bin/phpunit

# Run specific test file
vendor/bin/phpunit tests/RegionProviderTest.php

# Run with coverage
vendor/bin/phpunit --coverage-html coverage/
```

### Test Examples

```php
use Ydee\IntlRegion\RegionProvider;

class MyTest extends TestCase
{
    public function testGetAfricanCountries(): void
    {
        $provider = new RegionProvider();
        $countries = $provider->getCountriesByContinent('002');
        
        $this->assertArrayHasKey('DZ', $countries);
        $this->assertArrayHasKey('EG', $countries);
        $this->assertArrayHasKey('ZA', $countries);
    }
}
```

---

## 🔍 Error Handling

The package throws exceptions for invalid inputs:

```php
try {
    $countries = $provider->getCountriesByContinent('999');
} catch (\InvalidArgumentException $e) {
    // Handle invalid continent code
    echo $e->getMessage(); // "Invalid continent code: 999"
}

try {
    $countries = $provider->getCountriesBySubregion('999');
} catch (\InvalidArgumentException $e) {
    // Handle invalid subregion code
    echo $e->getMessage(); // "Invalid subregion code: 999"
}
```

---

## 📚 Examples

### Complete Example

```php
<?php

require_once 'vendor/autoload.php';

use Ydee\IntlRegion\RegionProvider;

// Create provider with French as default locale
$provider = new RegionProvider('fr');

// Get all African countries
$africanCountries = $provider->getCountriesByContinent('002');
echo "African countries:\n";
foreach ($africanCountries as $code => $name) {
    echo "- $code: $name\n";
}

// Get Eastern African countries in English
$easternAfricanCountries = $provider->getCountriesBySubregion('014', 'en');
echo "\nEastern African countries:\n";
foreach ($easternAfricanCountries as $code => $name) {
    echo "- $code: $name\n";
}

// Get continent info
$continentInfo = $provider->getContinentInfo('002', 'en');
echo "\nContinent: {$continentInfo['name']} ({$continentInfo['code']})\n";
echo "Countries: " . count($continentInfo['countries']) . "\n";
```

### Symfony Controller Example

```php
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Ydee\IntlRegion\RegionProvider;

class RegionController extends AbstractController
{
    public function __construct(
        private RegionProvider $regionProvider
    ) {}

    #[Route('/api/regions/continent/{code}', name: 'api_regions_continent')]
    public function getContinentCountries(string $code): JsonResponse
    {
        try {
            $countries = $this->regionProvider->getCountriesByContinent($code);
            return $this->json(['countries' => $countries]);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    #[Route('/api/regions/subregion/{code}', name: 'api_regions_subregion')]
    public function getSubregionCountries(string $code): JsonResponse
    {
        try {
            $countries = $this->regionProvider->getCountriesBySubregion($code);
            return $this->json(['countries' => $countries]);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }
}
```

---

## 🤝 Contributing

See [CONTRIBUTING.md](./CONTRIBUTING.md) for contribution guidelines.

---

## 📄 License

This package is licensed under the MIT License. See [LICENSE](./LICENSE) for details. 