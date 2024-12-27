<!-- search_results.php -->
<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'booknest');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$searchResults = [];

// Check if the search form is submitted
if (isset($_POST['search'])) {
    $keyword = $conn->real_escape_string($_POST['search']);
    $query = "SELECT * FROM books WHERE title LIKE '%$keyword%' OR author LIKE '%$keyword%' OR edition LIKE '%$keyword%'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $searchResults[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Search Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        	header {
		background-color: #2e6da7; 
		color: white;
		display: flex;
		justify-content: space-between;
		align-items: center;
	}

	#logo1{
		height: 80px;
		width: 80px;
		margin-left: 30px;
	}

	header h1 {
		font-size: 30px;
		margin-left: 220px;
		text-align: center;
		font-family: "times new roman";
	}

	.top-links a {
		color: white;
		text-decoration: none;
		margin-left: 5px;
		margin-right: 15px;
		font-weight: bold;
		
	}

	.top-links a:hover {
		text-decoration: underline;
	}
	
    </style>
    <style>
        .book-card {
            margin-bottom: 30px;
        }
        .book-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .book-info {
            padding: 10px;
        }
    </style>
</head>
		<!-- Header Section -->
		<header>
			<div class="logo">
				<a href="index.php">
					<img src="logo booknest.png" id="logo1" alt="Booknest Logo">
				</a>
			</div>

			<h1>BOOKNEST LIBRARY</h1>
			<div class="top-links">
				<a href="indexMain.php">Home</a>
				<a href="#">Catalog</a>
				<a href="#">Services</a>
				<a href="#">About</a>
                <a href="login.php">LOG OUT</a>
			</div>
		</header>
<body>
    <div class="container my-5">
        <h2 class="text-center">Search Results</h2>
        <form method="POST" action="search_results.php" class="mb-4">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Search by keyword, title, author, or subject" required>
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>

        <?php if (!empty($searchResults)): ?>
            <div class="row">
                <?php foreach ($searchResults as $book): ?>
                    <div class="col-md-4">
                        <div class="card book-card">
                            <img src="images/<?php echo $book['image']; ?>" alt="<?php echo $book['title']; ?>">
                            <div class="card-body book-info">
                                <h5 class="card-title"><?php echo $book['title']; ?></h5>
                                <p class="card-text">Author: <?php echo $book['author']; ?></p>
                                <p class="card-text">Edition: <?php echo $book['edition']; ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php elseif (isset($_POST['search'])): ?>
            <div class="alert alert-warning">No results found for "<?php echo htmlspecialchars($_POST['search']); ?>"</div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
