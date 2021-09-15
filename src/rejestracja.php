<?php
include("config.php");
session_start();

if (isset($_SESSION["zalogowany"]) && $_SESSION["zalogowany"] == true) {
  header("location ./");
}
?>

<html>

<head>
  <title>Książka adresowa - rejestracja</title>
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
              <a class="nav-link" href="./kontakty.php">Kontakty</a>
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
              <a class="nav-link active" href="./rejestracja.php">Rejestracja</a>
            </li>
          ');
          }
          ?>
        </ul>
      </div>
    </div>
  </nav>
  <main>
    <div class="container">
      <div id="row1" class="row pt-5">
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
          if (empty($_POST["email"])) {
            $err = "Email nie został podany.";
          } else {
            $email = $_POST["email"];
          }

          if (empty($_POST["password"])) {
            $err = "Hasło nie zostało podane.";
          } else {
            $password = $_POST["password"];
          }

          if (empty($_POST["login"])) {
            $err = "Login nie został podany.";
          } else {
            $login = $_POST["login"];
          }

          if (!isset($err)) {
            $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
            if ($conn->connect_error) {
              echo ('<div class="alert alert-danger mb-5" role="alert">Błąd połączenia z bazą danych</div>');
            }

            $stmt = $conn->prepare("INSERT INTO users (login, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $login, $email, $password);
            $stmt->execute();

            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
              $stmt->bind_result($id);
              $stmt->fetch();
            } else {
              echo ('<div class="alert alert-danger mb-5" role="alert">Wystąpił niespodziewany błąd</div>');
            }

            session_destroy();
            session_start();

            $_SESSION["zalogowany"] = true;
            $_SESSION["id"] = $id;
            $_SESSION["login"] = $login;

            $stmt->close();
            $conn->close();

            echo ('<div class="alert alert-success mb-5" role="alert">Zarejestrowano i zalogowano pomyślnie</div>');
          } else {
            echo ('<div class="alert alert-success mb-5" role="alert">' . $err . '</div>');
          }
        }
        ?>
        <form method="post">
          <div class="mb-3">
            <label for="login" class="form-label">Nazwa użytkownika</label>
            <input type="text" name="login" id="login" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Adres email</label>
            <input type="email" name="email" id="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Hasło</label>
            <input type="password" name="password" id="password" class="form-control" required>
          </div>
          <button type="submit" name="submit" id="submit" class="btn btn-primary">Zarejestruj się</button>
        </form>
      </div>
    </div>
  </main>
</body>

</html>