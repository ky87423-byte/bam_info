<?php
$_SERVER['__USE_API__'] = array('editor');
$top_menu_code = '600207';
include '../include/header.php';

$__code = 'send';
$nf_util->sess_page_save("ranking_list");

$query = $db->_query("
    SELECT 
        mb_id,
        mb_nick,
        mb_point,
        mb_level
    FROM nf_member
    WHERE mb_left = 0
    ORDER BY mb_point DESC
");
$rank = 1;
?>

<div class="wrap">
    <?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

    <section>
        <?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
        <div class="consadmin conbox">
            <h6>랭킹 관리</h6>
            <table class="table4">
                <colgroup>
                    <col width="3%">
                    <col width="10%">
                    <col width="10%">
                    <col width="10%">
                    <col width="">
                </colgroup>
                <thead>
                    <tr>
                        <th><input type="checkbox" id="check_all" onclick="nf_util.all_check(this, '.chk_')"></th>
                        <th>순위</th>
                        <th>아이디</th>
                        <th>닉네임</th>
                        <th>포인트</th>
                    </tr>
                </thead>
                <tbody class="tac">
                    <?php while ($row = $db->afetch($query)) {
                        if ($rank >= 100) break; // 100위까지만
                        ?>
                        <tr>
                            <td><input type="checkbox" name="chk[]" class="chk_" value="<?php echo $row['no']; ?>"></td>
                            <td><?php echo $rank ?></td>
                            <td><?php echo $row["mb_id"] ?></td>
                            <td><?php echo $row["mb_nick"] ?></td>
                            <td><?php echo $row["mb_point"] ?></td>
                        </tr>
                    <?php $rank++;
                }  ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
<?php include '../include/footer.php'; ?> <!--관리자 footer-->