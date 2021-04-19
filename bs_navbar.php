<?php
function is_active(...$file)
{
    $path = $_SERVER['PHP_SELF'];
    foreach ($file as $f) {
        if (stripos($path, $f)) {
            return 'active';
        }
    }
    return '';
}
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="index.php"><img src="image/logo.png" alt="logo" width="35"> &nbsp;
        <strong>SPN Store</strong></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item <?= is_active('/index.php') ?>">
                <a class="nav-link" href="index.php">หน้าแรก <span class="sr-only">(current)</span></a>
            </li>
            <?php if (!isset($_SESSION['member_name'])) {
                echo <<<HTML
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                สมาชิก
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="member-signin.php">ลงชื่อเข้าใช้</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="member-signup.php">สมัครสมาชิก</a>
                                
                            </div>
                        </li>
                HTML;
            } else {
                $member_name = mb_substr($_SESSION['member_name'], 0, 20);
                echo <<<HTML
                    <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                $member_name
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a href="#" class="dropdown-item w-auto">
                                ประวัติการสั่งซื้อ
                            </a>
                            <a href="wish-list.php" class="dropdown-item w-auto">
                                รายการที่ชอบ
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">
                                ข้อมูลส่วนตัว
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="member-signout.php">
                                ออกจากระบบ
                            </a>
                                
                            </div>
                        </li>
                    HTML;
            }

            ?>

        </ul>
        <form class="form-inline my-2 my-lg-0">
            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
    </div>
</nav>