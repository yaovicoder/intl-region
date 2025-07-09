<?php

declare(strict_types=1);

/**
 * UN M49 Data Download Script
 * 
 * This script downloads UN M49 data from the official UN website and saves it
 * to tests/_output/ for use in validation tests.
 */

class UNM49DataDownloader
{
    private const UN_M49_URL = 'https://unstats.un.org/unsd/methodology/m49/overview/';
    private const OUTPUT_DIR = __DIR__ . '/../tests/_output';
    private const UN_DATA_FILE = self::OUTPUT_DIR . '/un-m49-data.json';
    private const UN_HTML_FILE = self::OUTPUT_DIR . '/un-m49-overview.html';

    public function run(): void
    {
        echo "=== UN M49 Data Download Script ===\n\n";
        
        // Ensure output directory exists
        if (!is_dir(self::OUTPUT_DIR)) {
            mkdir(self::OUTPUT_DIR, 0755, true);
            echo "Created output directory: " . self::OUTPUT_DIR . "\n";
        }

        echo "Downloading UN M49 data from: " . self::UN_M49_URL . "\n";
        
        $html = file_get_contents(self::UN_M49_URL);
        if ($html === false) {
            throw new RuntimeException('Failed to fetch UN M49 data from website');
        }

        // Save raw HTML for debugging
        file_put_contents(self::UN_HTML_FILE, $html);
        echo "Saved HTML to: " . self::UN_HTML_FILE . "\n";

        // Extract data only from the English table
        $unData = $this->extractEnglishTableData($html);

        // Save extracted data
        file_put_contents(self::UN_DATA_FILE, json_encode($unData, JSON_PRETTY_PRINT));
        echo "Saved extracted data to: " . self::UN_DATA_FILE . "\n";
        echo "Extracted " . count($unData) . " records from UN website\n\n";

        // Generate summary
        $this->generateSummary($unData);
        
        echo "✅ Download completed successfully!\n";
    }

    private function extractEnglishTableData(string $html): array
    {
        // Find the English table section
        $pattern = '/<table id\s*=\s*"downloadTableEN"[^>]*>(.*?)<\/table>/s';
        if (!preg_match($pattern, $html, $tableMatch)) {
            throw new RuntimeException('Could not find English data table');
        }

        $tableHtml = $tableMatch[1];
        
        // Extract table rows from tbody
        $tbodyPattern = '/<tbody>(.*?)<\/tbody>/s';
        if (!preg_match($tbodyPattern, $tableHtml, $tbodyMatch)) {
            throw new RuntimeException('Could not find table body');
        }

        $tbodyHtml = $tbodyMatch[1];
        
        // Extract individual rows
        $rowPattern = '/<tr>\s*<td[^>]*>([^<]*)<\/td>\s*<td[^>]*>([^<]*)<\/td>\s*<td[^>]*>([^<]*)<\/td>\s*<td[^>]*>([^<]*)<\/td>\s*<td[^>]*>([^<]*)<\/td>\s*<td[^>]*>([^<]*)<\/td>\s*<td[^>]*>([^<]*)<\/td>\s*<td[^>]*>([^<]*)<\/td>\s*<td[^>]*>([^<]*)<\/td>\s*<td[^>]*>([^<]*)<\/td>\s*<td[^>]*>([^<]*)<\/td>\s*<td[^>]*>([^<]*)<\/td>/';
        
        preg_match_all($rowPattern, $tbodyHtml, $matches, PREG_SET_ORDER);
        
        $unData = [];
        foreach ($matches as $match) {
            $unData[] = [
                'global_code' => trim($match[1]),
                'global_name' => trim($match[2]),
                'region_code' => trim($match[3]),
                'region_name' => trim($match[4]),
                'subregion_code' => trim($match[5]),
                'subregion_name' => trim($match[6]),
                'intermediate_region_code' => trim($match[7]),
                'intermediate_region_name' => trim($match[8]),
                'country_or_area' => trim($match[9]),
                'm49_code' => trim($match[10]),
                'iso_alpha2' => trim($match[11]),
                'iso_alpha3' => trim($match[12])
            ];
        }

        return $unData;
    }

    private function generateSummary(array $unData): void
    {
        $countries = [];
        $regions = [];
        $subregions = [];

        foreach ($unData as $record) {
            if (!empty($record['iso_alpha2']) && $record['iso_alpha2'] !== 'ISO-alpha2 Code') {
                $countries[$record['iso_alpha2']] = $record['country_or_area'];
                $regions[$record['region_code']] = $record['region_name'];
                $subregions[$record['subregion_code']] = $record['subregion_name'];
            }
        }

        echo "=== Data Summary ===\n";
        echo "Total records: " . count($unData) . "\n";
        echo "Countries: " . count($countries) . "\n";
        echo "Regions: " . count($regions) . "\n";
        echo "Subregions: " . count($subregions) . "\n\n";
    }
}

// Run the script if called directly
if (php_sapi_name() === 'cli') {
    try {
        $downloader = new UNM49DataDownloader();
        $downloader->run();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        exit(1);
    }
} 