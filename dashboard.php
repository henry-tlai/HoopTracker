<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "cssql.seattleu.edu";
$username = "bd_aquibuyen";
$password = "kAd1OpcEv0nfF5WN";
$dbname = "bd_aquibuyen";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch user's media
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM Media WHERE UID = '$user_id' ORDER BY Media_ID DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - NBA Stats</title>
    <style>
        body {
            font-family: 'Crimson Text', serif;
            font-weight: 600;
            font-style: normal;
            padding: 20px;
            line-height: 1.5;
            background-color: #f5f5f5;
        }
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .welcome-section {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .media-section {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .nav-links {
            margin-bottom: 20px;
        }
        .nav-links a {
            color: #ea6b17;
            text-decoration: none;
            margin-right: 15px;
        }
        .nav-links a:hover {
            text-decoration: underline;
        }
        .media-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .media-item {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .media-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 4px;
        }
        .description {
            margin: 10px 0;
            color: #666;
        }
        .like-count {
            color: #666;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="nav-links">
            <a href="source.html">Home</a>
            <a href="upload_media.php">Upload Media</a>
            <a href="view_media.php">View All Media</a>
            <a href="logout.php">Logout</a>
        </div>
        
        <div class="welcome-section">
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        </div>
        
        <div class="media-section">
            <h3>Your Media Posts</h3>
            <div class="media-grid">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="media-item">
                        <img src="<?php echo htmlspecialchars($row['MediaURL']); ?>" alt="Media" class="media-image">
                        <div class="description"><?php echo htmlspecialchars($row['Description']); ?></div>
                        <div class="like-count"><?php echo $row['Like_Counter']; ?> likes</div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</body>
</html> 