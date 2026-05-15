<?php
    // =========================================================
    // TODO 1: SECURE DATABASE CONNECTION (XAMPP / MySQL)
    // =========================================================
    // 1. Connect to MySQL using mysqli_connect($host, $user, $password, $dbname)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "lab10pizza";
    
    $conn = new mysqli($servername, $username, $password, $database); // Replace this with your actual connection code
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    echo "Connected successfully!" ;
    // =========================================================
    // TODO 2: HANDLE POST REQUESTS (ALL CRUD OPERATIONS)
    // =========================================================
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // You can refresh the page by using header("Location: " . $_SERVER['PHP_SELF']); exit; after each operation to see changes immediately.
        
        // ---  PIZZA ADMIN ---
        if (isset($_POST['add_pizza'])) {
            // TODO: Write INSERT query for Pizzas 
            $name = $_POST['name'];
            $price = $_POST['price'];

            $sql = "INSERT INTO pizzas (name, price) VALUES ('$name', '$price')";
            mysqli_query($conn, $sql);

            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
        if (isset($_POST['update_pizza'])) {
            // TODO: Write UPDATE query to change pizza price
            $id = $_POST['item_id'];
            $price = $_POST['new_price'];

            $sql = "UPDATE pizzas SET price='$price' WHERE id='$id'";
            mysqli_query($conn, $sql);

            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
        if (isset($_POST['delete_pizza'])) {
            // TODO: Write DELETE query to remove a pizza
              $id = $_POST['item_id'];

            $sql = "DELETE FROM pizzas WHERE id='$id'";
            mysqli_query($conn, $sql);

            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }

        // ---  TOPPINGS ADMIN ---
        if (isset($_POST['add_topping'])) {
            // TODO: Write INSERT query for Toppings
            $name = $_POST['name'];
            $price = $_POST['price'];

            $sql = "INSERT INTO toppings (name, price) VALUES ('$name', '$price')";
            mysqli_query($conn, $sql);

            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
        if (isset($_POST['update_topping'])) {
            // TODO: Write UPDATE query to change topping price
            $id = $_POST['item_id'];
            $price = $_POST['new_price'];

            $sql = "UPDATE toppings SET price='$price' WHERE id='$id'";
            mysqli_query($conn, $sql);

            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
        if (isset($_POST['delete_topping'])) {
            // TODO: Write DELETE query to remove a topping
            $id = $_POST['item_id'];

            $sql = "DELETE FROM toppings WHERE id='$id'";
            mysqli_query($conn, $sql);

            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }

        // --- 🛒 ORDERING SYSTEM ---
        if (isset($_POST['create_order'])) {
            // TODO: 
            // 1. Fetch the selected Pizza's price from the database using mysqli_query
            // 2. Loop through selected Toppings, fetch their prices, and calculate total topping cost
            // 3. Calculate Grand Total: (Pizza Price + Toppings Total) * Quantity
            // 4. INSERT the final order into the 'orders' table
            $customer = $_POST['customer'];
            $pizza_id = $_POST['pizza'];
            $qty = $_POST['qty'];

            // 1. Get pizza price
            $pizza_query = mysqli_query($conn, "SELECT * FROM pizzas WHERE id='$pizza_id'");
            $pizza = mysqli_fetch_assoc($pizza_query);
            $pizza_price = $pizza['price'];
            $pizza_name = $pizza['name'];

            $toppings_total = 0;
            $topping_names = [];

            // 2. Loop toppings
            if (!empty($_POST['toppings'])) {
                foreach ($_POST['toppings'] as $tid) {
                    $tq = mysqli_query($conn, "SELECT * FROM toppings WHERE id='$tid'");
                    $t = mysqli_fetch_assoc($tq);

                    $toppings_total += $t['price'];
                    $topping_names[] = $t['name'];
                }
            }

            $toppings_str = implode(", ", $topping_names);

            // 3. Total
            $total = ($pizza_price + $toppings_total) * $qty;

            $details = $pizza_name . " (" . implode(", ", $topping_names) . ")";

            // 4. Insert
            $sql = "INSERT INTO orders (customer, pizza, toppings, qty, total, status)
            VALUES ('$customer', '$pizza_name', '$toppings_str', '$qty', '$total', 'Pending')";

            mysqli_query($conn, $sql);

            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }

        // --- 📋 MANAGE ORDERS ---
        if (isset($_POST['update_status'])) {
            // TODO: Write UPDATE query to change order status to 'Completed'
            $id = $_POST['order_id'];

            $sql = "UPDATE orders SET status='Completed' WHERE id='$id'";
            mysqli_query($conn, $sql);

            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
        if (isset($_POST['delete_order'])) {
            // TODO: Write DELETE query to remove an order
            $id = $_POST['order_id'];

            $sql = "DELETE FROM orders WHERE id='$id'";
            mysqli_query($conn, $sql);

            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>🍕 Pizza Master Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg, #FF6B6B 0%, #FFA500 100%); min-height: 100vh; padding: 40px 20px; color: #333;}
        .container { max-width: 1200px; margin: 0 auto; }
        header { text-align: center; color: white; margin-bottom: 40px; text-shadow: 2px 2px 4px rgba(0,0,0,0.3); }
        h1 { font-size: 3em; margin-bottom: 10px; }
        
        .grid-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;}
        .full-width { grid-column: 1 / -1; }
        @media(max-width: 800px) { .grid-layout { grid-template-columns: 1fr; } }
        
        .card { background: white; border-radius: 15px; padding: 30px; box-shadow: 0 10px 20px rgba(0,0,0,0.2); }
        .card h2 { color: #FF6B6B; border-bottom: 3px solid #FFA500; padding-bottom: 10px; margin-bottom: 20px; }
        
        .form-group { display: flex; gap: 10px; margin-bottom: 20px; align-items: flex-end; }
        .form-stack { display: flex; flex-direction: column; gap: 8px; margin-bottom: 15px; }
        input[type="text"], input[type="number"] { padding: 10px; border: 2px solid #FF6B6B; border-radius: 8px; width: 100%; }
        
        .radio-group, .checkbox-group { display: flex; flex-direction: column; gap: 10px; }
        .selection-item { display: flex; align-items: center; padding: 10px; border-radius: 8px; cursor: pointer; background: #fff5f5;}
        .selection-item:hover { background-color: #ffe8e8; }
        .selection-item input { margin-right: 10px; width: 18px; height: 18px; accent-color: #FF6B6B; }
        .price { color: #FFA500; font-weight: bold; }
        
        button { padding: 10px 15px; background: #FF6B6B; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; }
        button:hover { background: #FFA500; }
        .btn-large { width: 100%; padding: 15px; font-size: 1.1em; }
        .btn-update { background: #4CAF50; padding: 6px 12px; font-size: 0.9em; }
        .btn-delete { background: #f44336; padding: 6px 12px; font-size: 0.9em; }
        
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ecf0f1; }
        th { background-color: #FFF5E6; color: #FF6B6B; }
        .price-input { width: 90px !important; padding: 6px !important; margin-right: 5px; border: 1px solid #ccc !important;}
        
        .badge { padding: 5px 10px; border-radius: 20px; font-size: 0.8em; font-weight: bold; color: white; }
        .bg-pending { background-color: #FFA500; }
        .bg-completed { background-color: #4CAF50; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🍕 Pizza Master Dashboard</h1>
            <p>Admin Menu Management & Live Ordering System</p>
        </header>

        <div class="grid-layout">
            
            <div class="card">
                <h2>⚙️ Manage Pizzas</h2>
                <form method="post" class="form-group">
                    <div style="flex: 2;"><input type="text" name="name" placeholder="New Pizza Name" required></div>
                    <div style="flex: 1;"><input type="number" name="price" step="0.01" min="0" placeholder="Price" required></div>
                    <button type="submit" name="add_pizza">Add</button>
                </form>
                <table>
                    <tbody>
                        <?php
                            // TODO 3: Read from 'pizzas' table using mysqli_query and mysqli_fetch_assoc
                            // Remember to use htmlspecialchars() for security!
                            $result = mysqli_query($conn, "SELECT * FROM pizzas");
                            
                            // echo "<tr><td colspan='3'><em>Code pizza read logic here...</em></td></tr>";
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td><strong>" . htmlspecialchars($row['name']) . "</strong></td>";

                                echo "<td>
                                    <form method='post' style='display:flex;'>
                                        <input type='hidden' name='item_id' value='{$row['id']}'>
                                        <input type='number' name='new_price' value='{$row['price']}' step='0.01' class='price-input'>
                                        <button type='submit' name='update_pizza' class='btn-update'>Save</button>
                                    </form>
                                </td>";

                                echo "<td>
                                    <form method='post'>
                                        <input type='hidden' name='item_id' value='{$row['id']}'>
                                        <button type='submit' name='delete_pizza' class='btn-delete'>✖</button>
                                    </form>
                                </td>";

                                echo "</tr>";
                            }

                            /* Example of how the generated HTML should look:
                            <tr>
                                <td><strong>Safe Pizza Name</strong></td>
                                <td>
                                    <form method='post' style='display:flex;'>
                                        <input type='hidden' name='item_id' value='1'>
                                        <input type='number' name='new_price' value='150.00' step='0.01' class='price-input' required>
                                        <button type='submit' name='update_pizza' class='btn-update'>Save</button>
                                    </form>
                                </td>
                                <td>
                                    <form method='post'>
                                        <input type='hidden' name='item_id' value='1'>
                                        <button type='submit' name='delete_pizza' class='btn-delete'>✖</button>
                                    </form>
                                </td>
                            </tr>
                            */
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="card">
                <h2>⚙️ Manage Toppings</h2>
                <form method="post" class="form-group">
                    <div style="flex: 2;"><input type="text" name="name" placeholder="New Topping Name" required></div>
                    <div style="flex: 1;"><input type="number" name="price" step="0.01" min="0" placeholder="Price" required></div>
                    <button type="submit" name="add_topping">Add</button>
                </form>
                <table>
                    <tbody>
                        <?php
                            // TODO 4: Read from 'toppings' table and generate rows dynamically
                            //should also contain the a form with input to update price and a delete button similar to pizzas
                            $result = mysqli_query($conn, "SELECT * FROM toppings");

                            //echo "<tr><td colspan='3'><em>Code toppings read logic here...</em></td></tr>";

                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                
                                // Topping Name (safe output)
                                echo "<td><strong>" . htmlspecialchars($row['name']) . "</strong></td>";

                                // Update Price Form
                                echo "<td>
                                    <form method='post' style='display:flex;'>
                                        <input type='hidden' name='item_id' value='{$row['id']}'>
                                        <input type='number' name='new_price' value='{$row['price']}' step='0.01' class='price-input' required>
                                        <button type='submit' name='update_topping' class='btn-update'>Save</button>
                                    </form>
                                </td>";

                                // Delete Button
                                echo "<td>
                                    <form method='post'>
                                        <input type='hidden' name='item_id' value='{$row['id']}'>
                                        <button type='submit' name='delete_topping' class='btn-delete'>✖</button>
                                    </form>
                                </td>";

                                echo "</tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card" style="max-width: 800px; margin: 0 auto 30px auto;">
            <h2>🛒 Place New Order</h2>
            <form method="post">
                <div class="form-stack">
                    <label><strong>Customer Name</strong></label>
                    <input type="text" name="customer" required>
                </div>

                <div class="grid-layout" style="gap: 20px; margin-bottom: 0;">
                    
                    <div class="form-stack">
                        <label><strong>Select Pizza</strong></label>
                        <div class="radio-group">
                            <?php 
                                // TODO 5: Fetch Pizzas from DB to generate radio buttons
                                $result = mysqli_query($conn, "SELECT * FROM pizzas");

                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<label class='selection-item'>
                                            <input type='radio' name='pizza' value='{$row['id']}' required>
                                            {$row['name']} - ₱{$row['price']}
                                        </label>";
                                }

                                //echo "<label class='selection-item'><em>Code pizza radio buttons here...</em></label>";
                            ?>
                        </div>
                    </div>

                    <div class="form-stack">
                        <label><strong>Select Toppings</strong></label>
                        <div class="checkbox-group">
                            <?php 
                                // TODO 6: Fetch Toppings from DB to generate checkboxes
                                $result = mysqli_query($conn, "SELECT * FROM toppings");

                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<label class='selection-item'>
                                            <input type='checkbox' name='toppings[]' value='{$row['id']}'>
                                            {$row['name']} (+₱{$row['price']})
                                        </label>";
                                }

                                //echo "<label class='selection-item'><em>Code topping checkboxes here...</em></label>";
                            ?>
                        </div>
                    </div>
                </div>

                <div class="form-stack" style="margin-top: 15px;">
                    <label><strong>Quantity</strong></label>
                    <input type="number" name="qty" min="1" value="1" required>
                </div>

                <button type="submit" name="create_order" class="btn-large">🚀 Submit Order</button>
            </form>
        </div>

        <div class="card full-width">
            <h2>📋 Live Kitchen Orders</h2>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th><th>Customer</th><th>Order Details</th><th>Total</th><th>Status</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // TODO 7: Read from 'orders' table and display live kitchen orders
                            // If status is Pending, show the Checkmark (✔) button. Otherwise, hide it.
                            $result = mysqli_query($conn, "SELECT * FROM orders");

                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>{$row['id']}</td>";
                                echo "<td>{$row['customer']}</td>";
                                echo "<td>{$row['details']}</td>";
                                echo "<td>₱{$row['total']}</td>";

                                $statusClass = ($row['status'] == 'Pending') ? 'bg-pending' : 'bg-completed';

                                echo "<td><span class='badge $statusClass'>{$row['status']}</span></td>";

                                echo "<td>";

                                if ($row['status'] == 'Pending') {
                                    echo "<form method='post' style='display:inline;'>
                                            <input type='hidden' name='order_id' value='{$row['id']}'>
                                            <button name='update_status'>✔</button>
                                        </form>";
                                }

                                echo "<form method='post' style='display:inline;'>
                                        <input type='hidden' name='order_id' value='{$row['id']}'>
                                        <button name='delete_order' class='btn-delete'>✖</button>
                                    </form>";

                                echo "</td>";
                                echo "</tr>";
                            }

                            // echo "<tr><td colspan='6' style='text-align:center;'><em>Code orders read logic here...</em></td></tr>";
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
</body>
</html>