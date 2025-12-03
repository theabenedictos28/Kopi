<?php
// Connect to MySQL
session_start();
$mysqli = new mysqli("sql100.infinityfree.com", "if0_40584912", "kopishop28", "if0_40584912_kopi_shop");

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php"); // Assuming login.php is your login page
    exit;
}

// Initialize variables
$search_query = "";
$search_result = false;
$menu_items = array(); // Initialize menu_items array


// If search query is present
if (isset($_GET['query'])) {
    $search_query = $_GET['query'];

    // Perform search query
    $sql = "SELECT * FROM menu WHERE name LIKE '%$search_query%'";
    $search_result = $mysqli->query($sql);
} else {
    // Fetch menu items grouped by category
    $sql = "SELECT * FROM menu ORDER BY category";
    $result = $mysqli->query($sql);

    // Store items grouped by category
    $menu_items = array();
    while ($row = $result->fetch_assoc()) {
        $menu_items[$row['category']][] = $row;
    }
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- My CSS -->
    <link rel="stylesheet" href="admin1.css">

    <title>KOPI Admin</title>
</head>
<body>


    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="#" class="brand">
            <i class='bx bxs-smile'></i>
            <span class="text">KOPI Admin</span>
        </a>
        <ul class="side-menu top">
            <li class="active">
                <a href="#">
                    <i class='bx bxs-dashboard' ></i>
                    <span class="text">Menu</span>
                </a>
            </li>
            <li>
                <a href="edit.php">
                    <i class='bx bxs-shopping-bag-alt' ></i>
                    <span class="text">Edit Menu</span>
                </a>
            </li>
            <li>
                <a href="add.php">
                    <i class='bx bxs-doughnut-chart' ></i>
                    <span class="text">Add Product</span>
                </a>
            </li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="logout.php" class="logout">
                    <i class='bx bxs-log-out-circle' ></i>
                    <span class="text">Logout</span>
                </a>
            </li>
        </ul>
    </section>
    <!-- SIDEBAR -->



    <!-- CONTENT -->
    <section id="content">
        <!-- NAVBAR -->
        <nav>
            <i class='bx bx-menu' ></i>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
                <div class="form-input">
                    <input type="search" name="query" placeholder="Search..." value="<?php echo htmlspecialchars($search_query); ?>">
                    <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
                </div>
            </form>
            <input type="checkbox" id="switch-mode" hidden>
            <label for="switch-mode" class="switch-mode"></label>
        </nav>
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Menu</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Kopi</a>
                        </li>
                        <li><i class='bx bx-chevron-right' ></i></li>
                        <li>
                            <a class="active" href="#">Home</a>
                        </li>
                    </ul>
                </div>
            </div>

                  <ul class="box-info">
                <li>
                    <span class="text">
                        <div class="container-fluid pt-5">
                            <div class="container">
                                <div class="row">
                                    <?php
                                    // Check if there are search results or if no search query is provided
                                    if (!$search_result || empty($search_query)) {
                                        foreach ($menu_items as $category => $items) {
                                            echo '<div class="col-lg-6">';
                                            echo '<h1 class="mb-5">' . ucfirst($category) . '</h1>';
                                            foreach ($items as $item) {
                                                echo '<div class="row align-items-center mb-5">';
                                                echo '<div class="col-4 col-sm-3 position-relative">';
                                                echo '<img class="w-100 rounded-circle mb-3 mb-sm-0" src="' . $item['image'] . '" alt="">';
                                                echo '</div>';
                                                echo '<div class="col-8 col-sm-9">';
                                                echo '<h4>' . $item['name'] . '</h4>';
                                                echo '</div>';
                                                echo '</div>';
                                            }
                                            echo '</div>';
                                        }
                                    } else {
                                        // Display search results
                                        while ($row = $search_result->fetch_assoc()) {
                                            echo '<div class="col-lg-6">';
                                            echo '<div class="row align-items-center mb-5">';
                                            echo '<div class="col-4 col-sm-3 position-relative">';
                                            echo '<img class="w-100 rounded-circle mb-3 mb-sm-0" src="' . $row['image'] . '" alt="">';
                                            echo '</div>';
                                            echo '<div class="col-8 col-sm-9">';
                                            echo '<h4>' . $row['name'] . '</h4>';
                                            echo '</div>';
                                            echo '</div>';
                                            echo '</div>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </span>
                </li>
            </ul>


        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->
    

    <script src="admin.js"></script>
</body>
</html>