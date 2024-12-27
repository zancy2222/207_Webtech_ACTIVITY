<?php
include('db_conn.php');

if (isset($_POST['add_book'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $edition = mysqli_real_escape_string($conn, $_POST['edition']);

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        move_uploaded_file($image_tmp, "images/" . $image);
    } else {
        $image = ''; // Handle case where no image is uploaded
    }

    // Insert query
    $query = "INSERT INTO books (title, author, edition, image) VALUES ('$title', '$author', '$edition', '$image')";
    if (mysqli_query($conn, $query)) {
        header('Location: admin_dashboard.php');
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Edit Book
if (isset($_POST['edit_book'])) {
    $id = $_POST['book_id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $edition = mysqli_real_escape_string($conn, $_POST['edition']);

    // Update the book record in the database (without changing the image)
    $query = "UPDATE books SET title='$title', author='$author', edition='$edition' WHERE id=$id";

    if (mysqli_query($conn, $query)) {
        header('Location: admin_dashboard.php');
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}




// Delete Book
if (isset($_POST['delete_book'])) {
    $id = $_POST['delete_book'];
    $query = "DELETE FROM books WHERE id=$id";
    mysqli_query($conn, $query);
    header('Location: admin_dashboard.php');
}

$query = "SELECT * FROM books";
$result = mysqli_query($conn, $query);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Manage Books</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
        }

        .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #343a40;
            padding-top: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .sidebar a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
        }

        .sidebar a:hover {
            background-color: #ff5400;
            color: white;
        }

        .main-content {
            margin-left: 240px;
            padding: 20px;
        }

        .sidebar .fa {
            margin-right: 10px;
        }

        .btn-logout {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 12px;
            cursor: pointer;
            width: 100%;
            text-align: left;
            margin-top: 20px;
        }

        .btn-logout:hover {
            background-color: #c82333;
        }

        .table {
            margin-top: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .table th,
        .table td {
            text-align: center;
            padding: 12px;
        }

        .table th {
            background-color: #343a40;
            color: white;
        }

        .btn {
            border-radius: 4px;
        }

        .modal-header {
            background-color: #343a40;
            color: white;
        }

        .modal-body input,
        .modal-body textarea {
            margin-bottom: 10px;
        }

        .btn-primary {
            background-color: #ff5400;
            border: none;
        }

        .btn-primary:hover {
            background-color: #e55300;
        }
    </style>
</head>

<body>


    <div class="sidebar">
        <h3 class="text-white text-center mb-4">Admin Panel</h3>
        <a href="admin_dashboard.php"><i class="fas fa-book"></i> Manage Books</a>
        <div class="mt-auto">
            <a href="login.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>


    <div class="main-content">
        <h2>Manage Books</h2>

        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBookModal">
            <i class="fas fa-plus"></i> Add Book
        </button>

        <table class="table mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Edition</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['title'] ?></td>
                        <td><?= $row['author'] ?></td>
                        <td><?= $row['edition'] ?></td>
                        <td><img src="images/<?= $row['image'] ?>" alt="Book Image" width="50"></td>
                        <td>
                            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editBookModal<?= $row['id'] ?>"><i class="fas fa-edit"></i> Edit</button>
                            <form action="" method="POST" style="display:inline;">
                                <input type="hidden" name="delete_book" value="<?= $row['id'] ?>">
                                <button class="btn btn-danger"><i class="fas fa-trash"></i> Delete</button>
                            </form>
                        </td>
                    </tr>

                    <div class="modal fade" id="editBookModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="editBookModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="" method="POST">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editBookModalLabel">Edit Book</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="book_id" value="<?= $row['id'] ?>">
                                        <div class="mb-3">
                                            <label for="title" class="form-label">Title</label>
                                            <input type="text" class="form-control" name="title" value="<?= $row['title'] ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="author" class="form-label">Author</label>
                                            <input type="text" class="form-control" name="author" value="<?= $row['author'] ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="edition" class="form-label">Edition</label>
                                            <input type="text" class="form-control" name="edition" value="<?= $row['edition'] ?>" required>
                                        </div>
 
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" name="edit_book" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="addBookModal" tabindex="-1" aria-labelledby="addBookModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addBookModalLabel">Add Book</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="author" class="form-label">Author</label>
                            <input type="text" class="form-control" name="author" required>
                        </div>
                        <div class="mb-3">
                            <label for="edition" class="form-label">Edition</label>
                            <input type="text" class="form-control" name="edition" required>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control" name="image" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add_book" class="btn btn-primary">Add Book</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>