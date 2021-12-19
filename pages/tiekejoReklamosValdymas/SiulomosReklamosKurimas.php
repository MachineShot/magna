<!DOCTYPE html>
<html>
<?php
include '../../phpUtils/renderHead.php';
include '../../phpScripts/tiekejoReklamosValdymas.php';

$error = "";
$success = "";
$kaina = "";
$pavadinimas = "";
$galiojimo_laikotarpis = "";
$aktyvi = "";
$puslapio_adresas = "";
$tipas = "";

$miestas = "";
$adresas = "";
$koordinates = "";
$dydis = "";

date_default_timezone_set('Europe/Vilnius');
$curr_date = date("Y-m-d");

if ($_POST != null) {

    $kaina = $_POST['kaina'];
    $pavadinimas = $_POST['pavadinimas'];
    $galiojimo_laikotarpis = $_POST['galiojimo_laikotarpis'];
    $aktyvi = $_POST['aktyvi'];
    $puslapio_adresas = $_POST['puslapio_adresas'];
    $tipas = $_POST['tipas'];
    $miestas = $_POST['miestas'];
    $adresas = $_POST['adresas'];
    $koordinates = $_POST['koordinates'];
    $dydis = $_POST['dydis'];

    if ($galiojimo_laikotarpis == "") {
        $error .= "*Privalote nurodyti pabaigos datą.<br>";
    }
    if($curr_date > $galiojimo_laikotarpis){
        $error .= "*Pradžios data negali būti didesnė už pabaigos datą.<br>";
    }
    if($curr_date > $galiojimo_laikotarpis){
        $error .= "*Pabaigos data negali būti mažesnė už dabartinę datą.<br>";
    }
    if ($kaina == "") {
        $error .= "*Privalote įrašyti kainą.<br>";
    }

    if ($pavadinimas == "") {
        $error .= "*Privalote įrašyti pavadinimą.<br>";
    }
    if ($aktyvi == "") {
        $error .= "*Privalote pasirinkti ar reklama aktyvi ar ne.<br>";
    }
    if($error === ""){
        db_add_new_ad($kaina, $pavadinimas, $aktyvi, $curr_date, $galiojimo_laikotarpis, $puslapio_adresas, $tipas, $miestas, $adresas, $koordinates, $dydis);
        $success = "Sėkmingai sukurta reklama.";
    }
}
?>
<link rel='stylesheet' href='../../styles/forms.css'>
</head>
<body>
<div id="app">
    <navigation usertype="<?php echo $usertype;?>"></navigation>

    <h1>Reklamos kūrimas</h1>

    <?php
    if ($error != "") {
        echo "<p class='status-msg-error'>".$error."</p>";
    }
    else if ($success != "") {
        echo "<p class='status-msg-success'>".$success."</p>";
    }

    //$start_date = date("Y-m-d\TH:i");
    //$end_date = $data['galiojimo_laikotarpis'];
    ?>
    <div class="form-wrapper">
        <form method="post" id="update_order_form">
            <div>
                <label for="kaina">Kaina:</label><br>
                <input name='kaina' id="kaina" type='number' step="any" required>
            </div>
            <div>
                <label for="pavadinimas">Pavadinimas:</label><br>
                <input name='pavadinimas' id="pavadinimas" type='text' required>
            </div>
            <div>
                <label for="galiojimo_laikotarpis">Užsakymo galiojimo pabaigos data:</label><br>
                <input name='galiojimo_laikotarpis' id="galiojimo_laikotarpis" type='datetime-local' value="<?php echo $curr_date; ?>" required>
            </div>
            <div>
                <label for="aktyvi">Pasirinkite ar reklama bus aktyvi:</label>

                <select name="aktyvi" id="aktyvi">
                    <option value="0">Neaktyvi</option>
                    <option value="1">Aktyvi</option>
                </select>
            </div>
            <div>
                <label for="puslapio_adresas">Puslapio adresas:</label><br>
                <input name='puslapio_adresas' id="puslapio_adresas" type='text' required>
            </div>
            <div>
                <label for="tipas">Pasirinkite reklamos tipą:</label>

                <select name="tipas" id="tipas">
                    <option value="animuota">Animuota</option>
                    <option value="statine">Statinė</option>
                </select>
            </div>
            <div>
                <label for="miestas">Fizinės reklamos miestas:</label><br>
                <input name='miestas' id="miestas" type='text'>
            </div>
            <div>
                <label for="adresas">Fizinės reklamos adresas:</label><br>
                <input name='adresas' id="adresas" type='text'>
            </div>
            <div>
                <label for="koordinates">Fizinės reklamos koordinatės:</label><br>
                <input name='koordinates' id="koordinates" type='text'>
            </div>
            <div>
                <label for="dydis">Fizinės reklamos dydis:</label><br>
                <input name='dydis' id="dydis" type='text'>
            </div>
            <div>
                <button type='button' class='form-submit-button' onclick='toggleFormSubmit()'>
                    Pateikti duomenis
                </button>
            </div>
        </form>
        <div class='form-submit-wrapper'>
            <div class='form-submit-wrapper__content'>
                <h3>Ar tikrai norite pateikti užsakymo duomenis?</h3>
                <input class='form-submit-button form-submit-button--green' type='submit' form='update_order_form' value='Patvirtinti' onclick='toggleFormSubmit();'>
                <input class='form-submit-button form-submit-button--red' type='button' value='Atšaukti' onclick='toggleFormSubmit();'>
            </div>
        </div>
    </div>
</div>

<script src="../../components/navigation.js"></script>
<script>
    const app = new Vue({el: '#app'});

    const toggleFormSubmit = () => {
        const wrapper = document.getElementsByClassName(`form-submit-wrapper`)[0];
        wrapper.classList.toggle("form-visible");
    };
</script>
</body>
</html>
