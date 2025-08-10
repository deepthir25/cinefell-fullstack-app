<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$pageTitle = 'My Favorite Movies';

// Get user's favorite movies
$favorites = [];
try {
    $stmt = $pdo->prepare("
        SELECT m.*, md.mood_name, md.emoji 
        FROM favorites f
        JOIN movies m ON f.movie_id = m.movie_id
        JOIN moods md ON m.mood_id = md.mood_id
        WHERE f.user_id = ?
        ORDER BY f.added_at DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $favorites = $stmt->fetchAll();
} catch (PDOException $e) {
    setMessage('Failed to load favorites: ' . $e->getMessage(), 'danger');
}

require_once 'includes/header.php';
?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>My Favorite Movies</h1>
        <a href="mood-selection.php" class="btn btn-outline-primary">Find More Movies</a>
    </div>

    <?php displayMessage(); ?>

    <?php if (empty($favorites)): ?>
        <div class="text-center py-5">
            <div class="emoji-display fs-1 mb-3">😕</div>
            <h3>No favorites yet</h3>
            <p class="text-muted">Save movies you like by clicking the heart icon on any movie</p>
            <a href="mood-selection.php" class="btn btn-primary mt-3">Get Recommendations</a>
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
            <?php foreach ($favorites as $movie): ?>
                <div class="col">
                    <div class="card h-100 movie-card">
                        <img src="<?php echo $movie['poster_url'] ?: 'assets/images/default-poster.jpg'; ?>" 
                             class="card-img-top" 
                             alt="<?php echo htmlspecialchars($movie['title']); ?>"
                             style="height: 400px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($movie['title']); ?></h5>
                            <p class="card-text text-muted">
                                <?php echo htmlspecialchars($movie['genre']); ?> • 
                                <?php echo htmlspecialchars($movie['release_year']); ?>
                            </p>
                            <p class="card-text"><?php echo htmlspecialchars(substr($movie['description'], 0, 100) . '...'); ?></p>
                        </div>
                        <div class="card-footer bg-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="<?php echo htmlspecialchars($movie['trailer_link']); ?>" 
                                   target="_blank" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-play"></i> Trailer
                                </a>
                                <button class="btn btn-sm btn-danger favorite-toggle" 
                                        data-movie-id="<?php echo $movie['movie_id']; ?>">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle favorite removal
    document.querySelectorAll('.favorite-toggle').forEach(button => {
        button.addEventListener('click', function() {
            const movieId = this.dataset.movieId;
            const card = this.closest('.col');
            
            fetch('toggle-favorite.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `movie_id=${movieId}&action=remove`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the card from view
                    card.remove();
                    
                    // Show message if no favorites left
                    if (document.querySelectorAll('.movie-card').length === 0) {
                        location.reload(); // Reload to show empty state
                    }
                }
            });
        });
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>