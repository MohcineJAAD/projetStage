<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require '../php/db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

$user_name = $_SESSION['user_name'];

?>

<div class="head p-15 between-flex" dir="rtl">
    <h2 class="welcomUser">مرحبا, <?php echo $user_name; ?></h2>
    <a href="../php/logout.php" class="active d-flex align-c fs-14 color-000 rad-6 p-10">
        <i class="fa-solid fa-right-from-bracket fa-fw"></i>
        <span class="fs-14 ml-10">خروج </span>
    </a>
</div>