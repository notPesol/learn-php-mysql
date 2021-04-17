<!DOCTYPE html>
<html lang="en">

<head>
    <!-- head -->
    <?php require 'bs_head.php' ?>

</head>

<body>
    <!-- navbar -->
    <?php require 'bs_navbar.php' ?>

    <?php
    if (!isset($_SESSION['admin'])) {
        header('location: admin-signin.php');
        exit();
    }
    ?>

    <form class="text-center mt-5" method="POST" enctype="multipart/form-data">
    <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $type = explode('/', $_FILES['upfile']['type']);
            if ($_FILES['upfile']['error'] > 0 || $type[0] != 'image'){
                $msg = 'เกิดข้อผิดพลาดในการอัปโหลด !!!';
                $bs_class = 'alert-danger';
                goto end_post;
            }
            $mysqli = new mysqli('localhost', 'root', 'admin_080')

        }
    ?>
        <div class="form-group">
            <label for="name">ชื่อสินค้า</label>
            <input type="text" class="form-control" id="name" aria-describedby="nameHelp" required>
        </div>
        <div class="form-group">
            <label for="pass">รายละเอียดสินค้า</label>
            <small id="pass" class="form-text text-muted">รายละเอียดต้องเข้าใจ</small>
            <textarea type="text" class="form-control" name="detail" id="pass" aria-describedby="pass" required>
            </textarea>
        </div>
        <div class="form-group mb-3">
            <div class="mb-2 bg-info"><strong>ภาพประกอบ</strong></div>
            <input type="file" name="upfile" id="pic" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-primary">ตกลง</button>
    </form>

    <!-- footer -->
    <?php require 'bs_footer.php' ?>

</body>

</html>