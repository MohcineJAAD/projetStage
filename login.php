<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="assets/css/master1.css" />
    <link rel="stylesheet" href="assets/css/normalize.css" />
    <link rel="stylesheet" href="assets/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="assets/js/main.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&family=Work+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</head>

<body>
    <?php require "header.php" ?>
    <section class="landing">
        <div class="container">
            <div class="illustration">
                <img src="assets/images/login.png" alt="Illustration">
            </div>
            <div class="form-container" dir="rtl">
                <form action="assets/php/authentication.php" method="post" class="login">
                    <h2 class="title">تسجيل الدخول</h2>
                    <div class="input-filde">
                        <i class="fa-regular fa-circle-user"></i>
                        <input type="text" name="userName" id="user" placeholder="أدخل اسم المستخدم" autocomplete="off">
                    </div>
                    <div class="input-filde">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" name="password" id="pass" placeholder="أدخل كلمة المرور" autocomplete="off">
                        <img src="assets/images/hide.png" alt="password show" id="eye">
                    </div>
                    <input type="submit" value="Se connecter" class="btn">
                </form>
            </div>
        </div>
    </section>
    <script>
        const showPass = document.querySelector("#eye");
        const inputPass = document.querySelector("#pass");

        showPass.addEventListener("click", function() {
            if (inputPass.type === "password") {
                inputPass.type = "text";
                this.src = "assets/images/show.png";
            } else {
                inputPass.type = "password";
                this.src = "assets/images/hide.png";
            }
        });
    </script>
</body>

</html>