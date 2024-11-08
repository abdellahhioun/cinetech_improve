<div class="row">
    <div class="col-md-4">
        <?php if ($movie['poster_path']): ?>
            <img src="https://image.tmdb.org/t/p/w500<?= htmlspecialchars($movie['poster_path']) ?>" 
                 class="img-fluid rounded" 
                 alt="<?= htmlspecialchars($movie['title']) ?>">
        <?php endif; ?>
    </div>
    <div class="col-md-8">
        <h1 class="mb-3"><?= htmlspecialchars($movie['title']) ?></h1>
        
        <?php if (!empty($movie['tagline'])): ?>
            <h5 class="text-muted mb-3"><?= htmlspecialchars($movie['tagline']) ?></h5>
        <?php endif; ?>

        <div class="mb-3">
            <strong>Release Date:</strong> <?= date('F j, Y', strtotime($movie['release_date'])) ?>
        </div>

        <div class="mb-3">
            <strong>Rating:</strong> <?= number_format($movie['vote_average'], 1) ?>/10 
            (<?= number_format($movie['vote_count']) ?> votes)
        </div>

        <?php if (!empty($movie['genres'])): ?>
            <div class="mb-3">
                <strong>Genres:</strong>
                <?php foreach ($movie['genres'] as $genre): ?>
                    <span class="badge bg-secondary me-1"><?= htmlspecialchars($genre['name']) ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($movie['overview'])): ?>
            <div class="mb-4">
                <h4>Overview</h4>
                <p><?= htmlspecialchars($movie['overview']) ?></p>
            </div>
        <?php endif; ?>

        <?php if (!empty($movie['credits']['cast'])): ?>
            <div class="mb-4">
                <h4>Cast</h4>
                <div class="row row-cols-1 row-cols-md-4 g-3">
                    <?php foreach (array_slice($movie['credits']['cast'], 0, 8) as $cast): ?>
                        <div class="col">
                            <div class="card h-100">
                                <?php if ($cast['profile_path']): ?>
                                    <img src="https://image.tmdb.org/t/p/w200<?= htmlspecialchars($cast['profile_path']) ?>" 
                                         class="card-img-top" 
                                         alt="<?= htmlspecialchars($cast['name']) ?>">
                                <?php endif; ?>
                                <div class="card-body">
                                    <h6 class="card-title mb-1"><?= htmlspecialchars($cast['name']) ?></h6>
                                    <p class="card-text"><small class="text-muted"><?= htmlspecialchars($cast['character']) ?></small></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Add Favorite Button -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="mb-4">
                <button class="btn <?= $isFavorite ? 'btn-danger' : 'btn-primary' ?>" 
                        id="favoriteBtn" 
                        data-movie-id="<?= $movie['id'] ?>">
                    <i class="fas fa-heart"></i> 
                    <?= $isFavorite ? 'Remove from Favorites' : 'Add to Favorites' ?>
                </button>
            </div>
        <?php endif; ?>

        <!-- Reviews Section -->
        <div class="mt-5">
            <h3>Reviews</h3>
            <?php if (!empty($reviews['results'])): ?>
                <?php foreach ($reviews['results'] as $review): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h5 class="card-title">
                                    <?= htmlspecialchars($review['author']) ?>
                                </h5>
                                <small class="text-muted">
                                    <?= date('F j, Y', strtotime($review['created_at'])) ?>
                                </small>
                            </div>
                            <?php if (!empty($review['author_details']['rating'])): ?>
                                <div class="mb-2">
                                    Rating: <?= $review['author_details']['rating'] ?>/10
                                </div>
                            <?php endif; ?>
                            <p class="card-text">
                                <?php 
                                $content = $review['content'];
                                if (strlen($content) > 500) {
                                    echo htmlspecialchars(substr($content, 0, 500)) . '... ';
                                    echo '<a href="' . htmlspecialchars($review['url']) . '" target="_blank">Read More</a>';
                                } else {
                                    echo htmlspecialchars($content);
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- Reviews Pagination -->
                <?php if ($reviews['total_pages'] > 1): ?>
                    <nav aria-label="Reviews pagination">
                        <ul class="pagination justify-content-center">
                            <?php for ($i = 1; $i <= min($reviews['total_pages'], 5); $i++): ?>
                                <li class="page-item <?= $i === $currentReviewPage ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>#reviews">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php else: ?>
                <div class="alert alert-info">
                    No reviews available for this movie.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Add this JavaScript at the bottom of the file -->
<script>
document.getElementById('favoriteBtn')?.addEventListener('click', async function() {
    const movieId = this.dataset.movieId;
    try {
        const response = await fetch('/api/favorite', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ movieId })
        });
        const data = await response.json();
        
        if (data.success) {
            if (data.action === 'added') {
                this.classList.remove('btn-primary');
                this.classList.add('btn-danger');
                this.innerHTML = '<i class="fas fa-heart"></i> Remove from Favorites';
            } else {
                this.classList.remove('btn-danger');
                this.classList.add('btn-primary');
                this.innerHTML = '<i class="fas fa-heart"></i> Add to Favorites';
            }
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while updating favorites');
    }
});
</script> 