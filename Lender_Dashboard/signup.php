 <?php

include '../Functions/connect.php';
if (isset($_POST['submit'])){

$username=$_POST['username'];
$email=$_POST['email'];
$phone=$_POST['phonenumber'];
$id=$_POST['ID_Number'];
$password=$_POST['password'];
$confirmpassword=$_POST['confirmpassword'];
$check_query = "SELECT * FROM lender_reg WHERE email='$email' OR phonenumber='$phone' OR ID_Number='$id'";
$check_result = mysqli_query($conn, $check_query);
if (mysqli_num_rows($check_result) > 0) {
   if ($row = mysqli_fetch_assoc($check_result)) {
    if ($row['email'] == $email) {
      echo "<p style='color:red;'>Email already exists</p>";
    }
    if ($row['phonenumber'] == $phone) {
      echo "<p style='color:red;'>Phone number already exists</p>";
    }
    if ($row['ID_Number'] == $id) {
      echo "<p style='color:red;'>ID Number already exists</p>";
    }
  }
}
else{
$statement= $conn->prepare("INSERT into lender_reg (username,email,phonenumber,ID_Number,password,confirmpassword) VALUES (?,?,?,?,?,?)");
$statement->bind_param("ssiiss",$username,$email,$phone,$id,$password,$confirmpassword);
$statement->execute();
echo "<p style='color:green;'>Successfully Registered!</p>";
$statement->close();
$conn->close();
echo "
  <script>
    location.replace('login.php');
  </script>
";

}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="signup.css">
    <title>Create account</title>
    
</head>
<body>
    <section>
        <div class="container">
            <div class="promotext">
                <h3>Lend,Loan with Us</h3>
                <h2>Welcome back!</h2>
                
                <p>Your One time Financial Solution </p>
             </div>
             <div class="login">
                <h4>Create Your Account</h4>
                <div class="logbox">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                     <div class="inputbox">
                            <input type= "text" required placeholder ="Username" name="username">
                            <span></span>
                        </div>   
                        <div class="inputbox">
                            <input type="email"  required placeholder="Email" name="email">
                            <span></span>
                        </div>   
                        <div class="inputbox">
                            <input type="text" required placeholder="ID Number" name="ID_Number">
                            <span></span>
                        </div>   
                        <div class="inputbox">
                            <input type="text"  required placeholder="Phone Number" name="phonenumber">
                            <span></span>
                        </div>   
                        <div class="inputbox">
                            <input type="password"  required placeholder="Password" name="password">
                            <span></span>
                        </div>
                        <div class="inputbox">
                            <input type="password" required placeholder="Confirm Password" name="confirmpassword">
                            <span></span>
                        </div>
                        <div class="select">
                        <select type="user_type" onchange="location=this.value;">
                            <option value="Lender">Lender</option>
                            <option value="../Agent_Dashboard/signup.php">Agent</option>
                            <option value="../Customer_Dashboard/customersignup.php">Customer</option>
                        </select>   
                    
                    </div>
                    <button type="submit" class="button" name="submit">Submit</button> 

                    <p>Already have an Account? <a href="login.php">Login Now</a></p>
                </div>
            </form>    
                    
                       
                </div>
                <div class="bcgimage"></div>

             </div>



        </div>
    </section>
    
</body>
</html>