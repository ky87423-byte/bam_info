<?php
$top_menu_code = $_GET['top_menu_code'];
if(!$top_menu_code) $top_menu_code = '100101';
$_SERVER['__USE_API__'] = array('jqueryui');
include '../include/header.php';

switch($_GET['mode']) {
	case "sel_service":
		$length = count($_GET['chk']);
		if($length<=0) {
			$arr['msg'] = "업체정보를 하나이상 선택해주시기 바랍니다.";
			$arr['move'] = $nf_util->page_back();
			die($nf_util->move_url($arr['move'], $arr['msg']));
		}
	break;

	default:
		$no = $_GET['no'] ? $_GET['no'] : $_GET['nos'];
		if(!$no) {
			$arr['msg'] = "업체정보를 선택하시고 이용해주셔야합니다.";
			$arr['move'] = $nf_util->page_back();
			die($nf_util->move_url($arr['move'], $arr['msg']));
		}

		$shop_row = $db->query_fetch("select * from nf_shop where `no`=".intval($_GET['no']));
		$shop_info = $nf_shop->shop_info($shop_row);
	break;
}
?>


<!--상품 서비스 승인-->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->
	<section class="employ_modify">
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->

		<form name="fwrite" action="../regist.php" method="post">
		<input type="hidden" name="mode" value="service_update" />
		<input type="hidden" name="kind" value="shop" />
		<?php
		if($_GET['mode']=='sel_service') {
		?>
		<input type="hidden" name="code" value="sel_service" />
		<?php
			if(is_array($_GET['chk'])) { foreach($_GET['chk'] as $k=>$v) {
		?>
			<input type="hidden" name="chk[]" value="<?php echo intval($v);?>" />
		<?php
			} }
		} else {?>
			<input type="hidden" name="no" value="<?php echo $no;?>" />
		<?php
		}?>
		<input type="hidden" name="prev_page" value="<?php echo urlencode($nf_util->page_back());?>" />
		<div class="conbox">
			<h6>상품 서비스기간 설정</h6>
			<table>
				<colgroup>
					<col width="10%">
					<col width="6%">
				</colgroup>
				<tbody>
					<?php
					if(is_array($nf_shop->service_name['shop']['main'])) { foreach($nf_shop->service_name['shop']['main'] as $k=>$v) {
						$date_val = $shop_row['wr_service0_'.$k]>'1970-01-01' ? $shop_row['wr_service0_'.$k] : "";
					?>
					<tr>
						<th class="bl"><?php echo $v;?></th>
						<th class="bl">기간</th>
						<td><input type="text" name="service[0_<?php echo $k;?>]" class="input20 datepicker_inp_enddate" value="<?php echo $date_val;?>"></td>
					</tr>
					<?php
					} }
					?>
					<tr>
						<th class="bl">점프</th>
						<th class="bl">건수</th>
						<td><input type="text" name="service[jump]" class="input20" value="<?php echo intval($shop_row['wr_service_jump_int']);?>"></td>
					</tr>
				</tbody>
			</table>

			<div class="flex_btn">
				<button type="submit" class="save_btn">서비스승인</button>
				<a href="<?php echo $nf_util->page_back();?>"><button type="button" class="cancel_btn">돌아가기</button></a>
			</div>
		</div>
		<!--//conbox-->
		</form>

	</section>

</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->