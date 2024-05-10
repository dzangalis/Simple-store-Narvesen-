<?php

function displayBorder()
{
    echo "-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-\n\n";
}

$storeData = file_get_contents("StoreInventory.json");
$data = json_decode($storeData, true);
$products = $data['products'];
$cartProducts = [];
$boughtProducts = [];

while (true) {
    $input = ucfirst(strtolower(readline("Please input your desired action [Display, Add, Buy, Cart, Exit]: ")));

    switch ($input) {
        case "Display":
            foreach ($products as $display) {
                echo "Item: {$display['name']} , Price: " . number_format($display['price'], 2) . "€" . PHP_EOL;
                displayBorder();
            }
            break;

        case "Add":
            $productName = ucfirst(strtolower(readline("Enter the products name: ")));
            $contains = false;
            foreach ($products as $product) {
                if (strcasecmp($product['name' ], $productName) === 0) {
                    $contains = true;
                    break   ;
                }
            }
            if ($contains === false) {
                echo "Product not found." . PHP_EOL;
                break;
            }
            $productAmount = (int)readline("Enter the product amount: ");
            if (empty($productAmount) === true || $productAmount < 0) {
                echo "Invalid amount." . PHP_EOL;
                break;
            }

            foreach ($products as $product) {
                if (isset($cartProducts[$productName])) {
                    $cartProducts[$productName]['amount'] += $productAmount;
                } else {
                    $cartProducts[$productName] = [
                        'price' => $product['price'],
                        'amount' => $productAmount
                    ];
                }
                echo "Added $productAmount x $productName to the cart." . PHP_EOL;
                break;
            }

            displayBorder();
            break;

        case "Cart":
            if (empty($cartProducts) === true) {
                echo "Your cart is empty." . PHP_EOL;
            } else {
                $totalCost = 0;
                foreach ($cartProducts as $name => $info) {
                    $cost = $info['amount'] * $info['price'];
                    echo "Item: $name, Price: " . number_format($info['price'], 2) . "€" . ", Quantity: ";
                    echo "{$info['amount']}, Total: " . number_format($cost, 2) . "€" . PHP_EOL;

                    $totalCost += $cost;
                }
                echo "Total cart cost: " . number_format($totalCost, 2) . "€" . PHP_EOL;
            }
            displayBorder();
            break;

        case "Buy":
            if (empty($cartProducts) === true) {
                echo "Your cart is empty." . PHP_EOL;
            } else {
                $boughtProducts = $cartProducts;
                $cartProducts = [];
                echo "Purchase successful!" . PHP_EOL;
            }
            displayBorder();
            break;

        case "Exit":
            echo "Thank you for checking out." . PHP_EOL;
            if (empty($boughtProducts) === false) {
                $totalCost = 0;
                displayBorder();
                foreach ($boughtProducts as $name => $info) {
                    $cost = $info['amount'] * $info['price'];
                    echo "Item: $name , Amount: {$info['amount']} " . PHP_EOL;
                    $totalCost += $cost;

                }
                displayBorder();
                echo "Total amount bought: " . number_format($totalCost, 2) . "€" . PHP_EOL;
            }
            break 2;

        default:
            echo "There is an error in your input." . PHP_EOL;
    }
}
