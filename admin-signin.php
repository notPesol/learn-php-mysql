<!DOCTYPE html>
<html lang="en">

<head>
    <!-- head -->
    <?php require 'bs_head.php' ?>

</head>

<body>
    <!-- navbar -->
    <?php require 'bs_navbar.php' ?>

    <form method="post" class="m-auto mt-5">
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            @$username = $_POST['username'];
            @$password = $_POST['password'];

            $mysqli = @(new mysqli('localhost', 'root', 'admin_080', 'spn_store'));
            if ($mysqli->connect_error) {
                die('การเชื่อมต่อผิดพลาด: ' . $mysqli->connect_error);
            }
            $stmt = $mysqli->stmt_init();
            $sql = "SELECT * FROM admin WHERE username = ? AND password = ?";
            $stmt->prepare($sql);
            $param = [$username, $password];
            $stmt->bind_param('ss', ...$param);
            $stmt->execute();
            $result = $stmt->get_result();
            $num_row = $result->num_rows;

            $msg = '';
            $bs_context = '';
            if ($num_row != 1) {
                $msg = 'ชื่อผู้ใช้ หรือรหัสผ่านไม่ถูกต้อง !!!';
                $bs_context = 'alert-danger';
                echo <<<HTML
                <div class="alert <?= $bs_context ?> mb-4" role="alert">
                    $msg
                </div>
                HTML;
            } else {
                $_SESSION['admin'] = '1';
            }
            
        }

        if (!isset($_SESSION['admin'])) { //ถ้ายังไม่มีข้อมูลใน session admin
            echo <<<HTML
                <h6 class="text-info text-center mt-5">ผู้ดูแลเข้าสู่ระบบ</h6>
                <input class="form-control form-control-sm mb-4" type="text" name="username" placeholder="username">
                <input class="form-control form-control-sm mb-4" type="password" name="password" placeholder="รหัสผ่าน">
                <button class="btn btn-primary btm-sm d-block m-auto">ตกลง</button>
                HTML;
        } else {
            echo <<<HTML
                <h6 class="text-info text-center mt-5">สำหรับผู้ดูแล</h6>
                <a class="btn btn-primary form-control form-control-sm mb-4" href="admin-add-product.php">เพิ่มรายการสินค้า</a>
                <a class="btn btn-danger form-control form-control-sm mb-4" href="admin-signout.php">ออกจากระบบ</a>
                HTML;
        }
        ?>

    </form>

    <!-- footer -->
    <?php require 'bs_footer.php' ?>

</body>

</html>