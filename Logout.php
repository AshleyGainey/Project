        <?php
        if (!isset($_SESSION)) {
                @ob_start();
                session_start();
        }

        unset($_SESSION['userID']);
        unset($_SESSION['userEmail']);
        unset($_SESSION['userFirstName']);

        header('Location: index.php');

        ?>