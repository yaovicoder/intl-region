<?php

declare(strict_types=1);

namespace Ydee\IntlRegion;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\Intl\Countries;
use Ydee\IntlRegion\Mapping\ContinentMapping;
use Ydee\IntlRegion\Mapping\SubregionMapping;

/**
 * Core class for region-based country filtering.
 * 
 * This class provides methods to get countries by continent, subregion,
 * and to list available codes using UN M49 standard and ISO continent codes.
 */
class RegionProvider
{
    /**
     * Default locale for country names.
     */
    private string $defaultLocale;

    /**
     * Logger instance.
     */
    private LoggerInterface $logger;

    /**
     * ISO mappings JSON file path.
     */
    private const ISO_MAPPINGS_FILE = __DIR__ . '/../data/mapping/iso-mappings.json';

    /**
     * Cached ISO mappings.
     */
    private static ?array $isoMappings = null;

    /**
     * Load ISO mappings from JSON file.
     * 
     * @return array The ISO mappings
     * @throws \RuntimeException If the mappings file cannot be loaded
     */
    private static function loadIsoMappings(): array
    {
        if (self::$isoMappings !== null) {
            return self::$isoMappings;
        }

        if (!file_exists(self::ISO_MAPPINGS_FILE)) {
            throw new \RuntimeException('ISO mappings file not found: ' . self::ISO_MAPPINGS_FILE);
        }

        $content = file_get_contents(self::ISO_MAPPINGS_FILE);
        if ($content === false) {
            throw new \RuntimeException('Cannot read ISO mappings file: ' . self::ISO_MAPPINGS_FILE);
        }

        $mappings = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Invalid JSON in ISO mappings file: ' . json_last_error_msg());
        }

        self::$isoMappings = $mappings;
        return $mappings;
    }
    
    /**
     * Constructor.
     * 
     * @param string $defaultLocale Default locale for country names
     * @param LoggerInterface|null $logger Logger instance (optional)
     */
    public function __construct(string $defaultLocale = 'en', ?LoggerInterface $logger = null)
    {
        $this->defaultLocale = $defaultLocale;
        $this->logger = $logger ?? new NullLogger();
    }

    /**
     * Get all available countries with localized names.
     *
     * @param string|null $locale Locale for country names (default: uses configured default)
     * @return array<string, string> Array of country codes mapped to localized names
     */
    public function getAllCountries(?string $locale = null): array
    {
        $countryCodes = $this->getAvailableCountryCodes();
        return $this->getLocalizedCountryNames($countryCodes, $locale ?? $this->defaultLocale);
    }
    
    /**
     * Get all countries for a given continent.
     * 
     * @param string $continentCode UN M49 continent code or ISO continent code
     * @param string|null $locale Locale for country names (default: uses configured default)
     * @return array<string, string> Array of country codes mapped to localized names
     * @throws \InvalidArgumentException If continent code is invalid
     */
    public function getCountriesByContinent(string $continentCode, ?string $locale = null): array
    {
        $m49Code = $this->normalizeContinentCode($continentCode);
        
        if (!ContinentMapping::hasContinentCode($m49Code)) {
            throw new \InvalidArgumentException(sprintf('Invalid continent code: %s', $continentCode));
        }

        $countryCodes = ContinentMapping::getCountriesByContinent($m49Code);
        return $this->getLocalizedCountryNames($countryCodes, $locale ?? $this->defaultLocale);
    }

    /**
     * Get all countries for a given subregion.
     * 
     * @param string $subregionCode UN M49 subregion code or ISO subregion code
     * @param string|null $locale Locale for country names (default: uses configured default)
     * @return array<string, string> Array of country codes mapped to localized names
     * @throws \InvalidArgumentException If subregion code is invalid
     */
    public function getCountriesBySubregion(string $subregionCode, ?string $locale = null): array
    {
        $m49Code = $this->normalizeSubregionCode($subregionCode);
        
        if (!SubregionMapping::hasSubregionCode($m49Code)) {
            throw new \InvalidArgumentException(sprintf('Invalid subregion code: %s', $subregionCode));
        }

        $countryCodes = SubregionMapping::getCountriesBySubregion($m49Code);
        return $this->getLocalizedCountryNames($countryCodes, $locale ?? $this->defaultLocale);
    }

    /**
     * Get the continent code for a given country.
     * 
     * @param string $countryCode ISO 3166-1 alpha-2 country code
     * @param bool $asIsoCode Whether to return ISO continent code instead of UN M49 code
     * @return string|null Continent code or null if not found
     */
    public function getContinentCode(string $countryCode, bool $asIsoCode = false): ?string
    {
        $m49Code = ContinentMapping::getContinentCode($countryCode);
        
        if ($m49Code === null) {
            return null;
        }

        if ($asIsoCode) {
            $mappings = self::loadIsoMappings();
            $m49ToIso = $mappings['continent_mappings']['m49_to_iso'] ?? [];
            return $m49ToIso[$m49Code] ?? $m49Code;
        }

        return $m49Code;
    }

    /**
     * Get the subregion code for a given country.
     * 
     * @param string $countryCode ISO 3166-1 alpha-2 country code
     * @return string|null UN M49 subregion code or null if not found
     */
    public function getSubregionCode(string $countryCode): ?string
    {
        return SubregionMapping::getSubregionCode($countryCode);
    }

    /**
     * Get all available continent codes.
     * 
     * @param bool $asIsoCodes Whether to return ISO continent codes instead of UN M49 codes
     * @return array<string> Array of continent codes
     */
    public function getAvailableContinentCodes(bool $asIsoCodes = false): array
    {
        $m49Codes = ContinentMapping::getAvailableContinentCodes();
        
        if (!$asIsoCodes) {
            return $m49Codes;
        }

        $mappings = self::loadIsoMappings();
        $m49ToIso = $mappings['continent_mappings']['m49_to_iso'] ?? [];
        
        return array_map(
            fn(string $code) => $m49ToIso[$code] ?? $code,
            $m49Codes
        );
    }



    /**
     * Get all available country codes.
     * 
     * @return array<string> Array of country codes
     */
    public function getAvailableCountryCodes(): array
    {
        $continentCountries = ContinentMapping::getAvailableCountryCodes();
        $subregionCountries = SubregionMapping::getAvailableCountryCodes();
        return array_unique(array_merge($continentCountries, $subregionCountries));
    }

    /**
     * Get localized country names for given country codes.
     * 
     * @param array<string> $countryCodes Array of ISO 3166-1 alpha-2 country codes
     * @param string $locale Locale for country names
     * @return array<string, string> Array of country codes mapped to localized names
     */
    private function getLocalizedCountryNames(array $countryCodes, string $locale): array
    {
        $result = [];

        foreach ($countryCodes as $countryCode) {

            try {
                $countryName = Countries::getName($countryCode, $locale);
                if ($countryName !== null) {
                    $result[$countryCode] = $countryName;
                } else {
                    // Fallback to English if translation not available
                    $fallbackName = Countries::getName($countryCode, 'en');
                    if ($fallbackName !== null) {
                        $result[$countryCode] = $fallbackName;
                        $this->logger->warning(
                            'Translation not available for country {code} in locale {locale}, using English fallback',
                            ['code' => $countryCode, 'locale' => $locale]
                        );
                    } else {
                        // Use country code as fallback
                        $result[$countryCode] = $countryCode;
                        $this->logger->error(
                            'No translation available for country {code} in any locale',
                            ['code' => $countryCode]
                        );
                    }
                }
            } catch (\Exception $e) {
                // Handle any exceptions from Symfony Intl
                $this->logger->error(
                    'Error getting translation for country {code} in locale {locale}: {error}',
                    ['code' => $countryCode, 'locale' => $locale, 'error' => $e->getMessage()]
                );
                
                // Use country code as fallback
                $result[$countryCode] = $countryCode;
            }
        }

        // Sort by country name
        asort($result);

        return $result;
    }

    /**
     * Normalize continent code (convert ISO to UN M49 if needed).
     * 
     * @param string $continentCode Continent code (ISO or UN M49)
     * @return string UN M49 continent code
     */
    private function normalizeContinentCode(string $continentCode): string
    {
        $mappings = self::loadIsoMappings();
        $isoToM49 = $mappings['continent_mappings']['iso_to_m49'] ?? [];
        return $isoToM49[$continentCode] ?? $continentCode;
    }

    /**
     * Normalize subregion code (convert ISO to UN M49 if needed).
     * 
     * @param string $subregionCode Subregion code (ISO or UN M49)
     * @return string UN M49 subregion code
     */
    private function normalizeSubregionCode(string $subregionCode): string
    {
        $mappings = self::loadIsoMappings();
        $isoToM49 = $mappings['subregion_mappings']['iso_to_m49'] ?? [];
        return $isoToM49[$subregionCode] ?? $subregionCode;
    }

    /**
     * Check if a country code exists in the mapping.
     * 
     * @param string $countryCode ISO 3166-1 alpha-2 country code
     * @return bool True if the country code exists
     */
    public function hasCountryCode(string $countryCode): bool
    {
        return ContinentMapping::hasCountryCode($countryCode) || SubregionMapping::hasCountryCode($countryCode);
    }

    /**
     * Check if a continent code exists in the mapping.
     * 
     * @param string $continentCode UN M49 continent code or ISO continent code
     * @return bool True if the continent code exists
     */
    public function hasContinentCode(string $continentCode): bool
    {
        $m49Code = $this->normalizeContinentCode($continentCode);
        return ContinentMapping::hasContinentCode($m49Code);
    }

    /**
     * Check if a subregion code exists in the mapping.
     * 
     * @param string $subregionCode UN M49 subregion code
     * @return bool True if the subregion code exists
     */
    public function hasSubregionCode(string $subregionCode): bool
    {
        return SubregionMapping::hasSubregionCode($subregionCode);
    }

    /**
     * Get continent information including name and available countries.
     * 
     * @param string $continentCode UN M49 continent code or ISO continent code
     * @param string|null $locale Locale for country names (default: uses configured default)
     * @return array{code: string, name: string, countries: array<string, string>}|null Continent information or null if not found
     */
    public function getContinentInfo(string $continentCode, ?string $locale = null): ?array
    {
        $m49Code = $this->normalizeContinentCode($continentCode);
        
        if (!ContinentMapping::hasContinentCode($m49Code)) {
            return null;
        }

        return [
            'code' => $continentCode,
            'name' => ContinentMapping::getName($m49Code) ?? $continentCode,
            'countries' => $this->getCountriesByContinent($m49Code, $locale ?? $this->defaultLocale),
        ];
    }

    /**
     * Get subregion information including name and available countries.
     * 
     * @param string $subregionCode UN M49 subregion code
     * @param string|null $locale Locale for country names (default: uses configured default)
     * @return array{code: string, name: string, countries: array<string, string>}|null Subregion information or null if not found
     */
    public function getSubregionInfo(string $subregionCode, ?string $locale = null): ?array
    {
        if (!SubregionMapping::hasSubregionCode($subregionCode)) {
            return null;
        }

        return [
            'code' => $subregionCode,
            'name' => SubregionMapping::getName($subregionCode) ?? $subregionCode,
            'countries' => $this->getCountriesBySubregion($subregionCode, $locale ?? $this->defaultLocale),
        ];
    }

    /**
     * Get ISO continent code for a UN M49 continent code.
     * 
     * @param string $m49Code UN M49 continent code
     * @return string|null ISO continent code or null if not found
     */
    public function getIsoContinentCode(string $m49Code): ?string
    {
        $mappings = self::loadIsoMappings();
        $m49ToIso = $mappings['continent_mappings']['m49_to_iso'] ?? [];
        return $m49ToIso[$m49Code] ?? null;
    }

    /**
     * Get UN M49 continent code for an ISO continent code.
     * 
     * @param string $isoCode ISO continent code
     * @return string|null UN M49 continent code or null if not found
     */
    public function getM49ContinentCode(string $isoCode): ?string
    {
        $mappings = self::loadIsoMappings();
        $isoToM49 = $mappings['continent_mappings']['iso_to_m49'] ?? [];
        return $isoToM49[$isoCode] ?? null;
    }

    /**
     * Get ISO subregion code for a UN M49 subregion code.
     * 
     * @param string $m49Code UN M49 subregion code
     * @return string|null ISO subregion code or null if not found
     */
    public function getIsoSubregionCode(string $m49Code): ?string
    {
        $mappings = self::loadIsoMappings();
        $m49ToIso = $mappings['subregion_mappings']['m49_to_iso'] ?? [];
        return $m49ToIso[$m49Code] ?? null;
    }

    /**
     * Get UN M49 subregion code for an ISO subregion code.
     * 
     * @param string $isoCode ISO subregion code
     * @return string|null UN M49 subregion code or null if not found
     */
    public function getM49SubregionCode(string $isoCode): ?string
    {
        $mappings = self::loadIsoMappings();
        $isoToM49 = $mappings['subregion_mappings']['iso_to_m49'] ?? [];
        return $isoToM49[$isoCode] ?? null;
    }

    /**
     * Get all available subregion codes.
     * 
     * @param bool $asIsoCodes Whether to return ISO subregion codes instead of UN M49 codes
     * @return array<string> Array of subregion codes
     */
    public function getAvailableSubregionCodes(bool $asIsoCodes = false): array
    {
        $m49Codes = SubregionMapping::getAvailableSubregionCodes();
        
        if (!$asIsoCodes) {
            return $m49Codes;
        }

        $mappings = self::loadIsoMappings();
        $m49ToIso = $mappings['subregion_mappings']['m49_to_iso'] ?? [];
        
        return array_map(
            fn(string $code) => $m49ToIso[$code] ?? $code,
            $m49Codes
        );
    }
} 