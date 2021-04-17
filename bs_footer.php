<?php @session_start() ?>

<footer class="text-center mt-5 fixed-bottom text-white bg-dark py-1">
    &copy; SPN store &nbsp;&nbsp;&nbsp;

    <?php
    if (isset($_SESSION['admin'])) {
        echo <<<HTML
            <div class="dropup d-inline">
            <button class="btn btn-info btn-sm dropdown-toggle small" type="button" data-toggle="dropdown">admin</button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="admin-add-product.php">เพิ่มรายการสินค้า</a>
                <a class="dropdown-item" href="admin-signout.php">ออกจากระบบ</a>
            </div>
            </div>                  
            HTML;
    } else {
        echo '[<a href="admin-signin.php" class="text-warning">admin</a>]';
    }
    ?>
</footer>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
