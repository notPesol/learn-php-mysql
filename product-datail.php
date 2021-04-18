<!DOCTYPE html>
<html lang="en">

<head>
    <!-- head -->
    <?php require 'bs_head.php' ?>

    <script>
        $(function() {
            $('#modal-btn-submit').click(function() {
                $('#modal-form').submit();
                $('#modalReply').modal('hide');
            });

            $('a.delete').click(function() {
                if (confirm('ลบคำถามนี้ ?')) {
                    id = $(this).attr('id');
                    $('#delete_id').val(id);
                    $('#form-delete').submit();
                }
            });
        });

        function showModalReply(questionId) {
            $('#modelReply').modal();
            $('#modal-qid').val(questionId);
        }
    </script>
</head>

<body>
    <!-- navbar -->
    <?php require 'bs_navbar.php' ?>

    <!-- ##########  ส่วนแสดงรายละเอียดของสินค้า  ########## -->
    <?php

    $product_id = 0;

    if (isset($_GET['id'])) {
        $product_id = $_GET['id'];
    }

    $mysqli = new mysqli('localhost', 'root', 'admin_080', 'spn_store');

    $sql = "SELECT * FROM product WHERE id = $product_id";

    $result = $mysqli->query($sql);

    if ($mysqli->error || $result->num_rows == 0) {
        echo '<h6 class="text-danger text-center">ไม่พบข้อมูล</h6>';
        goto end_page;
    }

    list($id, $name, $detail, $img_file, $type_product) = $result->fetch_row();

    echo <<<HTML
            <div class="card mx-auto mt-2" style="max-width: 60rem;">
            <img src="product-images/$img_file" class="card-img-top" alt="productPic">
            <div class="card-body">
                <h5 class="card-title text-center">$name</h5>
                <p class="card-text">$detail</p>
            </div>
            </div>
        <hr>
        HTML;

    ?>

    <!-- ##########  ส่วนของฟอร์มเพื่อใส่คำถาม  ########## -->
    <h6 class="text-info text-center mb-3">คำถามเกี่ยวกับสินค้า</h6>
    <?php
    date_default_timezone_set('Asia/Bangkok');
    $now = date('Y-m-d H:i:s');
    if (isset($_POST['question'])) {
        if ($_POST['capcha'] == $_SESSION['captcha']) {
            $sql = "INSERT INTO question_answer VALUES(?, ?, ?, ?, ?, ?, ?)";
            $q = $_POST['question'];
            $qn = $_POST['questioner'];
            $param = [0, $product_id, $q, $qn, $now, '', ''];

            $stmt = $mysqli->stmt_init();
            $stmt->prepare($sql);
            $stmt->bind_param('issssss', ...$param);
            $stmt->execute();
        } else {
            echo '<h6 class="text-danger text-center">อักขระในภาพไม่ถูกต้อง !</h6>';
            goto end_page;
        }
    }

    require 'lib/SimpleCaptcha/captcha.class.php';
    $capcha = new SimpleCaptcha();
    ?>

    <form method="POST">
        <div class="media border p-3" style="background: powderblue;">
            <i class="fa fa-question-circle fa-3x mr-3"></i>
            <div class="media-body">
                <input type="text" name="questioner" placeholder="ชื่อผู้ถาม" class="form-control form-control-sm d-inline w-auto mt-2" required>
                <input type="text" name="question" class="form-control form-control-sm" placeholder="คำถาม" required>
                <div id="#capcha" class="mt-2">
                    <?php $capcha->show(); ?>
                </div>
                <input type="text" placeholder="กรอกอักขระในภาพ" name="capcha" class="form-control form-control-sm d-inline w-auto mt-2" required>
                <button type="submit" class="btn btn-info btn-sm d-block mt-3 px-5">ตกลง</button>
            </div>
        </div>
        <input type="hidden" name="product_id" value="<?= $product_id ?>">
    </form>
    <!-- สิ้นสุดส่วนฟอร์มคำถาม  -->

    <hr>

    <!-- ##########  ส่วนแสดงคำถามและคำตอบ  ########## -->
    <?php
    if (isset($_POST['answer'])) {
        $sql = "UPDATE question_answer SET answer = ?, date_replied = ? WHERE id = ?";
        $answer = $_POST['answer'];
        $q_id = $_POST['question_id'];
        $params = [$answer, $now, $q_id];
        $stmt = $mysqli->stmt_init();
        $stmt->prepare($sql);
        $stmt->bind_param('ssi', ...$params);
        $stmt->execute();
    } elseif (isset($_POST['delete_id'])) {
        $d_id = $_POST['delete_id'];
        $sql = "DELETE FROM question_answer WHERE id = $d_id";
        $mysqli->query($sql);
    }

    require 'lib/thai-datetime-friendly.class.php';
    require 'lib/pagination-v2.class.php';

    $dtf = new ThaiDateTimeFriendly();
    $page = new PaginationV2();

    $sql = "SELECT * FROM question_answer WHERE product_id = $product_id ORDER BY date_asked DESC";

    $result = $page->query($mysqli, $sql, 5);
    if ($mysqli->error || $result->num_rows == 0) {
        goto end_page;
    }

    echo '<ul class="list-unstyled mt-4 mb-4">'; //เริ่มต้น media body

    while ($data = $result->fetch_object()) {
        $q_id = $data->id;
        $admin_link = '';
        if (isset($_SESSION['admin'])) {
            $admin_link = <<<HTML
                                <a href="javascript: showModalReply($q_id)">ตอบกลับ</a>
                                <a href="#" class="delete" id="$q_id">ลบ</a>
                                HTML;
        }
        echo <<<HTML
                <li class="media border p-3 mb-2">
                    <i class="far fa-question-circle fa-3x mr-3 mt-1 text-secondary"></i>
                    <div class="media-body">
                        <h6 class="text-info">$data->questioner</h6>
                        $data->question
                        <div class="d-flex justify-content-between mt-3">
                            <div class="small text-block-50">
                                <i>{$dtf->of($data->date_asked)}</i>
                            </div>
                            <div class="text-right">$admin_link</div>
                        </div>
                    
                
                HTML;
        if (!empty($data->answer)) {
            echo <<<HTML
                    <div class="reply media mt-4 p-3">
                        <i class="far fa-check-circle fa-3x mr-3 mt-1 text-success"></i>
                        <div class="media-body">
                            $data->answer
                            <div class="small mt-2 text-black-50">
                                {$dtf->of($data->date_replied)}
                            </div>
                        </div>
                    </div>
                    HTML;
        }

        echo '</div>
        </li>';
    }

    echo '</ul>';

    if ($page->total_pages() > 1) {
        $page->echo_pagenums_bootstrap();
    }

    ?>

    <!-- Modal สำหรับใส่คำตอบ แสดงเมื่อคลิกที่ลิงก์ "ตอบกลับ" -->
    <div id="modelReply" class="modal fade">
        <form id="modal-form" method="post">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">ตอบกลับ</h5>
                        <button class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <textarea name="answer" rows="3" class="form-control form-control-sm bg-light" required></textarea>
                        <input type="hidden" name="question_id" id="modal-qid" value="0">
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="modal-btn-submit" class="btn btn-primary mx-auto">ตกลง</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- ฟอร์มสำหรับส่งค่า id ขึ้นไปลบคำถาม (เมื่อคลิกที่ลิงก์ ลบ) -->
    <form id="form-delete" method="post">
        <input type="hidden" name="delete_id" id="delete_id">
    </form>

    <?php
    end_page:
    $mysqli->close();
    ?>

    <!-- footer -->
    <?php require 'bs_footer.php' ?>


</body>

</html>