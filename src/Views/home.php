<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home - Cinetech</title>
    <!-- Add CSS here for styling -->
</head>
<body>
    <h1>Welcome to Cinetech</h1>
    <h2>Popular Movies</h2>
    <div>
        <?php foreach ($movies['results'] as $movie): ?>
            <div>
                <h3><?= htmlspecialchars($movie['title']); ?></h3>
                <p><?= htmlspecialchars($movie['overview']); ?></p>
                <a href="index.php?controller=movie&action=details&id=<?= $movie['id']; ?>">View Details</a>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
