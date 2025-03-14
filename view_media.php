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

// Handle like action
if (isset($_POST['like']) && isset($_POST['media_id'])) {
    $media_id = mysqli_real_escape_string($conn, $_POST['media_id']);
    $sql = "UPDATE Media SET Like_Counter = Like_Counter + 1 WHERE Media_ID = '$media_id'";
    mysqli_query($conn, $sql);
    header("Location: view_media.php");
    exit();
}

// Fetch media with user information
$sql = "SELECT m.*, u.Username, u.ProfileURL 
        FROM Media m 
        JOIN User u ON m.UID = u.UID 
        ORDER BY m.Media_ID DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Media - NBA Stats</title>
    <style>
        body {
            font-family: 'Crimson Text', serif;
            font-weight: 600;
            font-style: normal;
            padding: 20px;
            line-height: 1.5;
            background-color: #f5f5f5;
        }
        .media-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .media-item {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 15px;
        }
        .media-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .username {
            font-weight: bold;
            color: #333;
        }
        .media-content {
            margin: 10px 0;
        }
        .media-image {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
        }
        .description {
            margin: 10px 0;
            color: #666;
        }
        .like-button {
            background-color: #ea6b17;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .like-button:hover {
            background-color: #d55f14;
        }
        .like-count {
            margin-left: 10px;
            color: #666;
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
    </style>
</head>
<body>
    <div class="media-container">
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="upload_media.php">Upload Media</a>
            <a href="logout.php">Logout</a>
        </div>
        <h2>Media Gallery</h2>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="media-item">
                <div class="media-header">
                    <img src="<?php echo htmlspecialchars($row['ProfileURL']); ?>" alt="Profile" class="profile-pic">
                    <span class="username"><?php echo htmlspecialchars($row['Username']); ?></span>
                </div>
                <div class="media-content">
                    <img src="<?php echo htmlspecialchars($row['MediaURL']); ?>" alt="Media" class="media-image">
                    <div class="description"><?php echo htmlspecialchars($row['Description']); ?></div>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="media_id" value="<?php echo $row['Media_ID']; ?>">
                        <button type="submit" name="like" class="like-button">Like</button>
                        <span class="like-count"><?php echo $row['Like_Counter']; ?> likes</span>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html> 