<!DOCTYPE html>
<html>
    <?php include '../../phpUtils/renderHead.php';
    
    if ($_SESSION['username_login']=="")
        {
            header("Location:../../index.php");
            exit();
        }
        
        include '../../phpScripts/piniguPosistemesValdymas.php';
    ?>
    <link rel='stylesheet' href='../../styles/forms.css'>
    </head>
    <body>
        <div id="app">
            <navigation usertype="<?php echo $_SESSION['ulevel'];?>"></navigation>

            <h1>Mokejimo langas.</h1>
            <?php
            $result = db_get_all_payment_method_cardNum(); 
            if ($result->num_rows == 0) {
                echo "<h4>Jūs neturite nei vieno mokėjimo būdo.</h4>
                <form action='/isp'>
                    <button class='td-remove-entry__button' type='submit'>Atgal į pagrindinį puslapį</button>
                </form>";
                die();
            }?>
            <form id="form1" name="form1" method="post">  
            <select name='card' style='width: 400px; font-size:25px; border-radius: 0px; border: 2px solid black'> 
                
                <option value="Select" style="text-align: center">Pasirinkite mokėjimo kortele</option>  
                <?php
                while($row = mysqli_fetch_assoc($result)){
                    echo"<option>".$row['korteles_nr'];
                }
                  ?>
            </select> 
            </form> 
            <button class="form-submit-button form-submit-button--green" onclick="moketi()"> Mokėti </button>
        </div>

        <script src="../../components/navigation.js"></script>
        <script>
            const app = new Vue({
                el: '#app',
                data: {
                    message: '1. If you see this then Vue works.'
                }
            });
        </script>
        <script src = "https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
        <script>
            function moketi(){
                Swal.fire({
        							icon: "success",
        							title: "Mokėjimas atliktas",
        							showConfirmButton: false,
        							timer: 2500,
                                    timerProgressBar: true,
                                    position: 'top'
        						})
            }
            </script>
    </body>
</html>
