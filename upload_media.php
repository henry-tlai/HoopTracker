<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Generate CSRF token if not exists
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
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

$error = '';
$success = '';

// Fetch teams for dropdown using prepared statement
$teams = [];
if ($stmt = $conn->prepare("SELECT Team_ID, Team_Name FROM Team ORDER BY Team_Name")) {
    $stmt->execute();
    $result = $stmt->get_result();
    while ($team = $result->fetch_assoc()) {
        $teams[] = $team;
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Invalid request";
    } else {
        // Validate inputs
        $description = trim($_POST['description']);
        $media_url = trim($_POST['media_url']);
        $team_id = trim($_POST['team_id']);
        
        // Input validation
        if (empty($description) || empty($media_url) || empty($team_id)) {
            $error = "All fields are required";
        } else if (strlen($description) > 1000) { // Adjust max length as needed
            $error = "Description is too long";
        } else if (!filter_var($media_url, FILTER_VALIDATE_URL)) {
            $error = "Invalid media URL";
        } else if (!preg_match('/\.(jpg|jpeg|png|svg)$/i', $media_url)) {
            $error = "Invalid image format. Only JPG, PNG, and SVG are allowed";
        } else {
            // Verify team_id exists
            $team_check = $conn->prepare("SELECT Team_ID FROM Team WHERE Team_ID = ?");
            $team_check->bind_param("s", $team_id);
            $team_check->execute();
            if (!$team_check->get_result()->fetch_assoc()) {
                $error = "Invalid team selected";
                $team_check->close();
            } else {
                $team_check->close();
                
                // Generate a new Media_ID (9 digits)
                $media_id = str_pad(mt_rand(1, 999999999), 9, '0', STR_PAD_LEFT);
                
                // Use prepared statement for insert
                $stmt = $conn->prepare("INSERT INTO Media (Media_ID, UID, Team_ID, Description, MediaURL, Like_Counter) VALUES (?, ?, ?, ?, ?, 0)");
                $stmt->bind_param("sssss", $media_id, $_SESSION['user_id'], $team_id, $description, $media_url);
                
                if ($stmt->execute()) {
                    $success = "Media uploaded successfully!";
                } else {
                    $error = "Error uploading media: " . $stmt->error;
                }
                $stmt->close();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Media - NBA Stats</title>
    <style>
        body {
            font-family: 'Crimson Text', serif;
            font-weight: 600;
            font-style: normal;
            padding: 20px;
            line-height: 1.5;
            background-color: #f5f5f5;
        }
        .upload-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        input[type="text"],
        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            height: 100px;
            resize: vertical;
        }
        button {
            background-color: #ea6b17;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #d55f14;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
        .success {
            color: green;
            margin-bottom: 10px;
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
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            margin-bottom: 10px;
            font-family: 'Crimson Text', serif;
        }
    </style>
</head>
<body>
    <div class="upload-container">
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="view_media.php">View Media</a>
            <a href="logout.php">Logout</a>
        </div>
        <h2>Upload Media</h2>
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <div class="form-group">
                <label for="team_id">Select Team:</label>
                <select id="team_id" name="team_id" required>
                    <option value="">Select a team</option>
                    <?php foreach ($teams as $team): ?>
                        <option value="<?php echo htmlspecialchars($team['Team_ID']); ?>">
                            <?php echo htmlspecialchars($team['Team_Name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" required maxlength="1000"></textarea>
            </div>
            <div class="form-group">
                <label for="media_url">Media URL (SVG, PNG, JPG):</label>
                <input type="url" id="media_url" name="media_url" required pattern="https?://.+\.(jpg|jpeg|png|svg)$">
                <small style="color: #666;">Enter a valid URL to an image file (JPG, PNG, or SVG)</small>
            </div>
            <button type="submit">Upload Media</button>
        </form>
    </div>
</body>
</html> 