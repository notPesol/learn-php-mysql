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
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $type = explode('/', $_FILES['upfile']['type']);
            if ($_FILES['upfile']['error'] > 0 || $type[0] != 'image') {
                $msg = 'เกิดข้อผิดพลาดในการอัปโหลด !!!';
                $bs_class = 'alert-danger';
                goto end_post;
            }

            $mysqli = new mysqli('localhost', 'root', 'admin_080', 'spn_store');
            $sql = "INSERT INTO product VALUES(?, ?, ?, ?, ?, ?)";
            $stmt = $mysqli->stmt_init();
            $stmt->prepare($sql);
            $param = [0, $_POST['name'], ltrim($_POST['detail'], " "), $POST['price'], $_POST['remain'], '', $_POST['type_product']];
            $stmt->bind_param('issdisi', ...$param);
            $stmt->execute();
            $product_id = $stmt->insert_id;
            $stmt->close();

            $image_folder = 'product-images';
            @mkdir($image_folder);

            $old_name = $_FILES['upfile']['name'];

            $ext = pathinfo($old_name, PATHINFO_EXTENSION);

            $new_name = "$product_id.$ext";
            move_uploaded_file($_FILES['upfile']['tmp_name'], "$image_folder/$new_name");

            //แก้ไขชื่อไฟล์รูปภาพในฐานข้อมูล phpMySQL
            $sql = "UPDATE product set img_file = '$new_name' WHERE id = $product_id";

            $mysqli->query($sql);

            $mysqli->close();

            $msg = 'ข้อมูลถูกบันทึกแล้ว';
            $bs_class = 'alert-info';

            end_post:

            echo <<<HTML
                    <div class="alert $bs_class mb-4" role="alert">
                        $msg
                        <button class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    </div>
                    HTML;
        }

        ?>

        <h6 class="text-info text-center">เพิ่มรายการสินค้า</h6>
        <div class="form-group mt-3">
            <label for="name">ชื่อสินค้า</label>
            <input name="name" type="text" class="form-control" id="name" aria-describedby="nameHelp" required>
        </div>
        <div class="form-group">
            <label for="type_pro">ประเภทสินค้า</label>
            <input name="type_product" type="text" class="form-control" placeholder="ใส่รหัสประเภท" id="type_pro" required>
        </div>
        <div class="form-group">
            <label for="textarea">รายละเอียดสินค้า</label>
            <textarea type="text" rows="2" class="form-control" name="detail" id="textarea" aria-describedby="textarea" required>
            </textarea>
        </div>
        <div class="form-group mt-3">
            <label for="name">ราคา</label>
            <input name="price" type="text" class="form-control" id="price" aria-describedby="nameHelp" required>
        </div>
        <div class="form-group mt-3">
            <label for="name">จำนวนคงเหลือ</label>
            <input name="remain" type="text" class="form-control" id="remain" aria-describedby="nameHelp" required>
        </div>
        <div class="mb-2 bg-info"><strong>ภาพสินค้า</strong></div>
        <div class="form-group mb-3">
            <input type="file" name="upfile" id="pic" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-primary">ตกลง</button>
    </form>

    <!-- footer -->
    <?php require 'bs_footer.php' ?>

</body>

</html>
