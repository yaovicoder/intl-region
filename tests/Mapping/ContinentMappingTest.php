<?php

declare(strict_types=1);

namespace Ydee\IntlRegion\Tests\Mapping;

use PHPUnit\Framework\TestCase;
use Ydee\IntlRegion\Mapping\ContinentMapping;

/**
 * @covers \Ydee\IntlRegion\Mapping\ContinentMapping
 */
class ContinentMappingTest extends TestCase
{
    public function testGetContinentCode(): void
    {
        $this->assertEquals('002', ContinentMapping::getContinentCode('DZ'));
        $this->assertEquals('019', ContinentMapping::getContinentCode('US'));
        $this->assertEquals('142', ContinentMapping::getContinentCode('JP'));
        $this->assertEquals('150', ContinentMapping::getContinentCode('FR'));
        $this->assertEquals('009', ContinentMapping::getContinentCode('AU'));
    }

    public function testGetContinentCodeWithLowerCase(): void
    {
        $this->assertEquals('002', ContinentMapping::getContinentCode('dz'));
        $this->assertEquals('019', ContinentMapping::getContinentCode('us'));
    }

    public function testGetContinentCodeWithInvalidCode(): void
    {
        $this->assertNull(ContinentMapping::getContinentCode('XX'));
        $this->assertNull(ContinentMapping::getContinentCode(''));
    }

    public function testGetCountriesByContinent(): void
    {
        $africanCountries = ContinentMapping::getCountriesByContinent('002');
        $this->assertContains('DZ', $africanCountries);
        $this->assertContains('EG', $africanCountries);
        $this->assertContains('ZA', $africanCountries);

        $americanCountries = ContinentMapping::getCountriesByContinent('019');
        $this->assertContains('US', $americanCountries);
        $this->assertContains('CA', $americanCountries);
        $this->assertContains('BR', $americanCountries);
    }

    public function testGetCountriesByContinentWithInvalidCode(): void
    {
        $this->assertEmpty(ContinentMapping::getCountriesByContinent('999'));
    }

    public function testGetAvailableContinentCodes(): void
    {
        $codes = ContinentMapping::getAvailableContinentCodes();
        $this->assertContains('002', $codes);
        $this->assertContains('019', $codes);
        $this->assertContains('142', $codes);
        $this->assertContains('150', $codes);
        $this->assertContains('009', $codes);
        $this->assertCount(5, $codes);
    }

    public function testGetAvailableCountryCodes(): void
    {
        $codes = ContinentMapping::getAvailableCountryCodes();
        $this->assertContains('DZ', $codes);
        $this->assertContains('US', $codes);
        $this->assertContains('JP', $codes);
        $this->assertContains('FR', $codes);
        $this->assertContains('AU', $codes);
        $this->assertGreaterThan(100, count($codes));
    }

    public function testHasCountryCode(): void
    {
        $this->assertTrue(ContinentMapping::hasCountryCode('DZ'));
        $this->assertTrue(ContinentMapping::hasCountryCode('US'));
        $this->assertTrue(ContinentMapping::hasCountryCode('dz'));
        $this->assertFalse(ContinentMapping::hasCountryCode('XX'));
        $this->assertFalse(ContinentMapping::hasCountryCode(''));
    }

    public function testHasContinentCode(): void
    {
        $this->assertTrue(ContinentMapping::hasContinentCode('002'));
        $this->assertTrue(ContinentMapping::hasContinentCode('019'));
        $this->assertTrue(ContinentMapping::hasContinentCode('142'));
        $this->assertTrue(ContinentMapping::hasContinentCode('150'));
        $this->assertTrue(ContinentMapping::hasContinentCode('009'));
        $this->assertFalse(ContinentMapping::hasContinentCode('999'));
    }

    public function testContinentMappingConsistency(): void
    {
        // Test that all countries in the mapping have valid continent codes
        $validContinentCodes = ['002', '019', '142', '150', '009'];
        
        foreach (ContinentMapping::getAvailableCountryCodes() as $countryCode) {
            $continentCode = ContinentMapping::getContinentCode($countryCode);
            $this->assertNotNull($continentCode, "Country $countryCode should have a continent code");
            $this->assertContains($continentCode, $validContinentCodes, "Country $countryCode has invalid continent code: $continentCode");
        }
    }
} 