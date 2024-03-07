<?php
require_once('auth.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gebruikersnaam = $_SESSION['gebruikersnaam'];

    if (isset($_FILES['profielfoto'])) {
        $uploadDir = './images/';
        $uploadFile = $uploadDir . basename($_FILES['profielfoto']['name']);
        $maxFileSize = 2 * 1024 * 1024;

        if ($_FILES['profielfoto']['size'] > $maxFileSize) {
            echo "<script>alert('Bestandsgrootte is te groot. Kies een kleiner bestand.');</script>";
        } else {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileType = $_FILES['profielfoto']['type'];

            if (!in_array($fileType, $allowedTypes)) {
                echo "<script>alert('Ongeldig bestandstype. Upload een JPEG, PNG of GIF.');</script>";
            } else {
                if (move_uploaded_file($_FILES['profielfoto']['tmp_name'], $uploadFile)) {
                    require_once('database_login.php');
                    $query = "UPDATE gebruikers SET profielfoto = :profielfoto WHERE gebruikersnaam = :gebruikersnaam";
                    $stmt = $conn->prepare($query);
                    $stmt->bindParam(':profielfoto', $uploadFile);
                    $stmt->bindParam(':gebruikersnaam', $gebruikersnaam);

                    if ($stmt->execute()) {
                        header('Location: profile.php');
                        exit;
                    } else {
                        echo "<script>alert('Er is een probleem opgetreden bij het uploaden van de profielfoto.');</script>";
                    }
                } else {
                    echo "<script>alert('Er is een probleem opgetreden bij het uploaden van de profielfoto.');</script>";
                }
            }
        }
    }
}

require_once('database_login.php');

$gebruikersnaam = $_SESSION['gebruikersnaam'];

$query = "SELECT id, email, registratiedatum, verjaardag, profielfoto FROM gebruikers WHERE gebruikersnaam = :gebruikersnaam";
$stmt = $conn->prepare($query);
$stmt->bindParam(':gebruikersnaam', $gebruikersnaam);
$stmt->execute();

if ($stmt->rowCount() == 1) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $email = $row['email'];
    $registratiedatum = $row['registratiedatum'];
    $verjaardag = $row['verjaardag'];
    $profielfoto = $row['profielfoto'];
} else {
    echo "Gebruiker niet gevonden.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profiel</title>
    <link href="https://unpkg.com/tailwindcss@^2.0/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white w-96 p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-semibold mb-4">Profiel van <?= $gebruikersnaam ?></h1>
        <?php if (!empty($profielfoto)) : ?>
            <img src="<?= $profielfoto ?>" alt="<?= $gebruikersnaam ?>'s profielfoto" class="mb-4 rounded-full w-32 h-32">
        <?php endif; ?>
        <form action="profile.php" method="post" enctype="multipart/form-data" class="mb-4">
            <input type="file" name="profielfoto" accept="image/*">
            <button type="submit" class="text-blue-500 hover:underline">Upload profielfoto</button>
        </form>
        <div class="mb-4">
            <p class="text-gray-700 text-sm font-medium">Gebruikersnaam:</p>
            <p class="text-gray-800"><?= $gebruikersnaam ?></p>
        </div>
        <div class="mb-4">
            <p class="text-gray-700 text-sm font-medium">E-mailadres:</p>
            <p class="text-gray-800"><?= $email ?></p>
        </div>
        <div class="mb-4">
            <label for="birthday" class="block text-gray-700 text-sm font-medium">Verjaardag</label>
            <p id="birthday" class="text-gray-800"><?= $verjaardag ?></p>
        </div>
        <div class="mb-4">
            <p class="text-gray-700 text-sm font-medium">Registratiedatum:</p>
            <p class="text-gray-800"><?= $registratiedatum ?></p>
        </div>
        <a href="index.php" class="text-blue-500 hover:underline">Terug naar de startpagina</a>
    </div>
</body>

</html>