<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nev = $_POST['nev'];
    $cim = $_POST['cim'];
    $email = $_POST['email'];

    if (!empty($nev) && !empty($cim) && !empty($email)) {
        echo "Köszönjük a rendelést, " . htmlspecialchars($nev) . "!";
        // Kosár törlése
        unset($_SESSION['kosar']);
    } else {
        echo "Kérjük, töltse ki az összes mezőt!";
    }
}
?>

<form method="POST" action="">
    Név: <input type="text" name="nev"><br>
    Cím: <input type="text" name="cim"><br>
    E-mail: <input type="email" name="email"><br>
    <button type="submit">Rendelés leadása</button>
</form>