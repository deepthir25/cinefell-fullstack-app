<?php
require_once 'includes/config.php';
require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'Discover Movies for Your Mood';

// Get featured movies (random selection)
$featuredMovies = [];
try {
    $stmt = $pdo->query("SELECT m.*, md.mood_name, md.emoji 
                         FROM movies m 
                         JOIN moods md ON m.mood_id = md.mood_id 
                         ORDER BY RAND() LIMIT 8");
    $featuredMovies = $stmt->fetchAll();
} catch (PDOException $e) {
    
}


$moods = [];
try {
    $stmt = $pdo->query("SELECT * FROM moods ORDER BY mood_name");
    $moods = $stmt->fetchAll();
} catch (PDOException $e) {
    // Handle error or leave moods empty
}

require_once 'includes/header.php';
?>

<!-- Hero Section with Gradient Background -->
<section class="hero-section bg-gradient-primary text-white py-5 mb-5">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-6">
                
                <h1 class="display-4 fw-bold mb-4">Find the Perfect Movie for Your Mood</h1>
                <p class="lead mb-4">CineFeel analyzes your current emotions to recommend movies that perfectly match how you're feeling right now.</p>
                <div class="d-flex gap-3">
                    <?php if (isLoggedIn()): ?>
                        <a href="mood-selection.php" class="btn btn-light btn-lg px-4 py-2 rounded-pill">
                            <i class="fas fa-smile me-2"></i> Get Recommendations
                        </a>
                        <a href="favorites.php" class="btn btn-outline-light btn-lg px-4 py-2 rounded-pill">
                            <i class="fas fa-heart me-2"></i> My Favorites
                        </a>
                    <?php else: ?>
                        <a href="register.php" class="btn btn-light btn-lg px-4 py-2 rounded-pill">
                            <i class="fas fa-user-plus me-2"></i> Get Started
                        </a>
                        <a href="login.php" class="btn btn-outline-light btn-lg px-4 py-2 rounded-pill">
                            <i class="fas fa-sign-in-alt me-2"></i> Login
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
                <div class="hero-image-container position-relative">
                    
                    <div class="hero-badge bg-danger text-white p-2 rounded-pill position-absolute top-0 start-0 translate-middle">
                        <i class="fas fa-film me-1"></i> 10,000+ Movies
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container">
    <?php displayMessage(); ?>

    <!-- How It Works Section -->
    <section class="mb-5">
        <h2 class="text-center mb-5">How CineFeel Works</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm hover-effect">
                    <div class="card-body text-center p-4">
                        <div class="icon-circle bg-primary text-white rounded-circle p-3 mb-3 mx-auto">
                            <i class="fas fa-user-plus fs-4"></i>
                        </div>
                        <h4 class="mb-3">1. Create Account</h4>
                        <p class="text-muted mb-0">Sign up to save your favorite movies and get personalized recommendations tailored just for you.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm hover-effect">
                    <div class="card-body text-center p-4">
                        <div class="icon-circle bg-primary text-white rounded-circle p-3 mb-3 mx-auto">
                            <i class="fas fa-smile-beam fs-4"></i>
                        </div>
                        <h4 class="mb-3">2. Select Your Mood</h4>
                        <p class="text-muted mb-0">Tell us how you're feeling with our simple mood selector featuring fun emojis.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm hover-effect">
                    <div class="card-body text-center p-4">
                        <div class="icon-circle bg-primary text-white rounded-circle p-3 mb-3 mx-auto">
                            <i class="fas fa-film fs-4"></i>
                        </div>
                        <h4 class="mb-3">3. Enjoy Movies</h4>
                        <p class="text-muted mb-0">Get curated movie recommendations perfectly tailored to your current mood.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    

    <!-- Moods Section -->
    <?php if (!empty($moods)): ?>
    <section class="mb-5">
        <h2 class="text-center mb-5">Moods We Recognize</h2>
        <div class="row g-3">
            <?php foreach ($moods as $mood): ?>
            <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                <div class="card text-center p-3 h-100 border-0 shadow-sm hover-effect">
                    <span class="emoji-display fs-1 mb-2"><?php echo $mood['emoji']; ?></span>
                    <h5 class="mb-1"><?php echo htmlspecialchars($mood['mood_name']); ?></h5>
                    <small class="text-muted"><?php echo htmlspecialchars($mood['mapped_genre']); ?></small>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Testimonials Section -->
    <section class="mb-5 py-5 bg-light rounded-3">
        <div class="container">
            <h2 class="text-center mb-5">What Our Users Say</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <img src="https://randomuser.me/api/portraits/women/32.jpg" class="rounded-circle me-3" width="50" height="50" alt="User">
                                <div>
                                    <h5 class="mb-0">Sarah J.</h5>
                                    <div class="text-warning small">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mb-0">"CineFeel always knows exactly what I'm in the mood for. Found so many hidden gems!"</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <img src="https://randomuser.me/api/portraits/men/45.jpg" class="rounded-circle me-3" width="50" height="50" alt="User">
                                <div>
                                    <h5 class="mb-0">Michael T.</h5>
                                    <div class="text-warning small">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mb-0">"No more endless scrolling. I get perfect recommendations in seconds."</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <img src="https://randomuser.me/api/portraits/women/68.jpg" class="rounded-circle me-3" width="50" height="50" alt="User">
                                <div>
                                    <h5 class="mb-0">Emily R.</h5>
                                    <div class="text-warning small">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mb-0">"The mood selection is so fun and accurate. Never watched so many great movies!"</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="mb-5 text-center py-5 bg-dark text-white rounded-3">
        <h2 class="mb-4">Ready to Find Your Perfect Movie Match?</h2>
        <p class="lead mb-4">Join thousands of happy users discovering movies they love.</p>
        <a href="<?php echo isLoggedIn() ? 'mood-selection.php' : 'register.php'; ?>" class="btn btn-primary btn-lg px-5 py-3 rounded-pill">
            <i class="fas fa-play me-2"></i> Get Started Now
        </a>
    </section>
</div>

<?php require_once 'includes/footer.php'; ?>

<style>
/* Custom Styles */
/*.hero-section {
    background: linear-gradient(135deg, #06023aff  0%,  #590b6988 100%);
}*/

.hover-effect {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hover-effect:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15) !important;
}

.icon-circle {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.text-truncate-2 {
    display: -webkit-box;
    -webkit-line-clamp: var(--lines, 2);
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.hero-badge {
    animation: pulse 2s infinite;
}





@keyframes pulse {
    0% { transform: translate(-50%, -50%) scale(1); }
    50% { transform: translate(-50%, -50%) scale(1.05); }
    100% { transform: translate(-50%, -50%) scale(1); }
}
</style>