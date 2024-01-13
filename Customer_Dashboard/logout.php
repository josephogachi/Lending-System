<?php 
session_start();

    if(!isset($_SESSION['user'])){

        
        unset($_SESSION['user']);       
        session_destroy();
        echo " 
    <script>
        alert('You will be logged out');
        window.location.replace('login.php');                
    </script>
    
    ";
    }
    else {
        unset($_SESSION['user']);       
        session_destroy();
        echo "
    <script>
        alert('You will be logged out');
        window.location.replace('login.php');                
    </script>
    
    ";
    }

 
    echo "
    <script>
        alert('You will be logged out');
        window.location.replace('login.php');                
    </script>
    ";
// }


?>