<?php

namespace App\Commons;

use Symfony\Component\Console\Output\ConsoleOutput;

class GetArrayInput
{
    /**
     * Prompts the user to enter elements for the array.
     *
     * This function prompts the user to enter elements for the array one by one,
     * giving the option to stop entering elements after at least two elements have been entered.
     *
     * @return array The input array of numbers.
     */
    public function getArrayFromUser(): array
    {
        $arr = [];
        $output = new ConsoleOutput();
        while (true) {
            $output->writeln("Enter a number (or type 'done' to finish): ");
            $input = trim(fgets(STDIN));
            if (strtolower($input) === 'done' && count($arr) >= 2) {
                break;
            } elseif (is_numeric($input)) {
                $arr[] = (int)$input;
            } else {
                $output->writeln("<error>Invalid input. Please enter a number.</error>");
            }
        }
        return $arr;
    }
}