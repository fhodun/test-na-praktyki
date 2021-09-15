<?php
include("config.php");
session_start();
?>

<html>

<head>
  <title>Książka adresowa - kontakt</title>
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
        <form method="post">
          <?php
          if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
            $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
            if ($conn->connect_error) {
              echo ('<div class="alert alert-danger mb-5" role="alert">Błąd połączenia z bazą danych</div>');
            }

            $submit = explode("-", $_POST["submit"], 2);

            if ($submit[0] == "usunpotwierdzone") {
              $query = "DELETE FROM contacts WHERE id = " . $submit[1];
              if ($conn->query($query) == TRUE) {
                echo ('<div class="alert alert-success mb-5" role="alert">Kontakt został usunięty z bazy danych.</div>');
              } else {
                echo ('<div class="alert alert-danger mb-5" role="alert">Błąd usuwania kontaktu z bazy danych: ' . $conn->error . '</div>');
              }
            } else if ($submit[0] == "zapisz") {
              $uploadOk = 1;
              if (is_uploaded_file($_FILES["image"]["name"])) {
                $target_file = IMAGES_PATH . basename($_FILES["image"]["name"]);

                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                $check = getimagesize($_FILES["image"]["tmp_name"]);
                if ($check != false) {
                  $uploadOk = 1;
                } else {
                  echo ('<div class="alert alert-danger mb-5" role="alert">File is not an image.</div>');
                  $uploadOk = 0;
                }

                if (file_exists($target_file)) {
                  echo ('<div class="alert alert-danger mb-5" role="alert">Sorry, file already exists.</div>');
                  $uploadOk = 0;
                }

                if (
                  $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                ) {
                  echo ('<div class="alert alert-danger mb-5" role="alert">Sorry, only JPG, JPEG, PNG & GIF files are allowed.</div>');
                  $uploadOk = 0;
                }
                if ($uploadOk != 0) {
                  $pliknazwa = basename($_FILES["image"]["name"]);
                  $query_backwards = $query_backwards . ", '" . $pliknazwa . "'";
                  $query = $query . ', image';
                }
              } else {
                $uploadOk = 0;
              }
              if ($uploadOk != 0) {
                $basename = basename($_FILES["image"]["name"]);
                if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                  echo ('<div class="alert alert-danger mb-5" role="alert">Wystąpił błąd przesyłania pliku.</div>');
                }
              } else {
                $basename = null;
              }
              $query = "UPDATE contacts SET first_name = '" . $_POST["first_name"] . "', last_name = '" . $_POST["last_name"] . "', phone_number = '" . $_POST["phone_number"] . "', email = '" . $_POST["email"] . "', address = '" . $_POST["address"] . "', image = '" . $basename . "' WHERE id = " . $submit[1];
              if ($conn->query($query) == TRUE) {
                echo ('<div class="alert alert-success mb-5" role="alert">Kontakt został pomyślnie zaaktualizowany.</div>');
              } else {
                echo ('<div class="alert alert-danger mb-5" role="alert">Błąd usuwania kontaktu z bazy danych: ' . $conn->error . '</div>');
              }
            } else {

              $query = 'SELECT id, first_name, last_name, phone_number, email, address, image FROM contacts WHERE id = ' . $submit[1];
              $result = $conn->query($query);
              $row = $result->fetch_array(MYSQLI_ASSOC);

              if ($submit[0] ==  "usun") {
                echo ('
                <h3>Na pewno chcesz usunąć kontakt "' . $row["first_name"] . ' ' . $row["last_name"] . '"?</h3>
                <button type="submit" name="submit" id="submit" value="usunpotwierdzone-' . $submit[1] . '" class="btn btn-danger btn-lg">Tak, usuń</button></th>
                <br /><br /><br />
              ');
              }

              echo ('
          <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Imię</th>
              <th scope="col">Nazwisko</th>
              <th scope="col">Numer telefonu</th>
              <th scope="col">Email</th>
              <th scope="col">Adres</th>
              <th scope="col">Zdjęcie</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope="row">1</th>
          ');

              if ($submit[0] == "edytuj") {
                echo ('
            <td><input type="text" class="form-control" name="first_name" id="first_name" value="' . $row["first_name"] . '" required autofocus /></td>
            <td><input type="text" class="form-control" name="last_name" id="last_name" value="' . $row["last_name"] . '" /></td>
            <td><input type="tel" class="form-control" name="phone_number" id="phone_number" value="' . $row["phone_number"] . '" /></td>
            <td><input type="email" class="form-control" name="email" id="email" value="' . $row["email"] . '" /></td>
            <td><input type="text" class="form-control" name="address" id="address" value="' . $row["address"] . '" /></td>
            <td><input type="file" accept="image/*" class="form-control" name="image" id="image" /><img src="./images/' . $row["image"] . '" alt="Zdjęcie kontaktu" /></td>
          ');
                echo ('
                  <td>
                    <button type="submit" name="submit" id="submit" formenctype="multipart/form-data" value="zapisz-' . $submit[1] . '" class="btn btn-primary">
                      Zapisz
                    </button>
                  </td>
                ');
              } else {
                echo ('
            <td>' . $row["first_name"] . '</td>
            <td>' . $row["last_name"] . '</td>
            <td>' . $row["phone_number"] . '</td>
            <td>' . $row["email"] . '</td>
            <td>' . $row["address"] . '</td>
            <td><img src="./images/' . $row["image"] . '" alt="Zdjęcie kontaktu" /></td>
          ');
              }

              echo ('
              </tr>
              </tbody>
              </table>
            ');
            }
          }
          ?>
        </form>
      </div>
    </div>
  </main>
</body>

</html>