<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="index.php"><img src="image/logo.png" alt="logo" width="35"> &nbsp;
        <strong>SPN Store</strong></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
            <?php if (!isset($_SESSION['user'])) {
                echo <<<HTML
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                สมาชิก
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="#">ลงชื่อเข้าใช้</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">สมัครสมาชิก</a>
                                
                            </div>
                        </li>
                HTML;
            } else {
                echo '<li class="nav-item">
                <a class="nav-link" href="#">ตะกร้าสินค้า</a>
            </li>';
            } ?>

        </ul>
        <form class="form-inline my-2 my-lg-0">
            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
    </div>
</nav>



<!DOCTYPE html>
<html lang="en">

<head>
    <!-- head -->
    <?php require 'bs_head.php' ?>

    <style>
        #img{
            max-width: 500px;
            max-height: 500px;
        }
    </style>

</head>

<body>
    <!-- navbar -->
    <?php require 'bs_navbar.php' ?>


    <button id="myBtn" class="btn btn-outline-primary" data-toggle="model" data-target="#myModel">show full picture</button>

    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">1</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <img id="img" src="product-images/1.jpg" alt="ภาพสินค้า">
                </div>
            </div>
        </div>
    </div>


    <!-- footer -->
    <?php require 'bs_footer.php' ?>
    <script>
        $(document).ready(function() {
            $("#myBtn").click(function() {
                $("#myModal").modal();
            });
        });
    </script>

</body>

</html>