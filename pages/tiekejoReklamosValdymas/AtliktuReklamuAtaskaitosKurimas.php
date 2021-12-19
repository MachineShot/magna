<!DOCTYPE html>
<html>
<?php
include '../../phpUtils/renderHead.php';
include '../../phpScripts/tiekejoReklamosValdymas.php';
?>
<link rel='stylesheet' href='../../styles/forms.css'>
</head>
<body>
<div id="app">
    <navigation usertype="<?php echo $usertype;?>"></navigation>
    <?php
    $date_today = date('Y-m-d');
    $date_tomorrow = date('Y-m-d', strtotime($date_today . " +1 days"));
    ?>

    <h1>Reklamų ataskaitos kūrimas</h1>
    <p class="status-msg-error"></p>

    <div class="form-wrapper">
        <form id="report_form" onsubmit="return handleSubmit(event);">
            <div>
                <label for="date_start">Užsakymų pradžios data (nuo):</label><br>
                <input type="date" id="date_start" name="date_start" value="<?php echo $date_today; ?>" required>
            </div>
            <div>
                <label for="date_end">Užsakymų pabaigos data (iki):</label><br>
                <input type="date" id="date_end" name="date_end" value="<?php echo $date_tomorrow; ?>" required>
            </div>
            <div>
                <label for="price_start">Mažiausia mėnesinė kaina (nebūtina nurodyti):</label><br>
                <input name='price_start' id="price_start" type='number' min="0" step="0.1">
            </div>
            <div>
                <label for="price_end">Didžiausia mėnesinė kaina (nebūtina nurodyti):</label><br>
                <input name='price_end' id="price_end" type='number' min="0" step="0.1">
            </div>
            <div>
                <input type="submit" name="report_form" value="Kurti ataskaitą" class="form-submit-button">
            </div>
        </form>
    </div>
</div>

<script src="../../components/navigation.js"></script>
<script>
    const app = new Vue({el: '#app'});

    const handleSubmit = (e) => {
        e.preventDefault();
        const date_start = document.getElementById("date_start").value;
        const date_end = document.getElementById("date_end").value;
        let price_start = document.getElementById("price_start").value;
        let price_end = document.getElementById("price_end").value;

        // optional values
        if (price_start === "") {
            price_start = 0;
        }
        if (price_end === "") {
            price_end = 0;
        }

        // check if dates are correct
        const date1 = new Date(date_start);
        const date2 = new Date(date_end);
        let error_msg = "";

        if (date1.getTime() > date2.getTime()) {
            error_msg += "*Pradžios data negali būti didesnė už pabaigos datą.<br>";
        }

        if (date_start === date_end) {
            error_msg += "*Pradžios data negali būti tokia pati kaip pabaigos data.<br><br>";
            error_msg += "Jei norite matyti tik nustatytos dienos duomenis, pabaigos datai parinkite sekančią dieną.<br>";
        }

        // check if peice is correct (if entered)
        if (price_start >= 0 && price_end >= 0 && price_start > price_end) {
            error_msg += "*Mažiausias darbo stažas negali būti didesnis už nurodytą didžiausią darbo stažą.<br>";
        }

        if (error_msg !== "") {
            // show error
            const error_msg_tag = document.getElementsByClassName("status-msg-error")[0];
            error_msg_tag.innerHTML = error_msg;
        } else {
            // redirect with url parameters
            const url_params = `date_start=${date_start}&date_end=${date_end}&price_start=${price_start}&price_end=${price_end}`;
            window.location.href = `/isp/pages/tiekejoReklamosValdymas/AtliktuReklamuAtaskaita.php?${url_params}`;
        }
    };
</script>
</body>
</html>
