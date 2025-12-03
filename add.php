<?php
// Start the session
session_start();

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php"); // Assuming login.php is your login page
    exit;
}

// Database connection
$mysqli = new mysqli("sql100.infinityfree.com", "if0_40584912", "kopishop28", "if0_40584912_kopi_shop");

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// If form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle add, edit, delete actions as before...
}

// Database connection
$mysqli = new mysqli("sql100.infinityfree.com", "if0_40584912", "kopishop28", "if0_40584912_kopi_shop");

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// If form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['add'])) {
        // Handle add action
        if(isset($_POST['category'])) {
            $category = $_POST['category'];
        } else {
            echo "Please provide details"; // Display error message if category is not selected
            exit; // Stop further execution
        }

        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];

        // Check if admin has provided all details
        if(empty($category) || empty($name) || empty($description) || empty($price) || empty($_FILES["image"]["name"])) {
            echo "Please enter all details."; // Display message to admin
        } else {
            // Handle image upload
            $targetDirectory = "img/"; // Directory where images will be stored
            $targetFile = $targetDirectory . basename($_FILES["image"]["name"]); // Get the target file path
            $imageFileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION)); // Get the image file type
            
            // Upload file
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                //for special characters
                $category = $mysqli->real_escape_string($_POST['category']);
                $name = $mysqli->real_escape_string($_POST['name']);
                $description = $mysqli->real_escape_string($_POST['description']);
                $price = $mysqli->real_escape_string($_POST['price']);
                // Insert data into database
                $sql = "INSERT INTO menu (category, name, description, price, image) VALUES ('$category', '$name', '$description', '$price', '$targetFile')";
                if ($mysqli->query($sql) === TRUE) {
                    echo "New item added successfully";
                } else {
                    echo "Error: " . $sql . "<br>" . $mysqli->error;
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } elseif(isset($_POST['edit'])) {
        // Handle edit action
        $id = $_POST['id'];
        // Redirect to edit page with the ID
        header("Location: edit_menu.php?id=$id");
        exit();
    } elseif(isset($_POST['delete'])) {
        // Handle delete action
        $id = $_POST['id'];
        // Delete the menu item from database
        $sql = "DELETE FROM menu WHERE id = '$id'";
        if ($mysqli->query($sql) === TRUE) {
            echo "Menu item deleted successfully";
        } else {
            echo "Error deleting menu item: " . $mysqli->error;
        }
    }
}

// Fetch menu items
$sql = "SELECT * FROM menu";
$result = $mysqli->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<!-- My CSS -->
	<link rel="stylesheet" href="add.css">

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
			<li class="">
				<a href="admin1.php">
					<i class='bx bxs-shopping-bag-alt' ></i>
					<span class="text">Menu</span>
				</a>
			</li>
			<li>
				<a href="edit.php">
					<i class='bx bxs-doughnut-chart' ></i>
					<span class="text">Edit Menu</span>
				</a>
			</li>
            <li class="active">
				<a href="#">
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
			<form action="search.php" method="GET">
				<div class="form-input">
					<input type="search" name="query" placeholder="Search...">
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
					<h1>Add Product</h1>
					<ul class="breadcrumb">
						<li>
							<a href="#">Kopi</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="#">Add Product</a>
						</li>
					</ul>
				</div>
			</div>

			<ul class="box-info">
				<li>
					<span class="text">
						<h1 style="padding-bottom: 30px; font-size: 50px;">Add Product</h1>
						<form method="post" enctype="multipart/form-data">
                            <h2>Category:</h2>
                            <div class="radio-group">
                                
                                <label class="left-top" for="hot">Hot</label>
                                <input type="radio" id="hot" name="category" value="Hot" required>
                                
                                <label class="left-top" for="cold">Cold</label>
                                <input type="radio" id="cold" name="category" value="Cold" required>
                                
                                <label class="left-top" for="pastry">Pastry</label> 
                                <input type="radio" id="pastry" name="category" value="Pastry" required>
                                
                                <label class="left-top" for="salad">Salad</label>
                                <input type="radio" id="salad" name="category" value="Salad" required>
                            </div>

                            <div class="name">
                                <label for="name">Product Name:</label>
                                <input type="text" id="name" name="name" required>
                            </div>

                            <div class="desc">
                                <label for="description">Description:</label>
                                <textarea id="description" name="description" required></textarea>
                            </div>

                            <div class="prices">
                                <label for="price">Price:</label>
                                <input type="text" id="price" name="price" required>
                            </div>

                            <label for="image">Image:</label>
                            <input type="file" id="image" name="image" required><br>
                            <input type="submit" name="add" value="Submit">
                        </form>
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