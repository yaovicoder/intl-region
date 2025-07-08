<?php

declare(strict_types=1);

namespace Ydee\IntlRegion;

use Symfony\Component\Intl\Countries;
use Ydee\IntlRegion\Mapping\ContinentMapping;
use Ydee\IntlRegion\Mapping\SubregionMapping;

/**
 * Core class for region-based country filtering.
 * 
 * This class provides methods to get countries by continent, subregion,
 * and to list available codes using UN M49 standard.
 */
class RegionProvider
{
    /**
     * Default locale for country names.
     */
    private string $defaultLocale;

    /**
     * Constructor.
     * 
     * @param string $defaultLocale Default locale for country names
     */
    public function __construct(string $defaultLocale = 'en')
    {
        $this->defaultLocale = $defaultLocale;
    }
    /**
     * Get all countries for a given continent.
     * 
     * @param string $continentCode UN M49 continent code
     * @param string|null $locale Locale for country names (default: uses configured default)
     * @return array<string, string> Array of country codes mapped to localized names
     * @throws \InvalidArgumentException If continent code is invalid
     */
    public function getCountriesByContinent(string $continentCode, ?string $locale = null): array
    {
        if (!ContinentMapping::hasContinentCode($continentCode)) {
            throw new \InvalidArgumentException(sprintf('Invalid continent code: %s', $continentCode));
        }

        $countryCodes = ContinentMapping::getCountriesByContinent($continentCode);
        return $this->getLocalizedCountryNames($countryCodes, $locale ?? $this->defaultLocale);
    }

    /**
     * Get all countries for a given subregion.
     * 
     * @param string $subregionCode UN M49 subregion code
     * @param string|null $locale Locale for country names (default: uses configured default)
     * @return array<string, string> Array of country codes mapped to localized names
     * @throws \InvalidArgumentException If subregion code is invalid
     */
    public function getCountriesBySubregion(string $subregionCode, ?string $locale = null): array
    {
        if (!SubregionMapping::hasSubregionCode($subregionCode)) {
            throw new \InvalidArgumentException(sprintf('Invalid subregion code: %s', $subregionCode));
        }

        $countryCodes = SubregionMapping::getCountriesBySubregion($subregionCode);
        return $this->getLocalizedCountryNames($countryCodes, $locale ?? $this->defaultLocale);
    }

    /**
     * Get the continent code for a given country.
     * 
     * @param string $countryCode ISO 3166-1 alpha-2 country code
     * @return string|null UN M49 continent code or null if not found
     */
    public function getContinentCode(string $countryCode): ?string
    {
        return ContinentMapping::getContinentCode($countryCode);
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
     * @return array<string> Array of continent codes
     */
    public function getAvailableContinentCodes(): array
    {
        return ContinentMapping::getAvailableContinentCodes();
    }

    /**
     * Get all available subregion codes.
     * 
     * @return array<string> Array of subregion codes
     */
    public function getAvailableSubregionCodes(): array
    {
        return SubregionMapping::getAvailableSubregionCodes();
    }

    /**
     * Get all available country codes.
     * 
     * @return array<string> Array of country codes
     */
    public function getAvailableCountryCodes(): array
    {
        return ContinentMapping::getAvailableCountryCodes();
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
            $countryName = Countries::getName($countryCode, $locale);
            if ($countryName !== null) {
                $result[$countryCode] = $countryName;
            }
        }

        // Sort by country name
        asort($result);

        return $result;
    }

    /**
     * Check if a country code exists in the mapping.
     * 
     * @param string $countryCode ISO 3166-1 alpha-2 country code
     * @return bool True if the country code exists
     */
    public function hasCountryCode(string $countryCode): bool
    {
        return ContinentMapping::hasCountryCode($countryCode);
    }

    /**
     * Check if a continent code exists in the mapping.
     * 
     * @param string $continentCode UN M49 continent code
     * @return bool True if the continent code exists
     */
    public function hasContinentCode(string $continentCode): bool
    {
        return ContinentMapping::hasContinentCode($continentCode);
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
     * @param string $continentCode UN M49 continent code
     * @param string|null $locale Locale for country names (default: uses configured default)
     * @return array{code: string, name: string, countries: array<string, string>}|null Continent information or null if not found
     */
    public function getContinentInfo(string $continentCode, ?string $locale = null): ?array
    {
        if (!ContinentMapping::hasContinentCode($continentCode)) {
            return null;
        }

        $continentNames = [
            '002' => 'Africa',
            '019' => 'Americas',
            '142' => 'Asia',
            '150' => 'Europe',
            '009' => 'Oceania',
        ];

        return [
            'code' => $continentCode,
            'name' => $continentNames[$continentCode] ?? $continentCode,
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

        $subregionNames = [
            '014' => 'Eastern Africa',
            '017' => 'Middle Africa',
            '015' => 'Northern Africa',
            '018' => 'Southern Africa',
            '011' => 'Western Africa',
            '005' => 'South America',
            '013' => 'Central America',
            '021' => 'Northern America',
            '029' => 'Caribbean',
            '030' => 'Eastern Asia',
            '034' => 'Southern Asia',
            '035' => 'South-Eastern Asia',
            '143' => 'Central Asia',
            '145' => 'Western Asia',
            '151' => 'Eastern Europe',
            '154' => 'Northern Europe',
            '039' => 'Southern Europe',
            '155' => 'Western Europe',
            '053' => 'Australia and New Zealand',
            '054' => 'Melanesia',
            '057' => 'Micronesia',
            '061' => 'Polynesia',
        ];

        return [
            'code' => $subregionCode,
            'name' => $subregionNames[$subregionCode] ?? $subregionCode,
            'countries' => $this->getCountriesBySubregion($subregionCode, $locale ?? $this->defaultLocale),
        ];
    }
} 