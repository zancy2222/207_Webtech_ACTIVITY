<?php
$conn = new mysqli('localhost', 'root', '', 'booknest');
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

$searchResults = [];

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
	<title>BookNest - Home</title>
	<link rel="stylesheet" href="homepage.css">
</head>

<body>
	<div class="responsive-layout">
		<!-- Header Section -->
		<header>
			<div class="logo">
				<a href="index.php">
					<img src="logo booknest.png" id="logo1" alt="Booknest Logo">
				</a>
			</div>

			<h1>BOOKNEST LIBRARY</h1>
			<div class="top-links">
				<a href="index.php">Home</a>
				<a href="#">Catalog</a>
				<a href="#">Services</a>
				<a href="#">About</a>
				<a href="login.php">LOG IN</a>
			</div>
		</header>
		<section class="search-bar">
			<div class="search-container">
				<div class="logo-text-wrapper">
					<h2>Nest, Learn, Bliss</h2>
					<a href="index.php">
						<img src="207 LOGO.png" id="logo2" alt="Booknest Logo">
					</a>
				</div>
				<form method="POST" action="search_results.php" onsubmit="return checkLogin()">
					<input type="text" name="search" placeholder="Search by keyword, title, author, or subject" id="search">
					<button type="submit" class="search-button btn btn-primary">Search</button>
				</form>
				<div class="search-options">
					<a href="#">Advanced Search</a> |
					<a href="#">Course Reserves</a> |
					<a href="#">Databases</a> |
					<a href="#">Journals</a>
				</div>
			</div>
		</section>
		<!-- Information Section -->
		<section class="info-section">


			<!-- Featured Books Slider -->
			<div class="featured-books">
				<h3>Featured Books</h3>
				<div class="slider">
					<div class="slider-container">
						<?php
						$query = "SELECT * FROM books";
						$result = $conn->query($query);

						if ($result->num_rows > 0) {
							while ($row = $result->fetch_assoc()) {

								echo '<div class="book-info">';
								echo '<h3>' . $row['title'] . '</h3>';
								echo '<p>Author: ' . $row['author'] . '</p>';
								echo '<p>' . $row['edition'] . '</p>';

								echo '</div>';
								echo '<div class="slide">';
								echo '<a href="#"><img src="images/' . $row['image'] . '" alt="' . $row['title'] . '"></a>';
								echo '</div>';
							}
						}
						?>
					</div>
				</div>
			</div>

			<!-- Quicklinks Section -->
			<div class="quick-links">
				<h3>Quicklinks</h3>
				<ul>
					<li><a href="#">Faculty Resources</a></li>
					<li><a href="#">Grad Student Resources</a></li>
					<li><a href="#">Undergrad Resources</a></li>
					<li><a href="#">Community Resources</a></li>
					<li><a href="#">Reserve a Space</a></li>
					<li><a href="#">Interlibrary Loan</a></li>
					<li><a href="#">Off-Campus Access</a></li>
					<li><a href="#">My Library Account</a></li>
				</ul>
			</div>

			<!-- Library Hours Section (moved) -->
			<div class="library-info">
				<h3>Service Hours</h3>
				<h4>Today's Hours: 24 hours</h4>
				<p>BookNest Library</p>
				<a href="#">View all Library Hours</a>
			</div>
		</section>

		<!-- Calendar Section -->
		<div class="calendar">
			<h3>October 2024</h3>
			<div class="calendar-grid">
				<div class="day">Sun</div>
				<div class="day">Mon</div>
				<div class="day">Tue</div>
				<div class="day">Wed</div>
				<div class="day">Thu</div>
				<div class="day">Fri</div>
				<div class="day">Sat</div>
				<div class="empty"></div> <!-- Empty cell for the first day -->
				<div class="empty"></div> <!-- Empty cell for the second day -->
				<div class="date">1</div>
				<div class="date">2</div>
				<div class="date">3</div>
				<div class="date">4</div>
				<div class="date">5</div>
				<div class="date">6</div>
				<div class="date">7</div>
				<div class="date">8</div>
				<div class="date">9</div>
				<div class="date">10</div>
				<div class="date">11</div>
				<div class="date">12</div>
				<div class="date">13</div>
				<div class="date">14</div>
				<div class="date">15</div>
				<div class="date">16</div>
				<div class="date">17</div>
				<div class="date">18</div>
				<div class="date">19</div>
				<div class="date">20</div>
				<div class="date">21</div>
				<div class="date">22</div>
				<div class="date">23</div>
				<div class="date">24</div>
				<div class="date">25</div>
				<div class="date">26</div>
				<div class="date">27</div>
				<div class="date">28</div>
				<div class="date">29</div>
				<div class="date">30</div>
				<div class="date">31</div>
			</div>
		</div>



		<!-- Library Services Section -->
		<section class="services-section">
			<h2>Library Services</h2>
			<div class="services-grid">
				<div class="service">
					<a href="#">Online Databases</a>
				</div>
				<div class="service">
					<a href="#">Set an Appointment</a>
				</div>
				<div class="service">
					<a href="#">Borrow/Return Books</a>
				</div>
				<div class="service">
					<a href="#">Request Documents</a>
				</div>
			</div>
		</section>

		<footer>
			<p>&copy; 2024 BookNest Library. All Rights Reserved.</p>
			<address>
				Bulacan State University - Main<br>
				Guinhawa, Malolos, Bulacan, Philippines<br>
				<a href="mailto:2023106829@ms.bulsu.edu.ph">library.booknest@bulsu.edu.ph</a>
			</address>
		</footer>
	</div>

	<script>
		function searchBooks() {
			const query = document.getElementById('search-input').value;
			fetch(`search.php?query=${encodeURIComponent(query)}`)
				.then(response => response.json())
				.then(data => {
					const container = document.getElementById('books-container');
					container.innerHTML = '';
					if (data.length > 0) {
						data.forEach(book => {
							container.innerHTML += `
                                <div class="book-info">
                                    <h3>${book.title}</h3>
                                    <p>Author: ${book.author}</p>
                                    <p>${book.edition}</p>
                                    <div class="slide">
                                        <a href="#"><img src="images/${book.image}" alt="${book.title}"></a>
                                    </div>
                                </div>
                            `;
						});
					} else {
						container.innerHTML = '<p>No books found!</p>';
					}
				});
		}
	</script>
	<script>
		function checkLogin() {
			var isLoggedIn = false;

			if (!isLoggedIn) {
				alert("Please login first");
				return false;
			}

			return true;
		}
	</script>
</body>

</html>