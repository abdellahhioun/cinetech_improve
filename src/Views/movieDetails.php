<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($details['title'] ?? 'Movie Details'); ?> - Cinetech</title>
    <!-- Add CSS here for styling -->
</head>
<body>
    <div class="movie-details">
        <h1><?= htmlspecialchars($details['title'] ?? ''); ?></h1>
        
        <?php if (isset($details['poster_path'])): ?>
            <img src="https://image.tmdb.org/t/p/w500<?= htmlspecialchars($details['poster_path']); ?>" 
                 alt="<?= htmlspecialchars($details['title'] ?? ''); ?> Poster">
        <?php endif; ?>

        <div class="movie-info">
            <?php if (isset($details['release_date'])): ?>
                <p><strong>Release Date:</strong> <?= htmlspecialchars($details['release_date']); ?></p>
            <?php endif; ?>

            <?php if (isset($details['vote_average'])): ?>
                <p><strong>Rating:</strong> <?= htmlspecialchars($details['vote_average']); ?>/10</p>
            <?php endif; ?>

            <?php if (isset($details['overview'])): ?>
                <h2>Overview</h2>
                <p><?= htmlspecialchars($details['overview']); ?></p>
            <?php endif; ?>

            <?php if (isset($details['credits']['cast'])): ?>
                <h2>Cast</h2>
                <div class="cast-list">
                    <?php foreach (array_slice($details['credits']['cast'], 0, 5) as $actor): ?>
                        <div class="cast-member">
                            <p><strong><?= htmlspecialchars($actor['name']); ?></strong></p>
                            <p>as <?= htmlspecialchars($actor['character']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="navigation">
        <a href="index.php">Back to Home</a>
    </div>

    <style>
        .movie-details {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .movie-details img {
            max-width: 300px;
            height: auto;
        }

        .movie-info {
            margin-top: 20px;
        }

        .cast-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 10px;
        }

        .cast-member {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .navigation {
            margin-top: 20px;
            text-align: center;
        }

        .navigation a {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .navigation a:hover {
            background-color: #0056b3;
        }
    </style>
</body>
</html> 