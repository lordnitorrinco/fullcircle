<?php

namespace App\Controllers;

use App\Services\Arrays\FindSecondLargestArray;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput;

class ArrayProcessorController
{
    private FindSecondLargestArray $secondLargestArrayService;

    public function __construct()
    {
        $this->secondLargestArrayService = new FindSecondLargestArray();
    }

    public function findSecondLargestArray(array $array): void
    {
        $output = new ConsoleOutput();
        // Output the array
        $table = new Table($output);
        $table->setHeaders(['Elements'])
              ->addRow([implode(', ', $array)]);
        $table->render();
        // Call the findSecondLargest function and output the result
        $result = $this->secondLargestArrayService->findSecondLargestArray($array);
        
        // Output the result in a table
        $table = new Table($output);
        $table->setHeaders(['Second Largest Element'])
              ->addRow([$result !== null ? $result : 'None']);
        $table->render();
    }
}