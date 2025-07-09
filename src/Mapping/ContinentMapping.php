<?php

declare(strict_types=1);

namespace Ydee\IntlRegion\Mapping;

/**
 * UN M49 continent mapping.
 * 
 * Maps ISO 3166-1 alpha-2 country codes to UN M49 continent codes.
 * Data is loaded from JSON file for maintainability.
 */
final class ContinentMapping
{
    private static ?array $mapping = null;
    private static ?array $names = null;
    private static ?array $availableCodes = null;

    private const JSON_FILE = __DIR__ . '/../../data/mapping/continent.json';

    /**
     * Load mapping data from JSON file.
     */
    private static function loadData(): void
    {
        if (self::$mapping !== null) {
            return;
        }

        if (!file_exists(self::JSON_FILE)) {
            throw new \RuntimeException('Continent mapping JSON file not found: ' . self::JSON_FILE);
        }

        $data = json_decode(file_get_contents(self::JSON_FILE), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Invalid JSON in continent mapping file: ' . json_last_error_msg());
        }

        self::$mapping = $data['mapping'] ?? [];
        self::$names = $data['names'] ?? [];
    }

    /**
     * Get the continent code for a given country code.
     * 
     * @param string $countryCode ISO 3166-1 alpha-2 country code
     * @return string|null UN M49 continent code or null if not found
     */
    public static function getContinentCode(string $countryCode): ?string
    {
        self::loadData();
        return self::$mapping[strtoupper($countryCode)] ?? null;
    }

    /**
     * Get all country codes for a given continent.
     * 
     * @param string $continentCode UN M49 continent code
     * @return array<string> Array of country codes
     */
    public static function getCountriesByContinent(string $continentCode): array
    {
        self::loadData();
        return array_keys(array_filter(self::$mapping, fn($code) => $code === $continentCode));
    }

    /**
     * Get all available continent codes.
     * 
     * @return array<string> Array of continent codes
     */
    public static function getAvailableContinentCodes(): array
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
     * Get the English name for a continent code.
     *
     * @param string $continentCode
     * @return string|null
     */
    public static function getName(string $continentCode): ?string
    {
        self::loadData();
        return self::$names[$continentCode] ?? null;
    }

    /**
     * Check if a continent code exists in the mapping.
     *
     * @param string $continentCode UN M49 continent code
     * @return bool True if the continent code exists
     */
    public static function hasContinentCode(string $continentCode): bool
    {
        if (self::$availableCodes === null) {
            self::loadData();
            self::$availableCodes = array_unique(array_values(self::$mapping));
        }
        return in_array($continentCode, self::$availableCodes, true);
    }
} 