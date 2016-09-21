<?php
session_start();
if(null == $_POST['login_name'] || null == $_POST['login_pass'] || null == $_POST['login_code']){
echo "error";
 $_SESSION['is_login'] = false;
// echo "<script>window.location.href='login.php'</script>";
exit();
}

if( strtoupper($_POST['login_code']) != strtoupper($_SESSION['login_code'])){
    echo  "error validate code";
     $_SESSION['is_login'] = false;
    // echo "<script>window.location.href='login.php'</script>";
    exit();

}
$name = "dev1";
$passwd = "dev12014!";
if($_POST['login_name']  == $name && $_POST['login_pass'] == $passwd ){
    $_SESSION['is_login'] = true;
    echo "success";
    echo "<script>window.location.href='magmi.php'</script>";
}else{
    $_SESSION['is_login'] = false;
      echo "error login";
     // echo "<script>window.location.href='login.php'</script>";

}


?>