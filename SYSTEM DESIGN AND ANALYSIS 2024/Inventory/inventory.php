<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INVENTORY</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="inventory.css">
    <link rel="stylesheet" href="sales.css">
    <link rel="stylesheet" href="patients.css">
    <script src="script.js" defer></script>
    <style>
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
</head>

<body>

    <div class="mainContainer">

        <div class="sideBar">

            <div class="topMenue">
                <h3>MUST MEDICAL STORE</h3>
            </div>

            <div class="middleMenue">

                <button id="homeBtn" class="MenueItem">
                    <div><img src="../icon/home.png" alt="Home Icon" class="icons"></div>
                    <h4>Home</h4>
                </button>

                <button id="salesBtn" class="MenueItem">
                    <!-- Fixed ID to match JS -->
                    <div><img src="../icon/increase.png" alt="Sales Icon" class="icons"></div>
                    <h4>Sales</h4>
                </button>

                <button id="patientsBtn" class="MenueItem">
                    <!-- Cleaned up class spacing -->
                    <div><img src="../icon/patientrecords.png" alt="Patients Icon" class="icons"></div>
                    <h4>Patients</h4>
                </button>

                <button id="inventoryBtn" class="MenueItem">
                    <!-- Cleaned up class spacing -->
                    <div><img src="../icon/inventory.png" alt="Inventory Icon" class="icons"></div>
                    <h4>Inventory</h4>
                </button>
            </div>


            <div class="bottomMenue">
                <div class="userName">
                    <?php
                    // Start the session to access session variables
                    session_start();

                    // Database credentials
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $database = "hospitaldb";

                    // Create a connection
                    $conn = new mysqli($servername, $username, $password, $database);

                    // Check connection
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // Check if the user is logged in by checking if a session variable exists
                    if (isset($_SESSION['email'])) {
                        $loggedInUser = $_SESSION['email'];

                        // SQL query to retrieve the username and position for the logged-in user
                        $query = "SELECT username, position FROM logintb WHERE email = ?";

                        // Prepare and bind the statement to prevent SQL injection
                        $stmt = $conn->prepare($query);
                        if ($stmt === false) {
                            die("Error preparing statement: " . $conn->error);
                        }

                        $stmt->bind_param("s", $loggedInUser);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result && $result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            echo "<h3>" . htmlspecialchars($row['username']) . "</h3>";
                            echo "<h4>" . htmlspecialchars($row['position']) . "</h4>";
                        } else {
                            echo "<h3>Guest</h3>";
                            echo "<h4>Role Unknown</h4>";
                        }

                        // Close the statement
                        $stmt->close();
                    } else {
                        // Redirect to login page if no user is logged in
                        header("Location: ./login.html");
                        exit(); // Ensure no further code is executed after the redirect
                    }

                    // Close the connection
                    $conn->close();
                    ?>


                </div>


                <button type="button" style="border: none; outline: none; background-color: #003049;" class="logOut"
                    onclick="location.href='./../index.html';">
                    <img style="height: 27px;" src="../icon/log-out.png" alt="Log out">
                </button>

            </div>
        </div>

        <!-- ---------------------------home---------------------------  -->

        <div id="home" class="ContentContainer">




            <?php
            // Database credentials
            $servername = "localhost";
            $username = "root";
            $password = "";
            $database = "hospitaldb";

            // Create a connection
            $conn = new mysqli($servername, $username, $password, $database);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // SQL query to get the total number of patients assisted
            $patientsQuery = "SELECT COUNT(*) AS total_patients FROM patients";
            $patientsResult = $conn->query($patientsQuery);
            $totalPatients = ($patientsResult && $row = $patientsResult->fetch_assoc()) ? $row['total_patients'] : 0;

            // SQL query to get the total revenue from sales
            $revenueQuery = "SELECT SUM(price) AS total_revenue FROM patients";
            $revenueResult = $conn->query($revenueQuery);
            $totalRevenue = ($revenueResult && $row = $revenueResult->fetch_assoc()) ? $row['total_revenue'] : 0;

            $conn->close();
            ?>

            <div class="homeCards">

                <div class="infoCard">
                    <h3>Patients Assisted</h3>
                    <div class="cardSec">
                        <img class="cardIcon" src="../icon/patients.png">
                        <h4><?php echo number_format($totalPatients); ?></h4>
                    </div>
                </div>

                <div class="infoCard">
                    <h3>Revenue (MWK)</h3>
                    <div class="cardSec" style="width: 95%; padding: 0 10px;">
                        <img class="cardIcon" src="../icon/money.png">
                        <h4 style="font-size: 2.2em;"><?php echo number_format($totalRevenue); ?></h4>
                    </div>
                </div>

            </div>

            <form id="medicineForm" class="form" action="add_medicine.php" method="post">

                <div class="formGroup">
                    <div class="inputGroup">
                        <label for="name">Name of Medicine</label>
                        <input type="text" name="name" placeholder="Name of Medicine" required>
                    </div>

                    <div class="inputGroup">
                        <label for="quantity">Quantity</label>
                        <input type="number" name="quantity" placeholder="Quantity" required min="1">
                    </div>
                </div>

                <div class="formGroup" style="display: flex; width: 100%;">

                    <div class="inputGroup">
                        <label for="category">Category</label>
                        <input type="text" name="category" placeholder="Category" required>

                        <!-- div style="margin-right: 3.1rem;" class="inputGroup">
                            <label for="expiry">Expiry Date</label>
                            <input type="date" name="expiry" min="2024-12-27" required> -->
                    </div>

                    <div class="inputGroup">
                        <label for="manufacturer">Manufacture Name</label>
                        <input type="text" name="manufacturer" placeholder="Manufacture Name" required>
                    </div>

                </div>

                <div class="formGroup">

                    <div class="inputGroup">
                        <label for="price">Price (MWK)</label>
                        <input type="number" min="1000" name="price" placeholder="Price (MWK)" required>
                    </div>

                    <button id="openModalBtn" type="submit" class="Btn">ADD
                        MEDICINE <span class="arrow"><img class="arrowIcon" src="../icon/arrow.png"></span></button>
                </div>

            </form>

            <!-- Modal Popup -->
            <div id="confirmModal" class="modal">
                <div class="modal-content">

                    <h3>Confirm Addition</h3>

                    <p>Are you sure you want to add this medicine information to the database?</p>

                    <h4 class="modaldispay">Name: <span id="modalName"></span></h4>
                    <h4 class="modaldispay">Price: <span id="modalPrice"></span></h4>
                    <h4 class="modaldispay">Quantity: <span id="modalQuantity"></span></h4>
                    <h4 class="modaldispay">Category: <span id="modalCategory"></span></h4>
                    <h4 class="modaldispay">Expiry Date: <span id="modalExpiry"></span></h4>
                    <h4 class="modaldispay">Manufacture Name: <span id="modalManufacturer"></span></h4>
                    <div class="modalBtn">
                        <button id="confirmBtn" class="confirm-btn">Confirm</button>
                        <button id="cancelBtn" class="cancel-btn">Cancel</button>
                    </div>

                </div>
            </div>


        </div>
        <div id="sales" class="ContentContainer salesSection" style="display: none;">

            <div>

                <!-- Sales Content goes here -->
                <div class="homeCards">

                    <?php
                    // Database credentials
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $database = "hospitaldb";

                    // Create a connection
                    $conn = new mysqli($servername, $username, $password, $database);

                    // Check connection
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    /*// SQL query to get the total initial stock quantity
                    $initialQuery = "SELECT SUM(quantity) AS quantity FROM inventory";
                    $initialResult = $conn->query($initialQuery);
                    $initialQuantity = ($initialResult && $row = $initialResult->fetch_assoc()) ? $row['quantity'] : 0;
                    */
                    // SQL query to get the total current stock quantity
                    $currentQuery = "SELECT SUM(quantity) AS current_quantity FROM inventory";
                    $currentResult = $conn->query($currentQuery);
                    $currentQuantity = ($currentResult && $row = $currentResult->fetch_assoc()) ? $row['current_quantity'] : 0;

                    // Calculate the amount removed
                    //$monthlyRemoved = $initialQuantity - $currentQuantity;

                    // SQL query to check for medicines with low stock (<1000)
                    $lowStockQuery = "SELECT medicine FROM inventory WHERE quantity < 1000";
                    $lowStockResult = $conn->query($lowStockQuery);

                    // Collect low stock medicines for popup
                    $lowStockMedicines = [];
                    if ($lowStockResult && $lowStockResult->num_rows > 0) {
                        while ($row = $lowStockResult->fetch_assoc()) {
                            $lowStockMedicines[] = $row['medicine'];
                        }
                    }
                    $check = "SELECT SUM(quantity) AS quantity FROM patients";
                    $run = mysqli_query($conn, $check);

                    if ($run) {
                        $data = mysqli_fetch_assoc($run);
                        $quantity = $data['quantity'];
                    } else {
                        $quantity = 0; // Default in case of query failure
                    }

                    $conn->close();
                    ?>


                    <!-- HTML Structure -->
                    <div class="infoCard">
                        <h3>Monthly Issues</h3>
                        <div class="cardSec">
                            <img class="cardIcon" src="../icon/medicine.png">
                            <h4><?php echo number_format($quantity); ?></h4>
                        </div>
                    </div>

                    <div class="infoCard">
                        <h3>Remaining In Stock</h3>
                        <div class="cardSec">
                            <img class="cardIcon" src="../icon/warehouse.png">
                            <h4 style="font-size: 2.2em;"><?php echo number_format($currentQuantity); ?>
                            </h4>
                        </div>
                    </div>


                </div>
            </div>
            <div class="tableContainer">
                <?php
                // Database credentials
                $servername = "localhost";
                $username = "root";
                $password = "";
                $database = "hospitaldb";

                // Create a connection
                $conn = new mysqli($servername, $username, $password, $database);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // SQL query to retrieve data from the sales table
                $salesQuery = "SELECT medicine, visit_date, quantity, price FROM patients";
                $salesResult = $conn->query($salesQuery);
                ?>

                <table border="0">
                    <tr>

                        <th>Medicine</th>
                        <th>Issued Date</th>
                        <th>Quantity</th>
                        <th>Price (MWK)</th>
                    </tr>

                    <?php
                    // Check if there are results and display them
                    if ($salesResult && $salesResult->num_rows > 0) {
                        while ($row = $salesResult->fetch_assoc()) {
                            echo "<tr>";

                            echo "<td>" . htmlspecialchars($row['medicine']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['visit_date']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                            echo "<td>" . number_format($row['price']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No sales records found.</td></tr>";
                    }

                    $conn->close();
                    ?>
                </table>

            </div>

        </div>

        <div id="patients" class="ContentContainer" style="display: none;">

            <div class="searchBar">
                <div class="search"><input class="searchInput" type="text" placeholder="Search"> <img class="searchIcon"
                        style="height: 28px;" src="../icon/search.png"></div>
            </div>

            <div class="salesTableContainer">
                <?php
                // Database credentials
                $servername = "localhost";
                $username = "root";
                $password = "";
                $database = "hospitaldb";

                // Create a connection
                $conn = new mysqli($servername, $username, $password, $database);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // SQL query to retrieve data from the patients table
                $patientsQuery = "SELECT id, name, age, visit_date, phone, price, disease, location, prescription FROM patients";
                $patientsResult = $conn->query($patientsQuery);
                ?>

                <table border="0" style="width: 60%; position: absolute; top: 150px; left: 330px;">
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Age</th>
                        <th>Visit Date</th>
                        <th>Phone Number</th>
                        <th>Price</th>
                        <th>Health Issue</th>
                        <th>Location</th>
                        <th>Prescription</th>
                    </tr>

                    <?php
                    // Check if there are results and display them
                    if ($patientsResult && $patientsResult->num_rows > 0) {
                        while ($row = $patientsResult->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['age']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['visit_date']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                            echo "<td>" . number_format($row['price']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['disease']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['location']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['prescription']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9'>No patient records found.</td></tr>";
                    }

                    $conn->close();
                    ?>
                </table>

            </div>
        </div>

        <div id="inventory" class="ContentContainer" style="display: none;">
            <!-- Inventory Content goes here -->
            <div class="searchBar">
                <form method="POST" action="">
                    <div class="search">
                        <input class="searchInput" type="text" name="search" placeholder="Search">
                        <button type="submit">
                            <img class="searchIcon" style="height: 28px;" src="../icon/search.png" alt="Search">
                        </button>
                    </div>
                </form>
            </div>

            <div class="salesTableContainer">
                <?php
                // Database credentials
                $servername = "localhost";
                $username = "root";
                $password = "";
                $database = "hospitaldb";

                // Create a connection
                $conn = new mysqli($servername, $username, $password, $database);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // SQL query to retrieve data from the inventory table
                $searchTerm = isset($_POST['search']) ? $_POST['search'] : '';
                $inventoryQuery = "SELECT id, medicine, category, manufacturer, quantity, price FROM inventory";

                // Modify query if a search term is provided
                if (!empty($searchTerm)) {
                    $inventoryQuery .= " WHERE medicine LIKE ?";
                }

                // Prepare and execute query
                $stmt = $conn->prepare($inventoryQuery);

                if (!empty($searchTerm)) {
                    $searchParam = '%' . $searchTerm . '%';
                    $stmt->bind_param("s", $searchParam);
                }

                $stmt->execute();
                $inventoryResult = $stmt->get_result();
                ?>

                <table border="0">
                    <tr>
                        <th>ID</th>
                        <th>Medicine</th>
                        <th>Category</th>
                        <th>Manufacturer</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>

                    <?php
                    // Check if there are results and display them
                    if ($inventoryResult && $inventoryResult->num_rows > 0) {
                        while ($row = $inventoryResult->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['medicine']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['manufacturer']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                            echo "<td>" . number_format($row['price']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No records found.</td></tr>";
                    }

                    $stmt->close();
                    $conn->close();
                    ?>
                </table>

                <!-- Low Stock Popup Notification -->
                <?php if (!empty($lowStockMedicines)): ?>
                    <div class="lowStockPopup">
                        <h3>Low Stock Alert</h3>
                        <p>The following medicines are below 1000 in stock:</p>
                        <ul>
                            <?php foreach ($lowStockMedicines as $medicine): ?>
                                <li><?php echo htmlspecialchars($medicine); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

            </div>
        </div>

    </div>

    </div>

</body>

</html>