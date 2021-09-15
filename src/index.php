<?php
include("config.php");
session_start();
?>

<html>

<head>
  <title>Książka adresowa - strona główna</title>
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
            <a class="nav-link active" aria-current="page" href="./">Strona główna</a>
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
        
      </div>
    </div>
  </main>
</body>

</html>