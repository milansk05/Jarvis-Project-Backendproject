<?php
require_once('database_login.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gebruikersnaam = $_POST["username"];
    $email = $_POST["email"];
    $wachtwoord = $_POST["password"];
    $verjaardag = $_POST["birthday"];
    $registratiedatum = date("Y-m-d H:i:s");

    $defaultProfielfoto = "./images/default.jpg";

    $check_query = "SELECT * FROM gebruikers WHERE gebruikersnaam = :username";
    $result = $conn->prepare($check_query);
    $result->bindParam(':username', $gebruikersnaam);
    $result->execute();

    if ($result->rowCount() > 0) {
        echo "Deze gebruikersnaam is al in gebruik. Kies een andere gebruikersnaam.";
    } else {
        $insert_query = "INSERT INTO gebruikers (`id`, `gebruikersnaam`, `email`, `wachtwoord`, `verjaardag`, `registratiedatum`, `profielfoto`)
    VALUES (NULL, ?, ?, ?, ?, ?, ?)";
        $conn->prepare($insert_query)->execute([$gebruikersnaam, $email, $wachtwoord, $verjaardag, $registratiedatum, $defaultProfielfoto]);

        header('Location: login.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registreren - Bit-Tweets</title>
    <link href="https://unpkg.com/tailwindcss@^2.0/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        function validateForm() {
            var username = document.getElementById("username").value;
            var password = document.getElementById("password").value;
            var hasUpperCase = /[A-Z]/.test(password);
            var hasSpecialChar = /[!@#$%^&*()_+{}\[\]:;<>,.?~\\-]/.test(password);

            if (username.includes(" ")) {
                alert("De gebruikersnaam mag geen spaties bevatten.");
                return false;
            }

            if (!hasUpperCase || !hasSpecialChar) {
                alert("Het wachtwoord moet minimaal 1 hoofdletter en 1 speciaal teken bevatten.");
                return false;
            }

            return true;
        }
    </script>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <h1 class="text-2xl font-semibold text-center mb-6">Registreren voor Bit-Tweets</h1>
        <form method="POST" onsubmit="return validateForm();">
            <div class="mb-4">
                <label for="username" class="block text-gray-700 text-sm font-medium mb-2">Gebruikersnaam</label>
                <input type="text" id="username" name="username" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-medium mb-2">E-mailadres</label>
                <input type="email" id="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500" required>
            </div>
            <div class="mb-4">
                <label for="birthday" class="block text-gray-700 text-sm font-medium mb-2">Verjaardag</label>
                <input type="date" id="birthday" name="birthday" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500" required>
            </div>
            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Wachtwoord</label>
                <input type="password" id="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500" required>
            </div>
            <div class="flex items-center justify-between">
                <input type="submit" class="bg-indigo-500 text-white font-semibold px-4 py-2 rounded-full hover:bg-indigo-600 focus:outline-none focus:bg-indigo-600">
                <a href="login.php" class="text-indigo-500 hover:underline text-sm">Al een account? Log hier in</a>
            </div>
        </form>
    </div>
</body>

</html>