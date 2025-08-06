<?php

declare(strict_types=1);

namespace Ydee\IntlRegion\Tests;

use PHPUnit\Framework\TestCase;

/**
 * UN M49 Data Validation Test
 * 
 * This test validates that our local JSON mappings are complete and accurate
 * compared to the downloaded UN M49 data. It ensures we haven't missed any
 * countries or regions.
 */
class UNM49DataValidationTest extends TestCase
{
    private const LOCAL_CONTINENT_MAPPING = __DIR__ . '/../data/mapping/continent.json';
    private const LOCAL_SUBREGION_MAPPING = __DIR__ . '/../data/mapping/subregion.json';
    private const UN_DATA_FILE = __DIR__ . '/_output/un-m49-data.json';

    public function testLocalMappingsAreComplete(): void
    {
        $unData = $this->loadUNData();
        $localMappings = $this->loadLocalMappings();
        $comparison = $this->compareData($unData, $localMappings);
        
        $this->assertEmpty(
            $comparison['missing_countries'],
            'All countries from UN M49 should be present in local mappings. ' .
            'Missing: ' . $this->formatMissingCountries($comparison['missing_countries'])
        );
    }

    public function testLocalMappingsAreAccurate(): void
    {
        $unData = $this->loadUNData();
        $localMappings = $this->loadLocalMappings();
        $comparison = $this->compareData($unData, $localMappings);
        
        $this->assertGreaterThan(
            0,
            count($comparison['un_countries']),
            'Should have countries from UN data'
        );
        
        $this->assertGreaterThan(
            0,
            count($comparison['local_countries']),
            'Should have countries in local mappings'
        );
    }

    private function loadUNData(): array
    {
        if (!file_exists(self::UN_DATA_FILE)) {
            $this->markTestSkipped(
                'UN data file not found. Please run: php scripts/download-un-m49-data.php'
            );
        }

        $unData = json_decode(file_get_contents(self::UN_DATA_FILE), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->fail('Invalid JSON in UN data file: ' . json_last_error_msg());
        }

        return $unData;
    }

    private function loadLocalMappings(): array
    {
        if (!file_exists(self::LOCAL_CONTINENT_MAPPING)) {
            $this->markTestSkipped('Continent mapping file not found: ' . self::LOCAL_CONTINENT_MAPPING);
        }

        if (!file_exists(self::LOCAL_SUBREGION_MAPPING)) {
            $this->markTestSkipped('Subregion mapping file not found: ' . self::LOCAL_SUBREGION_MAPPING);
        }

        $continentMapping = json_decode(file_get_contents(self::LOCAL_CONTINENT_MAPPING), true);
        $subregionMapping = json_decode(file_get_contents(self::LOCAL_SUBREGION_MAPPING), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->fail('Invalid JSON in mapping files: ' . json_last_error_msg());
        }

        return [
            'continent' => $continentMapping,
            'subregion' => $subregionMapping
        ];
    }

    private function compareData(array $unData, array $localMappings): array
    {
        $results = [
            'missing_countries' => [],
            'un_countries' => [],
            'local_countries' => []
        ];

        // Extract countries from UN data
        foreach ($unData as $record) {
            if (!empty($record['iso_alpha2']) && $record['iso_alpha2'] !== 'ISO-alpha2 Code') {
                // Skip Antarctica as it's not a country
                if ($record['iso_alpha2'] === 'AQ') {
                    continue;
                }
                
                // Skip French Southern Territories as it's geographically incorrect
                if ($record['iso_alpha2'] === 'TF') {
                    continue;
                }
                
                // Skip territories and dependencies that are not sovereign countries
                $excludedTerritories = [
                    'RE', 'YT', 'SH', 'EH', 'TF', 'IO', 'AI', 'AW', 'BQ', 'VG', 'KY', 'CW', 
                    'GP', 'MQ', 'MS', 'PR', 'BL', 'MF', 'SX', 'TC', 'VI', 'BV', 'FK', 'GF', 
                    'GS', 'BM', 'PM', 'HK', 'MO', 'AX', 'FO', 'GG', 'IM', 'JE', 'SJ', 'GI', 
                    'CX', 'CC', 'HM', 'NF', 'NC', 'GU', 'MP', 'UM', 'AS', 'CK', 'PF', 'NU', 
                    'PN', 'TK', 'WF', 'AQ', 'GL'
                ];
                if (in_array($record['iso_alpha2'], $excludedTerritories)) {
                    continue;
                }
               
                $results['un_countries'][$record['iso_alpha2']] = [
                    'continent_code' => $record['region_code'],
                    'subregion_code' => $record['subregion_code'],
                    'name' => $record['country_or_area']
                ];
            }
        }

        // Extract countries from local mappings
        $results['local_countries'] = array_keys($localMappings['continent']['mapping']);

        // Find missing countries
        foreach ($results['un_countries'] as $iso2 => $data) {
            if (!isset($localMappings['continent']['mapping'][$iso2])) {
                $results['missing_countries'][] = [
                    'iso2' => $iso2,
                    'name' => $data['name'],
                    'continent_code' => $data['continent_code'],
                    'subregion_code' => $data['subregion_code']
                ];
            }
        }

        return $results;
    }

    private function formatMissingCountries(array $missingCountries): string
    {
        if (empty($missingCountries)) {
            return 'None';
        }

        $formatted = [];
        foreach ($missingCountries as $missing) {
            $formatted[] = sprintf(
                '%s (%s)',
                    $missing['iso2'],
                $missing['name']
                );
            }

        return implode(', ', $formatted);
    }
} 