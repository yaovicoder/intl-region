<?php

declare(strict_types=1);

namespace Ydee\IntlRegion\Tests\Mapping;

use PHPUnit\Framework\TestCase;
use Ydee\IntlRegion\Mapping\SubregionMapping;

/**
 * @covers \Ydee\IntlRegion\Mapping\SubregionMapping
 */
class SubregionMappingTest extends TestCase
{
    public function testGetSubregionCode(): void
    {
        $this->assertEquals('014', SubregionMapping::getSubregionCode('KE'));
        $this->assertEquals('021', SubregionMapping::getSubregionCode('US'));
        $this->assertEquals('030', SubregionMapping::getSubregionCode('JP'));
        $this->assertEquals('155', SubregionMapping::getSubregionCode('FR'));
        $this->assertEquals('053', SubregionMapping::getSubregionCode('AU'));
    }

    public function testGetSubregionCodeWithLowerCase(): void
    {
        $this->assertEquals('014', SubregionMapping::getSubregionCode('ke'));
        $this->assertEquals('021', SubregionMapping::getSubregionCode('us'));
    }

    public function testGetSubregionCodeWithInvalidCode(): void
    {
        $this->assertNull(SubregionMapping::getSubregionCode('XX'));
        $this->assertNull(SubregionMapping::getSubregionCode(''));
    }

    public function testGetCountriesBySubregion(): void
    {
        $easternAfricanCountries = SubregionMapping::getCountriesBySubregion('014');
        $this->assertContains('KE', $easternAfricanCountries);
        $this->assertContains('TZ', $easternAfricanCountries);
        $this->assertContains('UG', $easternAfricanCountries);

        $northernAmericanCountries = SubregionMapping::getCountriesBySubregion('021');
        $this->assertContains('US', $northernAmericanCountries);
        $this->assertContains('CA', $northernAmericanCountries);
    }

    public function testGetCountriesBySubregionWithInvalidCode(): void
    {
        $this->assertEmpty(SubregionMapping::getCountriesBySubregion('999'));
    }

    public function testGetAvailableSubregionCodes(): void
    {
        $codes = SubregionMapping::getAvailableSubregionCodes();
        $this->assertContains('014', $codes);
        $this->assertContains('021', $codes);
        $this->assertContains('030', $codes);
        $this->assertContains('155', $codes);
        $this->assertContains('053', $codes);
        $this->assertGreaterThan(20, count($codes));
    }

    public function testGetAvailableCountryCodes(): void
    {
        $codes = SubregionMapping::getAvailableCountryCodes();
        $this->assertContains('KE', $codes);
        $this->assertContains('US', $codes);
        $this->assertContains('JP', $codes);
        $this->assertContains('FR', $codes);
        $this->assertContains('AU', $codes);
        $this->assertGreaterThan(100, count($codes));
    }

    public function testHasCountryCode(): void
    {
        $this->assertTrue(SubregionMapping::hasCountryCode('KE'));
        $this->assertTrue(SubregionMapping::hasCountryCode('US'));
        $this->assertTrue(SubregionMapping::hasCountryCode('ke'));
        $this->assertFalse(SubregionMapping::hasCountryCode('XX'));
        $this->assertFalse(SubregionMapping::hasCountryCode(''));
    }

    public function testHasSubregionCode(): void
    {
        $this->assertTrue(SubregionMapping::hasSubregionCode('014'));
        $this->assertTrue(SubregionMapping::hasSubregionCode('021'));
        $this->assertTrue(SubregionMapping::hasSubregionCode('030'));
        $this->assertTrue(SubregionMapping::hasSubregionCode('155'));
        $this->assertTrue(SubregionMapping::hasSubregionCode('053'));
        $this->assertFalse(SubregionMapping::hasSubregionCode('999'));
    }

    public function testSubregionMappingConsistency(): void
    {
        // Test that all countries in the mapping have valid subregion codes
        $validSubregionCodes = [
            '014', '017', '015', '018', '011', // Africa
            '005', '013', '021', '029', // Americas
            '030', '034', '035', '143', '145', // Asia
            '151', '154', '039', '155', // Europe
            '053', '054', '057', '061', // Oceania
        ];
        
        foreach (SubregionMapping::getAvailableCountryCodes() as $countryCode) {
            $subregionCode = SubregionMapping::getSubregionCode($countryCode);
            $this->assertNotNull($subregionCode, "Country $countryCode should have a subregion code");
            $this->assertContains($subregionCode, $validSubregionCodes, "Country $countryCode has invalid subregion code: $subregionCode");
        }
    }
} 