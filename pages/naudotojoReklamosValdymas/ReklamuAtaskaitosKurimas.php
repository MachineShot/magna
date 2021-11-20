<!DOCTYPE html>
<html>
    <?php include '../../phpUtils/renderHead.php'; ?>
    <body>
        <div id="app">
            <?php include '../../phpUtils/renderNavigation.php'; ?>

            {{ message }}
            <br>
            <?php echo "2. If you see this then PHP works." ?>

            <h1>Reklamu ataskaitos kurimo langas.</h1>
            <ul>
              <li><a href="./reklamuAtaskaita.php">Reklamų ataskaitos langas</a></li>
            </ul>
        </div>

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
