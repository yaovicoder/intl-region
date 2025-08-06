# Installation Guide

This guide provides detailed installation instructions for the Ydee IntlRegion library.

## Table of Contents

1. [System Requirements](#system-requirements)
2. [Composer Installation](#composer-installation)
3. [Manual Installation](#manual-installation)
4. [Symfony Integration](#symfony-integration)
5. [Configuration](#configuration)
6. [Verification](#verification)
7. [Troubleshooting](#troubleshooting)

## System Requirements

### PHP Requirements

- **PHP**: 8.1 or higher
- **Extensions**: 
  - `intl` (for internationalization support)
  - `json` (for JSON data handling)
  - `mbstring` (for string operations)

### Dependencies

- **symfony/intl**: ^6.0 or ^7.0 (for country name localization)
- **psr/log**: ^3.0 (for logging support)
- **symfony/console**: ^6.0 or ^7.0 (for CLI commands)

### Check Your Environment

```bash
# Check PHP version
php --version

# Check required extensions
php -m | grep -E "(intl|json|mbstring)"

# Check Composer
composer --version
```

## Composer Installation

### 1. Install via Composer

```bash
# Install the package
composer require yaovicoder/intl-region

# Or specify version
composer require yaovicoder/intl-region:^1.0
```

### 2. Verify Installation

```bash
# Check if package is installed
composer show yaovicoder/intl-region

# Should show:
# name     : yaovicoder/intl-region
# descrip. : A PHP library for filtering localized country lists by UN M49 continent or subregion codes
# keywords : countries, regions, internationalization, un-m49
# versions : * 1.0.0
```

### 3. Autoload Check

```php
<?php

// Test autoloading
require_once 'vendor/autoload.php';

use Ydee\IntlRegion\RegionProvider;

// Create instance
$provider = new RegionProvider();

// Test basic functionality
$countries = $provider->getAvailableCountryCodes();
echo "Total countries: " . count($countries) . "\n"; // Should output: 196
```

## Manual Installation

### 1. Clone Repository

```bash
# Clone the repository
git clone https://github.com/yaovicoder/intl-region.git
cd intl-region

# Or download ZIP from GitHub releases
wget https://github.com/yaovicoder/intl-region/archive/refs/tags/v1.0.0.zip
unzip v1.0.0.zip
cd intl-region-1.0.0
```

### 2. Install Dependencies

```bash
# Install Composer dependencies
composer install

# Or install without dev dependencies for production
composer install --no-dev --optimize-autoloader
```

### 3. Verify Installation

```bash
# Run tests to verify everything works
vendor/bin/phpunit

# Should show: OK (68 tests, 1065 assertions)
```

## Symfony Integration

### 1. Enable Bundle

**config/bundles.php:**
```php
<?php

return [
    // ... other bundles
    Ydee\IntlRegion\IntlRegionBundle::class => ['all' => true],
];
```

### 2. Configure Bundle

**config/packages/ydee_intl_region.yaml:**
```yaml
ydee_intl_region:
    default_locale: 'en'  # Default locale for country names
```

### 3. Clear Cache

```bash
# Clear Symfony cache
php bin/console cache:clear

# Or in production
php bin/console cache:clear --env=prod
```

### 4. Verify Bundle

```bash
# Check if bundle is loaded
php bin/console debug:container --tag=ydee_intl_region

# Should show the RegionProvider service
```

## Configuration

### Basic Configuration

```yaml
# config/packages/ydee_intl_region.yaml
ydee_intl_region:
    default_locale: 'en'  # Default locale for country names
```

### Advanced Configuration

```yaml
# config/packages/ydee_intl_region.yaml
ydee_intl_region:
    default_locale: 'en'
    
# You can also configure logging
services:
    Ydee\IntlRegion\RegionProvider:
        arguments:
            $defaultLocale: '%ydee_intl_region.default_locale%'
            $logger: '@logger'  # Your PSR-3 logger service
```

### Environment-Specific Configuration

```yaml
# config/packages/dev/ydee_intl_region.yaml
ydee_intl_region:
    default_locale: 'en'

# config/packages/prod/ydee_intl_region.yaml
ydee_intl_region:
    default_locale: 'en'
```

## Verification

### 1. Basic Functionality Test

```php
<?php

require_once 'vendor/autoload.php';

use Ydee\IntlRegion\RegionProvider;

// Create provider
$provider = new RegionProvider();

// Test basic functionality
echo "Testing basic functionality...\n";

// Test 1: Get all countries
$allCountries = $provider->getAvailableCountryCodes();
echo "✓ Total countries: " . count($allCountries) . " (expected: 196)\n";

// Test 2: Get European countries
$europeanCountries = $provider->getCountriesByContinent('EUR');
echo "✓ European countries: " . count($europeanCountries) . " (expected: 45)\n";

// Test 3: Get African countries in French
$africanCountries = $provider->getCountriesByContinent('AFR', 'fr');
echo "✓ African countries in French: " . count($africanCountries) . " (expected: 54)\n";

// Test 4: Check specific country
if ($provider->hasCountryCode('FR')) {
    echo "✓ France is recognized as a sovereign country\n";
}

// Test 5: Get continent code
$continentCode = $provider->getContinentCode('FR', true);
echo "✓ France continent code: $continentCode (expected: EUR)\n";

echo "\nAll tests passed! Installation is successful.\n";
```

### 2. Symfony Integration Test

```php
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Ydee\IntlRegion\RegionProvider;

#[Route('/test')]
class TestController extends AbstractController
{
    private RegionProvider $regionProvider;

    public function __construct(RegionProvider $regionProvider)
    {
        $this->regionProvider = $regionProvider;
    }

    #[Route('/intl-region', name: 'test_intl_region')]
    public function testIntlRegion(): JsonResponse
    {
        $results = [
            'total_countries' => count($this->regionProvider->getAvailableCountryCodes()),
            'european_countries' => count($this->regionProvider->getCountriesByContinent('EUR')),
            'african_countries' => count($this->regionProvider->getCountriesByContinent('AFR')),
            'france_continent' => $this->regionProvider->getContinentCode('FR', true),
            'has_france' => $this->regionProvider->hasCountryCode('FR'),
        ];

        return $this->json([
            'success' => true,
            'message' => 'IntlRegion integration test',
            'results' => $results,
        ]);
    }
}
```

### 3. CLI Command Test

```bash
# Test CLI command
php bin/console intl-region:list

# Should show a table with all countries

# Test continent filtering
php bin/console intl-region:list continent EUR

# Should show European countries

# Test locale
php bin/console intl-region:list continent EUR --locale=fr

# Should show European countries in French
```

### 4. Run Full Test Suite

```bash
# Run all tests
vendor/bin/phpunit

# Should show: OK (68 tests, 1065 assertions)

# Run with coverage
vendor/bin/phpunit --coverage-html coverage/

# Open coverage/index.html in browser to see coverage report
```

## Troubleshooting

### Common Issues

#### 1. Autoload Issues

**Problem:** `Class 'Ydee\IntlRegion\RegionProvider' not found`

**Solution:**
```bash
# Regenerate autoloader
composer dump-autoload

# Or optimize for production
composer dump-autoload --optimize
```

#### 2. Missing Intl Extension

**Problem:** `Class 'IntlDateFormatter' not found`

**Solution:**
```bash
# Install intl extension (Ubuntu/Debian)
sudo apt-get install php-intl

# Install intl extension (CentOS/RHEL)
sudo yum install php-intl

# Restart web server
sudo systemctl restart apache2
# or
sudo systemctl restart nginx
```

#### 3. Symfony Bundle Not Found

**Problem:** `Bundle "Ydee\IntlRegion\IntlRegionBundle" does not exist`

**Solution:**
```bash
# Clear cache
php bin/console cache:clear

# Check if bundle is properly registered
php bin/console debug:container --tag=ydee_intl_region
```

#### 4. Permission Issues

**Problem:** `Permission denied` when running tests

**Solution:**
```bash
# Fix permissions
chmod -R 755 vendor/
chmod -R 755 tests/

# Or run with proper user
sudo -u www-data vendor/bin/phpunit
```

#### 5. Memory Issues

**Problem:** `Allowed memory size exhausted`

**Solution:**
```bash
# Increase memory limit
php -d memory_limit=512M vendor/bin/phpunit

# Or set in php.ini
memory_limit = 512M
```

### Debug Mode

Enable debug mode to get more information:

```bash
# Enable debug mode
export SYMFONY_DEBUG=1

# Run with verbose output
vendor/bin/phpunit --verbose

# Check Symfony debug info
php bin/console debug:container Ydee\\IntlRegion\\RegionProvider
```

### Logging

Enable logging to debug issues:

```php
<?php

use Ydee\IntlRegion\RegionProvider;
use Psr\Log\LoggerInterface;

// Create custom logger
class DebugLogger implements LoggerInterface
{
    public function log($level, $message, array $context = []): void
    {
        error_log("[$level] $message: " . json_encode($context));
    }
    
    // Implement other methods...
}

// Use with provider
$logger = new DebugLogger();
$provider = new RegionProvider('en', $logger);
```

### Performance Issues

If you experience performance issues:

```php
<?php

// Cache results
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

## Next Steps

After successful installation:

1. **Read the Usage Guide**: See [USAGE.md](USAGE.md) for detailed examples
2. **Check API Reference**: Review the complete API documentation
3. **Run Examples**: Try the provided code examples
4. **Integrate**: Start using the library in your application
5. **Test**: Run the test suite to ensure everything works
6. **Deploy**: Deploy to production with confidence

## Support

If you encounter issues:

1. **Check this guide** for common solutions
2. **Review the test suite** for usage examples
3. **Check GitHub Issues** for known problems
4. **Create an issue** if the problem persists

## Version Compatibility

| Library Version | PHP Version | Symfony Version |
|----------------|-------------|-----------------|
| 1.0.x          | 8.1+        | 6.0+, 7.0+     |
| 0.x.x          | 8.0+        | 5.4+, 6.0+     | 