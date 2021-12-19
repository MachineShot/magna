<!DOCTYPE html>
<html>
<?php
include '../../phpUtils/renderHead.php';
include '../../phpScripts/tiekejoReklamosValdymas.php';
$error = "";
$success = "";

    if ($_POST != null) {
    $id = $_POST['id'];

    db_remove_ad_provider($id);
    $success = "Sėkmingai pašalinta reklama";
    }
?>
<link rel='stylesheet' href='../../styles/forms.css'>
</head>
<body>
<div id="app">
    <navigation usertype="<?php echo $_SESSION['ulevel'];?>"> </navigation>

    <h1>Siūlomų reklamų sąrašas</h1>

    <?php
    if ($error != "") {
        echo "<p class='status-msg-error'>".$error."</p>";
    }
    else if ($success != "") {
        echo "<p class='status-msg-success'>".$success."</p>";
    }

    $result = db_get_all_provided_ads();
    if ($result->num_rows == 0) {
        echo "<h4>Nėra nei vienos reklamos.</h4>";
        die();
    }
    ?>

    <table id='data-table'>
        <tr>
            <th>ID</th>
            <th>Kaina</th>
            <th>Pavadinimas</th>
            <th>Užsakymo sudarymo data</th>
            <th>Užsakymo sudarymo pabaigos data</th>
            <th>Aktyvumas</th>
        </tr>

        <?php
        while($row = mysqli_fetch_assoc($result))
        {
            $id = $row['id'];
            echo "  <tr class='table-filter-row'>
                                                <td>".$id."</td>
                                                <td>".$row['kaina']."</td>
                                                <td>".$row['pavadinimas']."</td>
                                                <td>".$row['sudarymo_data']."</td>
                                                <td>".$row['galiojimo_laikotarpis']."</td>
                                                <td>".$row['aktyvi']."</td>
                                    <td class='td-remove-entry'>
                                        <form method='post' id='remove_employee_form".$id."'>
                                            <input name='id' type='hidden' value='$id'>
                                            <button type='button' class='td-remove-entry__button' onclick='toggleFormSubmit($id);'>
                                                Šalinti
                                            </button>
                                        </form>
                                    </td>
                                    <td class='td-remove-entry'>
                                        <button type='button' class='td-remove-entry__button' onclick='redirect($id);'>
                                            Redaguoti
                                        </button>
                                    </td>
                                </tr>
                                <div class='form-submit-wrapper wrapper-id-".$id."'>
                                    <div class='form-submit-wrapper__content'>
                                        <h3>Ar tikrai norite pašalinti reklamą</h3>
                                        <input class='form-submit-button form-submit-button--green' type='submit' form='remove_employee_form".$id."' value='Patvirtinti' onclick='toggleFormSubmit($id);'>
                                        <input class='form-submit-button form-submit-button--red' type='button' value='Atšaukti' onclick='toggleFormSubmit($id)'>
                                    </div>
                                </div>
                            ";
        }
        ?>
    </table>
</div>

<script src="../../components/navigation.js"></script>
<script>
    const app = new Vue({el: '#app'});
    const redirect = (id) => {
        window.location.href = `/isp/pages/tiekejoReklamosValdymas/siulomosReklamosValdymas.php?id=${id}`;
    };

    const toggleFormSubmit = (id) => {
        const wrapper = document.getElementsByClassName(`wrapper-id-${id}`)[0];
        wrapper.classList.toggle("form-visible");
    };
</script>
</body>
</html>
