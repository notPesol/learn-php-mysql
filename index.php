<!DOCTYPE html>
<html lang="en">

<head>
    <!-- head -->
    <?php require 'bs_head.php' ?>

    <style>
        #d1 {
            max-width: 300px;
        }
    </style>

</head>

<body>
    <!-- navbar -->
    <?php require 'bs_navbar.php' ?>

    <div class="card-deck mt-5 justify-content-center">
        <?php
        require 'lib/pagination-v2.class.php';
        $page = new PaginationV2();

        $mysqli = new mysqli('localhost', 'root', 'admin_080', 'spn_store');

        $sql = "SELECT p.id, p.name, p.detail, p.price, p.remain, p.img_file, t.name as tname FROM product p LEFT JOIN type_of_product t ON p.type_id = t.id";

        $result = $page->query($mysqli, $sql, 3);
        while ($p = $result->fetch_object()) {
            $name = $p->name;
            $detail = $p->detail;
            $price = number_format($p->price);
            if (strlen($name) > 20) {
                $name = mb_substr($name, 0, 20) . '...';
            }

            if (strlen($detail) > 50) {
                $detail = mb_substr($detail, 0, 50) . '...';
            }

            echo <<<HTML
                <div id="d1" class="card border border-info mb-4 shadow">
                    <div class="card-body d-flex flex-column justify-content-between p-2">
                        <a class="mb-4" href="product-detail.php?id=$p->id">
                            <img src="product-images/$p->img_file" alt="รูปสินค้า" class="card-img-top d-block mt-2 mx-auto">
                        </a>
                        <h6 class="card-title text-success text-center">$name</h6>
                        <p class="text-info">{$price}฿ $p->tname</p>
                        <p>$detail</p>
                        <a class="btn btn-info btn-sm p-1" href="product-detail.php?id=$p->id">
                            <i class="fa fa-search-plus"></i>
                        </a>
                    </div>
                </div>
                HTML;
        }

        $mysqli->close();

        ?>
    </div> <br>

    <?php
    if ($page->total_pages() > 1) {
        $page->echo_pagenums_bootstrap();
    }
    ?>

    <!-- footer -->
    <?php require 'bs_footer.php' ?>



</body>

</html>