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

    public function testGetContinentCodeAsIsoCode(): void
    {
        $this->assertEquals('AFR', $this->regionProvider->getContinentCode('DZ', true));
        $this->assertEquals('AMR', $this->regionProvider->getContinentCode('US', true));
        $this->assertEquals('ASI', $this->regionProvider->getContinentCode('JP', true));
        $this->assertEquals('EUR', $this->regionProvider->getContinentCode('FR', true));
        $this->assertEquals('OCE', $this->regionProvider->getContinentCode('AU', true));
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
        $this->assertContains('010', $codes);
        $this->assertCount(6, $codes); // Now includes Antarctica
    }

    public function testGetAvailableContinentCodesAsIsoCodes(): void
    {
        $codes = $this->regionProvider->getAvailableContinentCodes(true);
        
        $this->assertIsArray($codes);
        $this->assertContains('AFR', $codes);
        $this->assertContains('AMR', $codes);
        $this->assertContains('ASI', $codes);
        $this->assertContains('EUR', $codes);
        $this->assertContains('OCE', $codes);
        $this->assertContains('ANT', $codes);
        $this->assertCount(6, $codes);
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

    public function testGetCountriesByContinentWithIsoCode(): void
    {
        $countries = $this->regionProvider->getCountriesByContinent('EUR');
        
        $this->assertIsArray($countries);
        $this->assertNotEmpty($countries);
        $this->assertArrayHasKey('FR', $countries);
        $this->assertArrayHasKey('DE', $countries);
        $this->assertArrayHasKey('IT', $countries);
    }

    public function testGetCountriesByContinentWithIsoCodeAndLocale(): void
    {
        $countries = $this->regionProvider->getCountriesByContinent('AFR', 'fr');
        
        $this->assertIsArray($countries);
        $this->assertNotEmpty($countries);
        $this->assertArrayHasKey('DZ', $countries);
        $this->assertArrayHasKey('EG', $countries);
        $this->assertArrayHasKey('ZA', $countries);
    }

    public function testGetIsoContinentCode(): void
    {
        $this->assertEquals('AFR', $this->regionProvider->getIsoContinentCode('002'));
        $this->assertEquals('AMR', $this->regionProvider->getIsoContinentCode('019'));
        $this->assertEquals('ASI', $this->regionProvider->getIsoContinentCode('142'));
        $this->assertEquals('EUR', $this->regionProvider->getIsoContinentCode('150'));
        $this->assertEquals('OCE', $this->regionProvider->getIsoContinentCode('009'));
        $this->assertNull($this->regionProvider->getIsoContinentCode('999'));
    }

    public function testGetM49ContinentCode(): void
    {
        $this->assertEquals('002', $this->regionProvider->getM49ContinentCode('AFR'));
        $this->assertEquals('019', $this->regionProvider->getM49ContinentCode('AMR'));
        $this->assertEquals('142', $this->regionProvider->getM49ContinentCode('ASI'));
        $this->assertEquals('150', $this->regionProvider->getM49ContinentCode('EUR'));
        $this->assertEquals('009', $this->regionProvider->getM49ContinentCode('OCE'));
        $this->assertNull($this->regionProvider->getM49ContinentCode('XXX'));
    }

    public function testGeographicallyIncorrectCountryCodesAreFiltered(): void
    {
        $this->assertFalse($this->regionProvider->hasCountryCode('TF'));
        
        $allCountries = $this->regionProvider->getAvailableCountryCodes();
        $this->assertNotContains('TF', $allCountries);
    }

    public function testConstructorWithLogger(): void
    {
        $logger = $this->createMock(\Psr\Log\LoggerInterface::class);
        $provider = new RegionProvider('en', $logger);
        
        $this->assertInstanceOf(RegionProvider::class, $provider);
    }

    public function testGetCountriesBySubregionWithIsoCode(): void
    {
        $countries = $this->regionProvider->getCountriesBySubregion('WEU');
        
        $this->assertIsArray($countries);
        $this->assertNotEmpty($countries);
        $this->assertArrayHasKey('FR', $countries);
        $this->assertArrayHasKey('DE', $countries);
        $this->assertArrayHasKey('AT', $countries);
    }

    public function testGetIsoSubregionCode(): void
    {
        $this->assertEquals('WEU', $this->regionProvider->getIsoSubregionCode('155'));
        $this->assertEquals('NAM', $this->regionProvider->getIsoSubregionCode('021'));
        $this->assertEquals('EAF', $this->regionProvider->getIsoSubregionCode('014'));
        $this->assertNull($this->regionProvider->getIsoSubregionCode('999'));
    }

    public function testGetM49SubregionCode(): void
    {
        $this->assertEquals('155', $this->regionProvider->getM49SubregionCode('WEU'));
        $this->assertEquals('021', $this->regionProvider->getM49SubregionCode('NAM'));
        $this->assertEquals('014', $this->regionProvider->getM49SubregionCode('EAF'));
        $this->assertNull($this->regionProvider->getM49SubregionCode('XXX'));
    }

    public function testGetAvailableSubregionCodesAsIsoCodes(): void
    {
        $codes = $this->regionProvider->getAvailableSubregionCodes(true);
        
        $this->assertIsArray($codes);
        $this->assertContains('WEU', $codes);
        $this->assertContains('NAM', $codes);
        $this->assertContains('EAF', $codes);
        $this->assertGreaterThan(20, count($codes));
    }

    public function testAntarcticaSupport(): void
    {
        $this->assertTrue($this->regionProvider->hasContinentCode('010'));
        $this->assertTrue($this->regionProvider->hasContinentCode('ANT'));
        
        $antarcticaCountries = $this->regionProvider->getCountriesByContinent('ANT');
        $this->assertIsArray($antarcticaCountries);
        $this->assertArrayHasKey('AQ', $antarcticaCountries);
        $this->assertEquals('Antarctica', $antarcticaCountries['AQ']);
    }
} 