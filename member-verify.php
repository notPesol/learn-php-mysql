<head>
    <!-- head -->
    <?php require 'bs_head.php' ?>

</head>

<body>
    <!-- navbar -->
    <?php require 'bs_navbar.php' ?>

    <form method="post">
        <?php
        if (!isset($_SESSION['email'])){
            header('location: index.php');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $email = $_SESSION['email'];
            $verify = $_POST['verify'];

            $mysqli = new mysqli('localhost', 'root', 'admin_080', 'spn_store');
            $stmt = $mysqli->stmt_init();

            $sql = "UPDATE member SET code = '' WHERE email = ? AND code = ?";

            $stmt->prepare($sql);
            $params = [$email, $verify];
            $stmt->bind_param('ss', ...$params);
            $stmt->execute();

            $aff_rows = $stmt->affected_rows;

            //ถ้ามีการเปลี่ยนแปลงแสดงว่าใส่รหัสยืนยันถูกต้อง
            if($aff_rows == 1){
                $_SESSION['signed_in'] = '1';
                header('location: index.php');
                exit;
            }else{
                echo <<<HTML
                            <div class="alert alert-danger alert-dismissible">
                                รหัสยืนยันไม่ถูกต้อง ! <br>
                                <button class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                            HTML;
            }
        }
        ?>

        <h6 class="text-info text-center mb-3">ยืนยันสมาชิก</h6>
        <input class="form-control form-control-sm mb-3" type="text" name="verify" placeholder="รหัสยืนยันที่ได้รับทางอีเมลล์" required>
        <button class="btn btn-primary d-block m-auto my-4">ตกลง</button>
        <br>
        <div class="text-center">
            <a href="index.php">ยกเลิก</a>
        </div>

    </form>

    <!-- footer -->
    <?php require 'bs_footer.php' ?>

</body>

</html>