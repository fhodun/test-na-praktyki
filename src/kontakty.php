<?php
include("config.php");
session_start();
?>

<html>

<head>
  <title>Książka adresowa - kontakty</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.min.js" integrity="sha384-skAcpIdS7UcVUC05LJ9Dxay8AXcDYfBJqt1CJ85S/CFujBsIzCIv+l9liuYLaMQ/" crossorigin="anonymous"></script>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="./">Książka adresowa</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsupportedcontent" aria-controls="navbarsupportedcontent" aria-expanded="false" aria-label="toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarsupportedcontent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="./">Strona główna</a>
          </li>
          <?php
          if (isset($_SESSION["zalogowany"]) && $_SESSION["zalogowany"] == true) {
            echo ('
            <li class="nav-item">
              <a class="nav-link active" href="./kontakty.php">Kontakty</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="./dodaj-kontakt.php">Dodaj kontakt</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="./wyloguj-sie.php">Wyloguj się</a>
            </li>
            ');
          } else {
            echo ('
            <li class="nav-item">
              <a class="nav-link" href="./zaloguj-sie.php">Zaloguj sie</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="./rejestracja.php">Rejestracja</a>
            </li>
          ');
          }
          ?>
        </ul>
      </div>
    </div>
  </nav>
  <main>
    <section>
      <form method="post" action="./kontakt.php">
        <div class="container">
          <div id="row1" class="row justify-content-center pt-5">
            <?php
            if (isset($_SESSION["zalogowany"]) && $_SESSION["zalogowany"] == true) {
              if (isset($_GET["p"])) {
                $p = $_GET["p"];
              } else {
                $p = 1;
              }
              $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
              if ($conn->connect_error) {
                echo ("Błąd połączenia z bazą danych" . $conn->connect_error);
              }
              $query = 'SELECT id, first_name, last_name, phone_number FROM contacts WHERE user_id = ' . $_SESSION["id"];
              $result = $conn->query($query);
              $i = $result->num_rows;

              $query = 'SELECT id, first_name, last_name, phone_number FROM contacts WHERE user_id = ' . $_SESSION["id"] . ' LIMIT ' . ($p * 10) . ' OFFSET ' . (($p - 1) * 10);
              $result = $conn->query($query);


              while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $i++;
                echo ('
                  <div class="col-md-5 col-lg-3 p-2">
                    <div class="card" style="width: 18rem; height: 10rem;">
                      <div class="card-body">
                        <h5 class="card-title">' . $row["first_name"] . ' ' . $row["last_name"] . '</h5>
                        <h6 class="card-subtitle mb-2 text-muted">' . $row["phone_number"] . '</h6>
                        <br />
                        <button type="submit" name="submit" id="submit-wyswietl" value="wyswietl-' . $row["id"] . '" class="btn btn-primary m-1">Wyświetl</a>
                        <button type="submit" name="submit" id="submit-edytuj" value="edytuj-' . $row["id"] . '" class="btn btn-secondary m-1">Edytuj</a>
                        <button type="submit" name="submit" id="submit-usun" value="usun-' . $row["id"] . '" class="btn btn-danger m-1">Usuń</a>
                      </div>
                    </div>
                  </div>
                ');
              }
              if ($p * 10 <= $i) {
                echo ('<button type="submit" formmethod="get" formaction="./kontakty.php" name="p" id="submit" value="' . $p + 1 . '" class="mt-5 btn btn-primary btn-lg">
              Następna strona</button>');
              }
              if (($p - 1) * 10 > 1) {
                echo ('<button type="submit" formmethod="get" formaction="./kontakty.php" name="p" id="submit" value="' . $p - 1 . '" class="mt-1 btn btn-primary btn-lg">
              Poprzednia strona</button>');
              }

              $conn->close();
            }
            ?>
          </div>
      </form>
    </section>
    </div>
  </main>
</body>

</html>