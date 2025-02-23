<?php

namespace App\Cli\Commands;

use App\Controllers\ArrayProcessorController;
use App\Commons\GetArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;

class Question1Command
{
    private GetArrayInput $getArrayInput;

    public function __construct(GetArrayInput $getArrayInput)
    {
        $this->getArrayInput = $getArrayInput;
    }

    public function execute(array $argv): void
    {
        $output = new ConsoleOutput();
        $array = [];

        if (in_array('--manual', $argv)) {
            $array = $this->getArrayInput->getArrayFromUser();
        } elseif (in_array('--random', $argv)) {
            $array = array_map(function() {
                return rand(1, 100);
            }, range(1, rand(2, 20)));
        } else {
            $output->writeln("<error>Invalid usage for question1. Use -h or --help for instructions.</error>");
            return;
        }

        $controller = new ArrayProcessorController();
        $controller->findSecondLargestArray($array);
    }
}