<h1 class="mb-4">Search Results<?= $query ? ' for "' . htmlspecialchars($query) . '"' : '' ?></h1>

<?php if (empty($movies['results'])): ?>
    <div class="alert alert-info">
        <?= $query ? 'No movies found matching your search.' : 'Enter a search term to find movies.' ?>
    </div>
<?php else: ?>
    <div class="row row-cols-1 row-cols-md-4 g-4">
        <?php foreach ($movies['results'] as $movie): ?>
            <div class="col">
                <div class="card h-100">
                    <a href="/movie/<?= $movie['id'] ?>" class="text-decoration-none">
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
                            <p class="card-text">
                                <small class="text-muted">
                                    Rating: <?= number_format($movie['vote_average'], 1) ?>/10
                                </small>
                            </p>
                        </div>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if ($movies['total_pages'] > 1): ?>
    <nav aria-label="Page navigation" class="mt-4">
        <ul class="pagination justify-content-center">
            <?php if ($currentPage > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="/search?query=<?= urlencode($query) ?>&page=<?= $currentPage - 1 ?>">Previous</a>
                </li>
            <?php endif; ?>
            
            <?php for ($i = max(1, $currentPage - 2); $i <= min($movies['total_pages'], $currentPage + 2); $i++): ?>
                <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                    <a class="page-link" href="/search?query=<?= urlencode($query) ?>&page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
            
            <?php if ($currentPage < $movies['total_pages']): ?>
                <li class="page-item">
                    <a class="page-link" href="/search?query=<?= urlencode($query) ?>&page=<?= $currentPage + 1 ?>">Next</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
    <?php endif; ?>
<?php endif; ?> 