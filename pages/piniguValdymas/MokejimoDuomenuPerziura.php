<!DOCTYPE html>
<html>
    <?php include '../../phpUtils/renderHead.php'; 
        if ($_SESSION['username_login']=="")
        {
            header("Location:../../index.php");
            exit();
        }
    include '../../phpScripts/piniguPosistemesValdymas.php'; 

        $error = "";
        $success = "";

        

        if ($_POST != null) {
            echo var_dump($_POST);
            if(isset($_POST['remove'])){
                $id = $_POST['id'];
            
                $did_remove = db_remove_payment_method($id);
                if ($did_remove) {
                    $success = "Sėkmingai pašalintas mokėjimo būdas.";
                }
                else {
                    $error = "*Nepavyko.";
                }
            }
            if(isset($_POST['edit'])){
                $id = $_POST['edit'];
                $cardNum = $_POST['cardNum'];
                $cvv = $_POST['cvv'];
                $date = $_POST['date'];
                $address = $_POST['address'];
                $city = $_POST['city'];
                $postal_code = $_POST['postal_code'];
                echo $cardNum;
                db_update_payment_info($id, $cardNum, $cvv, $date, $address, $city, $postal_code);
            }
        }
    ?>
    <link rel='stylesheet' href='../../styles/forms.css'>
    </head>
    <body>
        <div id="app">
            <navigation usertype="<?php echo $usertype;?>"></navigation>
            <?php 
            
            // $cardNum = "";
            // $cvv = "";
            // $date = "";
            // $address = "";
            // $city = "";
            // $postal_code = "";

            ?>
            <h1>Mokejimu duomenu perziura</h1>
            <?php
                if ($error != "") {
                    echo "<p class='status-msg-error'>".$error."</p>";
                }
                else if ($success != "") {
                    echo "<p class='status-msg-success'>".$success."</p>";
                }

                $result = db_get_all_payment_methods();
                if ($result->num_rows == 0) {
                    echo "<h4>Jūs neturite nei vieno mokėjimo būdo.</h4>
                    <form action='/isp'>
                        <button class='td-remove-entry__button' type='submit'>Atgal į pagrindinį puslapį</button>
                    </form>";
                    die();
                }
            ?>
            <table id='data-table'>
                <tr>
                    <th>Korteles nr.</th>
                    <th>CVV</th>
                    <th>Vardas Pavardė</th>
                    <th>Galiojimo data</th>
                    <th>Adresas</th>
                    <th>Miestas</th>
                    <th>Pasto kodas</th>
                </tr>
         <!-- (`korteles_nr`, `cvv`, `galiojimo_data`, `vardas`, `pavarde`, `adresas`, `miestas`, `pasto_kodas`, `fk_naudotojo_slapyvardis`) -->
                
                <?php
                    while($row = mysqli_fetch_assoc($result)){
                    
                        $id = $row['id'];
                        $edit = $row['id'];
                        echo "  <tr class='table-filter-row'>
                                    <td>".$row['korteles_nr']."</td>
                                    <td>".$row['cvv']."</td>
                                    <td class='table__vardas-pavarde'>".$row['vardas'].' '.$row['pavarde']."</td>
                                    <td>".$row['galiojimo_data']."</td>
                                    <td>".$row['adresas']."</td>
                                    <td>".$row['miestas']."</td>
                                    <td>".$row['pasto_kodas']."</td>
                                    <td class='td-remove-entry'>
                                        <form method='post' id='remove_employee_form".$id."'>
                                            <input name='remove' type='hidden' value='$id'>
                                            <button type='button' class='td-remove-entry__button' onclick='toggleFormSubmit($id);'>
                                                Šalinti
                                            </button>
                                        </form>
                                    </td>
                                    <td class='td-remove-entry'>
                                        <form method='post' id='edit_form'>
                                        <input name='edit' type='hidden' value='$edit'>
                                        <button type='button' class='td-remove-entry__button' onclick='toggleFormSubmit(0);'>
                                            Redaguoti
                                        </button>
                                        </form>
                                    </td>
                                </tr>
                                <div class='form-submit-wrapper wrapper-id-".$id."'>
                                    <div class='form-submit-wrapper__content'>
                                        <h3>Ar tikrai norite pašalinti mokėjimo būdą?</h3>
                                        <input class='form-submit-button form-submit-button--green' type='submit' form='remove_employee_form".$id."' name='remove' value='Patvirtinti' onclick='toggleFormSubmit($id);'>
                                        <input class='form-submit-button form-submit-button--red' type='button' value='Atšaukti' onclick='toggleFormSubmit($id)'>
                                    </div>
                                </div>
                                <div class='form-submit-wrapper wrapper-id-0'>
                                    <div class='form-submit-wrapper__content'>
                                        <h3>Redaguoti mokėjimo būdo duomenis</h3>
                                        <input class='form-submit-button' type='text' form='edit_form' name='cardNum'  placeholder='Iveskite korteles nr.'>
                                        <input class='form-submit-button' type='text' form='edit_form' name='cvv' placeholder='Iveskite korteles cvv'>
                                        <input class='form-submit-button' type='date' form='edit_form' name='date'>
                                        <input class='form-submit-button' type='text' form='edit_form'  name='address' placeholder='Iveskite Adresa'>
                                        <input class='form-submit-button' type='text' form='edit_form'  name='city' placeholder='Iveskite Miesta'>
                                        <input class='form-submit-button' type='text' form='edit_form'  name='postal_code' placeholder='Iveskite Pasto koda'>
                                        <input class='form-submit-button form-submit-button--green' type='submit' form='edit_form' value='Patvirtinti' onclick='toggleFormSubmit(0);'>
                                        <input class='form-submit-button form-submit-button--red' type='button' value='Atšaukti' onclick='toggleFormSubmit(0)'>
                                    </div>
                                </div>
                            ";
                    }
                    
                ?>
        </div>

        <script src="../../components/navigation.js"></script>
        <script>
            const app = new Vue({
                el: '#app',
                data: {
                    message: '1. If you see this then Vue works.'
                }
            });
            const toggleFormSubmit = (id) => {
                const wrapper = document.getElementsByClassName(`wrapper-id-${id}`)[0];
                wrapper.classList.toggle("form-visible");
            };
        </script>
    </body>
</html>
