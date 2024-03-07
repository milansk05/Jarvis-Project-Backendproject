<?php
$servername = "localhost";
$username = "bit_academy";
$password = "wachtwoord";
$database = "bittweets";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Fout bij de verbinding met de database: " . $e->getMessage();
    exit();
}


if (isset($_SESSION['gebruikersnaam'])) {
    $gebruikersnaam = $_SESSION['gebruikersnaam'];

    $stmt = $conn->prepare("SELECT rang FROM gebruikers WHERE gebruikersnaam = :gebruikersnaam");
    $stmt->bindParam(':gebruikersnaam', $gebruikersnaam);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $_SESSION['rang'] = $result['rang'];
    }
}
