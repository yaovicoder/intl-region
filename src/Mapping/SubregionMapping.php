<?php

declare(strict_types=1);

namespace Ydee\IntlRegion\Mapping;

/**
 * Static mapping of ISO 3166-1 country codes to UN M49 subregion codes.
 * 
 * This mapping follows the UN M49 standard for geographic subregions.
 * Subregion codes include:
 * - 014: Eastern Africa
 * - 017: Middle Africa
 * - 015: Northern Africa
 * - 018: Southern Africa
 * - 011: Western Africa
 * - 005: South America
 * - 013: Central America
 * - 021: Northern America
 * - 029: Caribbean
 * - 030: Eastern Asia
 * - 034: Southern Asia
 * - 035: South-Eastern Asia
 * - 143: Central Asia
 * - 145: Western Asia
 * - 151: Eastern Europe
 * - 154: Northern Europe
 * - 039: Southern Europe
 * - 155: Western Europe
 * - 053: Australia and New Zealand
 * - 054: Melanesia
 * - 057: Micronesia
 * - 061: Polynesia
 */
final class SubregionMapping
{
    /**
     * Mapping of country codes to subregion codes.
     * 
     * @var array<string, string>
     */
    private static array $mapping = [
        // Eastern Africa (014)
        'BI' => '014', // Burundi
        'KM' => '014', // Comoros
        'DJ' => '014', // Djibouti
        'ER' => '014', // Eritrea
        'ET' => '014', // Ethiopia
        'KE' => '014', // Kenya
        'MG' => '014', // Madagascar
        'MW' => '014', // Malawi
        'MU' => '014', // Mauritius
        'MZ' => '014', // Mozambique
        'RW' => '014', // Rwanda
        'SC' => '014', // Seychelles
        'SO' => '014', // Somalia
        'SS' => '014', // South Sudan
        'TZ' => '014', // Tanzania
        'UG' => '014', // Uganda
        'ZM' => '014', // Zambia
        'ZW' => '014', // Zimbabwe

        // Middle Africa (017)
        'AO' => '017', // Angola
        'CM' => '017', // Cameroon
        'CF' => '017', // Central African Republic
        'TD' => '017', // Chad
        'CG' => '017', // Congo
        'CD' => '017', // Congo, Democratic Republic of the
        'GQ' => '017', // Equatorial Guinea
        'GA' => '017', // Gabon
        'ST' => '017', // Sao Tome and Principe

        // Northern Africa (015)
        'DZ' => '015', // Algeria
        'EG' => '015', // Egypt
        'LY' => '015', // Libya
        'MA' => '015', // Morocco
        'SD' => '015', // Sudan
        'TN' => '015', // Tunisia

        // Southern Africa (018)
        'BW' => '018', // Botswana
        'LS' => '018', // Lesotho
        'NA' => '018', // Namibia
        'ZA' => '018', // South Africa
        'SZ' => '018', // Eswatini

        // Western Africa (011)
        'BJ' => '011', // Benin
        'BF' => '011', // Burkina Faso
        'CV' => '011', // Cape Verde
        'CI' => '011', // Côte d'Ivoire
        'GM' => '011', // Gambia
        'GH' => '011', // Ghana
        'GN' => '011', // Guinea
        'GW' => '011', // Guinea-Bissau
        'LR' => '011', // Liberia
        'ML' => '011', // Mali
        'MR' => '011', // Mauritania
        'NE' => '011', // Niger
        'NG' => '011', // Nigeria
        'SN' => '011', // Senegal
        'SL' => '011', // Sierra Leone
        'TG' => '011', // Togo

        // South America (005)
        'AR' => '005', // Argentina
        'BO' => '005', // Bolivia
        'BR' => '005', // Brazil
        'CL' => '005', // Chile
        'CO' => '005', // Colombia
        'EC' => '005', // Ecuador
        'GY' => '005', // Guyana
        'PY' => '005', // Paraguay
        'PE' => '005', // Peru
        'SR' => '005', // Suriname
        'UY' => '005', // Uruguay
        'VE' => '005', // Venezuela

        // Central America (013)
        'BZ' => '013', // Belize
        'CR' => '013', // Costa Rica
        'SV' => '013', // El Salvador
        'GT' => '013', // Guatemala
        'HN' => '013', // Honduras
        'MX' => '013', // Mexico
        'NI' => '013', // Nicaragua
        'PA' => '013', // Panama

        // Northern America (021)
        'CA' => '021', // Canada
        'US' => '021', // United States

        // Caribbean (029)
        'AG' => '029', // Antigua and Barbuda
        'BS' => '029', // Bahamas
        'BB' => '029', // Barbados
        'CU' => '029', // Cuba
        'DM' => '029', // Dominica
        'DO' => '029', // Dominican Republic
        'GD' => '029', // Grenada
        'HT' => '029', // Haiti
        'JM' => '029', // Jamaica
        'KN' => '029', // Saint Kitts and Nevis
        'LC' => '029', // Saint Lucia
        'VC' => '029', // Saint Vincent and the Grenadines
        'TT' => '029', // Trinidad and Tobago

        // Eastern Asia (030)
        'CN' => '030', // China
        'HK' => '030', // Hong Kong
        'JP' => '030', // Japan
        'KP' => '030', // North Korea
        'KR' => '030', // South Korea
        'MO' => '030', // Macao
        'MN' => '030', // Mongolia
        'TW' => '030', // Taiwan

        // Southern Asia (034)
        'AF' => '034', // Afghanistan
        'BD' => '034', // Bangladesh
        'BT' => '034', // Bhutan
        'IO' => '034', // British Indian Ocean Territory
        'IN' => '034', // India
        'IR' => '034', // Iran
        'MV' => '034', // Maldives
        'NP' => '034', // Nepal
        'PK' => '034', // Pakistan
        'LK' => '034', // Sri Lanka

        // South-Eastern Asia (035)
        'BN' => '035', // Brunei Darussalam
        'KH' => '035', // Cambodia
        'ID' => '035', // Indonesia
        'LA' => '035', // Laos
        'MY' => '035', // Malaysia
        'MM' => '035', // Myanmar
        'PH' => '035', // Philippines
        'SG' => '035', // Singapore
        'TH' => '035', // Thailand
        'TL' => '035', // Timor-Leste
        'VN' => '035', // Vietnam

        // Central Asia (143)
        'KZ' => '143', // Kazakhstan
        'KG' => '143', // Kyrgyzstan
        'TJ' => '143', // Tajikistan
        'TM' => '143', // Turkmenistan
        'UZ' => '143', // Uzbekistan

        // Western Asia (145)
        'AM' => '145', // Armenia
        'AZ' => '145', // Azerbaijan
        'BH' => '145', // Bahrain
        'CY' => '145', // Cyprus
        'GE' => '145', // Georgia
        'IQ' => '145', // Iraq
        'IL' => '145', // Israel
        'JO' => '145', // Jordan
        'KW' => '145', // Kuwait
        'LB' => '145', // Lebanon
        'OM' => '145', // Oman
        'QA' => '145', // Qatar
        'SA' => '145', // Saudi Arabia
        'SY' => '145', // Syria
        'AE' => '145', // United Arab Emirates
        'YE' => '145', // Yemen

        // Eastern Europe (151)
        'BY' => '151', // Belarus
        'BG' => '151', // Bulgaria
        'CZ' => '151', // Czech Republic
        'HU' => '151', // Hungary
        'PL' => '151', // Poland
        'MD' => '151', // Moldova
        'RO' => '151', // Romania
        'RU' => '151', // Russia
        'SK' => '151', // Slovakia
        'UA' => '151', // Ukraine

        // Northern Europe (154)
        'DK' => '154', // Denmark
        'EE' => '154', // Estonia
        'FI' => '154', // Finland
        'IS' => '154', // Iceland
        'IE' => '154', // Ireland
        'LV' => '154', // Latvia
        'LT' => '154', // Lithuania
        'NO' => '154', // Norway
        'SE' => '154', // Sweden
        'GB' => '154', // United Kingdom

        // Southern Europe (039)
        'AL' => '039', // Albania
        'AD' => '039', // Andorra
        'BA' => '039', // Bosnia and Herzegovina
        'HR' => '039', // Croatia
        'GR' => '039', // Greece
        'IT' => '039', // Italy
        'MT' => '039', // Malta
        'ME' => '039', // Montenegro
        'MK' => '039', // North Macedonia
        'PT' => '039', // Portugal
        'SM' => '039', // San Marino
        'RS' => '039', // Serbia
        'SI' => '039', // Slovenia
        'ES' => '039', // Spain
        'VA' => '039', // Vatican City

        // Western Europe (155)
        'AT' => '155', // Austria
        'BE' => '155', // Belgium
        'FR' => '155', // France
        'DE' => '155', // Germany
        'LI' => '155', // Liechtenstein
        'LU' => '155', // Luxembourg
        'MC' => '155', // Monaco
        'NL' => '155', // Netherlands
        'CH' => '155', // Switzerland

        // Australia and New Zealand (053)
        'AU' => '053', // Australia
        'NZ' => '053', // New Zealand

        // Melanesia (054)
        'FJ' => '054', // Fiji
        'PG' => '054', // Papua New Guinea
        'SB' => '054', // Solomon Islands
        'VU' => '054', // Vanuatu

        // Micronesia (057)
        'KI' => '057', // Kiribati
        'MH' => '057', // Marshall Islands
        'FM' => '057', // Micronesia
        'NR' => '057', // Nauru
        'PW' => '057', // Palau

        // Polynesia (061)
        'WS' => '061', // Samoa
        'TO' => '061', // Tonga
        'TV' => '061', // Tuvalu
    ];

    /**
     * Get the subregion code for a given country code.
     * 
     * @param string $countryCode ISO 3166-1 alpha-2 country code
     * @return string|null UN M49 subregion code or null if not found
     */
    public static function getSubregionCode(string $countryCode): ?string
    {
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
        return array_keys(array_filter(self::$mapping, fn($code) => $code === $subregionCode));
    }

    /**
     * Get all available subregion codes.
     * 
     * @return array<string> Array of subregion codes
     */
    public static function getAvailableSubregionCodes(): array
    {
        static $codes = null;
        if ($codes === null) {
            $codes = array_unique(array_values(self::$mapping));
        }
        return $codes;
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
     * Check if a subregion code exists in the mapping.
     * 
     * @param string $subregionCode UN M49 subregion code
     * @return bool True if the subregion code exists
     */
    public static function hasSubregionCode(string $subregionCode): bool
    {
        return in_array($subregionCode, self::getAvailableSubregionCodes(), true);
    }
} 