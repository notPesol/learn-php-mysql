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
        if (isset($_SESSION['signed_in'])) {
            goto signed_in;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $mysqli = new mysqli('localhost', 'root', 'admin_080', 'spn_store');
            $sql = "SELECT * FROM member WHERE email = ? AND password = ?";
            $stmt = $mysqli->stmt_init();
            $stmt->prepare($sql);
            $params = [$_POST['email'], $_POST['password']];
            $stmt->bind_param('ss', ...$params);
            $stmt->execute();

            $result = $stmt->get_result();
            $num_rows = $result->num_rows;

            if ($num_rows == 1) {
                $data = $result->fetch_object();

                if (!empty($data->code)) {
                    $_SESSION['email'] = $data->email;
                    header('location: member-verify.php');
                    exit;
                    
                } else {
                    $_SESSION['signed_in'] = '1';
                    $_SESSION['member_id'] = $data->id;
                    $_SESSION['member_name'] = $data->firstname;
                    $mysqli->close();

                    echo '<script> location="member-signin.php"</script>';

                    exit;
                }
            } elseif ($num_rows == 0) {
                goto error;
            }

            signed_in: // ถ้ามีข้อมูลใน session member_id
            echo <<<HTML
                    <h6 class="text-info text-center mb-3 mt-3">
                        สำหรับสมาชิก
                    </h6>
                    <a class="btn btn-primary d-block btn-sm mx-auto mb-4 w-50" href="wish-list.php">รายการที่ชอบ</a>
                    <a class="btn btn-danger d-block btn-sm mx-auto mb-4 w-50" href="member-signout.php">ออกจากระบบ</a>
                    HTML;

            include 'bs_footer.php';
            exit('</form> </body> </html>');

            error:  //ถ้ามี error
            echo <<<HTML
                    <div class="alert alert-danger mb-4 mt-5" role="alert">
                        E-mail หรือ รหัสผ่านไม่ถูกต้อง !
                        <button class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    </div>
                    HTML;
        }
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