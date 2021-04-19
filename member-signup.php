<!DOCTYPE html>
<html lang="en">

<head>
    <!-- head -->
    <?php require 'bs_head.php' ?>

</head>

<body>
    <!-- navbar -->
    <?php require 'bs_navbar.php' ?>

    <form method="post" class="mt-5 m-auto">

        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($_POST['password'] == $_POST['re_password']) {
                $fname = $_POST['firstname'];
                $lname = $_POST['lastname'];
                $email = $_POST['email'];
                $password = $_POST['password'];

                $mysqli = new mysqli('localhost', 'root', 'admin_080', 'spn_store');

                $sql = "INSERT INTO member VALUES(?, ?, ?, ?, ?)";

                $stmt = $mysqli->stmt_init();
                $stmt->prepare($sql);

                $params = [0, $email, $password, $fname, $lname];

                $stmt->bind_param('issss', ...$params);
                $stmt->execute();

                $aff_rows = $stmt->affected_rows;
                $insert_id = $mysqli->insert_id;

                $stmt->close();
                $mysqli->close();

                // ถ้าสมัครสำเร็จให้เก็บข้อมูล member_id, member_firstname ไว้ใน session
                if ($aff_rows == 1){
                    $_SESSION['member_id'] = $insert_id;
                    $_SESSION['member_name'] = $fname;

                    echo '<script>location= "member-signin.php" </script>';
                    exit;
                }else{
                    echo <<<HTML
                        <div class="alert alert-danger alert-dismissible">
                            การสมัครผิดพลาด หรือ อีเมลล์มีคนใช้แล้ว !
                            <button class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        </div>
                        HTML;
                }
            }
        }
        ?>

        <h6 class="text-info text-center mb-3 mt-5">
            สมัครสมาชิก
        </h6>
        <div class="input-group input-group-sm mt-2 mb-2">
            <input type="text" name="firstname" class="form-control" placeholder="ชื่อ" required>
            <input type="text" name="lastname" class="form-control" placeholder="นามสกุล" required>
        </div>
        <input type="email" name="email" class="form-control form-control-sm" placeholder="E-mail" required>
        <div class="input-group input-group-sm mt-2 mb-2">
            <input type="password" name="password" class="form-control" placeholder="รหัสผ่าน" required>
            <input type="password" name="re_password" class="form-control" placeholder="ป้อน รหัสผ่านอีกครั้ง" required>
        </div>

        <button class="btn btn-primary d-block btn-sm w-25 mx-auto mt-3">ตกลง</button>

    </form>

    <!-- footer -->
    <?php require 'bs_footer.php' ?>

</body>

</html>