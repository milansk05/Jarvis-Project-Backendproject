<?php
require_once('database_login.php');

$sql = "SELECT gebruikers.gebruikersnaam, gebruikers.profielfoto, gebruikers.rang AS rangnaam
        FROM gebruikers";
$statement = $conn->prepare($sql);
$statement->execute();
$leden = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leden van Bit-Tweets</title>
    <link href="https://unpkg.com/tailwindcss@^2.0/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-4">

        <h1 class="text-2xl font-semibold mb-4">Andere leden van Bit-Tweets</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($leden as $lid) : ?>
                <div class="bg-white p-4 shadow rounded-md">
                    <img src="<?php echo $lid['profielfoto']; ?>" alt="<?php echo $lid['gebruikersnaam']; ?>" class="w-20 h-20 rounded-full mx-auto mb-3">
                    <p class="text-lg font-semibold text-center"><?php echo $lid['gebruikersnaam']; ?></p>
                    <p class="text-center text-sm text-gray-600"><?php echo $lid['rangnaam']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-4">
            <a href="index.php" class="bg-indigo-500 text-white px-4 py-2 rounded-full hover-bg-indigo-600 focus:outline-none focus-bg-indigo-600">
                Terug naar de homepage
            </a>
        </div>

    </div>
</body>

</html>