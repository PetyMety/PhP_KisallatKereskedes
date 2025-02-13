<?php
session_start();

// Termék eltávolítása a kosárból
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_id'])) {
    $remove_id = $_POST['remove_id'];
    unset($_SESSION['kosar'][$remove_id]);
    header("Location: cart.php"); // Frissítjük az oldalt
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nev = $_POST['nev'];
    $ar = $_POST['ar'];

    // Kosár inicializálása, ha még nem létezik
    if (!isset($_SESSION['kosar'])) {
        $_SESSION['kosar'] = [];
    }

    // Termék hozzáadása a kosárhoz
    if (isset($_SESSION['kosar'][$id])) {
        $_SESSION['kosar'][$id]['mennyiseg']++;
    } else {
        $_SESSION['kosar'][$id] = [
            'nev' => $nev,
            'ar' => $ar,
            'mennyiseg' => 1
        ];
    }
}

// Kosár tartalmának megjelenítése
if (isset($_SESSION['kosar']) && !empty($_SESSION['kosar'])) {
    echo "<h2>Kosár tartalma</h2>";
    $total = 0;

    foreach ($_SESSION['kosar'] as $id => $item) {
        echo "<p>{$item['nev']} - {$item['ar']} Ft x {$item['mennyiseg']}</p>";
        $total += $item['ar'] * $item['mennyiseg'];

        // Termék törlés gomb
        echo "<form method='POST' action='cart.php' style='display:inline;'>";
        echo "<input type='hidden' name='remove_id' value='" . htmlspecialchars($id) . "'>";
        echo "<button type='submit' class='remove-from-cart'>Eltávolítás</button>";
        echo "</form>";
    }

    echo "<p>Végösszeg: $total Ft</p>";
    echo "<a href='checkout.php'>Megrendelés leadása</a>";
} else {
    echo "<p>A kosár üres.</p>";
}
?>

<a href="index.php">Vissza a főoldalra</a>
