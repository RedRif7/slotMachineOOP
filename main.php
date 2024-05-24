<?php

class SlotMachine
{
    private int $rows;
    private int $columns;
    private array $elements;
    private array $board;
    private array $coordinates;
    private int $balance;

    public function __construct(int $rows, int $columns, int $playerBalance)
    {
        $this->rows = $rows;
        $this->columns = $columns;
        $this->elements = ['A', 'B', '*', '$', '#'];
        $this->coordinates = [
            [[0, 0], [0, 1], [0, 2], [0, 3]],
            [[1, 0], [1, 1], [1, 2], [1, 3]],
            [[2, 0], [2, 1], [2, 2], [2, 3]],
            [[0, 0], [1, 1], [2, 2], [2, 3]],
            [[2, 0], [1, 1], [0, 2], [0, 3]],
        ];
        $this->board = [];
        $this->balance = $playerBalance;
    }
    public function generateBoard(): void
    {
        for ($row = 0; $row < $this->rows; $row++) {
            for ($column = 0; $column < $this->columns; $column++) {
                $this->board[$row][$column] = $this->elements[array_rand($this->elements)];
            }
        }
    }
    public function displayBoard(): void
    {
        foreach ($this->board as $row) {
            foreach ($row as $element) {
                echo "[$element]";
            }
            echo "\n";
        }
    }

    public function checkWin(): bool
    {
        foreach ($this->coordinates as $combination) {
            $firstElement = $this->board[$combination[0][0]][$combination[0][1]];
            $winningCombination = true;

            foreach ($combination as $coordinate) {
                if ($this->board[$coordinate[0]][$coordinate[1]] !== $firstElement) {
                    $winningCombination = false;
                    break;
                }
            }
            if ($winningCombination) {
                return true;
            }
        }
        return false;
    }

    public function play(): void
    {
        while (true) {

            echo "Current balance: $" . $this->balance . "\n";
            $bet = readline("Enter the bet amount or type 'exit' to exit: ");

            if ($this->balance <= 0) {
                echo "You ran out of money. Type how much you want to add to your balance: ";
                $credit = (float) readline();
                $this->balance += $credit;
                continue;
            }

            if (strtolower($bet) === 'exit') {
                echo "Thank you for playing! Your final balance is $" . $this->balance . "\n";
                exit;
            }

            if ($bet <= 0 || $bet > $this->balance) {
                echo "Invalid bet amount. Please try again.\n";
                continue;
            }

            $this->balance -= $bet;
            $this->generateBoard();
            $this->displayBoard();

            if ($this->checkWin()) {
                $winnings = $bet * 2;
                $this->balance += $winnings;
                echo "You won! You gained $" . $winnings . ".\n";
            } else {
                echo "You lost! Better luck next time.\n";
            }
        }
    }
}

$playerBalance = 100;
$slotMachine = new SlotMachine(3, 4, $playerBalance);
$slotMachine->play();
