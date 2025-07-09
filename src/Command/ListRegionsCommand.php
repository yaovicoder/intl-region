<?php

declare(strict_types=1);

namespace Ydee\IntlRegion\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Ydee\IntlRegion\RegionProvider;

/**
 * Console command to list countries by region.
 * 
 * This command allows users to list countries by continent or subregion
 * from the command line, with support for localization.
 */
#[AsCommand(
    name: 'intl-region:list',
    description: 'List countries by region (continent or subregion)',
    aliases: ['intl:region:list']
)]
class ListRegionsCommand extends Command
{
    public function __construct(
        private RegionProvider $regionProvider
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument(
                'type',
                InputArgument::REQUIRED,
                'Type of region: "continent" or "subregion"'
            )
            ->addArgument(
                'code',
                InputArgument::REQUIRED,
                'UN M49 region code (e.g., "002" for Africa, "014" for Eastern Africa)'
            )
            ->addOption(
                'locale',
                'l',
                InputOption::VALUE_REQUIRED,
                'Locale for country names',
                'en'
            )
            ->addOption(
                'format',
                'f',
                InputOption::VALUE_REQUIRED,
                'Output format: "table", "json", or "csv"',
                'table'
            )
            ->setHelp(<<<'EOF'
The <info>%command.name%</info> command lists countries by region.

Examples:
  <info>php %command.full_name% continent 002</info>     List all African countries
  <info>php %command.full_name% subregion 014</info>     List Eastern African countries
  <info>php %command.full_name% continent 019 --locale=fr</info>  List American countries in French
  <info>php %command.full_name% continent 150 --format=json</info>  List European countries in JSON format

Available continent codes:
  002: Africa
  019: Americas
  142: Asia
  150: Europe
  009: Oceania

Available subregion codes:
  014: Eastern Africa, 017: Middle Africa, 015: Northern Africa, 018: Southern Africa, 011: Western Africa
  005: South America, 013: Central America, 021: Northern America, 029: Caribbean
  030: Eastern Asia, 034: Southern Asia, 035: South-Eastern Asia, 143: Central Asia, 145: Western Asia
  151: Eastern Europe, 154: Northern Europe, 039: Southern Europe, 155: Western Europe
  053: Australia and New Zealand, 054: Melanesia, 057: Micronesia, 061: Polynesia
EOF
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $type = $input->getArgument('type');
        $code = $input->getArgument('code');
        $locale = $input->getOption('locale');
        $format = $input->getOption('format');

        // Validate type
        if (!in_array($type, ['continent', 'subregion'], true)) {
            $io->error('Type must be either "continent" or "subregion"');
            return Command::INVALID;
        }

        // Get region information
        $regionInfo = $type === 'continent' 
            ? $this->regionProvider->getContinentInfo($code, $locale)
            : $this->regionProvider->getSubregionInfo($code, $locale);

        if ($regionInfo === null) {
            $io->error(sprintf('Invalid %s code: %s', $type, $code));
            return Command::INVALID;
        }

        // Display results based on format
        switch ($format) {
            case 'json':
                $this->outputJson($io, $regionInfo);
                break;
            case 'csv':
                $this->outputCsv($io, $regionInfo);
                break;
            case 'table':
            default:
                $this->outputTable($io, $regionInfo);
                break;
        }

        return Command::SUCCESS;
    }

    private function outputTable(SymfonyStyle $io, array $regionInfo): void
    {
        $io->title(sprintf('%s: %s (%s)', ucfirst($regionInfo['name']), $regionInfo['code'], count($regionInfo['countries'])));

        if (empty($regionInfo['countries'])) {
            $io->warning('No countries found for this region.');
            return;
        }

        $rows = [];
        foreach ($regionInfo['countries'] as $countryCode => $countryName) {
            $rows[] = [$countryCode, $countryName];
        }

        $io->table(['Code', 'Country'], $rows);
    }

    private function outputJson(SymfonyStyle $io, array $regionInfo): void
    {
        $io->writeln(json_encode($regionInfo, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    private function outputCsv(SymfonyStyle $io, array $regionInfo): void
    {
        $io->writeln('Code,Country');
        foreach ($regionInfo['countries'] as $countryCode => $countryName) {
            $io->writeln(sprintf('%s,"%s"', $countryCode, str_replace('"', '""', $countryName)));
        }
    }
} 