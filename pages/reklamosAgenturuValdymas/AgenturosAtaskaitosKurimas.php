<!DOCTYPE html>
<html>
    <?php
        include '../../phpUtils/renderHead.php';
        include '../../phpScripts/reklamosAgenturuValdymas.php';
    ?>
        <link rel='stylesheet' href='../../styles/forms.css'>
    </head>
    <body>
        <div id="app">
            <?php include '../../phpUtils/renderNavigation.php'; ?>

            <h1>Agentūros ataskaitos kūrimas</h1>
            <p class="status-msg-error"></p>

            <div class="form-wrapper">
                <form method="post" id="report_form" onsubmit="return handleSubmit(event);">
                    <div>
                        <label for="date_start">Užregistruotų reklamų sudarymo datos pradžia:</label><br>
                        <input type="date" id="date_start" name="date_start" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div>
                        <label for="date_end">Užregistruotų reklamų sudarymo datos pabaiga:</label><br>
                        <input type="date" id="date_end" name="date_end" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div>
                        <label for="stazas_start">Mažiausias darbuotojo darbo stažas (nebūtina nurodyti):</label><br>
                        <input name='stazas_start' id="stazas_start" type='number' min="0" step="0.1">
                    </div>	
                    <div>
                        <label for="stazas_end">Didžiausias darbuotojo darbo stažas (nebūtina nurodyti):</label><br>
                        <input name='stazas_end' id="stazas_end" type='number' min="0" step="0.1">
                    </div>	
                    <div>
                        <input type="submit" name="report_form" value="Kurti ataskaitą" class="form-submit-button">
                    </div>
                </form>
            </div>
        </div>

        <script>
            const app = new Vue({el: '#app'});

            const handleSubmit = (e) => {
                e.preventDefault();
                const date_start = document.getElementById("date_start").value;
                const date_end = document.getElementById("date_end").value;
                let stazas_start = document.getElementById("stazas_start").value;
                let stazas_end = document.getElementById("stazas_end").value;

                // optional values
                if (stazas_start === "") {
                    stazas_start = -1;
                }
                if (stazas_end === "") {
                    stazas_end = -1;
                }

                // check if dates are correct
                const date1 = new Date(date_start);
                const date2 = new Date(date_end);
                let error_msg = "";

                if (date1.getTime() > date2.getTime()) {
                    error_msg += "*Pradžios data negali būti didesnė už pabaigos datą.<br>";
                }

                // check if stazas is correct (if entered)
                if (stazas_start >= 0 && stazas_end >= 0 && stazas_start > stazas_end) {
                    error_msg += "*Mažiausias darbo stažas negali būti didesnis už nurodytą didžiausią darbo stažą.<br>";
                }

                if (error_msg !== "") {
                    // show error
                    const error_msg_tag = document.getElementsByClassName("status-msg-error")[0];
                    error_msg_tag.innerHTML = error_msg;
                } else {
                    // redirect
                    const url_params = `date_start=${date_start}&date_end=${date_end}&stazas_start=${stazas_start}&stazas_end=${stazas_end}`;
                    window.location.href = `/isp/pages/reklamosAgenturuValdymas/agenturosVeiklosAtaskaita.php?${url_params}`;
                }
            };
        </script>
    </body>
</html>
