<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Animal Listing</title>
</head>
<body>
    <header>
        <div class="container">
            <h2>List of Animals</h2>
        </div>
    </header>
    <div class="container">
        <?php
        include 'db.php';

        // Fetch visitor count from session
        session_start();
        if (!isset($_SESSION['visitor_count'])) {
            $_SESSION['visitor_count'] = 0;
        }
        $_SESSION['visitor_count']++;

        echo "<div class='visitor-count'>Visitor count: <span>" . $_SESSION['visitor_count'] . "</span></div>";

        // Fetch and display animals
        $sql = "SELECT * FROM animals";
        if (isset($_GET['category'])) {
            $category = $_GET['category'];
            $sql .= " WHERE category = '$category'";
        }
        if (isset($_GET['life_expectancy'])) {
            $life_expectancy = $_GET['life_expectancy'];
            $sql .= " WHERE life_expectancy = '$life_expectancy'";
        }
        if (isset($_GET['sort'])) {
            $sort = $_GET['sort'];
            $sql .= " ORDER BY $sort";
        } else {
            $sql .= " ORDER BY submission_date DESC";
        }

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Image</th>
                        <th>Description</th>
                        <th>Life Expectancy</th>
                        <th>Options</th>
                    </tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["name"] . "</td>
                        <td>" . $row["category"] . "</td>
                        <td><img src='" . $row["image"] . "' alt='" . $row["name"] . "'></td>
                        <td>" . $row["description"] . "</td>
                        <td>" . $row["life_expectancy"] . "</td>
                        <td>
                            <a href='edit.php?id=" . $row["id"] . "'>Edit</a> |
                            <a href='delete.php?id=" . $row["id"] . "'>Delete</a>
                        </td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No animals found.</p>";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
