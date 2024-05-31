<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Animal Listing</title>
    <style>
        /* Style for the link/button */
        .submit-link {
            display: block;
            text-align: center;
            margin-bottom: 20px;
            font-size: 18px;
            color: #fff;
            background-color: #4CAF50;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .submit-link:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h2>List of Animals</h2>
        </div>
    </header>
    <div class="container">
        <form action="" method="get">
            <label for="category">Filter by Category:</label>
            <select id="category" name="category">
                <option value="">All</option>
                <option value="herbivores">Herbivores</option>
                <option value="omnivores">Omnivores</option>
                <option value="carnivores">Carnivores</option>
            </select>

            <label for="life_expectancy">Filter by Life Expectancy:</label>
            <select id="life_expectancy" name="life_expectancy">
                <option value="">All</option>
                <option value="0-1 year">0-1 year</option>
                <option value="1-5 years">1-5 years</option>
                <option value="5-10 years">5-10 years</option>
                <option value="10+ years">10+ years</option>
            </select>

            <label for="sort">Sort by:</label>
            <select id="sort" name="sort">
                <option value="submission_date DESC">Date of Submission (Newest First)</option>
                <option value="submission_date ASC">Date of Submission (Oldest First)</option>
                <option value="name ASC">Name (A-Z)</option>
                <option value="name DESC">Name (Z-A)</option>
            </select>

            <input type="submit" value="Apply Filters">
        </form>

        <?php
        include 'db.php';
        session_start();

        // Initialize visitor count in the database if it doesn't exist
        $visitor_query = "SELECT * FROM visitor_count LIMIT 1";
        $visitor_result = $conn->query($visitor_query);
        
        if ($visitor_result->num_rows == 0) {
            $conn->query("INSERT INTO visitor_count (count) VALUES (0)");
            $visitor_count = 0;
        } else {
            $visitor_row = $visitor_result->fetch_assoc();
            $visitor_count = $visitor_row['count'];
        }

        // Check if the visitor has been counted in this session
        if (!isset($_COOKIE['visitor'])) {
            // Increment visitor count in the database and set a cookie for 1 day
            $visitor_count++;
            $conn->query("UPDATE visitor_count SET count = $visitor_count");
            setcookie('visitor', '1', time() + 86400, '/'); // 86400 seconds = 1 day
        }

        echo "<div class='visitor-count'>Visitor count: <span>" . $visitor_count . "</span></div>";

        // Fetch and display animals
        $sql = "SELECT * FROM animals";
        $conditions = [];
        if (isset($_GET['category']) && !empty($_GET['category'])) {
            $category = $_GET['category'];
            $conditions[] = "category = '$category'";
        }
        if (isset($_GET['life_expectancy']) && !empty($_GET['life_expectancy'])) {
            $life_expectancy = $_GET['life_expectancy'];
            $conditions[] = "life_expectancy = '$life_expectancy'";
        }
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }
        if (isset($_GET['sort']) && !empty($_GET['sort'])) {
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
                        <td>" . htmlspecialchars($row["name"]) . "</td>
                        <td>" . htmlspecialchars($row["category"]) . "</td>
                        <td><img src='" . htmlspecialchars($row["image"]) . "' alt='" . htmlspecialchars($row["name"]) . "' style='width:100px; height:auto;'></td>
                        <td>" . htmlspecialchars($row["description"]) . "</td>
                        <td>" . htmlspecialchars($row["life_expectancy"]) . "</td>
                        <td>
                            <a href='edit.php?id=" . htmlspecialchars($row["id"]) . "'>Edit</a> |
                            <a href='delete.php?id=" . htmlspecialchars($row["id"]) . "' onclick='return confirm(\"Are you sure you want to delete this animal?\")'>Delete</a>
                        </td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No animals found.</p>";
        }

        $conn->close();
        ?>
    <a href="submission.php" class="submit-link">Submit New Animal</a>
    </div>

</body>
</html>
