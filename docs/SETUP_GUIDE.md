# 🛠️ Setup Guide: Ydee Intl Region

Follow these steps to get the project running locally.

---

## Prerequisites
- PHP >= 8.1
- Composer
- Git
- **NEW**: PHP intl extension (for internationalization)

---

## Installation

### 1. Clone the repository:
```bash
git clone https://github.com/yaovicoder/intl-region.git
cd intl-region
```

### 2. Install dependencies:
```bash
composer install
```

### 3. Verify PHP extensions:
```bash
# Check required extensions
php -m | grep -E "(intl|json|mbstring)"

# If intl extension is missing:
# Ubuntu/Debian:
sudo apt-get install php-intl

# CentOS/RHEL:
sudo yum install php-intl

# macOS (with Homebrew):
brew install php@8.1
```

### 4. Run tests to verify setup:
```bash
vendor/bin/phpunit
```

**Expected output:**
```
OK (68 tests, 1065 assertions)
```

---

## Quick Test

### Test basic functionality:
```bash
# Test the library directly
php -r "
require 'vendor/autoload.php';
\$p = new Ydee\IntlRegion\RegionProvider();
echo 'Total countries: ' . count(\$p->getAvailableCountryCodes()) . PHP_EOL;
echo 'European countries: ' . count(\$p->getCountriesByContinent('EUR')) . PHP_EOL;
"
```

**Expected output:**
```
Total countries: 196
European countries: 45
```

### Test CLI command:
```bash
# List all countries
php bin/console intl-region:list

# List European countries
php bin/console intl-region:list continent EUR

# List African countries in French
php bin/console intl-region:list continent AFR --locale=fr
```

---

## Symfony Integration

### 1. Enable the bundle in `config/bundles.php`:
```php
<?php

return [
    // ... other bundles
    Ydee\IntlRegion\IntlRegionBundle::class => ['all' => true],
];
```

### 2. Configure the bundle in `config/packages/ydee_intl_region.yaml`:
```yaml
ydee_intl_region:
    default_locale: 'en'
```

### 3. Clear cache:
```bash
php bin/console cache:clear
```

### 4. Test integration:
```bash
# Check if service is available
php bin/console debug:container Ydee\\IntlRegion\\RegionProvider
```

---

## Data Validation

### Validate against UN M49 data:
```bash
# Download UN data (optional)
php scripts/download-un-m49-data.php

# Run validation tests
vendor/bin/phpunit tests/UNM49DataValidationTest.php
```

---

## Usage Examples

### Basic usage:
```php
<?php

require_once 'vendor/autoload.php';

use Ydee\IntlRegion\RegionProvider;

$provider = new RegionProvider();

// Get all countries
$allCountries = $provider->getAvailableCountryCodes();
echo "Total countries: " . count($allCountries); // 196

// Get European countries
$europeanCountries = $provider->getCountriesByContinent('EUR');
echo "European countries: " . count($europeanCountries); // 45
```

### Symfony integration:
```php
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Ydee\IntlRegion\RegionProvider;

#[Route('/api/regions')]
class RegionController extends AbstractController
{
    public function __construct(private RegionProvider $regionProvider) {}

    #[Route('/countries', methods: ['GET'])]
    public function getAllCountries(): JsonResponse
    {
        $countries = $this->regionProvider->getAvailableCountryCodes();
        return $this->json(['count' => count($countries), 'data' => $countries]);
    }
}
```

---

## Troubleshooting

### Common Issues:

1. **Missing intl extension:**
   ```bash
   # Install PHP intl extension
   sudo apt-get install php-intl  # Ubuntu/Debian
   sudo yum install php-intl       # CentOS/RHEL
   ```

2. **Permission issues:**
   ```bash
   # Fix permissions
   chmod -R 755 vendor/
   chmod -R 755 tests/
   ```

3. **Memory issues:**
   ```bash
   # Increase memory limit
   php -d memory_limit=512M vendor/bin/phpunit
   ```

4. **Autoload issues:**
   ```bash
   # Regenerate autoloader
   composer dump-autoload
   ```

5. **Symfony bundle not found:**
   ```bash
   # Clear cache
   php bin/console cache:clear
   
   # Check bundle registration
   php bin/console debug:container --tag=ydee_intl_region
   ```

### Debug Mode:

```bash
# Enable debug mode
export SYMFONY_DEBUG=1

# Run with verbose output
vendor/bin/phpunit --verbose
```

---

## Verification Checklist

- [ ] PHP 8.1+ installed
- [ ] Composer installed
- [ ] PHP intl extension installed
- [ ] Repository cloned
- [ ] Dependencies installed (`composer install`)
- [ ] Tests passing (`vendor/bin/phpunit`)
- [ ] Basic functionality working
- [ ] CLI commands working
- [ ] Symfony integration working (if applicable)

---

## Need Help?

- Check the [README.md](../README.md) for detailed usage
- Review [docs/USAGE.md](./USAGE.md) for comprehensive examples
- Open an issue on GitHub
- Ask in the discussions tab on GitHub 