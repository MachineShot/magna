<!DOCTYPE html>
<html>
    <?php include '../../phpUtils/renderHead.php'; ?>
    </head>
    <body>
        <div id="app">
            <navigation usertype="<?php echo $usertype;?>"></navigation>

            {{ message }}
            <br>
            <?php echo "2. If you see this then PHP works." ?>

            <h1>Registracijos langas.</h1>
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
    </body>
</html>
