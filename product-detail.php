<!DOCTYPE html>
<html lang="en">

<head>
    <!-- head -->
    <?php require 'bs_head.php' ?>
    <style>
        div.main-container {
            max-width: 680px;
            min-width: 400px;
        }

        img#product {
            max-width: 150px;
            max-height: 150px;
            cursor: pointer;
        }

        p#detail,
        div.container {
            font-size: 0.9rem;
        }

        div.modal img {
            max-width: 450px;
            max-height: 450px;
        }
    </style>

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

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $mid = 0;
        $msg = '';
        $bs_class = '';

        if (!isset($_SESSION['member_id'])) {
            $msg = 'ต้องเข้าสู่ระบบก่อนบันทึกรายการที่ชอบ';
            $bs_class = 'alert-danger';
            goto end_post;
        } else {
            $mid = $_SESSION['member_id'];
        }

        // เพื่อป้องกันการบันทึกซ้ำ ให้นับว่ามีสินค้านี้อยู่ในฐานข้อมูลแล้วหรือไม่
        $sql = "SELECT COUNT(*) FROM wish_list WHERE member_id = $mid AND product_id = $product_id";

        $resutll = $mysqli->query($sql);

        list($count) = $resutll->fetch_row();
        if ($count == 0) { // ถ้าไม่เคยบันทึก ให้ทำการบันทึก
            $sql = "INSERT INTO wish_list VALUE(?, ?, ?)";
            $params = [0, $mid, $product_id];
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param('iii', ...$params);
            $stmt->execute();
        }
        $msg = 'บันทึกเป็นรายการที่ชอบแล้ว';
        $bs_class = 'alert-success';

        end_post:
        echo <<<HTML
            <div class="alert $bs_class mb-4" role="alert">
                $msg
                <button class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            </div>
            HTML;
    }

    $sql = "SELECT * FROM product WHERE id = $product_id";

    $result = $mysqli->query($sql);

    if ($mysqli->error || $result->num_rows == 0) {
        echo '<h6 class="text-danger text-center">ไม่พบข้อมูล</h6>';
        goto end_page;
    }

    list($id, $name, $detail, $price, $remain, $img_file, $type_id) = $result->fetch_row();

    $price = number_format($price);

    if ($remain == 0) {
        $remain = 'สินค้าหมด';
    }

    $sql_t = "SELECT name FROM type_of_product WHERE id = $type_id";
    $t_result = $mysqli->query($sql_t);

    $type_name = '';
    if ($mysqli->error || $t_result->num_rows == 0) {
        $type_name = '';
    }
    list($type_name) = $t_result->fetch_row();


    echo <<<HTML
        <div class="main-container mx-auto mt-5">

            <div class="d-flex">
                <div>
                        <img src="product-images/$img_file" id="product" class="mr-4 mt-2">
                </div>
                <div>
                        <h6 id="pro-name" class="font-weight-bold text-info">$name</h6>
                        <p id="detail">$detail</p>
                        <div class="container">
                            <div class="row my-1">
                                    <div class="col">ราคา: $price</div>
                                    <div class="col-auto">
                                        <button class="btn btn-sm btn-secondary text-white-50 disabled">
                                                <i class="fa fa-shopping-cart mr-2"></i>หยิบใส่รถเข็น
                                        </button>
                                    </div>
                            </div>
                            <div class="row my-1">
                                    <div class="col">จำนวนสินค้า: $remain</div>
                                    <div class="col-auto">
                                        <form method="post">
                                                <input type="hidden" name="pid" value="$id"> 
                                                <button class="btn btn-sm btn-success">
                                                    <i class="fa fa-heart mr-2"></i>รายการที่ชอบ
                                                </button>
                                        </form>
                                    </div>
                            </div>                             
                        </div>
                </div>
            </div>
        </div>
        HTML;

    ?>

    <!-- ##########  ส่วนของฟอร์มเพื่อใส่คำถาม  ########## -->
    <h6 class="text-info text-center mb-3">คำถามเกี่ยวกับสินค้า</h6>
    <?php
    date_default_timezone_set('Asia/Bangkok');
    $now = date('Y-m-d H:i:s');
    if (isset($_POST['question'])) {
        if (!isset($_SESSION['member_name'])) {
            echo '<h6 class="text-danger text-center">ต้องเข้าสู่ระบบก่อนถามคำถาม !</h6>';
        } elseif ($_POST['capcha'] == $_SESSION['captcha']) {
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

    <!-- Bootstrap Modal Window ใช้แสดงภาพขนาดจริง -->
    <div class="modal" id="bsModal" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 id="title-model" class="modal-title"></h6>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div id="m-body" class="modal-body"><img src=""></div>
            </div> <!-- modal-content -->
        </div> <!-- modal-dialog -->
    </div> <!-- modal -->

    <!-- footer -->
    <?php require 'bs_footer.php' ?>


    <script>
        //เมื่อคลิกที่ภาพ ให้แสดงภาพขนาดจริงใน Modal
        $('img#product').click(function() {
            var name = $('#pro-name').text();
            $('.modal-title#title-model').text(name);
            var img_src = $(this).prop('src');
            $('.modal-body#m-body > img').prop('src', img_src);
            $('#bsModal').modal();
        });
    </script>

</body>

</html>