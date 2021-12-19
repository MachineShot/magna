<!DOCTYPE html>
<html>
<?php
    include './phpUtils/settings.php';
    include './phpUtils/renderHead.php';
    if (!isset($_SESSION['username_login']))
    {
        inisession("full");
    }
    $_SESSION['prev'] = "index";
?>
    <body>
        <div id="app">
            <navigation usertype="<?php echo $_SESSION['ulevel'];?>"></navigation>
            <?php echo $_SESSION['message'];?>

            <br>
        </div>


        <script src="./components/navigation.js"></script>
        <script>
            const app = new Vue({
                el: '#app',
            });
        </script>
    </body>
</html>
