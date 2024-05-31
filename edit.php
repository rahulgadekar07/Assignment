<?php
include 'db.php';
session_start();

// Fetch existing data
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM animals WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $animal = $result->fetch_assoc();

    if (!$animal) {
        echo "Animal not found!";
        exit;
    }
} else {
    echo "Invalid request!";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $life_expectancy = $_POST['life_expectancy'];

    $sql = "UPDATE animals SET name=?, category=?, description=?, life_expectancy=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $name, $category, $description, $life_expectancy, $id);

    if (!empty($_FILES['image']['name'])) {
        // Handle file upload if a new file is provided
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

        $image = $target_file;

        // Update image field
        $sql = "UPDATE animals SET image=? WHERE id=?";
        $stmt_img = $conn->prepare($sql);
        $stmt_img->bind_param("si", $image, $id);
        $stmt_img->execute();
        $stmt_img->close();
    }

    if ($stmt->execute()) {
        header("Location: index.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Edit Animal Information</title>
</head>
<body>
    <header>
        <div class="container">
            <h2>Edit Animal Information</h2>
        </div>
    </header>
    <div class="container">
        <form action="edit.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
            <h3>Animal Information</h3>
            <label for="name">Name of the animal:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($animal['name']); ?>" required>

            <label>Category:</label>
            <input type="radio" id="herbivores" name="category" value="herbivores" <?php echo ($animal['category'] == 'herbivores') ? 'checked' : ''; ?> required>
            <label for="herbivores">Herbivores</label>
            <input type="radio" id="omnivores" name="category" value="omnivores" <?php echo ($animal['category'] == 'omnivores') ? 'checked' : ''; ?> required>
            <label for="omnivores">Omnivores</label>
            <input type="radio" id="carnivores" name="category" value="carnivores" <?php echo ($animal['category'] == 'carnivores') ? 'checked' : ''; ?> required>
            <label for="carnivores">Carnivores</label>

            <label for="image">Image:</label>
            <input type="file" id="image" name="image" accept="image/*">
            <img src="<?php echo htmlspecialchars($animal['image']); ?>" alt="<?php echo htmlspecialchars($animal['name']); ?>" style="max-width: 100px;">

            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($animal['description']); ?></textarea>

            <label for="life_expectancy">Life expectancy:</label>
            <select id="life_expectancy" name="life_expectancy" required>
                <option value="0-1 year" <?php echo ($animal['life_expectancy'] == '0-1 year') ? 'selected' : ''; ?>>0-1 year</option>
                <option value="1-5 years" <?php echo ($animal['life_expectancy'] == '1-5 years') ? 'selected' : ''; ?>>1-5 years</option>
                <option value="5-10 years" <?php echo ($animal['life_expectancy'] == '5-10 years') ? 'selected' : ''; ?>>5-10 years</option>
                <option value="10+ years" <?php echo ($animal['life_expectancy'] == '10+ years') ? 'selected' : ''; ?>>10+ years</option>
            </select>

            <input type="submit" value="Update">
        </form>
    </div>
</body>
</html>
