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

$user_id = $_SESSION['user_id'];

// Fetch the user's name and last name from the database
$stmt = $conn->prepare("SELECT nom, prenom, image_path FROM adherents WHERE identifier = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$stmt->bind_result($nom, $prenom, $image_profil);
$stmt->fetch();
$stmt->close();

$user_name = htmlspecialchars("$prenom $nom", ENT_QUOTES, 'UTF-8');
?>

<div class="head p-15 between-flex">
    <h2 class="welcomUser">Bonjour, <?php echo $user_name; ?></h2>
    <img src="<?php echo htmlspecialchars("../uploads/".$image_profil); ?>" alt="avatar" id="avatar">
    <div class="drop-menu p-10" id="dropMenu">
        <div class="userHeader mb-5">
            <img src="<?php echo htmlspecialchars("../uploads/".$image_profil); ?>" alt="avatar">
            <span class="fs-14 m-0"><?php echo htmlspecialchars($prenom, ENT_QUOTES, 'UTF-8'); ?></span>
        </div>
        <ul>
            <li>
                <a href="profile.php" class="d-flex align-c fs-14 color-000 rad-6 p-10">
                    <i class="fa-solid fa-user fa-fw"></i>
                    <span class="fs-14 ml-10">Profil</span>
                </a>
            </li>
            <li>
                <a href="alterPass.php" class="d-flex align-c fs-14 color-000 rad-6 p-10">
                    <i class="fa-solid fa-key fa-fw"></i>
                    <span class="fs-14 ml-10">Changer le mot de passe</span>
                </a>
            </li>
            <li class="mt-20">
                <a href="../php/logout.php" class="active d-flex align-c fs-14 color-000 rad-6 p-10">
                    <i class="fa-solid fa-right-from-bracket fa-fw"></i>
                    <span class="fs-14 ml-10">Se déconnecter</span>
                </a>
            </li>
        </ul>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const avatar = document.getElementById("avatar");
        const dropMenu = document.getElementById("dropMenu");

        avatar.addEventListener("click", function(event) {
            event.stopPropagation();
            dropMenu.classList.toggle("drop-menu-Active");
        });

        document.addEventListener("click", function(event) {
            if (!dropMenu.contains(event.target) && !avatar.contains(event.target)) {
                dropMenu.classList.remove("drop-menu-Active");
            }
        });
    });
</script>