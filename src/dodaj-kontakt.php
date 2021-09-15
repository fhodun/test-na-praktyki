<?php
include("config.php");
session_start();
?>

<html>

<head>
  <title>Książka adresowa - dodaj kontakt</title>
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
              <a class="nav-link active" href="./dodaj-kontakt.php">Dodaj kontakt</a>
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
    <div class="container">
      <div id="row1" class="row pt-5">
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"]) && $_POST["submit"] == "dodaj-kontakt") {
          $query_backwards = $_SESSION["id"];
          $query = "INSERT INTO contacts (user_id";

          if (empty($_POST["first_name"])) {
            $err = "Imię nie zostało podane.";
          } else {
            $query_backwards = $query_backwards . ", '" . $_POST["first_name"] . "'";
            $query = $query . ', first_name';
          }

          if (!empty($_POST["last_name"])) {
            // $last_name = $_POST["last_name"];
            // $query_arr = array_push($query_arr, $_POST["last_name"]);
            $query_backwards = $query_backwards . ", '" . $_POST["last_name"] . "'";
            $query = $query . ', last_name';
          }

          if (!empty($_POST["phone_number"])) {
            $query_backwards = $query_backwards . ", '" . $_POST["phone_number"] . "'";
            $query = $query . ', phone_number';
          }

          if (!empty($_POST["email"])) {
            $query_backwards = $query_backwards . ", '" . $_POST["email"] . "'";
            $query = $query . ', email';
          }

          if (!empty($_POST["address"])) {
            $query_backwards = $query_backwards . ", '" . $_POST["address"] . "'";
            $query = $query . ', address';
          }

          $target_dir = "images/";
          $target_file = $target_dir . basename($_FILES["image"]["name"]);
          $uploadOk = 1;
          $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
          $check = getimagesize($_FILES["image"]["tmp_name"]);
          if ($check != false) {
            $uploadOk = 1;
          } else {
            echo ('<div class="alert alert-danger mb-5" role="alert">File is not an image.</div>');
            $uploadOk = 0;
          }
          if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
          ) {
            echo ('<div class="alert alert-danger mb-5" role="alert">Sorry, only JPG, JPEG, PNG & GIF files are allowed.</div>');
            $uploadOk = 0;
          }
          $pliknazwa = basename($_FILES["image"]["name"]);
          echo $pliknazwa;
          $query_backwards = $query_backwards . ", '" . $pliknazwa . "'";
          $query = $query . ', image';


          $query = $query . ') VALUES (' . $query_backwards . ')';

          if (!isset($err) && $uploadOk != 0) {
            $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
            if ($conn->connect_error) {
              echo ('<div class="alert alert-danger mb-5" role="alert">Błąd połączenia z bazą danych</div>');
            }

            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
              echo ('<div class="alert alert-danger mb-5" role="alert">Wystąpił błąd przesyłania pliku.</div>');
            }

            if ($conn->query($query) === TRUE) {
              echo ('<div class="alert alert-success mb-5" role="alert">Kontakt dodany pomyślnie</div>');
            } else {
              echo ('<div class="alert alert-success mb-5" role="alert">Wystąpił nieoczekiwany błąd: ' . $conn->error . '</div>');
            }

            $conn->close();
          } else {
            echo ('<div class="alert alert-danger mb-5" role="alert">' . $err . '</div>');
          }
        }
        ?>
        <form method="post" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="fist_name" class="form-label">Imię</label>
            <input type="text" class="form-control" name="first_name" id="first_name" required autofocus>
          </div>
          <div class="mb-3">
            <label for="fist_name" class="form-label">Nazwisko</label>
            <input type="text" class="form-control" name="last_name" id="last_name">
          </div>
          <div class="mb-3">
            <label for="phone_number" class="form-label">Numer telefonu</label>
            <input type="tel" class="form-control" name="phone_number" id="phone_number">
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" id="email">
          </div>
          <div class="mb-3">
            <label for="address" class="form-label">Adres</label>
            <input type="text" class="form-control" name="address" id="address">
          </div>
          <div class="mb-3">
            <label for="image" class="form-label">Zdjęcie</label>
            <input type="file" accept="image/*" class="form-control" name="image" id="image">
          </div>
          <button type="submit" name="submit" id="submit-dodaj-kontakt" value="dodaj-kontakt" class="btn btn-primary">Submit</button>
        </form>
      </div>
    </div>
  </main>
</body>

</html>