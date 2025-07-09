<?php

declare(strict_types=1);

/**
 * Update Mappings Script
 * 
 * This script updates our local JSON mapping files with missing countries
 * from the downloaded UN M49 data.
 */

class MappingUpdater
{
    private const UN_DATA_FILE = __DIR__ . '/../tests/_output/un-m49-data.json';
    private const CONTINENT_MAPPING_FILE = __DIR__ . '/../data/mapping/continent.json';
    private const SUBREGION_MAPPING_FILE = __DIR__ . '/../data/mapping/subregion.json';

    public function run(): void
    {
        echo "=== Update Mappings Script ===\n\n";

        if (!file_exists(self::UN_DATA_FILE)) {
            throw new RuntimeException('UN data file not found. Please run: php scripts/download-un-m49-data.php');
        }

        $unData = json_decode(file_get_contents(self::UN_DATA_FILE), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('Invalid JSON in UN data file: ' . json_last_error_msg());
        }

        $continentMapping = json_decode(file_get_contents(self::CONTINENT_MAPPING_FILE), true);
        $subregionMapping = json_decode(file_get_contents(self::SUBREGION_MAPPING_FILE), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('Invalid JSON in mapping files: ' . json_last_error_msg());
        }

        $missingCountries = $this->findMissingCountries($unData, $continentMapping);
        
        if (empty($missingCountries)) {
            echo "✅ All countries are already present in mappings!\n";
            return;
        }

        echo "Found " . count($missingCountries) . " missing countries:\n";
        foreach ($missingCountries as $country) {
            echo "- {$country['iso2']} ({$country['name']}) - Continent: {$country['continent_code']}, Subregion: {$country['subregion_code']}\n";
        }
        echo "\n";

        // Update continent mapping
        $updatedContinentMapping = $this->updateContinentMapping($continentMapping, $missingCountries);
        file_put_contents(self::CONTINENT_MAPPING_FILE, json_encode($updatedContinentMapping, JSON_PRETTY_PRINT));
        echo "Updated continent mapping file\n";

        // Update subregion mapping
        $updatedSubregionMapping = $this->updateSubregionMapping($subregionMapping, $missingCountries);
        file_put_contents(self::SUBREGION_MAPPING_FILE, json_encode($updatedSubregionMapping, JSON_PRETTY_PRINT));
        echo "Updated subregion mapping file\n";

        echo "\n✅ Mappings updated successfully!\n";
        echo "Added " . count($missingCountries) . " countries to both mapping files.\n";
    }

    private function findMissingCountries(array $unData, array $continentMapping): array
    {
        $missingCountries = [];
        $existingCountries = array_keys($continentMapping['mapping']);

        foreach ($unData as $record) {
            if (!empty($record['iso_alpha2']) && $record['iso_alpha2'] !== 'ISO-alpha2 Code') {
                $iso2 = $record['iso_alpha2'];
                if (!in_array($iso2, $existingCountries)) {
                    $missingCountries[] = [
                        'iso2' => $iso2,
                        'name' => $record['country_or_area'],
                        'continent_code' => $record['region_code'],
                        'subregion_code' => $record['subregion_code']
                    ];
                }
            }
        }

        return $missingCountries;
    }

    private function updateContinentMapping(array $mapping, array $missingCountries): array
    {
        foreach ($missingCountries as $country) {
            $mapping['mapping'][$country['iso2']] = $country['continent_code'];
        }

        // Update metadata
        $mapping['metadata']['last_updated'] = date('Y-m-d H:i:s');
        $mapping['metadata']['countries_count'] = count($mapping['mapping']);

        return $mapping;
    }

    private function updateSubregionMapping(array $mapping, array $missingCountries): array
    {
        foreach ($missingCountries as $country) {
            $mapping['mapping'][$country['iso2']] = $country['subregion_code'];
        }

        // Update metadata
        $mapping['metadata']['last_updated'] = date('Y-m-d H:i:s');
        $mapping['metadata']['countries_count'] = count($mapping['mapping']);

        return $mapping;
    }
}

// Run the script if called directly
if (php_sapi_name() === 'cli') {
    try {
        $updater = new MappingUpdater();
        $updater->run();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        exit(1);
    }
} 