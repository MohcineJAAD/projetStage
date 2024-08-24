<?php
require "assets/php/db_connection.php";
$sql = "SELECT club_name, logo, email, phone, address, facebook, instagram, twitter from admin";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
?>
<header class="header" id="header">
    <div class="container">
        <a href="#" class="logo"><img src="assets/images/<?php echo $row['logo'];?>" alt="logo salle de sport"></a>
        <ul class="main-nav">
            <li><a href="index.php#hero">الرئيسية</a></li>
            <li><a href="index.php#about">حولنا</a></li>
            <li><a href="index.php#plans">الخطط</a></li>
            <li><a href="index.php#Horaire">المواعيد</a></li>
            <li><a href="login.php">تسجيل الدخول</a></li>
        </ul>
    </div>
</header>