<?php
session_start();

require_once('database_login.php');

if (isset($_SESSION['gebruikersnaam'])) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM gebruikers WHERE gebruikersnaam = :username AND wachtwoord = :wachtwoord";
    $result = $conn->prepare($sql);
    $result->bindParam(':username', $username);
    $result->bindParam(':wachtwoord', $password);
    $result->execute();

    if ($result->rowCount() == 1) {
        $_SESSION['gebruikersnaam'] = $username;
        header("Location: index.php");
        exit();
    } else {
        echo "<script>alert('Inloggen mislukt. Controleer je gebruikersnaam en wachtwoord.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen - Bit-Tweets</title>
    <link href="https://unpkg.com/tailwindcss@^2.0/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <h1 class="text-2xl font-semibold text-center mb-6">Inloggen op Bit-Tweets</h1>
        <form method="POST">
            <div class="mb-4">
                <label for="username" class="block text-gray-700 text-sm font-medium mb-2">Gebruikersnaam</label>
                <input type="text" id="username" name="username" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500" required>
            </div>
            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Wachtwoord</label>
                <input type="password" id="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500" required>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-indigo-500 text-white font-semibold px-4 py-2 rounded-full hover:bg-indigo-600 focus:outline-none focus:bg-indigo-600">Inloggen</button>
                <a href="register.php" class="text-indigo-500 hover:underline text-sm">Account
                    aanmaken</a>
            </div>
        </form>
    </div>
</body>

</html>