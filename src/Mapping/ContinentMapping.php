<?php

declare(strict_types=1);

namespace Ydee\IntlRegion\Mapping;

/**
 * Static mapping of ISO 3166-1 country codes to UN M49 continent codes.
 * 
 * This mapping follows the UN M49 standard for geographic regions.
 * Continent codes:
 * - 002: Africa
 * - 019: Americas
 * - 142: Asia
 * - 150: Europe
 * - 009: Oceania
 */
final class ContinentMapping
{
    /**
     * Mapping of country codes to continent codes.
     * 
     * @var array<string, string>
     */
    private static array $mapping = [
        // Africa (002)
        'DZ' => '002', // Algeria
        'AO' => '002', // Angola
        'BJ' => '002', // Benin
        'BW' => '002', // Botswana
        'BF' => '002', // Burkina Faso
        'BI' => '002', // Burundi
        'CM' => '002', // Cameroon
        'CV' => '002', // Cape Verde
        'CF' => '002', // Central African Republic
        'TD' => '002', // Chad
        'KM' => '002', // Comoros
        'CG' => '002', // Congo
        'CD' => '002', // Congo, Democratic Republic of the
        'CI' => '002', // Côte d'Ivoire
        'DJ' => '002', // Djibouti
        'EG' => '002', // Egypt
        'GQ' => '002', // Equatorial Guinea
        'ER' => '002', // Eritrea
        'ET' => '002', // Ethiopia
        'GA' => '002', // Gabon
        'GM' => '002', // Gambia
        'GH' => '002', // Ghana
        'GN' => '002', // Guinea
        'GW' => '002', // Guinea-Bissau
        'KE' => '002', // Kenya
        'LS' => '002', // Lesotho
        'LR' => '002', // Liberia
        'LY' => '002', // Libya
        'MG' => '002', // Madagascar
        'MW' => '002', // Malawi
        'ML' => '002', // Mali
        'MR' => '002', // Mauritania
        'MU' => '002', // Mauritius
        'MA' => '002', // Morocco
        'MZ' => '002', // Mozambique
        'NA' => '002', // Namibia
        'NE' => '002', // Niger
        'NG' => '002', // Nigeria
        'RW' => '002', // Rwanda
        'ST' => '002', // Sao Tome and Principe
        'SN' => '002', // Senegal
        'SC' => '002', // Seychelles
        'SL' => '002', // Sierra Leone
        'SO' => '002', // Somalia
        'ZA' => '002', // South Africa
        'SS' => '002', // South Sudan
        'SD' => '002', // Sudan
        'SZ' => '002', // Eswatini
        'TZ' => '002', // Tanzania
        'TG' => '002', // Togo
        'TN' => '002', // Tunisia
        'UG' => '002', // Uganda
        'ZM' => '002', // Zambia
        'ZW' => '002', // Zimbabwe

        // Americas (019)
        'AR' => '019', // Argentina
        'BO' => '019', // Bolivia
        'BR' => '019', // Brazil
        'CL' => '019', // Chile
        'CO' => '019', // Colombia
        'EC' => '019', // Ecuador
        'GY' => '019', // Guyana
        'PY' => '019', // Paraguay
        'PE' => '019', // Peru
        'SR' => '019', // Suriname
        'UY' => '019', // Uruguay
        'VE' => '019', // Venezuela
        'BZ' => '019', // Belize
        'CR' => '019', // Costa Rica
        'SV' => '019', // El Salvador
        'GT' => '019', // Guatemala
        'HN' => '019', // Honduras
        'MX' => '019', // Mexico
        'NI' => '019', // Nicaragua
        'PA' => '019', // Panama
        'CA' => '019', // Canada
        'US' => '019', // United States
        'AG' => '019', // Antigua and Barbuda
        'BS' => '019', // Bahamas
        'BB' => '019', // Barbados
        'CU' => '019', // Cuba
        'DM' => '019', // Dominica
        'DO' => '019', // Dominican Republic
        'GD' => '019', // Grenada
        'HT' => '019', // Haiti
        'JM' => '019', // Jamaica
        'KN' => '019', // Saint Kitts and Nevis
        'LC' => '019', // Saint Lucia
        'VC' => '019', // Saint Vincent and the Grenadines
        'TT' => '019', // Trinidad and Tobago

        // Asia (142)
        'AF' => '142', // Afghanistan
        'BH' => '142', // Bahrain
        'BD' => '142', // Bangladesh
        'BT' => '142', // Bhutan
        'IO' => '142', // British Indian Ocean Territory
        'BN' => '142', // Brunei Darussalam
        'KH' => '142', // Cambodia
        'CN' => '142', // China
        'HK' => '142', // Hong Kong
        'IN' => '142', // India
        'ID' => '142', // Indonesia
        'IR' => '142', // Iran
        'IQ' => '142', // Iraq
        'IL' => '142', // Israel
        'JP' => '142', // Japan
        'JO' => '142', // Jordan
        'KZ' => '142', // Kazakhstan
        'KP' => '142', // North Korea
        'KR' => '142', // South Korea
        'KW' => '142', // Kuwait
        'KG' => '142', // Kyrgyzstan
        'LA' => '142', // Laos
        'LB' => '142', // Lebanon
        'MO' => '142', // Macao
        'MY' => '142', // Malaysia
        'MV' => '142', // Maldives
        'MN' => '142', // Mongolia
        'MM' => '142', // Myanmar
        'NP' => '142', // Nepal
        'OM' => '142', // Oman
        'PK' => '142', // Pakistan
        'PH' => '142', // Philippines
        'QA' => '142', // Qatar
        'SA' => '142', // Saudi Arabia
        'SG' => '142', // Singapore
        'LK' => '142', // Sri Lanka
        'SY' => '142', // Syria
        'TW' => '142', // Taiwan
        'TJ' => '142', // Tajikistan
        'TH' => '142', // Thailand
        'TL' => '142', // Timor-Leste
        'AE' => '142', // United Arab Emirates
        'UZ' => '142', // Uzbekistan
        'VN' => '142', // Vietnam
        'YE' => '142', // Yemen

        // Europe (150)
        'AL' => '150', // Albania
        'AD' => '150', // Andorra
        'AM' => '150', // Armenia
        'AT' => '150', // Austria
        'AZ' => '150', // Azerbaijan
        'BY' => '150', // Belarus
        'BE' => '150', // Belgium
        'BA' => '150', // Bosnia and Herzegovina
        'BG' => '150', // Bulgaria
        'HR' => '150', // Croatia
        'CY' => '150', // Cyprus
        'CZ' => '150', // Czech Republic
        'DK' => '150', // Denmark
        'EE' => '150', // Estonia
        'FI' => '150', // Finland
        'FR' => '150', // France
        'GE' => '150', // Georgia
        'DE' => '150', // Germany
        'GR' => '150', // Greece
        'HU' => '150', // Hungary
        'IS' => '150', // Iceland
        'IE' => '150', // Ireland
        'IT' => '150', // Italy
        'LV' => '150', // Latvia
        'LI' => '150', // Liechtenstein
        'LT' => '150', // Lithuania
        'LU' => '150', // Luxembourg
        'MT' => '150', // Malta
        'MD' => '150', // Moldova
        'MC' => '150', // Monaco
        'ME' => '150', // Montenegro
        'NL' => '150', // Netherlands
        'MK' => '150', // North Macedonia
        'NO' => '150', // Norway
        'PL' => '150', // Poland
        'PT' => '150', // Portugal
        'RO' => '150', // Romania
        'RU' => '150', // Russia
        'SM' => '150', // San Marino
        'RS' => '150', // Serbia
        'SK' => '150', // Slovakia
        'SI' => '150', // Slovenia
        'ES' => '150', // Spain
        'SE' => '150', // Sweden
        'CH' => '150', // Switzerland
        'TR' => '150', // Turkey
        'UA' => '150', // Ukraine
        'GB' => '150', // United Kingdom
        'VA' => '150', // Vatican City

        // Oceania (009)
        'AU' => '009', // Australia
        'FJ' => '009', // Fiji
        'KI' => '009', // Kiribati
        'MH' => '009', // Marshall Islands
        'FM' => '009', // Micronesia
        'NR' => '009', // Nauru
        'NZ' => '009', // New Zealand
        'PW' => '009', // Palau
        'PG' => '009', // Papua New Guinea
        'WS' => '009', // Samoa
        'SB' => '009', // Solomon Islands
        'TO' => '009', // Tonga
        'TV' => '009', // Tuvalu
        'VU' => '009', // Vanuatu
    ];

    /**
     * Get the continent code for a given country code.
     * 
     * @param string $countryCode ISO 3166-1 alpha-2 country code
     * @return string|null UN M49 continent code or null if not found
     */
    public static function getContinentCode(string $countryCode): ?string
    {
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
        return array_keys(array_filter(self::$mapping, fn($code) => $code === $continentCode));
    }

    /**
     * Get all available continent codes.
     * 
     * @return array<string> Array of continent codes
     */
    public static function getAvailableContinentCodes(): array
    {
        return array_unique(array_values(self::$mapping));
    }

    /**
     * Get all available country codes.
     * 
     * @return array<string> Array of country codes
     */
    public static function getAvailableCountryCodes(): array
    {
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
        return isset(self::$mapping[strtoupper($countryCode)]);
    }

    /**
     * Check if a continent code exists in the mapping.
     * 
     * @param string $continentCode UN M49 continent code
     * @return bool True if the continent code exists
     */
    public static function hasContinentCode(string $continentCode): bool
    {
        return in_array($continentCode, self::getAvailableContinentCodes(), true);
    }
} 