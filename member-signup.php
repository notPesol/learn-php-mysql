<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
?>

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
                $code = strval(mt_rand(100000, 999999)); //รหัสยืนยัน
                $mysqli = new mysqli('localhost', 'root', 'admin_080', 'spn_store');

                $sql = "INSERT INTO member VALUES(?, ?, ?, ?, ?, ?)";

                $stmt = $mysqli->stmt_init();
                $stmt->prepare($sql);

                $params = [0, $email, $password, $fname, $lname, $code];

                $stmt->bind_param('isssss', ...$params);
                $stmt->execute();

                $aff_rows = $stmt->affected_rows;
                $insert_id = $mysqli->insert_id;

                $stmt->close();
                $mysqli->close();

                $err = ''; //เอาไว้เก็บข้อผิดพลาดหากส่งเมลล์ไม่สำเร็จ

                // ถ้าสมัครสำเร็จให้เก็บข้อมูล member_id, member_firstname ไว้ใน session
                if ($aff_rows == 1) {
                    //Load Composer's autoloader
                    require 'vendor/autoload.php';
                    //Instantiation and passing `true` enables exceptions
                    $mail = new PHPMailer(true);
                    $mail->CharSet = 'utf-8';

                    //Server settings
                    $mail->isSMTP();                                            //Send using SMTP
                    $mail->Mailer = 'smtp';
                    $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
                    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                    $mail->Username   = 'pesol2012@gmail.com';                  //SMTP username
                    $mail->Password   = 'ivgqainqcvptgwbv';                     //SMTP password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                    $mail->Port       = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

                    //Recipients
                    $mail->setFrom('admin@example.com', 'SPN STORE');
                    $mail->addAddress($email, $fname);     //Add a recipient
                    $mail->addReplyTo('info@example.com', 'Information');
                    $mail->addCC('cc@example.com');
                    $mail->addBCC('bcc@example.com');

                    //Content
                    $mail->isHTML(true);                                  //Set email format to HTML
                    $mail->Subject = 'รหัสยืนยันจาก SPN STORE';
                    $mail->Body    = 'รหัสยืนยัน: ' . "<b>{$code}</b> <br> (นี่เป็นระบบอัตโนมัติ)";
                    $mail->AltBody = 'รหัสยืนยัน: ' . $code;

                    if($mail->send()){
                        $_SESSION['email'] = $email;
                        echo <<<HTML
                        <div class="alert alert-success alert-dismissible">
                            การสมัครสำเร็จ รหัสยืนยันถูกส่งไปที่อีเมลล์เรียบร้อยแล้ว <br>
                            กรุณานำรหัสดังกล่าวมายืนยันในขั้นตอนการเข้าสู่ระบบ
                            <button class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        </div>
                        HTML;
                        sleep(10);
                        echo '<script>location= "member-verify.php" </script>';
                        exit;
                    }else{
                        $err = "Message could not be sent. Mailer Error: {$mail->ErrorInfo} <br> กรุณาติดต่อผู้ดูแล";
                    }

                } else {
                    echo <<<HTML
                        <div class="alert alert-danger alert-dismissible">
                            การสมัครผิดพลาด หรือ อีเมลล์มีคนใช้แล้ว ! <br>
                            $err
                            <button class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        </div>
                        HTML;
                }
            }else{
                echo <<<HTML
                            <div class="alert alert-danger alert-dismissible">
                                รหัสผ่านทั้งสองช่องไม่ตรงกัน ! <br>
                                <button class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                            HTML;
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