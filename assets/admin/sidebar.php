<?php
    $current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar bg-fff p-20 p-relative">
    <h3 class="p-relative txt-c mt-0">A.C.S.E</h3>
    <ul>
        <li>
            <a href="index.php" class="<?php echo $current_page == 'index.php' ? 'active' : ''; ?> d-flex align-c fs-14 color-000 rad-6 p-10">
                <i class="fa-solid fa-chart-simple fa-fw"></i>
                <span class="fs-14 ml-10">الرئيسية</span>
            </a>
        </li>
        <li>
            <a href="adherentes.php" class="<?php echo $current_page == 'adherentes.php' ? 'active' : ''; ?> d-flex align-c fs-14 color-000 rad-6 p-10">
                <i class="fa-solid fa-user fa-fw"></i>
                <span class="fs-14 ml-10">المشتركين</span>
            </a>
        </li>
        <li>
            <a href="paiement.php" class="<?php echo $current_page == 'paiement.php' ? 'active' : ''; ?> d-flex align-c fs-14 color-000 rad-6 p-10">
                <i class="fa-solid fa-credit-card fa-fw"></i>
                <span class="fs-14 ml-10">الدفع</span>
            </a>
        </li>
        <li>
            <a href="evaluation.php" class="<?php echo $current_page == 'evaluation.php' ? 'active' : ''; ?> d-flex align-c fs-14 color-000 rad-6 p-10">
                <i class="fa-solid fa-star fa-fw"></i>
                <span class="fs-14 ml-10">التقييم</span>
            </a>
        </li>
        <li>
            <a href="absence.php" class="<?php echo $current_page == 'absence.php' ? 'active' : ''; ?> d-flex align-c fs-14 color-000 rad-6 p-10">
                <i class="fa-solid fa-calendar-xmark fa-fw"></i>
                <span class="fs-14 ml-10">الغياب</span>
            </a>
        </li>
        <li>
            <a href="horaire.php" class="<?php echo $current_page == 'horaire.php' ? 'active' : ''; ?> d-flex align-c fs-14 color-000 rad-6 p-10">
                <i class="fa-solid fa-calendar-xmark fa-fw"></i>
                <span class="fs-14 ml-10">الجدول الزمني</span>
            </a>
        </li>
        <li>
            <a href="exame.php" class="<?php echo $current_page == 'exame.php' ? 'active' : ''; ?> d-flex align-c fs-14 color-000 rad-6 p-10">
                <i class="fa-solid fa-person-arrow-up-from-line fa-fw"></i>
                <span class="fs-14 ml-10">الامتحانات</span>
            </a>
        </li>
    </ul>
</div>