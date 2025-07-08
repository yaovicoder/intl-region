# 🌍 Ydee Intl Region

**Region-based country filtering for Symfony Intl**  
Filter localized country lists by continent or subregion with zero manual mapping. Powered by `symfony/intl`.

---

## 📦 Package

**Repository:** [`yaovicoder/intl-region`](https://github.com/yaovicoder/intl-region)  
**Namespace:** `Ydee\Intl\Region`  
**License:** MIT

---

## ✨ What It Does

This library lets you:
- Filter countries by **continent** (e.g. Europe, Asia)
- Filter countries by **subregion** (e.g. Western Europe, Northern Africa)
- Get country names localized in any language using `symfony/intl`
- Use a clean, tested static mapping based on **UN M49**
- Integrate easily with Symfony as a service
- List countries from CLI

No more manual lists — no more errors.

---

## ✅ Features

- ✔️ ISO 3166-1 → UN M49 continent mapping
- ✔️ Subregion support
- ✔️ Localized names from ICU
- ✔️ Symfony Bundle with DI + CLI
- ✔️ Fully unit tested

---

## 🧩 Quick Example

```php
use Ydee\Intl\Region\RegionProvider;

$provider = new RegionProvider();

$countries = $provider->getCountriesForContinent('EU', 'fr');

print_r($countries);
```

---

## ⚙️ Symfony Bundle

```yaml
# config/packages/intl_region.yaml
intl_region:
  default_locale: fr
```

CLI usage:
```bash
php bin/console intl-region:list EU --locale=fr
```

---

## 🗂️ Requirements

- PHP >= 8.1
- `symfony/intl`
- `symfony/console`
- `symfony/dependency-injection`
- `symfony/config`

---

## 🧪 Tests

```bash
vendor/bin/phpunit
```

---

## 🚀 Onboarding & Contributing

- New? See [docs/ONBOARDING_CHECKLIST.md](./docs/ONBOARDING_CHECKLIST.md)
- How to contribute: [CONTRIBUTING.md](./CONTRIBUTING.md)
- Setup instructions: [docs/SETUP_GUIDE.md](./docs/SETUP_GUIDE.md)
- Architecture: [docs/ARCHITECTURE.md](./docs/ARCHITECTURE.md)
- Testing: [docs/TESTING.md](./docs/TESTING.md)

---

## 📑 License

MIT — see [`LICENSE`](./LICENSE).

---

## 📌 Roadmap

See [`docs/ROADMAP.md`](./docs/ROADMAP.md)

---

## ✅ Full Scope

See [`docs/FULLSCOPE.md`](./docs/FULLSCOPE.md)

---

**Made with ❤️ by [Yaovi Ametepe](https://github.com/yaovicoder)**
