<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Animal Submission Form</title>
</head>
<body>
    <h2>Submit Animal Information</h2>
    <form action="submission.php" method="post" enctype="multipart/form-data">
        <label for="name">Name of the animal:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label>Category:</label><br>
        <input type="radio" id="herbivores" name="category" value="herbivores" required>
        <label for="herbivores">Herbivores</label>
        <input type="radio" id="omnivores" name="category" value="omnivores" required>
        <label for="omnivores">Omnivores</label>
        <input type="radio" id="carnivores" name="category" value="carnivores" required>
        <label for="carnivores">Carnivores</label><br><br>

        <label for="image">Image:</label><br>
        <input type="file" id="image" name="image" accept="image/*" required><br><br>

        <label for="description">Description:</label><br>
        <textarea id="description" name="description" required></textarea><br><br>

        <label for="life_expectancy">Life expectancy:</label><br>
        <select id="life_expectancy" name="life_expectancy" required>
            <option value="0-1 year">0-1 year</option>
            <option value="1-5 years">1-5 years</option>
            <option value="5-10 years">5-10 years</option>
            <option value="10+ years">10+ years</option>
        </select><br><br>

        <label for="captcha">Captcha: What is 3 + 2?</label><br>
        <input type="text" id="captcha" name="captcha" required><br><br>

        <input type="submit" value="Submit">
    </form>
</body>
</html>
