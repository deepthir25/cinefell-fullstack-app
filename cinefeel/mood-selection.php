<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$pageTitle = 'Select Your Mood';

// Get all moods from database
$stmt = $pdo->query("SELECT * FROM moods");
$moods = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once 'includes/header.php';
?>

<div class="text-center mb-5">
    <h1>How are you feeling today?</h1>
    <p class="lead">Select your current mood to get personalized movie recommendations</p>
</div>

<div class="row justify-content-center">
    <?php foreach ($moods as $mood): ?>
        <div class="col-6 col-md-4 col-lg-3 mb-4">
            <form action="recommendations.php" method="POST" class="mood-form">
                <input type="hidden" name="mood_id" value="<?php echo $mood['mood_id']; ?>">
                <button type="submit" class="btn btn-outline-primary mood-btn w-100 h-100 py-4">
                    <span class="emoji-display" style="font-size: 2.5rem;"><?php echo $mood['emoji']; ?></span>
                    <h3 class="mt-2"><?php echo $mood['mood_name']; ?></h3>
                    <p class="text-muted"><?php echo $mood['description']; ?></p>
                </button>
            </form>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once 'includes/footer.php'; ?>