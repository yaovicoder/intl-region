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
     * ISO continent code to UN M49 mapping.
     */
    private const ISO_TO_M49_MAPPING = [
        'AFR' => '002', // Africa
        'AMR' => '019', // Americas
        'ASI' => '142', // Asia
        'EUR' => '150', // Europe
        'OCE' => '009', // Oceania
        'ANT' => '010', // Antarctica
    ];

    /**
     * UN M49 to ISO continent code mapping.
     */
    private const M49_TO_ISO_MAPPING = [
        '002' => 'AFR', // Africa
        '019' => 'AMR', // Americas
        '142' => 'ASI', // Asia
        '150' => 'EUR', // Europe
        '009' => 'OCE', // Oceania
        '010' => 'ANT', // Antarctica
    ];

    /**
     * ISO subregion code to UN M49 mapping.
     */
    private const ISO_SUBREGION_TO_M49_MAPPING = [
        // Africa
        'NAF' => '015', // Northern Africa
        'EAF' => '014', // Eastern Africa
        'MAF' => '017', // Middle Africa
        'SAF' => '018', // Southern Africa
        'WAF' => '011', // Western Africa
        'SSA' => '202', // Sub-Saharan Africa
        
        // Americas
        'NAM' => '021', // Northern America
        'CAM' => '013', // Central America
        'SAM' => '005', // South America
        'CAR' => '029', // Caribbean
        'LAC' => '419', // Latin America and the Caribbean
        
        // Asia
        'EAS' => '030', // Eastern Asia
        'SAS' => '034', // Southern Asia
        'SEA' => '035', // South-Eastern Asia
        'CAS' => '143', // Central Asia
        'WAS' => '145', // Western Asia
        
        // Europe
        'EEU' => '151', // Eastern Europe
        'NEU' => '154', // Northern Europe
        'SEU' => '039', // Southern Europe
        'WEU' => '155', // Western Europe
        
        // Oceania
        'ANZ' => '053', // Australia and New Zealand
        'MEL' => '054', // Melanesia
        'MIC' => '057', // Micronesia
        'POL' => '061', // Polynesia
    ];

    /**
     * UN M49 to ISO subregion code mapping.
     */
    private const M49_TO_ISO_SUBREGION_MAPPING = [
        // Africa
        '015' => 'NAF', // Northern Africa
        '014' => 'EAF', // Eastern Africa
        '017' => 'MAF', // Middle Africa
        '018' => 'SAF', // Southern Africa
        '011' => 'WAF', // Western Africa
        '202' => 'SSA', // Sub-Saharan Africa
        
        // Americas
        '021' => 'NAM', // Northern America
        '013' => 'CAM', // Central America
        '005' => 'SAM', // South America
        '029' => 'CAR', // Caribbean
        '419' => 'LAC', // Latin America and the Caribbean
        
        // Asia
        '030' => 'EAS', // Eastern Asia
        '034' => 'SAS', // Southern Asia
        '035' => 'SEA', // South-Eastern Asia
        '143' => 'CAS', // Central Asia
        '145' => 'WAS', // Western Asia
        
        // Europe
        '151' => 'EEU', // Eastern Europe
        '154' => 'NEU', // Northern Europe
        '039' => 'SEU', // Southern Europe
        '155' => 'WEU', // Western Europe
        
        // Oceania
        '053' => 'ANZ', // Australia and New Zealand
        '054' => 'MEL', // Melanesia
        '057' => 'MIC', // Micronesia
        '061' => 'POL', // Polynesia
    ];

    /**
     * Geographically incorrect country codes that should be excluded.
     * These are classified incorrectly in UN M49 data.
     */
    private const GEOGRAPHICALLY_INCORRECT_CODES = [
        'TF', // French Southern Territories - classified as Africa but geographically near Antarctica
    ];
    
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

        return $asIsoCode ? (self::M49_TO_ISO_MAPPING[$m49Code] ?? $m49Code) : $m49Code;
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

        return array_map(
            fn(string $code) => self::M49_TO_ISO_MAPPING[$code] ?? $code,
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
        $allCountries = array_unique(array_merge($continentCountries, $subregionCountries));
        
        // Filter out geographically incorrect country codes
        return array_filter($allCountries, fn(string $code) => !in_array($code, self::GEOGRAPHICALLY_INCORRECT_CODES));
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
            // Skip geographically incorrect country codes
            if (in_array($countryCode, self::GEOGRAPHICALLY_INCORRECT_CODES)) {
                $this->logger->warning('Skipping geographically incorrect country code: {code}', ['code' => $countryCode]);
                continue;
            }

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
        return self::ISO_TO_M49_MAPPING[$continentCode] ?? $continentCode;
    }

    /**
     * Normalize subregion code (convert ISO to UN M49 if needed).
     * 
     * @param string $subregionCode Subregion code (ISO or UN M49)
     * @return string UN M49 subregion code
     */
    private function normalizeSubregionCode(string $subregionCode): string
    {
        return self::ISO_SUBREGION_TO_M49_MAPPING[$subregionCode] ?? $subregionCode;
    }

    /**
     * Check if a country code exists in the mapping.
     * 
     * @param string $countryCode ISO 3166-1 alpha-2 country code
     * @return bool True if the country code exists
     */
    public function hasCountryCode(string $countryCode): bool
    {
        if (in_array($countryCode, self::GEOGRAPHICALLY_INCORRECT_CODES)) {
            return false;
        }
        
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
            'countries' => $this->getCountriesByContinent($continentCode, $locale ?? $this->defaultLocale),
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
        return self::M49_TO_ISO_MAPPING[$m49Code] ?? null;
    }

    /**
     * Get UN M49 continent code for an ISO continent code.
     * 
     * @param string $isoCode ISO continent code
     * @return string|null UN M49 continent code or null if not found
     */
    public function getM49ContinentCode(string $isoCode): ?string
    {
        return self::ISO_TO_M49_MAPPING[$isoCode] ?? null;
    }

    /**
     * Get ISO subregion code for a UN M49 subregion code.
     * 
     * @param string $m49Code UN M49 subregion code
     * @return string|null ISO subregion code or null if not found
     */
    public function getIsoSubregionCode(string $m49Code): ?string
    {
        return self::M49_TO_ISO_SUBREGION_MAPPING[$m49Code] ?? null;
    }

    /**
     * Get UN M49 subregion code for an ISO subregion code.
     * 
     * @param string $isoCode ISO subregion code
     * @return string|null UN M49 subregion code or null if not found
     */
    public function getM49SubregionCode(string $isoCode): ?string
    {
        return self::ISO_SUBREGION_TO_M49_MAPPING[$isoCode] ?? null;
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

        return array_map(
            fn(string $code) => self::M49_TO_ISO_SUBREGION_MAPPING[$code] ?? $code,
            $m49Codes
        );
    }
} 