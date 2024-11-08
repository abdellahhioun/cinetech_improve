<h1 class="mb-4">My Favorite Movies</h1>

<?php if (empty($favorites)): ?>
    <div class="alert alert-info">
        You haven't added any movies to your favorites yet.
    </div>
<?php else: ?>
    <div class="row row-cols-1 row-cols-md-4 g-4">
        <?php foreach ($favorites as $movie): ?>
            <div class="col">
                <div class="card h-100">
                    <a href="/movie/<?= $movie['tmdb_id'] ?>" class="text-decoration-none">
                        <?php if ($movie['poster_path']): ?>
                            <img src="https://image.tmdb.org/t/p/w500<?= htmlspecialchars($movie['poster_path']) ?>" 
                                 class="card-img-top movie-poster" 
                                 alt="<?= htmlspecialchars($movie['title']) ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title text-dark"><?= htmlspecialchars($movie['title']) ?></h5>
                            <p class="card-text text-secondary">
                                <?= strlen($movie['overview']) > 150 ? 
                                    htmlspecialchars(substr($movie['overview'], 0, 150)) . '...' : 
                                    htmlspecialchars($movie['overview']) ?>
                            </p>
                        </div>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?> 