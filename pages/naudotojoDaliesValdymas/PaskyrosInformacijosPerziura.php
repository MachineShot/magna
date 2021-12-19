<!DOCTYPE html>
<html>
<?php
    include '../../phpScripts/naudotojoDaliesValdymas.php';
    $error = "";
    $success = "";
    include '../../phpUtils/startSession.php';
    if ($_SESSION['username_login']=="")
    {
       header("Location:../../index.php");
       exit();
    }
    inisession("part");
?>
<link rel="stylesheet" href="../../styles/InputForm.css" />
<body>
    <div id="app" style="padding: 0;">
        <navigation usertype="<?php echo $_SESSION['ulevel'];?>"> </navigation>
        <link rel="stylesheet" href="../../styles/forms.css" />
        <h2 style="text-align: center;">Jūsų paskyros informacija</h2>
    </div>
    <div class="card">
        <?php
            if ($error != "") {
            echo "<p class='status-msg-error'>".$error."</p>";
            }
            else if ($success != "") {
            echo "<p class='status-msg-success'>".$success."</p>";
            }
            $result = db_get_user_information();
            if ($result->num_rows == 0) {
            echo "<h4>Informacija nerasta.</h4>";
            die();
            }
            $row = mysqli_fetch_assoc($result);
        ?>
          <p class="title">Slapyvardis: <?php echo $row['slapyvardis']; ?></p>
          <p class="title">Vardas:<?php echo $row['vardas']; ?></p>
          <p class="title">Pavardė:<?php echo $row['pavarde']; ?></p>
          <p class="title">El. paštas:<?php echo $row['email']; ?></p>
          <p class="title">Telefono numeris:<?php echo $row['tel_nr']; ?></p>
          <p class="title">Gimimo data:<?php echo $row['gimimo_data']; ?></p>
          <p>
          <button onclick="location.href = 'PaskyrosInformacijosKeitimas.php';">Keisti informaciją</button>
          <button onclick="location.href = 'PaskyrosSlaptazodzioKeitimas.php';">Keisti slaptažodį</button>
          <button class="button" onclick="location.href = 'PaskyrosTrynimas.php';">Pašalinti paskyrą</button>
          </p>
    </div>
      <script src="../../components/navigation.js"> </script>
      <script src="../../components/navigation.js"> </script>
      <script>
        const app = new Vue({
          el: '#app'}
                           );
      </script>
</html>
