# 🧪 Testing Guide: Ydee Intl Region

This document explains how to run and write tests for the project.

---

## Running Tests

- Run all tests:
  ```bash
  vendor/bin/phpunit
  ```
- Test output should show all tests passing before submitting code.

---

## Writing Tests
- Place tests in the `tests/` directory.
- Use PHPUnit for all tests.
- Name test classes after the class being tested (e.g., `RegionProviderTest`).
- Cover all public methods and edge cases.
- Use mock objects for dependencies where needed.

---

## Test Structure
- `tests/`
  - `RegionProviderTest.php`
  - `Mapping/`
  - `Command/`

---

## Best Practices
- Write tests for every new feature and bugfix.
- Keep tests isolated and repeatable.
- Use descriptive method names.

---

See `CONTRIBUTING.md` for test requirements before submitting code. 