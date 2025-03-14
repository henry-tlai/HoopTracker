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

// Add CSS styles
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
    body {
        font-family: 'Crimson Text', serif;
        font-weight: 600;
        font-style: normal;
        padding: 20px;
        line-height: 1.5;
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

// Get and validate query data
$query_type = $_POST['query_type'] ?? '';
$query_text = $_POST['query'] ?? '';

// Validate query type
$allowed_types = ['SELECT', 'INSERT INTO', 'UPDATE', 'DELETE FROM'];
if (!in_array($query_type, $allowed_types)) {
    die("Invalid query type");
}

// Basic SQL injection prevention
$dangerous_keywords = ['DROP', 'TRUNCATE', 'ALTER', 'GRANT', 'REVOKE'];
foreach ($dangerous_keywords as $keyword) {
    if (stripos($query_text, $keyword) !== false) {
        die("Query contains forbidden keywords");
    }
}

$full_query = $query_type . ' ' . $query_text;

try {
    $result = $conn->query($full_query);
    
    if ($result === TRUE) {
        // For queries that don't return results (INSERT, UPDATE, DELETE)
        echo "<div style='margin-top: 20px;'>Query executed successfully. " . $conn->affected_rows . " row(s) affected.</div>";
    } elseif ($result === FALSE) {
        // Query error
        die("Error: " . $conn->error);
    } else {
        // For SELECT queries
        if (mysqli_num_rows($result) > 0) {
            echo "<table>\n";
            
            // Get and display column headers
            $first_row = mysqli_fetch_assoc($result);
            echo "<tr>\n";
            foreach ($first_row as $column => $value) {
                echo "<th>" . htmlspecialchars($column) . "</th>\n";
            }
            echo "</tr>\n";
            
            // Display first row
            echo "<tr>\n";
            foreach ($first_row as $value) {
                echo "<td>" . formatCellContent($value) . "</td>\n";
            }
            echo "</tr>\n";
            
            // Display remaining rows
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>\n";
                foreach ($row as $value) {
                    echo "<td>" . formatCellContent($value) . "</td>\n";
                }
                echo "</tr>\n";
            }
            
            echo "</table>\n";
        } else {
            echo "<div style='margin-top: 20px;'>0 results</div>";
        }
        $result->free();
    }
} catch (Exception $e) {
    die("Error: " . htmlspecialchars($e->getMessage()));
}

$conn->close();

// Add link back to dashboard
echo "<div style='margin-top: 20px;'><a href='dashboard.php'>Back to Dashboard</a></div>";
?> 