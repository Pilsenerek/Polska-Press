<?php

namespace App\Command;

use App\Command\ImportDistrictsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
 */
class ImportDistrictsCommandMock extends ImportDistrictsCommand {
    
    
    protected static $defaultName = 'import:districts';
    
    public function configure() {
        
        parent::configure();
    }

    public function execute(InputInterface $input, OutputInterface $output) {
        
        parent::execute($input, $output);
    }
 
    
}
