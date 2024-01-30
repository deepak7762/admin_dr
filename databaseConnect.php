<?php
//     $host = "localhost";
//     $database = "dr_live";
//     $username = "root";
//     $password = "";

// try {
//     $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
//     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//     // echo "Connected to MySQL database successfully.";
// } catch (PDOException $e) {
//     die("Error: " . $e->getMessage());
// }

?>

<?php
    $host = "mymedirecords.cbs8imeeg5j7.us-east-1.rds.amazonaws.com";
    $port = 5432;
    $database = "mymedirecords";
    $username = "postgres";
    $password = "admin1234";

    try {
        $conn = new PDO("pgsql:host=$host;port=$port;dbname=$database;user=$username;password=$password");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // echo "Connected to PostgreSQL database successfully.";
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
?>

