<?php

declare(strict_types=1);

namespace Ydee\IntlRegion\Tests\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Ydee\IntlRegion\Command\ListRegionsCommand;
use Ydee\IntlRegion\RegionProvider;

/**
 * @covers \Ydee\IntlRegion\Command\ListRegionsCommand
 */
class ListRegionsCommandTest extends TestCase
{
    private Application $application;
    private CommandTester $commandTester;
    private RegionProvider $regionProvider;

    protected function setUp(): void
    {
        $this->regionProvider = new RegionProvider('en');
        
        $this->application = new Application();
        $this->application->add(new ListRegionsCommand($this->regionProvider));
        
        $command = $this->application->find('intl-region:list');
        $this->commandTester = new CommandTester($command);
    }

    public function testListContinentCountries(): void
    {
        $this->commandTester->execute([
            'type' => 'continent',
            'code' => '002',
        ]);

        $output = $this->commandTester->getDisplay();
        
        $this->assertStringContainsString('Africa: 002', $output);
        $this->assertStringContainsString('DZ', $output);
        $this->assertStringContainsString('EG', $output);
        $this->assertStringContainsString('ZA', $output);
        $this->assertEquals(0, $this->commandTester->getStatusCode());
    }

    public function testListSubregionCountries(): void
    {
        $this->commandTester->execute([
            'type' => 'subregion',
            'code' => '014',
        ]);

        $output = $this->commandTester->getDisplay();
        
        $this->assertStringContainsString('Eastern Africa: 014', $output);
        $this->assertStringContainsString('KE', $output);
        $this->assertStringContainsString('TZ', $output);
        $this->assertStringContainsString('UG', $output);
        $this->assertEquals(0, $this->commandTester->getStatusCode());
    }

    public function testListContinentWithCustomLocale(): void
    {
        $this->commandTester->execute([
            'type' => 'continent',
            'code' => '002',
            '--locale' => 'fr',
        ]);

        $output = $this->commandTester->getDisplay();
        
        $this->assertStringContainsString('Africa: 002', $output);
        $this->assertStringContainsString('DZ', $output);
        $this->assertEquals(0, $this->commandTester->getStatusCode());
    }

    public function testListContinentWithJsonFormat(): void
    {
        $this->commandTester->execute([
            'type' => 'continent',
            'code' => '002',
            '--format' => 'json',
        ]);

        $output = $this->commandTester->getDisplay();
        $data = json_decode($output, true);
        
        $this->assertIsArray($data);
        $this->assertArrayHasKey('code', $data);
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('countries', $data);
        $this->assertEquals('002', $data['code']);
        $this->assertEquals('Africa', $data['name']);
        $this->assertIsArray($data['countries']);
        $this->assertNotEmpty($data['countries']);
        $this->assertEquals(0, $this->commandTester->getStatusCode());
    }

    public function testListContinentWithCsvFormat(): void
    {
        $this->commandTester->execute([
            'type' => 'continent',
            'code' => '002',
            '--format' => 'csv',
        ]);

        $output = $this->commandTester->getDisplay();
        
        $this->assertStringContainsString('Code,Country', $output);
        $this->assertStringContainsString('DZ,', $output);
        $this->assertStringContainsString('EG,', $output);
        $this->assertStringContainsString('ZA,', $output);
        $this->assertEquals(0, $this->commandTester->getStatusCode());
    }

    public function testInvalidType(): void
    {
        $this->commandTester->execute([
            'type' => 'invalid',
            'code' => '002',
        ]);

        $output = $this->commandTester->getDisplay();
        
        $this->assertStringContainsString('Type must be either "continent" or "subregion"', $output);
        $this->assertEquals(2, $this->commandTester->getStatusCode());
    }

    public function testInvalidContinentCode(): void
    {
        $this->commandTester->execute([
            'type' => 'continent',
            'code' => '999',
        ]);

        $output = $this->commandTester->getDisplay();
        
        $this->assertStringContainsString('Invalid continent code: 999', $output);
        $this->assertEquals(2, $this->commandTester->getStatusCode());
    }

    public function testInvalidSubregionCode(): void
    {
        $this->commandTester->execute([
            'type' => 'subregion',
            'code' => '999',
        ]);

        $output = $this->commandTester->getDisplay();
        
        $this->assertStringContainsString('Invalid subregion code: 999', $output);
        $this->assertEquals(2, $this->commandTester->getStatusCode());
    }

    public function testCommandHelp(): void
    {
        $this->commandTester->execute(['--help']);

        $output = $this->commandTester->getDisplay();
        
        $this->assertStringContainsString('List countries by region', $output);
        $this->assertStringContainsString('continent 002', $output);
        $this->assertStringContainsString('subregion 014', $output);
        $this->assertStringContainsString('--locale', $output);
        $this->assertStringContainsString('--format', $output);
        $this->assertEquals(0, $this->commandTester->getStatusCode());
    }
} 