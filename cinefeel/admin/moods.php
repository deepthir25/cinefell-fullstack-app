<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

requireAdmin();

$pageTitle = 'Manage Moods';
$adminScript = true;

// Handle mood actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_mood'])) {
        $moodName = sanitize($_POST['mood_name']);
        $mappedGenre = sanitize($_POST['mapped_genre']);
        $emoji = sanitize($_POST['emoji']);
        $description = sanitize($_POST['description']);
        
        try {
            $stmt = $pdo->prepare("INSERT INTO moods (mood_name, mapped_genre, emoji, description) VALUES (?, ?, ?, ?)");
            $stmt->execute([$moodName, $mappedGenre, $emoji, $description]);
            setMessage('Mood added successfully!', 'success');
        } catch (PDOException $e) {
            setMessage('Failed to add mood: ' . $e->getMessage(), 'danger');
        }
    } 
    elseif (isset($_POST['update_mood'])) {
        $moodId = (int)$_POST['mood_id'];
        $moodName = sanitize($_POST['mood_name']);
        $mappedGenre = sanitize($_POST['mapped_genre']);
        $emoji = sanitize($_POST['emoji']);
        $description = sanitize($_POST['description']);
        
        try {
            $stmt = $pdo->prepare("UPDATE moods SET mood_name = ?, mapped_genre = ?, emoji = ?, description = ? WHERE mood_id = ?");
            $stmt->execute([$moodName, $mappedGenre, $emoji, $description, $moodId]);
            setMessage('Mood updated successfully!', 'success');
        } catch (PDOException $e) {
            setMessage('Failed to update mood: ' . $e->getMessage(), 'danger');
        }
    } 
    elseif (isset($_POST['delete_mood'])) {
        $moodId = (int)$_POST['mood_id'];
        
        try {
            // First check if any movies use this mood
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM movies WHERE mood_id = ?");
            $stmt->execute([$moodId]);
            $movieCount = $stmt->fetchColumn();
            
            if ($movieCount > 0) {
                setMessage('Cannot delete mood - it is associated with existing movies', 'danger');
            } else {
                $stmt = $pdo->prepare("DELETE FROM moods WHERE mood_id = ?");
                $stmt->execute([$moodId]);
                setMessage('Mood deleted successfully!', 'success');
            }
        } catch (PDOException $e) {
            setMessage('Failed to delete mood: ' . $e->getMessage(), 'danger');
        }
    }
    
    redirect('moods.php');
}

// Get all moods
$moods = $pdo->query("SELECT * FROM moods ORDER BY mood_name")->fetchAll();

// Get all genres for dropdown
$genres = $pdo->query("SELECT DISTINCT genre FROM movies ORDER BY genre")->fetchAll(PDO::FETCH_COLUMN);

$isAdminPage = true;
require_once '../includes/header.php';
?>

<div class="admin-container">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h2 class="h5 mb-0">Manage Moods</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMoodModal">
                <i class="fas fa-plus me-1"></i> Add New Mood
            </button>
        </div>
        <div class="card-body">
            <?php displayMessage(); ?>
            
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Mood</th>
                            <th>Emoji</th>
                            <th>Mapped Genre</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($moods as $mood): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($mood['mood_name']); ?></td>
                            <td class="fs-4"><?php echo $mood['emoji']; ?></td>
                            <td><?php echo htmlspecialchars($mood['mapped_genre']); ?></td>
                            <td><?php echo htmlspecialchars($mood['description']); ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary edit-mood" 
                                        data-mood-id="<?php echo $mood['mood_id']; ?>"
                                        data-mood-name="<?php echo htmlspecialchars($mood['mood_name']); ?>"
                                        data-mapped-genre="<?php echo htmlspecialchars($mood['mapped_genre']); ?>"
                                        data-emoji="<?php echo htmlspecialchars($mood['emoji']); ?>"
                                        data-description="<?php echo htmlspecialchars($mood['description']); ?>">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="mood_id" value="<?php echo $mood['mood_id']; ?>">
                                    <button type="submit" name="delete_mood" class="btn btn-sm btn-outline-danger" 
                                            onclick="return confirm('Are you sure you want to delete this mood?')">
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

<!-- Add Mood Modal -->
<div class="modal fade" id="addMoodModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Mood</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="mood_name" class="form-label">Mood Name</label>
                        <input type="text" class="form-control" id="mood_name" name="mood_name" required>
                    </div>
                    <div class="mb-3">
    <label for="emoji" class="form-label">Emoji</label>
    <select class="form-select emoji-select" id="emoji" name="emoji" required>
        <option value="">Select Emoji</option>

        <optgroup label="Happy Moods">
            <option value="😊">😊 Smiling</option>
            <option value="😂">😂 Laughing</option>
            <option value="🤣">🤣 ROFL</option>
            <option value="😄">😄 Grinning</option>
            <option value="😁">😁 Beaming</option>
            <option value="😃">😃 Smiley</option>
            <option value="😍">😍 Loving</option>
            <option value="🥰">🥰 Adoring</option>
            <option value="😎">😎 Cool</option>
            <option value="🤩">🤩 Starstruck</option>
        </optgroup>

        <optgroup label="Chill Moods">
            <option value="😌">😌 Relaxed</option>
            <option value="😴">😴 Sleepy</option>
            <option value="🧘">🧘 Meditating</option>
            <option value="🍃">🍃 Leaf</option>
            <option value="🌊">🌊 Ocean</option>
            <option value="🛀">🛀 Bath</option>
            <option value="🌅">🌅 Sunrise</option>
            <option value="🎧">🎧 Headphones</option>
        </optgroup>

        <optgroup label="Adventurous">
            <option value="🏔️">🏔️ Mountain</option>
            <option value="🚀">🚀 Rocket</option>
            <option value="🏄">🏄 Surfing</option>
            <option value="🧗">🧗 Climbing</option>
            <option value="🚴">🚴 Biking</option>
            <option value="🌋">🌋 Volcano</option>
            <option value="🏜️">🏜️ Desert</option>
        </optgroup>

        <optgroup label="Romantic">
            <option value="💕">💕 Hearts</option>
            <option value="💖">💖 Sparkling Heart</option>
            <option value="💘">💘 Heart Arrow</option>
            <option value="🌹">🌹 Rose</option>
            <option value="💑">💑 Couple</option>
            <option value="💞">💞 Revolving Hearts</option>
        </optgroup>

        <optgroup label="Sad / Thoughtful">
            <option value="😔">😔 Pensive</option>
            <option value="😢">😢 Crying</option>
            <option value="😭">😭 Sob</option>
            <option value="🤔">🤔 Thinking</option>
            <option value="😕">😕 Confused</option>
            <option value="🌧️">🌧️ Rain Cloud</option>
            <option value="☔">☔ Umbrella</option>
        </optgroup>

        <optgroup label="Energetic / Party">
            <option value="🥳">🥳 Celebrating</option>
            <option value="🎉">🎉 Party Popper</option>
            <option value="🎊">🎊 Confetti</option>
            <option value="🍾">🍾 Champagne</option>
            <option value="💃">💃 Dance</option>
            <option value="🕺">🕺 Disco Dance</option>
            <option value="🔥">🔥 Fire</option>
        </optgroup>

        <optgroup label="Dreamy / Magical">
            <option value="🌌">🌌 Galaxy</option>
            <option value="🌠">🌠 Shooting Star</option>
            <option value="🪐">🪐 Saturn</option>
            <option value="✨">✨ Sparkles</option>
            <option value="🧚">🧚 Fairy</option>
            <option value="🦄">🦄 Unicorn</option>
        </optgroup>

        <optgroup label="Dark / Mysterious">
            <option value="🌑">🌑 New Moon</option>
            <option value="🦇">🦇 Bat</option>
            <option value="🕸️">🕸️ Web</option>
            <option value="🕷️">🕷️ Spider</option>
            <option value="👻">👻 Ghost</option>
            <option value="😈">😈 Devilish</option>
        </optgroup>
    </select>
    <small class="text-muted">Select from popular mood emojis</small>
</div>

                    <div class="mb-3">
    <label for="mapped_genre" class="form-label">Mapped Genre</label>
    <input type="text" class="form-control" id="mapped_genre" name="mapped_genre" required>


                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_mood" class="btn btn-primary">Add Mood</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Mood Modal -->
<div class="modal fade" id="editMoodModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <input type="hidden" name="mood_id" id="edit_mood_id">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Mood</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_mood_name" class="form-label">Mood Name</label>
                        <input type="text" class="form-control" id="edit_mood_name" name="mood_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_emoji" class="form-label">Emoji</label>
                        <input type="text" class="form-control" id="edit_emoji" name="emoji" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_mapped_genre" class="form-label">Mapped Genre</label>
                        <input type="text" class="form-control" id="edit_genre" name="genre" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="update_mood" class="btn btn-primary">Update Mood</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle edit buttons
    document.querySelectorAll('.edit-mood').forEach(button => {
        button.addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('editMoodModal'));
            
            document.getElementById('edit_mood_id').value = this.dataset.moodId;
            document.getElementById('edit_mood_name').value = this.dataset.moodName;
            document.getElementById('edit_emoji').value = this.dataset.emoji;
            document.getElementById('edit_genre').value = this.dataset.mappedGenre;
            document.getElementById('edit_description').value = this.dataset.description;
            
            modal.show();
        });
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize emoji dropdowns
    const emojiSelects = document.querySelectorAll('.emoji-select');
    
    emojiSelects.forEach(select => {
        // For edit modal, set the current emoji as selected
        if (select.id === 'edit_emoji' && select.dataset.currentEmoji) {
            select.value = select.dataset.currentEmoji;
        }
        
        // Add emoji preview
        const preview = document.createElement('div');
        preview.className = 'emoji-preview fs-3 mb-2 text-center';
        preview.textContent = select.value || '😊';
        select.parentNode.insertBefore(preview, select);
        
        // Update preview when selection changes
        select.addEventListener('change', function() {
            preview.textContent = this.value;
        });
    });
    });
</script>
<?php require_once '../includes/footer.php'; ?>
<style>
.emoji-select {
    font-size: 1.2rem;
    height: 50px;
}
.emoji-preview {
    transition: all 0.3s ease;
}
.emoji-select option {
    font-size: 1.2rem;
    padding: 5px;
}
</style>