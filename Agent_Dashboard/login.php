<?php
include '../Functions/connect.php';
session_start();

 if (isset($_POST['submit'])){
	$user_query = mysqli_query($conn,"select * from agent_reg where email='{$_POST["email"]}' and password='{$_POST["password"]}'");
	$user_data = mysqli_fetch_assoc($user_query);
  
	if(empty($user_data)){
	  echo "user not found";
	}else{
	  $_SESSION["user"] = $user_data['email'];
	  echo("
		<script>
		  window.location.replace('agent.php');
		</script>
	  ");
	}
  
  }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Lender_Dashboard/signup.css">

    <title>login</title>
</head>
<body>
    <section>
        <div class="container">
            <div class="promotext">
                <h3>Welcome</h3>
                <h3>Back</h3>
                <p>Your One time Financial Solution</p>
             </div>
             <div class="login2">
                <h4>Create Your Account</h4>
                <div class="logbox">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">

                <div class="inputbox">
                            <input type="email" required placeholder="Email" name="email">
                            <span></span>
                        </div>   
                        
                        </div>   
                        <div class="inputbox">
                            <input type="password" required placeholder="Password" name="password">
                            <span></span>
                        </div>
                       
                    
                        <button type="submit" class="button" name="submit">Submit</button> 

                        <p>Don't have an Account? <a href="signup.php">Signup Now</a></p>
                   
                    </form>
                </div>   
                       
                </div>
                <div class="bcgimage"></div>

             </div>



        </div>
    </section>
    
    
</body>
</html>