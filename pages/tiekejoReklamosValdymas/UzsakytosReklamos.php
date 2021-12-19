<!DOCTYPE html>
<html>
<?php
include '../../phpUtils/renderHead.php';
include '../../phpScripts/tiekejoReklamosValdymas.php';
include '../../phpUtils/startSession.php';
if ($_SESSION['username_login']=="")
{
    header("Location:../../index.php");
    exit();
}
$error = "";
$success = "";
?>
<link rel='stylesheet' href='../../styles/forms.css'>
</head>
<body>
<div id="app">
    <navigation usertype="<?php echo $_SESSION['ulevel'];?>"> </navigation>

    <h1>Užsakytų reklamų sąrašas</h1>

    <?php
    if ($error != "") {
        echo "<p class='status-msg-error'>".$error."</p>";
    }
    else if ($success != "") {
        echo "<p class='status-msg-success'>".$success."</p>";
    }

    $result = db_get_all_ordered_ads();
    if ($result->num_rows == 0) {
        echo "<h4>Nėra nei vienos reklamos.</h4>";
        die();
    }
    ?>

    <table id='data-table'>
        <tr>
            <th>ID</th>
            <th>Kaina</th>
            <th>Užsakymo sudarymo data</th>
            <th>Užsakymo sudarymo pabaigos data</th>
            <th>Būsena</th>
            <th>Užsakovo slapyvardis</th>
        </tr>

        <?php
        while($row = mysqli_fetch_assoc($result))
        {
            $id = $row['nr'];
            echo "  <tr class='table-filter-row'>
                                                <td>".$id."</td>
                                                <td>".$row['kaina']."</td>
                                                <td>".$row['sudarymo_data']."</td>
                                                <td>".$row['pabaigos_data']."</td>
                                                <td>".$row['busena']."</td>
                                                <td>".$row['fk_uzsakovo_slapyvardis']."
                                    <td class='td-remove-entry'>
                                        <button type='button' class='td-remove-entry__button' onclick='redirect($id);'>
                                            Redaguoti
                                        </button>
                                    </td>
                                </tr>
                                <div class='form-submit-wrapper wrapper-id-".$id."'>
                                    <div class='form-submit-wrapper__content'>
                                        <h3>Ar tikrai norite iš agentūros pašalinti darbuotoją?</h3>
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
        window.location.href = `/isp/pages/tiekejoReklamosValdymas/uzsakytosReklamosValdymas.php?id=${id}`;
    };
</script>
</body>
</html>
