<?php

declare(strict_types=1);

namespace Ydee\IntlRegion\Mapping;

/**
 * UN M49 subregion mapping.
 * 
 * Maps ISO 3166-1 alpha-2 country codes to UN M49 subregion codes.
 * Data is loaded from JSON file for maintainability.
 */
final class SubregionMapping
{
    private static ?array $mapping = null;
    private static ?array $names = null;
    private static ?array $availableCodes = null;

    private const JSON_FILE = __DIR__ . '/../../data/mapping/subregion.json';

    /**
     * Load mapping data from JSON file.
     */
    private static function loadData(): void
    {
        if (self::$mapping !== null) {
            return;
        }

        if (!file_exists(self::JSON_FILE)) {
            throw new \RuntimeException('Subregion mapping JSON file not found: ' . self::JSON_FILE);
        }

        $data = json_decode(file_get_contents(self::JSON_FILE), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Invalid JSON in subregion mapping file: ' . json_last_error_msg());
        }

        self::$mapping = $data['mapping'] ?? [];
        self::$names = $data['names'] ?? [];
    }

    /**
     * Get the subregion code for a given country code.
     * 
     * @param string $countryCode ISO 3166-1 alpha-2 country code
     * @return string|null UN M49 subregion code or null if not found
     */
    public static function getSubregionCode(string $countryCode): ?string
    {
        self::loadData();
        return self::$mapping[strtoupper($countryCode)] ?? null;
    }

    /**
     * Get all country codes for a given subregion.
     * 
     * @param string $subregionCode UN M49 subregion code
     * @return array<string> Array of country codes
     */
    public static function getCountriesBySubregion(string $subregionCode): array
    {
        self::loadData();
        return array_keys(array_filter(self::$mapping, fn($code) => $code === $subregionCode));
    }

    /**
     * Get all available subregion codes.
     * 
     * @return array<string> Array of subregion codes
     */
    public static function getAvailableSubregionCodes(): array
    {
        if (self::$availableCodes === null) {
            self::loadData();
            self::$availableCodes = array_unique(array_values(self::$mapping));
        }
        return self::$availableCodes;
    }

    /**
     * Get all available country codes.
     * 
     * @return array<string> Array of country codes
     */
    public static function getAvailableCountryCodes(): array
    {
        self::loadData();
        return array_keys(self::$mapping);
    }

    /**
     * Check if a country code exists in the mapping.
     * 
     * @param string $countryCode ISO 3166-1 alpha-2 country code
     * @return bool True if the country code exists
     */
    public static function hasCountryCode(string $countryCode): bool
    {
        self::loadData();
        return isset(self::$mapping[strtoupper($countryCode)]);
    }

    /**
     * Get the English name for a subregion code.
     *
     * @param string $subregionCode
     * @return string|null
     */
    public static function getName(string $subregionCode): ?string
    {
        self::loadData();
        return self::$names[$subregionCode] ?? null;
    }

    /**
     * Check if a subregion code exists in the mapping.
     *
     * @param string $subregionCode UN M49 subregion code
     * @return bool True if the subregion code exists
     */
    public static function hasSubregionCode(string $subregionCode): bool
    {
        if (self::$availableCodes === null) {
            self::loadData();
            self::$availableCodes = array_unique(array_values(self::$mapping));
        }
        return in_array($subregionCode, self::$availableCodes, true);
    }
} 