<?php
require_once('auth.php');
require_once('database_login.php');

if (!isset($_SESSION['gebruikersnaam'])) {
    header('Location: login.php');
    exit();
}

if ($_SESSION['rang'] !== 'Admin') {
    header('Location: index.php');
    exit();
}

function getBerichten($conn)
{
    $stmt = $conn->prepare("SELECT * FROM berichten");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAccounts($conn)
{
    $stmt = $conn->prepare("SELECT gebruikers.id, gebruikers.gebruikersnaam, gebruikers.email, gebruikers.rang AS rangnaam FROM gebruikers");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_bericht'])) {
        $berichtId = $_POST['bericht_id'];
        $stmt = $conn->prepare("DELETE FROM berichten WHERE id = :id");
        $stmt->bindParam(':id', $berichtId);
        $stmt->execute();
    } elseif (isset($_POST['pin_bericht'])) {
        $berichtId = $_POST['bericht_id'];
        $stmt = $conn->prepare("UPDATE berichten SET is_gepind = 1 WHERE id = :id");
        $stmt->bindParam(':id', $berichtId);
        $stmt->execute();
    } elseif (isset($_POST['unpin_bericht'])) {
        $berichtId = $_POST['bericht_id'];
        $stmt = $conn->prepare("UPDATE berichten SET is_gepind = 0 WHERE id = :id");
        $stmt->bindParam(':id', $berichtId);
        $stmt->execute();
    } elseif (isset($_POST['update_rang'])) {
        $gebruikerId = $_POST['gebruiker_id'];
        $nieuweRang = $_POST['nieuwe_rang'];
        $stmt = $conn->prepare("UPDATE gebruikers SET rang = :nieuwe_rang WHERE id = :id");
        $stmt->bindParam(':nieuwe_rang', $nieuweRang);
        $stmt->bindParam(':id', $gebruikerId);
        $stmt->execute();
    } elseif (isset($_POST['delete_gebruiker'])) {
        $gebruikerId = $_POST['gebruiker_id'];
        $stmt = $conn->prepare("DELETE FROM gebruikers WHERE id = :id");
        $stmt->bindParam(':id', $gebruikerId);
        $stmt->execute();
    }
}

$berichten = getBerichten($conn);
$accounts = getAccounts($conn);
$rangen = ['Student', 'Coach', 'Admin'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" href="./images/favicon.ico" type="image/x-icon">
    <title>Admin Panel</title>
</head>

<body class="bg-gray-100">
    <div class="bg-white p-8 mx-auto mt-8 max-w-3xl rounded shadow-md">
        <h1 class="text-center text-2xl font-semibold mb-4">⚙️ Admin Panel ⚙️</h1>
        <div class="mb-8">
            <div class="bg-gray-200 p-4 rounded">
                <h2 class="text-lg font-semibold mb-2">Berichten</h2>
                <ul>
                    <?php foreach ($berichten as $bericht) : ?>
                        <li class="mb-2">
                            <div class="flex justify-between items-center bg-gray-100 p-2 rounded">
                                <div class="w-1/2 overflow-hidden whitespace-nowrap overflow-ellipsis pr-2">
                                    <?= $bericht['gebruikersnaam']; ?>: <?= $bericht['tekst']; ?>
                                </div>
                                <form method="post">
                                    <input type="hidden" name="bericht_id" value="<?= $bericht['id'] ?>">
                                    <?php if ($bericht['is_gepind'] == 0) : ?>
                                        <button type="submit" name="pin_bericht" class="text-blue-600">Pin</button>
                                    <?php else : ?>
                                        <button type="submit" name="unpin_bericht" class="text-green-600">Unpin</button>
                                    <?php endif; ?>
                                    <button type="submit" name="delete_bericht" class="text-red-600">Verwijderen</button>
                                </form>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div>
            <div class="bg-gray-200 p-4 rounded">
                <h2 class="text-lg font-semibold mb-2">Accounts</h2>
                <ul>
                    <?php foreach ($accounts as $account) : ?>
                        <li class="mb-2">
                            <div class="flex justify-between items-center bg-gray-100 p-2 rounded">
                                <p><?= $account['gebruikersnaam']; ?> (<?= $account['rangnaam']; ?>): <?= $account['email']; ?></p>
                                <form method="post">
                                    <input type="hidden" name="gebruiker_id" value="<?= $account['id'] ?>">
                                    <select name="nieuwe_rang">
                                        <?php foreach ($rangen as $rang) : ?>
                                            <option value="<?= $rang ?>" <?= $rang == $account['rangnaam'] ? 'selected' : '' ?>>
                                                <?= $rang ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" name="update_rang" class="text-green-600">Rang bijwerken</button>
                                    <button type="submit" name="delete_gebruiker" class="text-red-600">Verwijderen</button>
                                </form>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="mt-4">
            <a href="index.php" class="text-blue-600 hover:underline">Terug naar de Homepagina</a>
        </div>
    </div>
</body>

</html>