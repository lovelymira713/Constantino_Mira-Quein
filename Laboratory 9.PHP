<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🍕 Pizza Ordering System - Student Template</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg, #FF6B6B 0%, #FFA500 50%, #FFD700 100%); min-height: 100vh; padding: 40px 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        header { text-align: center; color: white; margin-bottom: 40px; text-shadow: 2px 2px 4px rgba(0,0,0,0.3); }
        h1 { font-size: 3.5em; margin-bottom: 10px; text-transform: uppercase; }
        .card { background: white; border-radius: 20px; padding: 35px; box-shadow: 0 15px 35px rgba(0,0,0,0.2); border: 3px solid #FF6B6B; margin-bottom: 30px; }
        .card h2 { color: #FF6B6B; border-bottom: 3px solid #FFA500; padding-bottom: 10px; margin-bottom: 20px; }
        .content { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
        .full-width { grid-column: 1 / -1; }
        .form-group { display: flex; flex-direction: column; gap: 8px; margin-bottom: 15px; }
        input[type="text"], input[type="number"] { padding: 12px; border: 2px solid #FF6B6B; border-radius: 10px; }
        .radio-group, .checkbox-group { display: flex; flex-direction: column; gap: 10px; }
        .radio-item, .checkbox-item { display: flex; align-items: center; padding: 10px; border-radius: 8px; cursor: pointer; transition: background-color 0.3s;}
        .radio-item:hover, .checkbox-item:hover { background-color: #fff5f5; }
        .radio-item input, .checkbox-item input { margin-right: 10px; width: 18px; height: 18px; accent-color: #FF6B6B; cursor: pointer;}
        .radio-item label, .checkbox-item label { cursor: pointer; flex: 1; display: flex; justify-content: space-between; }
        .price { color: #FFA500; font-weight: 700; }
        button { padding: 15px; background: linear-gradient(135deg, #FF6B6B, #FFA500); color: white; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; width: 100%; text-transform: uppercase; font-size: 1.1em;}
        .summary-item { display: flex; justify-content: space-between; padding: 10px; background: #fff5f5; border-radius: 8px; margin-bottom: 10px; border-left: 4px solid #FFA500; }
        .total { font-weight: bold; color: white; font-size: 1.5em; background: linear-gradient(135deg, #FF6B6B, #FFA500); padding: 20px; border-radius: 10px; text-align: center; }
        .menu-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; }
        .menu-item { background: #FFF5E6; padding: 15px; border-radius: 12px; border: 2px solid #FFA500; text-align: center; }
        .menu-item-name { font-weight: bold; color: #FF6B6B; margin-bottom: 5px; }
        .order-counter { background: linear-gradient(135deg, #FF6B6B, #FFA500); color: white; }
        .order-item { background: rgba(255,255,255,0.2); padding: 10px; border-radius: 8px; margin-bottom: 5px; font-weight: 500; }
        .section-title { color: #FF6B6B; margin: 20px 0 10px; text-transform: uppercase; grid-column: 1 / -1; font-size: 1.2em;}
    </style>
</head>
<body>

<?php
$pizzaMenu = [
    "Cheese" => 150,
    "Margherita" => 160,
    "Veggie" => 170,
    "Pepperoni" => 180,
    "Four Cheese" => 190,
    "Hawaiian" => 200,
    "BBQ Chicken" => 220,
    "Meat Lovers" => 250
];

$toppingsMenu = [
    "Onions" => 15,
    "Green Peppers" => 15,
    "Mushroom" => 20,
    "Jalapenos" => 20,
    "Olives" => 25,
    "Pineapple" => 25,
    "Extra Cheese" => 30,
    "Sausage" => 35,
    "Bacon" => 40
];

// TODO 1
function computePizzaTotal($pizza, $toppings, $qty, $pizzaMenu, $toppingsMenu) {
    $total = 0;

    $total = $total + $pizzaMenu[$pizza];

    if (!empty($toppings)) {
        foreach ($toppings as $t) {
            $total = $total + $toppingsMenu[$t];
        }
    }

    $total = $total * $qty;

    return $total;
}
?>

<div class="container">
<header>
<h1>🍕 Pizza Bites Express</h1>
</header>

<div class="content">

<div class="card">
<h2>Order</h2>
<form method="post">

<input type="text" name="customer" placeholder="Name">

<div class="radio-group">
<?php
// TODO 2
foreach ($pizzaMenu as $name => $price) {
    echo "<div class='radio-item'>
    <input type='radio' name='pizza' value='$name'>
    <label>$name <span class='price'>₱$price</span></label>
    </div>";
}
?>
</div>

<div class="checkbox-group">
<?php
// TODO 3
foreach ($toppingsMenu as $name => $price) {
    echo "<div class='checkbox-item'>
    <input type='checkbox' name='toppings[]' value='$name'>
    <label>$name <span class='price'>₱$price</span></label>
    </div>";
}
?>
</div>

<input type="number" name="qty" value="1">

<button name="order">Order</button>
</form>
</div>

<div class="card">
<h2>Summary</h2>

<?php
if (isset($_POST['order'])) {

    // TODO 4
    $customer = $_POST['customer'];
    $pizza = $_POST['pizza'];
    $qty = $_POST['qty'];

    if (isset($_POST['toppings'])) {
        $toppings = $_POST['toppings'];
    } else {
        $toppings = [];
    }

    // TODO 5
    $total = computePizzaTotal($pizza, $toppings, $qty, $pizzaMenu, $toppingsMenu);

    // TODO 6
    echo "<div class='summary-item'>Name: $customer</div>";
    echo "<div class='summary-item'>Pizza: $pizza</div>";
    echo "<div class='summary-item'>Qty: $qty</div>";

    echo "<div class='summary-item'>Toppings: ";
    if (!empty($toppings)) {
        echo implode(", ", $toppings);
    } else {
        echo "None";
    }
    echo "</div>";

    echo "<div class='total'>Total ₱$total</div>";

}
?>

</div>

<div class="card full-width">
<h2>Menu</h2>
<div class="menu-grid">

<?php
// TODO 7
foreach ($pizzaMenu as $name => $price) {
    echo "<div class='menu-item'>
    <div class='menu-item-name'>$name</div>
    <div>₱$price</div>
    </div>";
}
?>

</div>
</div>

<div class="card full-width order-counter">
<h2>Orders</h2>

<?php
if (isset($_POST['order'])) {
    // TODO 8
    for ($i = 1; $i <= $qty; $i++) {
        echo "<div class='order-item'>Order #$i - $pizza</div>";
    }
}
?>

</div>

</div>
</div>

</body>
</html>