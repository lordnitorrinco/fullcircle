<?php

namespace App\Cli;

use App\Commons\GetArrayInput;
use App\Commons\GetOrdersInput;
use App\Commons\GetTransactionsInput;
use App\Cli\Commands\Question1Command;
use App\Cli\Commands\Question2Command;
use App\Cli\Commands\Question3Command;
use Symfony\Component\Console\Output\ConsoleOutput;

class Cli
{
    private GetArrayInput $getArrayInput;
    private GetOrdersInput $getOrdersInput;
    private GetTransactionsInput $getTransactionsInput;

    public function __construct()
    {
        $this->getArrayInput = new GetArrayInput();
        $this->getOrdersInput = new GetOrdersInput();
        $this->getTransactionsInput = new GetTransactionsInput();
    }

    public function run(array $argv): void
    {
        $output = new ConsoleOutput();
        if (in_array('-h', $argv) || in_array('--help', $argv)) {
            $this->showHelp();
            return;
        }

        if (count($argv) < 2) {
            $output->writeln("<error>Invalid usage. Use -h or --help for instructions.</error>");
            return;
        }

        $command = $argv[1];

        // Add --random to argv if not present
        if (!in_array('--random', $argv)) {
            $argv[] = '--random';
        }

        switch ($command) {
            case 'question1':
                $command = new Question1Command($this->getArrayInput);
                $command->execute($argv);
                break;

            case 'question2':
                $command = new Question2Command($this->getOrdersInput);
                $command->execute($argv);
                break;

            case 'question3':
                $command = new Question3Command($this->getTransactionsInput);
                $command->execute($argv);
                break;

            case 'all':
                $this->executeAllCommands($argv);
                break;

            default:
                $output->writeln("<error>Invalid command. Use -h or --help for instructions.</error>");
                break;
        }
    }

    private function executeAllCommands(array $argv): void
    {
        $output = new ConsoleOutput();
        $output->writeln("<info>Executing all commands with random data...</info>");

        $output->section()->writeln("<info>Executing Question 1...</info>");
        $question1Command = new Question1Command($this->getArrayInput);
        $question1Command->execute($argv);
        $output->section()->writeln("<info>Question 1 executed successfully.</info>");

        $output->section()->writeln("<info>Executing Question 2...</info>");
        $question2Command = new Question2Command($this->getOrdersInput);
        $question2Command->execute($argv);
        $output->section()->writeln("<info>Question 2 executed successfully.</info>");

        $output->section()->writeln("<info>Executing Question 3...</info>");
        $question3Command = new Question3Command($this->getTransactionsInput);
        $question3Command->execute($argv);
        $output->section()->writeln("<info>Question 3 executed successfully.</info>");

        $output->writeln("<info>All commands executed successfully.</info>");
    }

    private function showHelp(): void
    {
        $output = new ConsoleOutput();
        $output->writeln("Usage: fullcircle [command] [options]");
        $output->writeln("Commands:");
        $output->writeln("  question1          Execute question 1");
        $output->writeln("  question2          Execute question 2");
        $output->writeln("  question3          Execute question 3");
        $output->writeln("  all                Execute all questions with random data");
        $output->writeln("Options:");
        $output->writeln("  -h, --help         Show this help message");
        $output->writeln("  --manual           Enter data manually");
        $output->writeln("  --random           Use random data [default]");
    }
}