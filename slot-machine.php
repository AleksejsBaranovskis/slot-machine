<?php

//Izveidot slot machien aparātu, konsolē, kas izveido 5x3 sistēmas laukumu.
//Par piemēru varat ņemt bēdīgi slaveno "Book of Ra". Jums nav jāveido brīvspēļu sistēma, vai kas tāds.
//Galvenais uzvars ir uz pašu spēles mehāniku, un to, cik akurāts ir kods,
//lai mēs spētu pamainīt/ieviest JEBKĀDUS simbolus (Ņemam nodarbību par rock paper scissors un tic tac toe).
//Ideja tāda, ka jums ir maks (šoreiz ar karti var maksāt), ir spēles likme (teiksim 0.10, 0.20, 0.30).
//Spēles līnijas ir kā slot machine (book of ra) ka dod par 3,4,5 vinā rindā, "gandrīz kā diogonāles" utml.
//https://i.stack.imgur.com/YUmqZ.jpg
//Paylines varam ņemt 1-5 pašlaik, bet idejai jābūt, ka varat ērti pievienot jaunas.
//Uzvarošā naudas summa ir atkarīga no 2 lietām. Simbola un koeficenta.
//Piemērs: Simbols A izkrīt 4 līnijā, summa ir 0.5 * 4 * koef.
//Tā tad par vienu simbolu dod centu, vai 5 centus * simbolu skaits. Koeficents tiek noteikts pēc spēles summas.
//Tā tad ja spēlē ar 0.10 tad koef ir 1, ja spēlē ar 0.20 tad koef ir 2.
//Respektīvi, jo vairāk "uzliek", jo palielinas gan risks, gan "reward".

system('clear');
$cashIn = 0;
$selection = 0;
$bet = 0.10;
$balance = 0;

$betSize = [0.10, 0.20, 0.50, 1, 2, 5];
$payLines = [
    1 => [0, 1, 2, 3, 4],
    2 => [5, 6, 7, 8, 9],
    3 => [10, 11, 12, 13, 14],
    4 => [0, 6, 12, 8, 4],
    5 => [10, 6, 2, 8, 14]
];
$symbols = ['| 9 |', '| 9 |', '| 9 |', '| 9 |', '| 9 |', '| 9 |', '| 9 |', '| 9 |', '| 9 |', '| 9 |',
    '| J |', '| J |', '| J |', '| J |', '| J |', '| J |', '| J |', '| J |', '| J |', '| J |',
    '| Q |', '| Q |', '| Q |', '| Q |', '| Q |', '| Q |', '| Q |', '| Q |', '| Q |', '| Q |',
    '| K |', '| K |', '| K |', '| K |', '| K |', '| K |', '| K |', '| K |',
    '| A |', '| A |', '| A |', '| A |', '| A |', '| A |', '| A |', '| A |',
    '| # |', '| # |', '| # |', '| # |', '| # |', '| # |',
    '| $ |', '| $ |', '| $ |', '| $ |', '| $ |', '| $ |',
    '| 7 |', '| 7 |', '| 7 |', '| 7 |'
];
$board = [];
//For three in a row
$symbolPay = ['| 9 |' => 1, '| J |' => 1, '| Q |' => 1,
    '| K |' => 2, '| A |' => 2, '| # |' => 4, '| $ |' => 4, '| 7 |' => 10];

function displayBoard(array $symbol): array
{
    echo 'Good luck!';
    $board = array_fill(0, 14, 0);
    for ($i = 0; $i < 15; $i++) {
        echo ($i % 5 == 0) ? PHP_EOL : '';
        echo $board[$i] = array_rand(array_flip(($symbol)));
    }
    return $board;
}

function welcomeBoard(): void
{
    echo "         Welcome!";
    for ($i = 0; $i < 3; $i++) {
        echo PHP_EOL;
        for ($j = 0; $j < 5; $j++) {
            echo "| $ |";
        }
    }
}

function displayMenu(): void
{
    echo "1. One more spin." . PHP_EOL;
    echo "2. Set your bet." . PHP_EOL;
    echo "3. Insert money." . PHP_EOL;
    echo "4. Cash out." . PHP_EOL;
}

function countWin(array $board, array $payLines, array $symbolPay, float $bet, int $balance): int
{
    $win = 0;
    for ($i = 1; $i <= count($payLines); $i++) {
        if ($board[$payLines[$i][0]] == $board[$payLines[$i][1]] && $board[$payLines[$i][1]] == $board[$payLines[$i][2]]
            && $board[$payLines[$i][2]] == $board[$payLines[$i][3]] && $board[$payLines[$i][3]] == $board[$payLines[$i][4]]) {
            foreach ($symbolPay as $symbol => $pay) {
                if (strcmp($symbol, $board[$payLines[$i][0]]) == 0) {
                    $win = $bet * $pay * 5 * 100;
                    $balance += $win;
                    echo "Nice! You win " . $win . " credits! Five $symbol in a row!" . PHP_EOL;
                }
            }
        } elseif ($board[$payLines[$i][0]] == $board[$payLines[$i][1]] && $board[$payLines[$i][1]] == $board[$payLines[$i][2]]
            && $board[$payLines[$i][2]] == $board[$payLines[$i][3]]) {
            foreach ($symbolPay as $symbol => $pay) {
                if (strcmp($symbol, $board[$payLines[$i][0]]) == 0) {
                    $win = $bet * $pay * 4 * 100;
                    $balance += $win;
                    echo "Nice! You win " . $win . " credits! Four $symbol in a row!" . PHP_EOL;
                }

            }
        } elseif ($board[$payLines[$i][0]] == $board[$payLines[$i][1]] && $board[$payLines[$i][1]] == $board[$payLines[$i][2]]) {
            foreach ($symbolPay as $symbol => $pay) {
                if (strcmp($symbol, $board[$payLines[$i][0]]) == 0) {
                    $win = $bet * $pay * 100;
                    $balance += $win;
                    echo "Nice! You win " . $win . " credits! Three $symbol in a row!" . PHP_EOL;
                }
            }
        }
    }
    return $balance;
}


echo welcomeBoard() . PHP_EOL;
echo PHP_EOL;

while ($selection != 4) {

    echo PHP_EOL;
    echo "Your balance: $balance credits. Your bet: $bet eur" . PHP_EOL;
    displayMenu();

    $selection = readline('Choose your option: ');

    switch ($selection) {
        case 1:
            system('clear');
            if ($balance < $bet) {
                echo 'Your balance is too low!' . PHP_EOL;
            } else {
                $board = displayBoard($symbols);
                echo PHP_EOL;
                $balance = countWin($board, $payLines, $symbolPay, $bet, $balance);
                $balance -= $bet * 100;
            }
            break;
        case 2:
            $bet = readline('Choose your bet(' . implode(', ', $betSize) . '): ');
            while (!in_array($bet, $betSize))
                $bet = readline('Choose your bet(' . implode(', ', $betSize) . '): ');
            break;
        case 3:
            $cashIn = readline('Give me your money! Enter your amount in euros: ');
            while (!is_numeric($cashIn) || $cashIn <= 0) {
                $cashIn = readline('Give me your money! Enter your amount in euros: ');
            }
            $balance += $cashIn * 100;
            break;
        case 4:
            echo 'You cashed out ' . $balance / 100 . ' eur.' . PHP_EOL;
            echo 'Have a nice day!' . PHP_EOL;
            exit;
        default:
            echo "Please choose valid case";
            break;
    }


}
