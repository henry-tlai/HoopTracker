<?php
$servername = "cssql.seattleu.edu";
$username = "bd_aquibuyen";
$password = "kAd1OpcEv0nfF5WN";
$dbname = "bd_aquibuyen";

// Establish connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "<style>
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
    .svg-image {
        width: 50px;
        height: 50px;
        object-fit: contain;
    }
</style>";

// Function to check if a value is an SVG URL
function isSvgUrl($value) {
    return (strpos($value, '.svg') !== false);
}

// Function to format cell content
function formatCellContent($value) {
    if (isSvgUrl($value)) {
        return "<img src='" . htmlspecialchars($value) . "' class='svg-image' alt='SVG Image'>";
    }
    return htmlspecialchars($value);
}

// Defining the Queries using an Associative Array
$queries = array(
    1 => "SELECT Media.Description, Media.MediaURL, Media.Like_Counter, User.Username, Team.Team_Name, Team.LogoURL FROM Media Natural JOIN User NATURAL JOIN Team;",
    2 => "SELECT Username, COUNT(Media_ID) as Number_Of_Posts FROM Media JOIN User ON User.UID = Media.UID WHERE Media.UID = '000000005';",
    3 => "SELECT Team_Name, LogoURL, Win FROM Team WHERE Win > (SELECT AVG(Win) FROM Team);",
    4 => "SELECT F_Name, L_Name, COUNT(Season) AS Number_Of_MVP FROM MVP_Pick NATURAL JOIN Player GROUP BY Player_ID HAVING Number_Of_MVP > 1;",
    5 => "SELECT T.Team_Name, COUNT(F.UID) as Favorite_Count FROM Team T LEFT JOIN Fav_Team F ON T.Team_ID = F.Team_ID GROUP BY T.Team_Name ORDER BY Team_Name;"
);

// Checking User Input
if (!isset($_GET['id']) || !isset($queries[$_GET['id']])) {
    die("Invalid query ID.");
}

// Execution without HTML Injection
$query = $queries[$_GET['id']];
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    echo "<table>\n";
    
    // Fetch the first row to get column names
    $firstRow = mysqli_fetch_assoc($result);
    echo "<tr>\n";
    foreach ($firstRow as $colName => $value) {
        echo "<th>" . htmlspecialchars($colName) . "</th>\n"; 
    }
    echo "</tr>\n";
    
    // Print first row's data
    echo "<tr>\n";
    foreach ($firstRow as $value) {
        echo "<td>" . formatCellContent($value) . "</td>\n"; 
    }
    echo "</tr>\n";
    
    // Print the remaining rows
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>\n";
        foreach ($row as $value) {
            echo "<td>" . formatCellContent($value) . "</td>\n"; 
        }
        echo "</tr>\n";
    }
    
    echo "</table>\n";
} else {
    echo "0 results";
}

mysqli_free_result($result);
?>
