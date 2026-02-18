<?php
$conn = new mysqli("localhost","root","","angeles_electric_db");

$password = password_hash("123456", PASSWORD_DEFAULT);

$sql = "INSERT INTO users (account_number,name,email,password,dob,address,contact_no)
VALUES (
'123456789',
'Jeon Jungkook',
'jeon.jungkook@gmail.com',
'$password',
'1997-09-01',
'1234 Celina St., Angeles City, Pampanga',
'09678431245'
)";

if($conn->query($sql)){
    echo "User Created Successfully!";
} else {
    echo "Error: " . $conn->error;
}
?>
