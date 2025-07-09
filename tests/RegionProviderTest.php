<?php

declare(strict_types=1);

namespace Ydee\IntlRegion\Tests;

use PHPUnit\Framework\TestCase;
use Ydee\IntlRegion\RegionProvider;

/**
 * @covers \Ydee\IntlRegion\RegionProvider
 */
class RegionProviderTest extends TestCase
{
    private RegionProvider $regionProvider;

    protected function setUp(): void
    {
        $this->regionProvider = new RegionProvider('en');
    }

    public function testConstructorWithDefaultLocale(): void
    {
        $provider = new RegionProvider();
        $this->assertInstanceOf(RegionProvider::class, $provider);
    }

    public function testConstructorWithCustomLocale(): void
    {
        $provider = new RegionProvider('fr');
        $this->assertInstanceOf(RegionProvider::class, $provider);
    }

    public function testGetCountriesByContinent(): void
    {
        $countries = $this->regionProvider->getCountriesByContinent('002');
        
        $this->assertIsArray($countries);
        $this->assertNotEmpty($countries);
        $this->assertArrayHasKey('DZ', $countries);
        $this->assertArrayHasKey('EG', $countries);
        $this->assertArrayHasKey('ZA', $countries);
    }

    public function testGetCountriesByContinentWithCustomLocale(): void
    {
        $countries = $this->regionProvider->getCountriesByContinent('002', 'fr');
        
        $this->assertIsArray($countries);
        $this->assertNotEmpty($countries);
        $this->assertArrayHasKey('DZ', $countries);
        $this->assertArrayHasKey('EG', $countries);
        $this->assertArrayHasKey('ZA', $countries);
    }

    public function testGetCountriesByContinentWithInvalidCode(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid continent code: 999');
        
        $this->regionProvider->getCountriesByContinent('999');
    }

    public function testGetCountriesBySubregion(): void
    {
        $countries = $this->regionProvider->getCountriesBySubregion('014');
        
        $this->assertIsArray($countries);
        $this->assertNotEmpty($countries);
        $this->assertArrayHasKey('KE', $countries);
        $this->assertArrayHasKey('TZ', $countries);
        $this->assertArrayHasKey('UG', $countries);
    }

    public function testGetCountriesBySubregionWithCustomLocale(): void
    {
        $countries = $this->regionProvider->getCountriesBySubregion('014', 'fr');
        
        $this->assertIsArray($countries);
        $this->assertNotEmpty($countries);
        $this->assertArrayHasKey('KE', $countries);
        $this->assertArrayHasKey('TZ', $countries);
        $this->assertArrayHasKey('UG', $countries);
    }

    public function testGetCountriesBySubregionWithInvalidCode(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid subregion code: 999');
        
        $this->regionProvider->getCountriesBySubregion('999');
    }

    public function testGetContinentCode(): void
    {
        $this->assertEquals('002', $this->regionProvider->getContinentCode('DZ'));
        $this->assertEquals('019', $this->regionProvider->getContinentCode('US'));
        $this->assertEquals('142', $this->regionProvider->getContinentCode('JP'));
        $this->assertEquals('150', $this->regionProvider->getContinentCode('FR'));
        $this->assertEquals('009', $this->regionProvider->getContinentCode('AU'));
    }

    public function testGetContinentCodeWithInvalidCode(): void
    {
        $this->assertNull($this->regionProvider->getContinentCode('XX'));
        $this->assertNull($this->regionProvider->getContinentCode(''));
    }

    public function testGetSubregionCode(): void
    {
        $this->assertEquals('014', $this->regionProvider->getSubregionCode('KE'));
        $this->assertEquals('021', $this->regionProvider->getSubregionCode('US'));
        $this->assertEquals('030', $this->regionProvider->getSubregionCode('JP'));
        $this->assertEquals('155', $this->regionProvider->getSubregionCode('FR'));
        $this->assertEquals('053', $this->regionProvider->getSubregionCode('AU'));
    }

    public function testGetSubregionCodeWithInvalidCode(): void
    {
        $this->assertNull($this->regionProvider->getSubregionCode('XX'));
        $this->assertNull($this->regionProvider->getSubregionCode(''));
    }

    public function testGetAvailableContinentCodes(): void
    {
        $codes = $this->regionProvider->getAvailableContinentCodes();
        
        $this->assertIsArray($codes);
        $this->assertContains('002', $codes);
        $this->assertContains('019', $codes);
        $this->assertContains('142', $codes);
        $this->assertContains('150', $codes);
        $this->assertContains('009', $codes);
        $this->assertCount(5, $codes);
    }

    public function testGetAvailableSubregionCodes(): void
    {
        $codes = $this->regionProvider->getAvailableSubregionCodes();
        
        $this->assertIsArray($codes);
        $this->assertContains('014', $codes);
        $this->assertContains('021', $codes);
        $this->assertContains('030', $codes);
        $this->assertContains('155', $codes);
        $this->assertContains('053', $codes);
        $this->assertGreaterThan(20, count($codes));
    }

    public function testGetAvailableCountryCodes(): void
    {
        $codes = $this->regionProvider->getAvailableCountryCodes();
        
        $this->assertIsArray($codes);
        $this->assertContains('DZ', $codes);
        $this->assertContains('US', $codes);
        $this->assertContains('JP', $codes);
        $this->assertContains('FR', $codes);
        $this->assertContains('AU', $codes);
        $this->assertGreaterThan(100, count($codes));
    }

    public function testHasCountryCode(): void
    {
        $this->assertTrue($this->regionProvider->hasCountryCode('DZ'));
        $this->assertTrue($this->regionProvider->hasCountryCode('US'));
        $this->assertTrue($this->regionProvider->hasCountryCode('dz'));
        $this->assertFalse($this->regionProvider->hasCountryCode('XX'));
        $this->assertFalse($this->regionProvider->hasCountryCode(''));
    }

    public function testHasContinentCode(): void
    {
        $this->assertTrue($this->regionProvider->hasContinentCode('002'));
        $this->assertTrue($this->regionProvider->hasContinentCode('019'));
        $this->assertTrue($this->regionProvider->hasContinentCode('142'));
        $this->assertTrue($this->regionProvider->hasContinentCode('150'));
        $this->assertTrue($this->regionProvider->hasContinentCode('009'));
        $this->assertFalse($this->regionProvider->hasContinentCode('999'));
    }

    public function testHasSubregionCode(): void
    {
        $this->assertTrue($this->regionProvider->hasSubregionCode('014'));
        $this->assertTrue($this->regionProvider->hasSubregionCode('021'));
        $this->assertTrue($this->regionProvider->hasSubregionCode('030'));
        $this->assertTrue($this->regionProvider->hasSubregionCode('155'));
        $this->assertTrue($this->regionProvider->hasSubregionCode('053'));
        $this->assertFalse($this->regionProvider->hasSubregionCode('999'));
    }

    public function testGetContinentInfo(): void
    {
        $info = $this->regionProvider->getContinentInfo('002');
        
        $this->assertIsArray($info);
        $this->assertArrayHasKey('code', $info);
        $this->assertArrayHasKey('name', $info);
        $this->assertArrayHasKey('countries', $info);
        $this->assertEquals('002', $info['code']);
        $this->assertEquals('Africa', $info['name']);
        $this->assertIsArray($info['countries']);
        $this->assertNotEmpty($info['countries']);
    }

    public function testGetContinentInfoWithCustomLocale(): void
    {
        $info = $this->regionProvider->getContinentInfo('002', 'fr');
        
        $this->assertIsArray($info);
        $this->assertArrayHasKey('code', $info);
        $this->assertArrayHasKey('name', $info);
        $this->assertArrayHasKey('countries', $info);
        $this->assertEquals('002', $info['code']);
        $this->assertEquals('Africa', $info['name']);
        $this->assertIsArray($info['countries']);
        $this->assertNotEmpty($info['countries']);
    }

    public function testGetContinentInfoWithInvalidCode(): void
    {
        $this->assertNull($this->regionProvider->getContinentInfo('999'));
    }

    public function testGetSubregionInfo(): void
    {
        $info = $this->regionProvider->getSubregionInfo('014');
        
        $this->assertIsArray($info);
        $this->assertArrayHasKey('code', $info);
        $this->assertArrayHasKey('name', $info);
        $this->assertArrayHasKey('countries', $info);
        $this->assertEquals('014', $info['code']);
        $this->assertEquals('Eastern Africa', $info['name']);
        $this->assertIsArray($info['countries']);
        $this->assertNotEmpty($info['countries']);
    }

    public function testGetSubregionInfoWithCustomLocale(): void
    {
        $info = $this->regionProvider->getSubregionInfo('014', 'fr');
        
        $this->assertIsArray($info);
        $this->assertArrayHasKey('code', $info);
        $this->assertArrayHasKey('name', $info);
        $this->assertArrayHasKey('countries', $info);
        $this->assertEquals('014', $info['code']);
        $this->assertEquals('Eastern Africa', $info['name']);
        $this->assertIsArray($info['countries']);
        $this->assertNotEmpty($info['countries']);
    }

    public function testGetSubregionInfoWithInvalidCode(): void
    {
        $this->assertNull($this->regionProvider->getSubregionInfo('999'));
    }

    public function testLocalizedCountryNamesAreSorted(): void
    {
        $countries = $this->regionProvider->getCountriesByContinent('002');
        
        $countryNames = array_values($countries);
        $sortedNames = $countryNames;
        sort($sortedNames);
        
        $this->assertEquals($sortedNames, $countryNames, 'Country names should be sorted alphabetically');
    }
} 