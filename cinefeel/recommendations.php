<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

if (!isset($_POST['mood_id'])) {
    redirect('mood-selection.php');
}

$moodId = (int)$_POST['mood_id'];

// Get mood info
$stmt = $pdo->prepare("SELECT * FROM moods WHERE mood_id = ?");
$stmt->execute([$moodId]);
$mood = $stmt->fetch();

if (!$mood) {
    redirect('mood-selection.php');
}

$pageTitle = "Movies for {$mood['mood_name']} Mood";

// Get movies for this mood
$stmt = $pdo->prepare("SELECT * FROM movies WHERE mood_id = ? ORDER BY title");
$stmt->execute([$moodId]);
$movies = $stmt->fetchAll();

// Check if movies are in favorites
$favorites = [];
if ($movies) {
    $movieIds = array_column($movies, 'movie_id');
    $placeholders = implode(',', array_fill(0, count($movieIds), '?'));
    
    $stmt = $pdo->prepare("SELECT movie_id FROM favorites WHERE user_id = ? AND movie_id IN ($placeholders)");
    $stmt->execute(array_merge([$_SESSION['user_id']], $movieIds));
    $favorites = $stmt->fetchAll(PDO::FETCH_COLUMN);
}

require_once 'includes/header.php';
?>

<div class="mb-4">
    <h1>Movies for your <span class="text-primary"><?php echo $mood['mood_name']; ?></span> mood</h1>
    <p class="lead"><?php echo $mood['description']; ?></p>
    <a href="mood-selection.php" class="btn btn-outline-secondary">Choose a different mood</a>
</div>

<?php if (empty($movies)): ?>
    <div class="alert alert-info">
        No movies found for this mood. Please check back later or try a different mood.
    </div>
<?php else: ?>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
        <?php foreach ($movies as $movie): ?>
            <div class="col">
                <div class="card h-100 movie-card">
                    <img src="<?php echo $movie['poster_url'] ?: 'assets/images/default-poster.jpg'; ?>" 
                         class="card-img-top" 
                         alt="<?php echo htmlspecialchars($movie['title']); ?>">
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
                            <button class="btn btn-sm favorite-toggle <?php echo in_array($movie['movie_id'], $favorites) ? 'btn-danger' : 'btn-outline-danger'; ?>" 
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle favorite toggles
    document.querySelectorAll('.favorite-toggle').forEach(button => {
        button.addEventListener('click', function() {
            const movieId = this.dataset.movieId;
            const isFavorite = this.classList.contains('btn-danger');
            
            fetch('toggle-favorite.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `movie_id=${movieId}&action=${isFavorite ? 'remove' : 'add'}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.classList.toggle('btn-danger');
                    this.classList.toggle('btn-outline-danger');
                }
            });
        });
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>