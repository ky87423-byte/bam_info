<?php
$add_cate_arr = array('job_tema');
include '../include/header_meta.php';
include '../include/header.php';
$m_title = "랭킹";

?>

<head>
    <link rel="stylesheet" href="ranking.css" />
</head>

<body>
    <section class="my_sub wrap1400">
        <?php
        include '../include/board_leftmenu.php';
        ?>

        <div class="my_con">
            <section class="commu view">
                <div class="table-wrap">
                    <table class="table">
                        <thead class="thead-dark">
                            <tr>
                                <th>순위</th>
                                <th>닉네임</th>
                                <th>포인트</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php include 'ranking.inc.php' ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </section>
</body>
<?php
include NFE_PATH . '/plugin/login/login_api.php';
include NFE_PATH . '/include/footer.php';
?>