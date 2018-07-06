<?php

namespace App\Command;

use App\Repository\DistrictRepository;
use App\Service\DistrictImport;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportDistrictsCommand extends Command
{


    /**
     * @var DistrictImport
     */
    private $districtImport;

    /**
     * @var DistrictRepository
     */
    private $districtRepository;

    public function __construct(DistrictImport $districtImport, DistrictRepository $districtRepository)
    {
        $this->districtImport = $districtImport;
        $this->districtRepository = $districtRepository;

        parent::__construct();
    }

    protected static $defaultName = 'import:districts';

    protected function configure()
    {
        $this
            ->setDescription('Import districts from outer web pages or services into app db')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $districtsNumber = $this->districtRepository->count([]);
        if ($districtsNumber == 0) {
            $ifContinue = true;
        } else {
            $ifContinue = $io->confirm('It seems that your districts table is not empty, new import overwrites old. Do you still want to continue?', false);
        }
        if ($ifContinue) {
            $this->districtImport->run($io);
        } else {
            $io->warning('Import has been interrupted!');

            return;
        }
    }
}
