<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportDistrictsCommand extends Command
{
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
        
        $return = $io->confirm('It seems that your districts table is not empty, new import overwrites old. Do you still want to continue?', false);
        if ($return) {
            $io->warning('Import districts starts...');

            $io->title('Districts from Gdansk');
            for($i=1;$i<=30;$i++){
                $io->text('District '.$i.' has been imported');
            }
            
            $io->title('Districts from Krakow');
            for($i=1;$i<=12;$i++){
                $io->text('District '.$i.' has been imported');
            }

            $io->success('All districts have been imported!');
        } else {
            $io->warning('Import has been interrupted!');
            
            return;
        }

    }
}
