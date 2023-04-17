
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Home</title>
<meta name="description" content="">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,700,300&amp;subset=latin,latin-ext" rel="stylesheet" type="text/css">
<link href="http://fonts.googleapis.com/css?family=Raleway:700,400,300" rel="stylesheet" type="text/css">
</head>
<body>

<form method="post" action="index.php" enctype="multipart/form-data">
<input type="hidden" name="form_signup" />
<h4>Sign Up Form</h4>
Username: <input type="text" name="username" required><br><br>
Password: <input type="password" name="password" required><br><br>
eMail: <input type="text" name="email" required><br><br>
Gender: <select name="gender" required><option value="male">Male</option><option value="female">Female</option></select><br><br>
Agree to the terms? <input type="checkbox" name="agree" required><br><br>
Image MAX Size(1 MB) <input type="file" name="fileToUpload" ><br><br>
<input type="submit" value="Register" />
</form>

<?php
function ValidateEmail($email)
{
	return preg_match("/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/", $email);
}
function HashPassword($password)
{
    return strtoupper(md5($password));
}
$errors = array();
$target_dir = 'uploads/';
$uploadOk = 1;

$form = isset($_POST['form_signup']);
if($form)
{
	$username = htmlspecialchars(htmlentities(strip_tags(trim($_POST['username'])), ENT_QUOTES, "UTF-8"), ENT_QUOTES);
	$pw = htmlspecialchars(htmlentities(strip_tags(trim($_POST['password'])), ENT_QUOTES, "UTF-8"), ENT_QUOTES);
	$email = htmlspecialchars(htmlentities(strip_tags(trim($_POST['email'])), ENT_QUOTES, "UTF-8"), ENT_QUOTES);
	$gender = htmlspecialchars(htmlentities(strip_tags(trim($_POST['gender'])), ENT_QUOTES, "UTF-8"), ENT_QUOTES);
    $password = HashPassword($pw);
    $target_file = $target_dir . basename($_FILES['fileToUpload']['name']);
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $check = getimagesize($_FILES['fileToUpload']['tmp_name']);
    $image = $_FILES['fileToUpload']['name'];
    if(strlen($username) < 8)
    {
        $errors[] = 'Invalid Username, Username cannot be less than 8 characters';
    }
    if(strlen($password) < 6)
    {
        $errors[] = 'Invalid Password, Password cannot be less than 6 characters';
    }
    if(!ValidateEmail($email))
    {
        $errors[] = 'Invalid email, please re-enter a valid one.';
    }
    if($check == false)
    {
        $errors[] = "File is not an image.";
        $uploadOk = 0;
    }
    if(count($errors) == 0)
    {
        $dbhost = "localhost";
        $dbuser = "root";
        $dbpw = "";
          $conn = new PDO("mysql:host=$dbhost;dbname=Nehal", $dbuser, $dbpw);
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

          $dbh = $conn->prepare('INSERT INTO users(username,password,email,gender,image) VALUES(?,?,?,?,?)');
          $dbh->bindParam(1, $username);
          $dbh->bindParam(2, $password);
          $dbh->bindParam(3, $email);
          $dbh->bindParam(4, $gender);
          $dbh->bindParam(5, $image);
          $execute = $dbh->execute();
            if($execute)
            {
                echo "Register Successful";
            }
            else
            {
                $errors[] = 'Unsuccessfull';
            }

        }
        else
        {
            foreach($errors as $error)
            {
                echo "<p>$error</p><br>";
            }
        }

}

?>

</body>
</html>