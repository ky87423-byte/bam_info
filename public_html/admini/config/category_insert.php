<?php
include "../../engine/_core.php";
$kind_group_k = $nf_category->kind_arr[$_GET['code']][2];
$top_menu_code = "20020".($kind_group_k+1);
if($_GET['code']=='area') $top_menu_code = '200201';
if($_GET['code']=='job_part') $top_menu_code = '200202';
if($_GET['code']=='job_tema') $top_menu_code = '200203';
if($_GET['code']=='notice') $top_menu_code = '200204';
if($_GET['code']=='on2on') $top_menu_code = '200205';
if($_GET['code']=='shop_shop_report_reason') $top_menu_code = '200206';
if($_GET['code']=='email') $top_menu_code = '200207';
if($_GET['code']=='online') $top_menu_code = '500202';

include '../include/header.php';

$wr_type = $_GET['code'];
$category_this = $nf_category->kind_arr[$wr_type];

$cate_table = 'nf_category';
if($_GET['code']=='area') $cate_table = 'nf_area';

$q = $cate_table." where `wr_type`=? and `pno`=".intval($_POST['pno']);
$query = $db->_query("select * from ".$q." order by `wr_rank` asc", array($wr_type));
$lens = $db->num_rows($query);
?>
<style type="text/css">
.add_form_view { display:none; }
</style>
<!-- 공통적용 분류(근무형태) -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<form name="fwrite" method="post" onSubmit="return validate(this)" enctype="multipart/form-data">
	<input type="hidden" name="mode" value="category_insert" />
	<input type="hidden" name="wr_type" value="<?php echo $wr_type;?>" />
	<input type="hidden" name="ajax" value="1" />
	<input type="hidden" name="no" value="" />
	<input type="hidden" name="pno" value="" />
	<input type="hidden" name="depth" value="" />
	<input type="hidden" name="colspan" value="" />
	<div class="put_category_form" style="display:none;">
	</div>
	</form>

	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">

			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
				<?php if($_GET['code']=='online') { ?>
					<li>고객님의 입금계좌를 등록해주시면 해당 계좌정보는 결제페이지에서 무통장입금 결제시 노출됩니다.</li>
				<?php }else{ ?>
					<li>해당 페이지의 안내는 메뉴얼을 참조하세요<button type="button" class="s_basebtn4 gray2"  onclick="window.open('../pop/guide.php#guide2-2','window','width=1400, height=800,left=0, top=0, scrollbard=1, scrollbars=yes, resizable=yes')">메뉴얼</button></li>
				<?php } ?>
				</ul>
			</div>


			<?php
			//include "../include/category_group.php";
			?>

			<h6> 분류 :: <?php echo $nf_category->kind_arr[$_GET['code']][0];?></h6>

			<?php
			switch($category_this[1]<=1) {

				// : 1차 카테고리
				case  true:
			?>
			<div class="table_top_btn">
				<button type="button" onclick="nf_util.all_check('#check_all', '.chk_')" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" class="gray" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" no="<?php echo $row['no'];?>" mode="delete_select_category" url="../regist.php" check_code="checkbox" tag="chk[]"><strong>-</strong> 선택삭제</button>
				<button type="button" class="gray" onclick="nf_util.ajax_select_confirm(this, 'flist', '순서값을 저장하시겠습니까?')" no="<?php echo $row['no'];?>" hname="순서" mode="rank_update_category" url="../regist.php" para="wr_type=<?php echo $_GET['code'];?>" check_code="input" tag="rank[]"><strong>-</strong> 순서저장</button>
			</div>

			<form name="flist" action="../regist.php" method="post" enctype="multipart/form-data">
			<input type="hidden" name="mode" value="" />
			<table class="table4 category_in-">
				<colgroup>
					<col width="3%">
					<col width="5%">
					<col width="5%">
					<?php if(in_array($_GET['code'], array('job_date'))) {?><col width="5%"><?php }?>
					<?php if(in_array($_GET['code'], array('job_date'))) {?><col width="5%"><?php }?>
					<?php if(in_array($_GET['code'], array('online'))) {?><col width="10%"><?php }?>
					<col width="">
					<?php if(in_array($_GET['code'], array('online'))) {?><col width="15%"><?php }?>
					<?php if(!in_array($_GET['code'], array('job_resume_icon'))) {?><col width="7%"><?php }?>
					<col width="7%">
				</colgroup>
				<thead>
					<tr>
						<th><input type="checkbox" id="check_all" onClick="nf_util.all_check(this, '.chk_')"></th>
						<th>순서</th>
						<th>출력</th>
						<?php if(in_array($_GET['code'], array('job_date'))) {?><th>단기</th><?php }?>
						<?php if(in_array($_GET['code'], array('job_date'))) {?><th>장기</th><?php }?>
						<?php if(in_array($_GET['code'], array('online'))) {?><th>은행명</th><?php }?>
						<th><?php echo (in_array($_GET['code'], array('online'))) ? '계좌번호' : '분류명';?></th>
						<?php if(in_array($_GET['code'], array('online'))) {?><th>예금주</th><?php }?>
						<?php if(!in_array($_GET['code'], array('job_resume_icon'))) {?><th>수정</th><?php }?>
						<th>삭제</th>
					</tr>
				</thead>
				<tbody class="cate_list">
					<?php
					switch($lens<=0) {
						case true:
							$colspan = 6;
							if(in_array($_GET['code'], array('job_date'))) $colspan = 8;
							if(in_array($_GET['code'], array('online'))) $colspan = 9;
							ob_start();
					?>
					<tr>
						<td class="no_list not_list-" colspan="<?php echo $colspan;?>"></td>
					</tr>
					<?php
							$not_list_tag = ob_get_clean();
							echo $not_list_tag;
							break;

						default:
							while($row=$db->afetch($query)) {
								if(in_array($row['wr_type'], array('online'))) $wr_name_arr = json_decode($row['wr_name']);
								if(is_file(NFE_PATH.'/admini/include/category/'.$wr_type.'.inc.php'))
									include NFE_PATH.'/admini/include/category/'.$wr_type.'.inc.php';
								else
									include NFE_PATH.'/admini/include/category/one_basic.inc.php';
							}
							break;
					}
					?>
				</tbody>
				<tbody>
					<?php
					$colspan = 3;
					if(in_array($_GET['code'], array('job_date'))) $colspan = 5;
					if(in_array($_GET['code'], array('online'))) $colspan = 3;
					?>
					<tr class="add_list tac">
						<td colspan="<?php echo $colspan;?>">분류명 입력</td>
						<?php
						switch($_GET['code']) {
							case 'online':
						?>
						<td><input type="text" name="subject[]" value="" hname="은행명" onKeypress="if(event.keyCode==13) return nf_category.insert(this)"></td>
						<td><input type="text" name="subject[]" value="" hname="계좌번호" onKeypress="if(event.keyCode==13) return nf_category.insert(this)"></td>
						<td><input type="text" name="subject[]" value="" hname="예금주" onKeypress="if(event.keyCode==13) return nf_category.insert(this)"></td>
						<?php
							break;

							case "job_resume_icon":
						?>
						<td><input type="file" name="attach" /></td>
						<?php
							break;

							default:
						?>
						<td><input type="text" name="subject" id="one_cate_subject-" hname="분류명" autofocus value="" onKeypress="if(event.keyCode==13) return nf_category.insert(this)"></td>
						<?php
							break;
						}?>
						<td colspan="2"><button type="button" class="gray common" cate='one' onClick="nf_category.insert(this)"><i class="axi axi-ion-arrow-up-a"></i> 등록</button></td>
					</tr>
				</tbody>
			</table>
			<div class="table_top_btn bbn">
				<button type="button" onclick="nf_util.all_check('#check_all', '.chk_')" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" class="gray" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" no="<?php echo $row['no'];?>" mode="delete_select_category" url="../regist.php" check_code="checkbox" tag="chk[]"><strong>-</strong> 선택삭제</button>
				<button type="button" class="gray" onclick="nf_util.ajax_select_confirm(this, 'flist', '순서값을 저장하시겠습니까?')" no="<?php echo $row['no'];?>" hname="순서" mode="rank_update_category" url="../regist.php" para="wr_type=<?php echo $_GET['code'];?>" check_code="input" tag="rank[]"><strong>-</strong> 순서저장</button>
			</div>
			</form>
			<?php
				break;




				// : 다중 카테고리
				default:
					$cate_txt = $nf_category->kind_arr[$_GET['code']][0];
					include NFE_PATH.'/admini/include/category.multi.inc.php';
				break;
			}
			?>
		</div>
		<!--//conbox-->
	</section>

	<table class="not_list_tag-" style="display:none;">
	<tbody>
	<?php echo $not_list_tag;?>
	</tbody>
	</table>


</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->