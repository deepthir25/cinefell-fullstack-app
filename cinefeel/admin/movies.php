<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

requireAdmin();

$pageTitle = 'Manage Movies';
$adminScript = true;

// Handle movie actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_movie'])) {
        $title = sanitize($_POST['title']);
        $genre = sanitize($_POST['genre']);
        $moodId = (int)$_POST['mood_id'];
        $description = sanitize($_POST['description']);
        $posterUrl = sanitize($_POST['poster_url']);
        $trailerLink = sanitize($_POST['trailer_link']);
        $releaseYear = (int)$_POST['release_year'];
        $duration = sanitize($_POST['duration']);
        
        try {
            $stmt = $pdo->prepare("INSERT INTO movies (title, genre, mood_id, description, poster_url, trailer_link, release_year, duration) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $genre, $moodId, $description, $posterUrl, $trailerLink, $releaseYear, $duration]);
            setMessage('Movie added successfully!', 'success');
        } catch (PDOException $e) {
            setMessage('Failed to add movie: ' . $e->getMessage(), 'danger');
        }
    } 
    elseif (isset($_POST['update_movie'])) {
        $movieId = (int)$_POST['movie_id'];
        $title = sanitize($_POST['title']);
        $genre = sanitize($_POST['genre']);
        $moodId = (int)$_POST['mood_id'];
        $description = sanitize($_POST['description']);
        $posterUrl = sanitize($_POST['poster_url']);
        $trailerLink = sanitize($_POST['trailer_link']);
        $releaseYear = (int)$_POST['release_year'];
        $duration = sanitize($_POST['duration']);
        
        try {
            $stmt = $pdo->prepare("UPDATE movies SET title = ?, genre = ?, mood_id = ?, description = ?, poster_url = ?, trailer_link = ?, release_year = ?, duration = ? WHERE movie_id = ?");
            $stmt->execute([$title, $genre, $moodId, $description, $posterUrl, $trailerLink, $releaseYear, $duration, $movieId]);
            setMessage('Movie updated successfully!', 'success');
        } catch (PDOException $e) {
            setMessage('Failed to update movie: ' . $e->getMessage(), 'danger');
        }
    } 
    elseif (isset($_POST['delete_movie'])) {
        $movieId = (int)$_POST['movie_id'];
        
        try {
            $stmt = $pdo->prepare("DELETE FROM movies WHERE movie_id = ?");
            $stmt->execute([$movieId]);
            setMessage('Movie deleted successfully!', 'success');
        } catch (PDOException $e) {
            setMessage('Failed to delete movie: ' . $e->getMessage(), 'danger');
        }
    }
    
    redirect('movies.php');
}

// Get all movies with mood info
$movies = $pdo->query("
    SELECT m.*, md.mood_name 
    FROM movies m
    LEFT JOIN moods md ON m.mood_id = md.mood_id
    ORDER BY m.title
")->fetchAll();

// Get all moods for dropdown
$moods = $pdo->query("SELECT * FROM moods ORDER BY mood_name")->fetchAll();

// Get all genres for dropdown
$genres = $pdo->query("SELECT DISTINCT genre FROM movies ORDER BY genre")->fetchAll(PDO::FETCH_COLUMN);

$isAdminPage = true;
require_once '../includes/header.php';
?>

<div class="admin-container">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h2 class="h5 mb-0">Manage Movies</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMovieModal">
                <i class="fas fa-plus me-1"></i> Add New Movie
            </button>
        </div>
        <div class="card-body">
            <?php displayMessage(); ?>
            
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Poster</th>
                            <th>Title</th>
                            <th>Genre</th>
                            <th>Mood</th>
                            <th>Year</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($movies as $movie): ?>
                        <tr>
                            <td>
                                <img src="<?php echo $movie['poster_url'] ?: '../assets/images/default-poster.jpg'; ?>" 
                                     alt="<?php echo htmlspecialchars($movie['title']); ?>" 
                                     style="width: 50px; height: 75px; object-fit: cover;">
                            </td>
                            <td><?php echo htmlspecialchars($movie['title']); ?></td>
                            <td><?php echo htmlspecialchars($movie['genre']); ?></td>
                            <td><?php echo $movie['mood_name'] ?? 'None'; ?></td>
                            <td><?php echo htmlspecialchars($movie['release_year']); ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary edit-movie" 
                                        data-movie-id="<?php echo $movie['movie_id']; ?>"
                                        data-title="<?php echo htmlspecialchars($movie['title']); ?>"
                                        data-genre="<?php echo htmlspecialchars($movie['genre']); ?>"
                                        data-mood-id="<?php echo $movie['mood_id']; ?>"
                                        data-description="<?php echo htmlspecialchars($movie['description']); ?>"
                                        data-poster-url="<?php echo htmlspecialchars($movie['poster_url']); ?>"
                                        data-trailer-link="<?php echo htmlspecialchars($movie['trailer_link']); ?>"
                                        data-release-year="<?php echo htmlspecialchars($movie['release_year']); ?>"
                                        data-duration="<?php echo htmlspecialchars($movie['duration']); ?>">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="movie_id" value="<?php echo $movie['movie_id']; ?>">
                                    <button type="submit" name="delete_movie" class="btn btn-sm btn-outline-danger" 
                                            onclick="return confirm('Are you sure you want to delete this movie?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Movie Modal -->
<div class="modal fade" id="addMovieModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- In admin/movies.php, admin/moods.php, admin/users.php -->
<form method="POST" action="<?php echo BASE_URL; ?>/admin/<?php echo basename($_SERVER['PHP_SELF']); ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Movie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label for="genre" class="form-label">Genre</label>
                                <input type="text" class="form-control" id="genre" name="genre" list="genres" required>
                                <datalist id="genres">
                                    <?php foreach ($genres as $genre): ?>
                                        <option value="<?php echo htmlspecialchars($genre); ?>">
                                    <?php endforeach; ?>
                                </datalist>
                            </div>
                            <div class="mb-3">
                                <label for="mood_id" class="form-label">Mood</label>
                                <select class="form-select" id="mood_id" name="mood_id">
                                    <option value="">Select Mood (Optional)</option>
                                    <?php foreach ($moods as $mood): ?>
                                        <option value="<?php echo $mood['mood_id']; ?>">
                                            <?php echo htmlspecialchars($mood['mood_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="release_year" class="form-label">Release Year</label>
                                <input type="number" class="form-control" id="release_year" name="release_year" 
                                       min="1900" max="<?php echo date('Y') + 5; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="duration" class="form-label">Duration</label>
                                <input type="text" class="form-control" id="duration" name="duration" 
                                       placeholder="e.g., 2h 15m">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="poster_url" class="form-label">Poster URL</label>
                                <input type="url" class="form-control" id="poster_url" name="poster_url">
                                <div class="form-text">Leave blank to use default poster</div>
                            </div>
                            <div class="mb-3">
                                <label for="trailer_link" class="form-label">Trailer Link</label>
                                <input type="url" class="form-control" id="trailer_link" name="trailer_link">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_movie" class="btn btn-primary">Add Movie</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Movie Modal -->
<div class="modal fade" id="editMovieModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST">
                <input type="hidden" name="movie_id" id="edit_movie_id">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Movie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="edit_title" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_genre" class="form-label">Genre</label>
                                <input type="text" class="form-control" id="edit_genre" name="genre" list="genres" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_mood_id" class="form-label">Mood</label>
                                <select class="form-select" id="edit_mood_id" name="mood_id">
                                    <option value="">Select Mood (Optional)</option>
                                    <?php foreach ($moods as $mood): ?>
                                        <option value="<?php echo $mood['mood_id']; ?>">
                                            <?php echo htmlspecialchars($mood['mood_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="edit_release_year" class="form-label">Release Year</label>
                                <input type="number" class="form-control" id="edit_release_year" name="release_year" 
                                       min="1900" max="<?php echo date('Y') + 5; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_duration" class="form-label">Duration</label>
                                <input type="text" class="form-control" id="edit_duration" name="duration">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_poster_url" class="form-label">Poster URL</label>
                                <input type="url" class="form-control" id="edit_poster_url" name="poster_url">
                            </div>
                            <div class="mb-3">
                                <label for="edit_trailer_link" class="form-label">Trailer Link</label>
                                <input type="url" class="form-control" id="edit_trailer_link" name="trailer_link">
                            </div>
                            <div class="mb-3">
                                <label for="edit_description" class="form-label">Description</label>
                                <textarea class="form-control" id="edit_description" name="description" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="update_movie" class="btn btn-primary">Update Movie</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle edit buttons
    document.querySelectorAll('.edit-movie').forEach(button => {
        button.addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('editMovieModal'));
            
            document.getElementById('edit_movie_id').value = this.dataset.movieId;
            document.getElementById('edit_title').value = this.dataset.title;
            document.getElementById('edit_genre').value = this.dataset.genre;
            document.getElementById('edit_mood_id').value = this.dataset.moodId || '';
            document.getElementById('edit_description').value = this.dataset.description;
            document.getElementById('edit_poster_url').value = this.dataset.posterUrl;
            document.getElementById('edit_trailer_link').value = this.dataset.trailerLink;
            document.getElementById('edit_release_year').value = this.dataset.releaseYear;
            document.getElementById('edit_duration').value = this.dataset.duration;
            
            modal.show();
        });
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>