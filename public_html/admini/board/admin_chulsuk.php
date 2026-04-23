<?php
$_SERVER['__USE_API__'] = array('editor');
$top_menu_code = '600206';
include '../include/header.php';

$__code = 'send';
$nf_util->sess_page_save("chulsuk_list");

$_where = "";

$q = "nf_chulsuk ".$_where;
$order = " order by `no` desc";
$total = $db->query_fetch("select count(*) as c from ".$q);

$_arr = array();
$_arr['num'] = 15;
if($_GET['page_row']>0) $_arr['num'] = intval($_GET['page_row']);
$_arr['total'] = $total['c'];
$paging = $nf_util->_paging_($_arr);

$chulsuk_query = $db->_query("select * from ".$q.$order." limit ".$paging['start'].", ".$_arr['num']);
?>
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->
	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
            <h6>출석부 관리<span>총 <b><?php echo number_format(intval($_arr['total']));?></b>건의 출석이 검색되었습니다.</span></h6>

            <div class="table_top_btn">
				<button type="button" onclick="nf_util.all_check('#check_all', '.chk_')" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" class="gray" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="<?php echo NFE_URL;?>/include/regist.php" mode="delete_select_chulsuk" tag="chk[]" check_code="checkbox"><strong>-</strong> 선택삭제</button>
			</div>
            <form name="flist" method="">
			<table class="table4">
				<colgroup>
					<col width="3%">
					<col width="10%">
					<col width="10%">
					<col width="">
					<col width="10%">
                    <col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th><input type="checkbox" id="check_all" onclick="nf_util.all_check(this, '.chk_')"></th>
						<th>아이디</th>
						<th>닉네임</th>
						<th>내용</th>
						<th>작성일</th>
                        <th>삭제</th>
					</tr>
				</thead>
                <tbody class="tac">
                    <?php while($row=$db->afetch($chulsuk_query)) {
					?>
                    <tr>
						<td><input type="checkbox" name="chk[]" class="chk_" value="<?php echo $row['no'];?>"></td>
                        <td><?php echo $row["id"] ?></td>
                        <td><?php echo $row["nick"] ?></td>
                        <td><?php echo $row["text"] ?></td>
                        <td><?php echo $row["datetime"] ?></td>
                        <td><button type="button" class="gray common" onclick="nf_util.ajax_post(this, '하나 삭제하시겠습니까?')" no="<?php echo $row['no'];?>" mode="delete_chulsuk" url="../regist.php"><i class="axi axi-minus2"></i> 삭제</button></td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
            <div><?php echo $paging['paging'];?></div>
        </form>
        </div>
    </section>
</div>
<?php include '../include/footer.php'; ?> <!--관리자 footer-->