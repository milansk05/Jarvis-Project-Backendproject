<?php
require_once('auth.php');
require_once('database_login.php');

$sql = "SELECT berichten.*, gebruikers.profielfoto, gebruikers.rang AS rangnaam
FROM berichten
LEFT JOIN gebruikers ON berichten.gebruikersnaam = gebruikers.gebruikersnaam
ORDER BY berichten.is_gepind DESC, berichten.datum_tijd DESC";

$statement = $conn->prepare($sql);
$statement->execute();
$messages = $statement->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gebruikersnaam = $_SESSION['gebruikersnaam'];
    $bericht = htmlspecialchars($_POST["message"]);
    $isGepind = isset($_POST["isGepind"]) ? 1 : 0;

    $maximale_bericht_lengte = 500;

    if (strlen($bericht) > $maximale_bericht_lengte) {
        $bericht = substr($bericht, 0, $maximale_bericht_lengte);
    }

    $sql = "SELECT MAX(datum_tijd) AS laatste_bericht_tijd FROM berichten WHERE gebruikersnaam = :gebruikersnaam";
    $result = $conn->prepare($sql);
    $result->bindParam(':gebruikersnaam', $gebruikersnaam);
    $result->execute();
    $row = $result->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $laatste_bericht_tijd = strtotime($row['laatste_bericht_tijd']);
        $huidige_tijd = time();

        if ($huidige_tijd - $laatste_bericht_tijd < 10) { // 10 seconden is bedoeld voor de Online website (milansnoeijink.nl)
            echo "<script>alert('Je kunt slechts één bericht per 10 seconden plaatsen.');</script>";
        } else {
            $sql = "INSERT INTO berichten (`gebruikersnaam`, `tekst`) VALUES (:gebruikersnaam, :bericht)";
            $result = $conn->prepare($sql);
            $result->bindParam(':gebruikersnaam', $gebruikersnaam);
            $result->bindParam(':bericht', $bericht);

            if ($result->execute() === TRUE) {
                header("Location: index.php");
                exit;
            } else {
                echo "<script>alert('Fout bij het plaatsen van het bericht.');</script>";
            }
        }
    } else {
        $sql = "INSERT INTO berichten (`gebruikersnaam`, `tekst`) VALUES (:gebruikersnaam, :bericht)";
        $result = $conn->prepare($sql);
        $result->bindParam(':gebruikersnaam', $gebruikersnaam);
        $result->bindParam(':bericht', $bericht);

        if ($result->execute() === TRUE) {
            header("Location: index.php");
            exit;
        } else {
            echo "<script>alert('Fout bij het plaatsen van het bericht.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bit-Tweets</title>
    <link href="https://unpkg.com/tailwindcss@^2.0/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" href="./images/favicon.ico" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex flex-row">
        <!-- In/Uitklapbare sidebar -->
        <aside id="sidebar" class="h-screen w-64 bg-indigo-900 text-gray-300 transform transition-transform duration-300 ease-in-out sticky top-0">
            <div class="sidebar-header flex items-center justify-between py-4 px-6">
                <a href="index.php" class="inline-flex flex-row items-center">
                    <span class="leading-10 text-2xl font-bold uppercase">Bit-Tweets</span>
                </a>
                <button id="toggleSidebar" class="text-gray-300 hover:text-gray-100 focus:outline-none focus:text-gray-100">
                    <svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                        <path id="sidebarIcon" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="sidebar-content px-4 py-6">
                <ul class="flex flex-col w-full">
                    <li class="my-px">
                        <span class="flex font-medium text-sm text-gray-300 px-4 my-4 uppercase">Algemeen</span>
                    </li>
                    <li class="my-px">
                        <a href="index.php" class="flex flex-row items-center h-10 px-3 rounded-lg text-gray-300 hover:bg-gray-100 hover:text-gray-700">
                            <span class="flex items-center justify-center text-lg text-gray-400">
                                <svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                                    <path d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                </svg>
                            </span>
                            <span class="ml-3 striped">Homepagina</span>
                        </a>
                    </li>
                    <li class="my-px">
                        <a href="" class="flex flex-row items-center h-10 px-3 rounded-lg text-gray-300 hover:bg-gray-100 hover:text-gray-700">
                            <span class="flex items-center justify-center text-lg text-gray-400">
                                <svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                                    <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </span>
                            <span class="ml-3 line-through">Vrienden</span>
                        </a>
                        <small class="ml-3 text-xs text-gray-500">* Non-actieve functie</small>
                    </li>
                    <li class="my-px">
                        <a href="" class="flex flex-row items-center h-10 px-3 rounded-lg text-gray-300 hover:bg-gray-100 hover:text-gray-700">
                            <span class="flex items-center justify-center text-lg text-gray-400">
                                <svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </span>
                            <span class="ml-3 line-through">Prive Berichten</span>
                        </a>
                        <small class="ml-3 text-xs text-gray-500">* Non-actieve functie</small>
                    </li>
                    <li class="my-px">
                        <a href="leden.php" class="flex flex-row items-center h-10 px-3 rounded-lg text-gray-300 hover:bg-gray-100 hover:text-gray-700">
                            <span class="flex items-center justify-center text-lg text-gray-400">
                                <svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                                    <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </span>
                            <span class="ml-3">Bit-Tweets Leden</span>
                        </a>
                    </li>
                    <li class="my-px">
                        <span class="flex font-medium text-sm text-gray-300 px-4 my-4 uppercase">Account</span>
                    </li>
                    <li class="my-px">
                        <a href="./profile.php" class="flex flex-row items-center h-10 px-3 rounded-lg text-gray-300 hover:bg-gray-100 hover:text-gray-700">
                            <span class="flex items-center justify-center text-lg text-gray-400">
                                <svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </span>
                            <span class="ml-3">Profile</span>
                        </a>
                    </li>
                    <li class="my-px">
                        <a href="logout.php" class="flex flex-row items-center h-10 px-3 rounded-lg text-gray-300 hover:bg-gray-100 hover:text-gray-700">
                            <span class="flex items-center justify-center text-lg text-red-400">
                                <svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                                    <path d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                </svg>
                            </span>
                            <span class="ml-3">Logout</span>
                        </a>
                    </li>
                    <li class="my-px">
                        <span class="flex font-medium text-sm text-gray-300 px-4 my-4 uppercase">Other</span>
                    </li>
                    <li class="my-px">
                        <a href="changelog.php" class="flex flex-row items-center h-10 px-3 rounded-lg text-gray-300 hover:bg-gray-100 hover:text-gray-700">
                            <span class="flex items-center justify-center text-lg text-gray-400">
                                <svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </span>
                            <span class="ml-3">Changelog</span>
                        </a>
                    </li>
                    <li class="my-px">
                        <a href="admin.php" class="flex flex-row items-center h-10 px-3 rounded-lg text-gray-300 hover:bg-gray-100 hover:text-gray-700">
                            <span class="flex items-center justify-center text-lg text-gray-400">
                                <svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                                    <path d="M12 2c-5.5228 0-10 4.4772-10 10s4.4772 10 10 10 10-4.4772 10-10-4.4772-10-10-10zm-1 16.18v-1c0-.28.22-.5.5-.5h1c.28 0 .5.22.5.5v1a2 2 0 01-1 0zm1-3a2 2 0 100-4 2 2 0 000 4z" />
                                </svg>
                            </span>
                            <span class="ml-3">Admin Pagina</span>
                        </a>
                        <small class="ml-3 text-xs text-gray-500">
                            * Alleen toegankelijk voor admins<br><br>
                            <?php
                            if (isset($_SESSION['rang'])) {
                                echo 'Jij bent ingelogd als: ' . $_SESSION['rang'];
                            }
                            ?>
                        </small>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- Tweetbox -->
        <div class="flex-grow p-6 mt-6 ml-6 sm:ml-0">
            <div class="mt-6 mx-auto max-w-full sm:max-w-xl bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-indigo-500 px-4 py-2">
                    <h1 class="text-white text-lg font-semibold">Nieuwe Tweet (Max 500 char, 10 sec cooldown)</h1>
                </div>

                <div class="p-4">
                    <form class="" method="POST">
                        <textarea name="message" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500" rows="4" placeholder="Wat is er aan de hand?"></textarea>
                        <div class="flex justify-between items-center px-4 py-2 border-t border-gray-200">
                            <button type="submit" class="px-4 py-2 bg-indigo-500 text-white font-semibold rounded-full hover:bg-indigo-600 focus:outline-none focus:bg-indigo-600">Tweeten</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Normale Berichten -->
            <div class="mt-6">
                <?php

                if ($statement->rowCount() > 0) {
                    foreach ($messages as $message) {
                        if ($message['is_gepind'] == 0) {
                            $gebruikersnaam = $message['gebruikersnaam'];
                            $tekst = $message['tekst'];
                            $datum_tijd = $message['datum_tijd'];
                            $profielfoto = $message['profielfoto'];
                            $rangnaam = $message['rangnaam'];

                            echo '<div class="bg-white p-4 border border-gray-300 rounded-lg shadow-md mb-4">';
                            echo '<div class="flex items-center mb-3">';
                            echo '<img src="' . $profielfoto . '" class="w-12 h-12 rounded-full mr-3" alt="' . $gebruikersnaam . '">';
                            echo '<div class="flex flex-col ml-3">';
                            echo '<span class="font-semibold">' . $gebruikersnaam . ' (' . $rangnaam . ')</span>';
                            echo '<span class="text-gray-600">@' . $gebruikersnaam . ' - ' . $datum_tijd . '</span>';
                            echo '</div>';
                            echo '</div>';

                            echo '<div class="text-gray-800 max-w-5xl break-words">' . $message['tekst'] . '</div>';

                            echo '</div>';
                        }
                    }
                } else {
                    echo "Er zijn geen normale berichten.";
                }
                ?>
            </div>
        </div>

        <!-- Gepinde Sidebar -->
        <aside class="w-1/4 p-4 bg-white rounded-lg shadow-md sm:w-1/3 md:w-1/4">
            <h2 class="text-lg font-semibold mb-4 text-center">📌 Gepinde Berichten</h2>
            <div class="py-4 px-2">
                <?php
                $sql = "SELECT berichten.*, gebruikers.profielfoto, gebruikers.rang AS rangnaam
                FROM berichten
                LEFT JOIN gebruikers ON berichten.gebruikersnaam = gebruikers.gebruikersnaam
                WHERE berichten.is_gepind = 1
                ORDER BY berichten.datum_tijd DESC";

                $statement = $conn->prepare($sql);
                $statement->execute();
                $gepindeBerichten = $statement->fetchAll(PDO::FETCH_ASSOC);

                if ($statement->rowCount() > 0) {
                    foreach ($gepindeBerichten as $bericht) {
                        $rangnaam = $bericht['rangnaam'];

                        echo '<div class="bg-white p-4 border border-gray-300 rounded-lg shadow-md mb-4">';
                        echo '<div class="flex items-center mb-3">';
                        echo '<img src="' . $bericht['profielfoto'] . '" class="w-12 h-12 rounded-full mr-3" alt="' . $bericht['gebruikersnaam'] . '">';
                        echo '<div class="flex flex-col">';
                        echo '<span class="font-semibold">' . $bericht['gebruikersnaam'] . ' (' . $rangnaam . ')</span>';
                        echo '<span class="text-gray-600">@' . $bericht['gebruikersnaam'] . ' - ' . $bericht['datum_tijd'] . '</span>';
                        echo '</div>';
                        echo '</div>';

                        echo '<p class="text-gray-800">' . $bericht['tekst'] . '</p>';

                        echo '</div>';
                    }
                } else {
                    echo "Er zijn geen gepinde berichten.";
                }
                ?>
            </div>
        </aside>

        <button id="toggleSidebarButton" class="bg-gray-800 text-white p-2 rounded-r-full focus:outline-none hover:bg-gray-700 absolute left-0 top-1/2 transform -translate-y-1/2 hidden">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6">
                <path id="toggleButtonIcon" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>

    </div>

    <script src="./js/navbar.js"></script>
</body>