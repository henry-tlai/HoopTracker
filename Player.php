<?php
$servername = "cssql.seattleu.edu";
$username = "bd_aquibuyen";
$password = "kAd1OpcEv0nfF5WN";
$dbname = "bd_aquibuyen";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Player Information</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Text:ital,wght@0,400;0,600;0,700;1,400;1,600;1,700&family=Paytone+One&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/21c13be224.js" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: "Crimson Text", serif;
            font-weight: 600;
            font-style: normal;
            padding: 20px;
            line-height: 1.5;
        }
        i {
            margin-right: 10px;
            font-size: 30px;
            color: #ea6b17;
        }
        nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        nav ul {
            display: flex;
            align-items: center;
            list-style: none;
            padding: 0;
            margin: 0;
            margin-left: auto;
        }
        nav ul li {
            margin: 10px 20px;
        } 
        nav ul li a {
            text-decoration: none;
            font-size: 18px;
            position: relative;
            color: black;
            transition: color 0.3s ease;
        }
        nav ul li a:hover {
            color: grey;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #ea6b17;
            color: white;
        }
        img {
            width: 50px; /* Adjust size of the image */
            height: 50px;
            object-fit: cover;
            border-radius: 50%; /* Optional: To make the image round */
        }
    </style>
</head>
<body>

    <!-----header/navbar----->
    <div id="header">
        <div class="container">
            <nav>
                <i class="fa-solid fa-basketball"></i><h2>HoopTracker</h2>
                <ul>
                    <li><a href="source.html">Home</a></li>
                    <li><a href="User.php">User</a></li>
                    <li><a href="Team.php">Team</a></li>
                    <li><a href="Player.php">Player</a></li>
                    <li><a href="Conference.php">Conference</a></li>
                    <li><a href="FavTeam.php">Favorite Team</a></li>
                    <li><a href="FavPlayer.php">Favorite Player</a></li>
                    <li><a href="Game.php">Game</a></li>
                    <li><a href="MVP_Pick.php">MVP Pick</a></li>
                    <li><a href="Media.php">Media</a></li>
                </ul>
            </nav>
        </div>
    </div>
    <div class="section-divider"></div>
    
    <!----- Player Data Section ----->
    <div class="container">
        <h2>Player Information</h2>
        <?php
        $sql = "SELECT * FROM Player";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo "<table>\n"; 
            echo "<tr>\n";
            echo "<th>Player_ID</th>\n
                  <th>F_name</th>\n
                  <th>L_Name</th>\n
                  <th>Age</th>\n
                  <th>Year</th>\n
                  <th>Position</th>\n
                  <th>Points</th>\n
                  <th>Rebounds</th>\n
                  <th>Assists</th>\n
                  <th>Player Photo</th>\n
                  <th>Team_ID</th>\n";
            echo "</tr>\n";

            // Output data for each row
            while($row = mysqli_fetch_row($result)) {
                echo "<tr>\n";
                echo "<td>" . $row[0] . "</td>\n"; 
                echo "<td>" . $row[1] . "</td>\n";  
                echo "<td>" . $row[2] . "</td>\n"; 
                echo "<td>" . $row[3] . "</td>\n";
                echo "<td>" . $row[4] . "</td>\n"; 
                echo "<td>" . $row[5] . "</td>\n"; 
                echo "<td>" . $row[6] . "</td>\n";
                echo "<td>" . $row[7] . "</td>\n"; 
                echo "<td>" . $row[8] . "</td>\n";
                echo "<td><img src='" . $row[9] . "' alt='Player Photo'></td>\n"; // Display image from PlayerURL
                echo "<td>" . $row[10] . "</td>\n";
                echo "</tr>\n";
            }
            echo "</table>\n";
        } else {
            echo "0 results";
        }

        mysqli_free_result($result);
        mysqli_close($conn);
        ?>
    </div>

</body>
</html>
