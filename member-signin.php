<!DOCTYPE html>
<html lang="en">

<head>
    <!-- head -->
    <?php require 'bs_head.php' ?>

</head>

<body>
    <!-- navbar -->
    <?php require 'bs_navbar.php' ?>

    <form method="post" class="m-auto">

        <?php
            
        ?>

        <h6 class="text-info text-center mt-4 mb-3">
            สมาชิกเข้าสู่ระบบ
        </h6>
        <input type="email" name="email" placeholder="E-mail" class="form-control form-control-sm mb-3" required>
        <input type="password" name="password" placeholder="Password" class="form-control form-control-sm mb-3" required>
        <button class="btn btn-sm btn-primary d-block mx-auto mb-4 w-50">ตกลง</button>

        <a href="member-signup.php" class="btn btn-primary d-block btn-sm mx-auto mb-4 w-50">สมัครสมาชิก</a>
    </form>

    <!-- footer -->
    <?php require 'bs_footer.php' ?>

</body>

</html>