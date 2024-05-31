<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];
    
    // Simple captcha check
    if ($_POST['captcha'] != '5') {
        $errors[] = "Captcha failed!";
    }

    // Form validation
    if (empty($_POST['name'])) {
        $errors[] = "Name of the animal is required.";
    }

    if (empty($_POST['category'])) {
        $errors[] = "Category is required.";
    }

    if (empty($_POST['description'])) {
        $errors[] = "Description is required.";
    }

    if (empty($_POST['life_expectancy'])) {
        $errors[] = "Life expectancy is required.";
    }

    if (empty($_FILES['image']['name'])) {
        $errors[] = "Image is required.";
    } else {
        // Validate image file
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['image']['type'], $allowed_types)) {
            $errors[] = "Invalid image type. Allowed types are JPEG, PNG, and GIF.";
        }
    }

    if (empty($errors)) {
        $name = $_POST['name'];
        $category = $_POST['category'];
        $description = $_POST['description'];
        $life_expectancy = $_POST['life_expectancy'];

        // Handling file upload
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

        $image = $target_file;

        // Insert data into database
        $stmt = $conn->prepare("INSERT INTO animals (name, category, image, description, life_expectancy) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $category, $image, $description, $life_expectancy);

        if ($stmt->execute()) {
            header("Location: index.php");
        } else {
            $errors[] = "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Animal Submission Form</title>
</head>
<body>
    <header>
        <div class="container">
            <h2>Submit Animal Information</h2>
        </div>
    </header>
    <div class="container">
        <?php
        if (!empty($errors)) {
            echo "<div class='errors'><ul>";
            foreach ($errors as $error) {
                echo "<li>$error</li>";
            }
            echo "</ul></div>";
        }
        ?>
        <form action="submission.php" method="post" enctype="multipart/form-data">
            <h3>Animal Information</h3>
            <label for="name">Name of the animal:</label>
            <input type="text" id="name" name="name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required>

            <label>Category:</label>
            <input type="radio" id="herbivores" name="category" value="herbivores" <?php echo (isset($_POST['category']) && $_POST['category'] == 'herbivores') ? 'checked' : ''; ?> required>
            <label for="herbivores">Herbivores</label>
            <input type="radio" id="omnivores" name="category" value="omnivores" <?php echo (isset($_POST['category']) && $_POST['category'] == 'omnivores') ? 'checked' : ''; ?> required>
            <label for="omnivores">Omnivores</label>
            <input type="radio" id="carnivores" name="category" value="carnivores" <?php echo (isset($_POST['category']) && $_POST['category'] == 'carnivores') ? 'checked' : ''; ?> required>
            <label for="carnivores">Carnivores</label>

            <label for="image">Image:</label>
            <input type="file" id="image" name="image" accept="image/*" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>

            <label for="life_expectancy">Life expectancy:</label>
            <select id="life_expectancy" name="life_expectancy" required>
                <option value="">Select life expectancy</option>
                <option value="0-1 year" <?php echo (isset($_POST['life_expectancy']) && $_POST['life_expectancy'] == '0-1 year') ? 'selected' : ''; ?>>0-1 year</option>
                <option value="1-5 years" <?php echo (isset($_POST['life_expectancy']) && $_POST['life_expectancy'] == '1-5 years') ? 'selected' : ''; ?>>1-5 years</option>
                <option value="5-10 years" <?php echo (isset($_POST['life_expectancy']) && $_POST['life_expectancy'] == '5-10 years') ? 'selected' : ''; ?>>5-10 years</option>
                <option value="10+ years" <?php echo (isset($_POST['life_expectancy']) && $_POST['life_expectancy'] == '10+ years') ? 'selected' : ''; ?>>10+ years</option>
            </select>

            <label for="captcha">Captcha: What is 3 + 2?</label>
            <input type="text" id="captcha" name="captcha" value="<?php echo isset($_POST['captcha']) ? htmlspecialchars($_POST['captcha']) : ''; ?>" required>

            <input type="submit" value="Submit">
        </form>
    </div>
</body>
</html>
