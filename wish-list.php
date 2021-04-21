<!DOCTYPE html>
<html lang="en">

<head>
    <!-- head -->
    <?php require 'bs_head.php' ?>

    <?php
    if (!isset($_SESSION['member_id'])) {
        header('location: index.php');
        exit;
    }
    ?>

    <style>
        div.main-container {
            max-width: 1600px;
            min-width: 900px;
        }

        img#product {
            max-width: 170px;
            max-height: 170px;
        }

        #detail {
            font-size: 1rem;
        }
    </style>

</head>

<body>
    <!-- navbar -->
    <?php require 'bs_navbar.php' ?>
    <div class="main-container mx-auto mt-5">
        <?php

        require 'lib/pagination-v2.class.php';
        $page = new PaginationV2();

        $mid = $_SESSION['member_id'];

        $mysqli = new mysqli('localhost', 'root', 'admin_080', 'spn_store');


        // สำหรับลบรายการที่ชอบ
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $product_id = $_POST['product_id'];
            $sql = "DELETE FROM wish_list WHERE member_id = $mid AND product_id = $product_id";
            $mysqli->query($sql);
            header('location: wish-list.php');
        }


        // อ่านข้อมูลสินค้าจากตาราง product ที่มีค่า id เก็บไว้ในตาราง wish_list
        $sql = "SELECT * FROM product WHERE id in (SELECT product_id FROM wish_list WHERE member_id = $mid)";

        $result = $page->query($mysqli, $sql, 3);

        if ($mysqli->error || $result->num_rows == 0) {
            echo '<div class="text-center text-danger">ไม่พบข้อมูล</div>';
            $mysqli->close();
            exit('</div></body></html>');
        }

        echo <<<HTML
            <h6 class="text-info text-center mb-4">รายการที่ชอบ</h6>
            <hr>
            <div class="container mb-5">
            HTML;

        while ($p = $result->fetch_object()) {
            $n = mb_substr($p->name, 0, 30);
            $d = mb_substr($p->detail, 0, 50) . '...';
            $src = "product-images/$p->img_file";
            $prc = number_format($p->price);
            if ($p->remain > 0) {
                $r = '<span class="text-secondary">มีสินค้า</span>';
            } else {
                $r = '<span class="text-danger">สินค้าหมด</span>';
            }

            echo <<<HTML
                <div class="row mt-3 pb-3 border-bottom">
                        <div class="col-12 col-md-2 mt-2"><img src="$src" id="product"></div>
                        <div class="col-12 col-md-10">
                            <h6><a href="product-detail.php?id=$p->id">$n</a></h6>
                            <p id="detail">$d</p>
                            <div class="d-flex justify-content-between align-items-end">
                                <div class="small">ราคา: $prc<br>$r</div>
                                <div>   
                                    <form method="post">
                                        <input type="hidden" name="product_id" id="pid" value="$p->id">
                                        <button class="btn btn-sm btn-transparent" title="ลบออกจากรายการที่ชอบ">
                                            <i class="fa fa-trash text-danger"></i>
                                        </button>                        
                                    </form>
                                </div>
                            </div>
                        </div>
                </div>
                HTML;
        }
        echo '</div>';          //container (grid)

        $mysqli->close();

        if ($page->total_pages() > 1) {
            $page->echo_pagenums_bootstrap();
        }
        ?>
    </div>



    <!-- footer -->
    <?php require 'bs_footer.php' ?>

</body>

</html>