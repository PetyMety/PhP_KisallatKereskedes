<?php
$servername = "localhost";
$username = "root"; // vagy a saját felhasználóneved
$password = ""; // vagy a saját jelszavad
$dbname = "kisallat_kereskedes";

// Kapcsolódás az adatbázishoz
$conn = new mysqli($servername, $username, $password, $dbname);

// Ellenőrizd a kapcsolatot
if ($conn->connect_error) {
    die("Kapcsolódási hiba: " . $conn->connect_error);
}

// Keresési kifejezés
$search = isset($_POST['search']) ? $_POST['search'] : '';

// Termékek lekérdezése a keresési kifejezés alapján
$sql = "SELECT * FROM termekek WHERE nev LIKE ? OR ar LIKE ? OR leiras LIKE ?";
$stmt = $conn->prepare($sql);
$searchParam = "%" . $search . "%";
$stmt->bind_param("sss", $searchParam, $searchParam, $searchParam);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kisállatkereskedés Webshop</title>
    <link rel="stylesheet" href="styles.css ">
</head>
<body>
    <header>
        <h1>Kisállatkereskedés Webshop</h1>
        <div class="cart-icon">
            <a href="cart.php">Kosár (<?php echo isset($_SESSION['kosar']) ? count($_SESSION['kosar']) : 0; ?>)</a>
        </div>
        <button class="toggle-button" id="toggleMode">Sötét mód</button>
        <form method="POST" action="">
            <input type="text" name="search" placeholder="Keresés..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Keresés</button>
        </form>
    </header>
    <main>
        <div class="product-list">
            <?php
            if ($result->num_rows > 0) {
                // Termékek megjelenítése
                while($row = $result->fetch_assoc()) {
                    echo "<div class='product'>";
                    echo "<h2>" . htmlspecialchars($row["nev"]) . "</h2>";
                    echo "<img src='images/" . htmlspecialchars($row["kep"]) . "' alt='" . htmlspecialchars($row["nev"]) . "'>";
                    echo "<p>Ár: " . htmlspecialchars($row["ar"]) . " Ft</p>";
                    echo "<p>Készleten: " . htmlspecialchars($row["keszlet"]) . " db</p>";
                    echo "<p>" . htmlspecialchars($row["leiras"]) . "</p>";

                    // Akciós termékek kezelése
                    if ($row["akcios"]) {
                        $kedvezmeny = htmlspecialchars($row["kedvezmeny"]);
                        echo "<p style='color: red;'>Akciós! Kedvezmény: " . $kedvezmeny . "%</p>";
                    }

                    // Kosárba rakom gomb
                    echo "<form method='POST' action='cart.php'>";
                    echo "<input type='hidden' name='id' value='" . htmlspecialchars($row["id"]) . "'>";
                    echo "<input type='hidden' name='nev' value='" . htmlspecialchars($row["nev"]) . "'>";
                    echo "<input type='hidden' name='ar' value='" . htmlspecialchars($row["ar"]) . "'>";
                    echo "<button type='submit' class='add-to-cart'>Kosárba rakom</button>";
                    echo "</form>";

                    echo "</div>";
                }
            } else {
                echo "<p>Nincsenek termékek a keresési feltételeknek megfelelően.</p>";
            }
            $stmt->close();
            $conn->close();
            ?>
        </div>
    </main>
    <footer>
        <p>© 2023 Kisállatkereskedés. Minden jog fenntartva.</p>
        <p>Székhely: 1234 Budapest, Fő utca 1.</p>
        <p>Kapcsolat: info@kisallatkereskedes.hu | Telefon: +36 1 234 5678</p>
    
        <h2>Üzletünk helye</h2>
        <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.835434509123!2d144.9537353153163!3d-37.81627997975157!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad642af0f0f0f0f%3A0x0!2zMTIzNCBCdWRhcGVzdCwgRm8gVXRjYSAxIQ!5e0!3m2!1shu!2shu!4v1631234567890!5m2!1shu!2shu" 
            width="600" 
            height="450" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy"></iframe>
    
    </footer>
    <script>
        const toggleButton = document.getElementById('toggleMode');
        toggleButton.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');
            toggleButton.textContent = document.body.classList.contains('dark-mode') ? 'Világos mód' : 'Sötét mód';
        });
    </script>
</body>
</html>