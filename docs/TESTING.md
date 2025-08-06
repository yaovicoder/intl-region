# 🧪 Testing Guide: Ydee Intl Region

This document explains how to run and write tests for the project.

---

## Running Tests

### Run all tests:
```bash
vendor/bin/phpunit
```

**Expected output:**
```
PHPUnit 10.5.48 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.4.5
Configuration: /opt/yaovi/src/intl-region/phpunit.xml.dist

................................................................. 65 / 68 ( 95%)
...                                                               68 / 68 (100%)

Time: 00:00.165, Memory: 12.00 MB

OK (68 tests, 1065 assertions)
```

### Run specific test suites:
```bash
# Run only RegionProvider tests
vendor/bin/phpunit tests/RegionProviderTest.php

# Run only mapping tests
vendor/bin/phpunit tests/Mapping/

# Run only CLI command tests
vendor/bin/phpunit tests/Command/

# Run UN M49 validation tests
vendor/bin/phpunit tests/UNM49DataValidationTest.php
```

### Run with coverage:
```bash
# Generate HTML coverage report
vendor/bin/phpunit --coverage-html coverage/

# Open coverage report
open coverage/index.html  # macOS
xdg-open coverage/index.html  # Linux
```

---

## Test Structure

```
tests/
├── RegionProviderTest.php          # Main service tests
├── UNM49DataValidationTest.php     # UN data validation
├── Mapping/
│   ├── ContinentMappingTest.php    # Continent mapping tests
│   └── SubregionMappingTest.php    # Subregion mapping tests
└── Command/
    └── ListRegionsCommandTest.php  # CLI command tests
```

---

## Test Categories

### 1. Unit Tests (RegionProviderTest.php)
- Core functionality testing
- Method parameter validation
- Return type validation
- Error handling
- ISO code conversion
- Locale handling

### 2. Mapping Tests (Mapping/)
- JSON file loading
- Data consistency
- Country code validation
- Continent/subregion code validation
- Performance optimization

### 3. CLI Command Tests (Command/)
- Command execution
- Output formatting
- Parameter validation
- Help text validation
- Error handling

### 4. Integration Tests (UNM49DataValidationTest.php)
- UN M49 data validation
- Data completeness verification
- Sovereign country filtering
- Official data comparison

---

## Writing Tests

### Test Naming Conventions
- Use descriptive method names
- Follow pattern: `test[MethodName][Scenario]`
- Example: `testGetCountriesByContinentWithIsoCode()`

### Test Structure
```php
<?php

namespace Ydee\IntlRegion\Tests;

use PHPUnit\Framework\TestCase;
use Ydee\IntlRegion\RegionProvider;

class ExampleTest extends TestCase
{
    private RegionProvider $regionProvider;

    protected function setUp(): void
    {
        $this->regionProvider = new RegionProvider();
    }

    public function testMethodName(): void
    {
        // Arrange
        $expected = 'expected_value';
        
        // Act
        $result = $this->regionProvider->methodName();
        
        // Assert
        $this->assertEquals($expected, $result);
    }
}
```

### Best Practices
- Test all public methods
- Test edge cases and error conditions
- Use descriptive assertions
- Keep tests isolated and repeatable
- Mock external dependencies when needed

---

## Data Validation Tests

### UN M49 Validation
The library includes validation against official UN M49 data:

```bash
# Download UN data (optional)
php scripts/download-un-m49-data.php

# Run validation tests
vendor/bin/phpunit tests/UNM49DataValidationTest.php
```

This ensures:
- All sovereign countries are included
- No territories or dependencies are included
- Data matches official UN M49 standard
- Country codes are valid ISO 3166-1 alpha-2 codes

---

## Performance Testing

### Memory Usage
```bash
# Test memory usage
php -d memory_limit=128M vendor/bin/phpunit
```

### Execution Time
```bash
# Test execution time
time vendor/bin/phpunit
```

---

## Continuous Integration

### GitHub Actions
The project includes GitHub Actions for:
- PHP 8.1, 8.2, 8.3, 8.4 testing
- Symfony 6.x and 7.x compatibility
- Code coverage reporting
- Static analysis

### Local CI
```bash
# Run full CI suite locally
composer ci

# Or run individual checks
composer test
composer lint
composer static-analysis
```

---

## Debugging Tests

### Verbose Output
```bash
vendor/bin/phpunit --verbose
```

### Debug Mode
```bash
# Enable debug mode
export SYMFONY_DEBUG=1
vendor/bin/phpunit
```

### Specific Test
```bash
# Run specific test method
vendor/bin/phpunit --filter testGetCountriesByContinent
```

---

## Test Data

### Test Fixtures
- JSON mapping files in `data/mapping/`
- UN M49 data in `tests/_output/` (downloaded)
- Reference data in `data/all_continents_countries.json`

### Mock Data
- Use PHPUnit mocks for external dependencies
- Mock PSR-3 logger for testing error handling
- Mock Symfony Intl for testing localization

---

## Coverage Requirements

- **Minimum coverage**: 90%
- **Current coverage**: 100% (68 tests, 1065 assertions)
- **Coverage areas**:
  - All public methods
  - All error conditions
  - All edge cases
  - CLI command functionality

---

## Troubleshooting

### Common Issues

1. **Tests failing due to missing extensions:**
   ```bash
   # Install required extensions
   sudo apt-get install php-intl php-json php-mbstring
   ```

2. **Memory issues:**
   ```bash
   # Increase memory limit
   php -d memory_limit=512M vendor/bin/phpunit
   ```

3. **Permission issues:**
   ```bash
   # Fix permissions
   chmod -R 755 tests/
   chmod -R 755 data/
   ```

4. **Autoload issues:**
   ```bash
   # Regenerate autoloader
   composer dump-autoload
   ```

---

## Contributing

Before submitting code:
- [ ] All tests pass (`vendor/bin/phpunit`)
- [ ] Coverage is maintained or improved
- [ ] New features have corresponding tests
- [ ] Bug fixes include regression tests

See [CONTRIBUTING.md](../CONTRIBUTING.md) for detailed requirements. 