<?php
$add_cate_arr = array('job_tema');
include '../include/header_meta.php';
include '../include/header.php';

// -----------------------------
// ① 선택 날짜 받기 (없으면 오늘)
// -----------------------------
$search_date = $_GET['date'] ?? date("Y-m-d");

// -----------------------------
// ② 선택 날짜 기준으로 출석 데이터 조회
// -----------------------------
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $search_date)) {
    $search_date = date("Y-m-d"); // 잘못된 입력이면 오늘 날짜로 설정
}
$query = $db->_query("
    SELECT *
    FROM nf_chulsuk
    
    ORDER BY datetime DESC
"); //WHERE date = '{$search_date}'
?>

<body>
    <section class="my_sub wrap1400">
        <?php
        $m_title = "출석체크";
        include '../include/board_leftmenu.php';
        ?>

        <div class="my_con">
            <section class="commu view">
                <div id="reply_chulsuk_body-" class="reply_con">

                    <h3 style="color: #fff;">출석체크</h3>

                    <form method="get" action="">
                        <h3>
                            <em class="rpy_num" style="margin-left:0px;">
                                현재 날짜 <?php echo $search_date; ?>
                                <?php /*<input 
                                    name="date"
                                    class="datepicker_inp"
                                    value="<?php echo $search_date; ?>"
                                    style="color:#f24443; margin-bottom:5px; border:0; background-color:transparent;"
                                >
                                
                                <button style="
                                padding: 7px 18px;
                                background: #f24443;
                                color: white;
                                border: none;
                                border-radius: 8px;
                                font-size: 14px;
                                cursor: pointer;
                                box-shadow: 0 2px 5px rgba(0,0,0,0.15);
                                transition: 0.2s;
                            "
                            onmouseover="this.style.background='#d93b3a'"
                            onmouseout="this.style.background='#f24443'" type="submit">조회</button> */ ?>
                            </em>
                        </h3>
                        
                    </form>

                    <form name="fchulsuk" action="./regist.php" method="post"
                        onSubmit="return nf_util.ajax_submit(this)">
                        <input type="hidden" name="mode" value="chulsuk" />
                        <input type="hidden" name="code" value="chulsuk_insert" />

                        <div class="reply_con_write">
                            <div class="text_area">
                                <textarea name="wr_text" hname="댓글내용" needed placeholder="">출석</textarea>
                                <button >출석</button>
                            </div>
                        </div>
                    </form>

                    <ul class="reply_list">
                        <?php while ($row = $db->afetch($query)) { ?>
                            <li class="comment_li-" id="comment_li-<?php echo $row['no']; ?>">
                                <?php include NFE_PATH . '/board/chulsuk.inc.php'; ?>
                            </li>
                        <?php } ?>
                    </ul>

                </div>
            </section>
        </div>
    </section>
</body>

<?php
include NFE_PATH . '/plugin/login/login_api.php';
include NFE_PATH . '/include/footer.php';
?>
