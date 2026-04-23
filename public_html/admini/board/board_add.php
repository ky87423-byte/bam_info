<?php
$_SERVER['__USE_API__'] = array('editor');
$top_menu_code = '600101';
$add_cate_arr = array('notice');
include '../include/header.php';

$bo_row = $db->query_fetch("select * from nf_board where `no`=".intval($_GET['no']));
$bno = $bo_row['code'] ? $bo_row['code'] : $_GET['bno'];

$bo_page_rows = $bo_row['bo_page_rows']>0 ? $bo_row['bo_page_rows'] : 10;
$bo_new = $bo_row['bo_new'] ? $bo_row['bo_new'] : 24;
?>
<script type="text/javascript">
var use_attach = function(el) {
	if(el.checked) {
		$(".attach-").css({"display":"table-row"});
	} else {
		$(".attach-").css({"display":"none"});
	}
}

var check_bo_table_func = function(el) {
	var form = document.forms['fwrite'];
	var para = $(form).serialize();

	$.post("../regist.php", para+"&mode=check_bo_table", function(data) {
		data = $.parseJSON(data);
		if(data.msg) alert(data.msg);
		if(data.move) location.href = data.move;
		if(data.js) eval(data.js);
	});
}
</script>
<!-- 게시판 환경설정 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

<!--공지사항(notice_insert.php) 파일 복사해서 수정한 것이니 수정할 부분은 수정해주세요!!!-->
	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			
			<form name="fwrite" action="../regist.php" method="post" enctype="multipart/form-data" onSubmit="return nf_util.ajax_submit(this)">
			<input type="hidden" name="mode" value="board_write" />
			<input type="hidden" name="bno" value="<?php echo intval($bno);?>" />
			<input type="hidden" name="no" value="<?php echo intval($bo_row['no']);?>" />
			<h6>게시판 <?php echo $bo_row ? '등록' : '수정';?>하기</h6>
			<table>
				<colgroup>
					<col width="150">
					<col >
				</colgroup>
				<tr>
					<th><?php echo $icon_need;?>테이블명</th>
					<td>
						<?php if($bo_row['bo_table']) {?>
						<?php echo $nf_util->get_text($bo_row['bo_table']);?>
						<?php } else {?>
						<input type="text" name="bo_table" onkeyup="nf_util.input_text(this)" hname="테이블명" needed value="" option="engnumonly" class="input10">
						<input type="hidden" class="check_bo_table- dupl-hidden-" name="check_bo_table" value="" message="테이블명을 중복확인해주시기 바랍니다." needed="">
						<button type="button" onclick="check_bo_table_func(this)" class="base2 basebtn gray MAL5">중복확인</button>
						<span class="bojotext red">* 중복되지 않는 영문이나 숫자로 해야합니다.</span>
						<?php }?>
					</td>
				</tr>
				<tr>
					<th><?php echo $icon_need;?>게시판 이름</th>
					<td><input type="text" name="bo_subject" hname="게시판 이름" needed value="<?php echo $nf_util->get_html($bo_row['bo_subject']);?>"></td>
				</tr>
				<tr>
					<th><?php echo $icon_need;?>게시판 형태</th>
					<td>
						<?php
						$bo_type = $bo_row['bo_type'];
						if(!$bo_type) $bo_type = 'text';
						foreach($nf_board->bo_type as $k=>$v) {
							$checked = $bo_type==$k ? 'checked' : '';
						?>
						<label><input type="radio" name="bo_type" value="<?php echo $k?>" <?php echo $checked;?>><?php echo $v;?></label>
						<?php
						}?>
						<span class="bojotext blue">* 이미지형, 웹진형 선택시 첨부파일은 필수항목</span>
					</td>
				</tr>
				<tr>
					<th>페이지당 게시글수</th>
					<td><input type="text" name="bo_page_rows" hname="페이지당 게시글수" needed value="<?php echo intval($bo_page_rows);?>" class="input10"> 개/페이지당 <span class="bojotext blue">* 1 페이지당 출력될 게시글 수</span></td>
				</tr>
				<tr>
					<th>게시판 출력</th>
					<td><label><input type="checkbox" name="bo_board_view" value="1" <?php echo !$bo_row || $bo_row['bo_board_view'] ? 'checked' : '';?>>사용함</label> <span class="bojotext red">* 체크해제시 사이트에 해당 게시판/게시글들이 더이상 출력되지 않음</span></td>
				</tr>
				<tr>
					<th rowspan="7">권한설정</th>
				</tr>
				<tr>
					<td>
						리스트 접근
						<select name="bo_level[list]">
							<option value="0" <?php echo $bo_row['bo_list_level']==='0' ? 'selected' : '';?>>손님(1레벨)</option>
							<?php
							echo $nf_member->level_option($bo_row['bo_list_level']);
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						답변글 쓰기
						<select name="bo_level[reply]">
							<option value="0" <?php echo $bo_row['bo_reply_level']==='0' ? 'selected' : '';?>>손님(1레벨)</option>
							<?php
							echo $nf_member->level_option($bo_row['bo_reply_level']);
							?>
						</select>
						<span class="bojotext blue">* 1:1상담형은 적용되지 않습니다.</span>
					</td>
				</tr>
				<tr>
					<td>
						게시물 읽기
						<select name="bo_level[read]">
							<option value="0" <?php echo $bo_row['bo_read_level']==='0' ? 'selected' : '';?>>손님(1레벨)</option>
							<?php
							echo $nf_member->level_option($bo_row['bo_read_level']);
							?>
						</select>
						<span class="bojotext blue">* 1:1상담형은 적용되지 않습니다.</span>
					</td>
				</tr>
				<tr>
					<td>
						댓글 쓰기
						<select name="bo_level[comment]">
							<option value="0" <?php echo $bo_row['bo_comment_level']==='0' ? 'selected' : '';?>>손님(1레벨)</option>
							<?php
							echo $nf_member->level_option($bo_row['bo_comment_level']);
							?>
						</select>
						<span class="bojotext blue">* 1:1상담형은 적용되지 않습니다.</span>
					</td>
				</tr>
				<tr>
					<td>
						게시물 쓰기
						<select name="bo_level[write]">
							<option value="0" <?php echo $bo_row['bo_write_level']==='0' ? 'selected' : '';?>>손님(1레벨)</option>
							<?php
							echo $nf_member->level_option($bo_row['bo_write_level']);
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						비밀글 쓰기
						<select name="bo_level[secret]">
							<option value="0" <?php echo $bo_row['bo_secret_level']==='0' ? 'selected' : '';?>>손님(1레벨)</option>
							<?php
							echo $nf_member->level_option($bo_row['bo_secret_level']);
							?>
						</select>
						<span class="bojotext blue">* 1:1상담형은 적용되지 않습니다.</span>
					</td>
				</tr>
				<tr>
					<th rowspan="5">포인트설정</th>
				</tr>
				<tr>
					<td>게시물 읽기 <input type="text" name="bo_read_point" hname="게시물 읽기 포인트" onkeyup="this.value=this.value.number_format(this.value)" value="<?php echo number_format(intval($bo_row['bo_read_point']));?>" class="input10"> 포인트</td>
				</tr>				
				<tr>
					<td>게시물쓰기 <input type="text" name="bo_write_point" hname="게시물쓰기 포인트" onkeyup="this.value=this.value.number_format(this.value)" value="<?php echo number_format(intval($bo_row['bo_write_point']));?>" class="input10"> 포인트</td>
				</tr>
				<tr>
					<td>댓글쓰기 <input type="text" name="bo_comment_point" hname="댓글쓰기 포인트" onkeyup="this.value=this.value.number_format(this.value)" value="<?php echo number_format(intval($bo_row['bo_comment_point']));?>" class="input10"> 포인트</td>
				</tr>
				<tr>
					<td>다운로드 <input type="text" name="bo_download_point" hname="다운로드 포인트" onkeyup="this.value=this.value.number_format(this.value)" value="<?php echo number_format(intval($bo_row['bo_download_point']));?>" class="input10"> 포인트</td>
				</tr>
				<tr>
					<th>댓글기능 사용</th>
					<td><label><input type="checkbox" name="bo_use_comment" value="1" <?php echo !$bo_row || $bo_row['bo_use_comment'] ? 'checked' : '';?>>사용함</label> <span class="bojotext blue">* 게시판에 댓글 입력을 할수 있도록 설정</span></td>
				</tr>
				<tr>
					<th>추천 사용</th>
					<td><label><input type="checkbox" name="bo_use_good" value="1" <?php echo !$bo_row || $bo_row['bo_use_good'] ? 'checked' : '';?>>사용함</label></td>
				</tr>
				<tr>
					<th>비밀글 기능 사용</th>
					<td><label><input type="checkbox" name="bo_use_secret" value="1" <?php echo $bo_row['bo_use_secret'] ? 'checked' : '';?>>사용함</label></td>
				</tr>
				<tr>
					<th>작성자이름 출력</th>
					<td>
						<label><input type="radio" name="bo_use_name" value="0" checked>닉네임</label>
						<label><input type="radio" name="bo_use_name" value="1" <?php echo $bo_row['bo_use_name']==='1' ? 'checked' : '';?>>아이디</label>
						<label><input type="radio" name="bo_use_name" value="2" <?php echo $bo_row['bo_use_name']==='2' ? 'checked' : '';?>>이름</label>
						<label><input type="radio" name="bo_use_name" value="3" <?php echo $bo_row['bo_use_name']==='3' ? 'checked' : '';?>>익명</label>
						<span class="bojotext blue">* 게시물 작성자에 선택된 명칭으로 출력 (익명의 경우 '익명' 으로 출력됨)</span>
					</td>
				</tr>
				<tr>
					<th>NEW 아이콘 출력</th>
					<td><input type="text" name="bo_new" hname="NEW 아이콘 출력" value="<?php echo intval($bo_new);?>" class="input10"> 시간 <span class="bojotext blue">* 글 입력후 new 이미지를 출력하는 시간</span></td>
				</tr>
				<tr>
					<th>상세페이지 목록 출력</th>
					<td><label><input type="checkbox" name="bo_use_list_view" value="1" <?php echo $bo_row['bo_use_list_view'] ? 'checked' : '';?>>사용함</label> <span class="bojotext blue">* 게시물 상세페이지 하단에 목록 출력</span></td>
				</tr>
				<tr>
					<th>리스트 정렬 필드</th>
					<td>
						<select name="bo_sort_field">
							<option value="">wr_num, wr_reply : 기본</option>
							<option value="wr_datetime asc">wr_datetime asc : 날짜 이전것 부터</option>
							<option value="wr_datetime desc">wr_datetime desc : 날짜 최근것 부터</option>
							<option value="wr_hit asc, wr_num, wr_reply">wr_hit asc : 조회수 낮은것 부터</option>
							<option value="wr_hit desc, wr_num, wr_reply">wr_hit desc : 조회수 높은것 부터</option>
							<option value="wr_last asc">wr_last asc : 최근글 이전것 부터</option>
							<option value="wr_last desc">wr_last desc : 최근글 최근것 부터</option>
							<option value="wr_comment asc, wr_num, wr_reply">wr_comment asc : 코멘트수 낮은것 부터</option>
							<option value="wr_comment desc, wr_num, wr_reply">wr_comment desc : 코멘트수 높은것 부터</option>
							<option value="wr_good asc, wr_num, wr_reply">wr_good asc : 추천수 낮은것 부터</option>
							<option value="wr_good desc, wr_num, wr_reply">wr_good desc : 추천수 높은것 부터</option>
							<option value="wr_nogood asc, wr_num, wr_reply">wr_nogood asc : 비추천수 낮은것 부터</option>
							<option value="wr_nogood desc, wr_num, wr_reply">wr_nogood desc : 비추천수 높은것 부터</option>
							<option value="wr_subject asc, wr_num, wr_reply">wr_subject asc : 제목 내림차순</option>
							<option value="wr_subject desc, wr_num, wr_reply">wr_subject desc : 제목 오름차순</option>
							<option value="wr_name asc, wr_num, wr_reply">wr_name asc : 글쓴이 내림차순</option>
							<option value="wr_name desc, wr_num, wr_reply">wr_name desc : 글쓴이 오름차순</option>
							<option value="ca_name asc, wr_num, wr_reply">wr_category asc : 분류명 내림차순</option>
							<option value="ca_name desc, wr_num, wr_reply">wr_category desc : 분류명 오름차순</option>
						</select>
						<script type="text/javascript">
						$("[name='bo_sort_field']").val("<?php echo $bo_row['bo_sort_field'];?>");
						</script>
						<span class="bojotext blue">* 리스트에서 기본으로 정렬에 사용할 필드를 선택합니다. '기본'으로 사용하지 않으시는 경우 속도가 느려질 수 있습니다.</span>
					</td>
				</tr>
				<tr>
					<th>분류</th>
					<td><input type="text" name="bo_category_list" value="<?php echo $nf_util->get_html($bo_row['bo_category_list']);?>"> <span class="bojotext blue">* 입력시 ( '|' )로 구분</span></td>
				</tr>
				<tr>
					<th>첨부파일 사용</th>
					<td><label><input type="checkbox" name="bo_use_upload" value="1" onClick="use_attach(this)" <?php echo $bo_row['bo_use_upload'] ? 'checked' : '';?>>사용함</label> <span class="bojotext blue">* 이미지/웹진형 사용시 반드시 선택</span></td>
				</tr>
				<tr class="attach-" style="display:<?php echo $bo_row['bo_use_upload'] ? 'table-row' : 'none';?>;">
					<th>첨부파일 갯수</th>
					<td><input type="text" name="bo_upload_count" hname="첨부파일 갯수" value="<?php echo intval($bo_row['bo_upload_count']);?>" class="input10"> 개 <span class="bojotext red">* 최대 10개까지 설정가능</span></td>
				</tr>
				<tr class="attach-" style="display:<?php echo $bo_row['bo_use_upload'] ? 'table-row' : 'none';?>;">
					<th>첨부파일 최대크기</th>
					<td><input type="text" name="bo_upload_size" hname="첨부파일 갯수" onkeyup="this.value=this.value.number_format(this.value)" value="<?php echo number_format(intval($bo_row['bo_upload_size']));?>" class="input10"> MB/1파일</td>
				</tr>
				<tr class="attach-" style="display:<?php echo $bo_row['bo_use_upload'] ? 'table-row' : 'none';?>;">
					<th>첨부파일 추가확장자</th>
					<td><input type="text" name="bo_upload_ext" hname="첨부파일 추가확장자" value="<?php echo $nf_util->get_html($bo_row['bo_upload_ext']);?>"> <span class="bojotext blue">* 입력시 ( '|' )로 구분 (<?php echo implode(", ", $nf_util->photo_ext);?> 외 추가할 확장자)</span></td>
				</tr>
				<tr>
					<th>게시판 상단내용</th>
					<td><textarea type="editor" name="bo_content_head" hname="게시판 상단내용" style="height:200px;"><?php echo stripslashes($bo_row['bo_content_head']);?></textarea></td>
				</tr>
				<tr>
					<th>게시판 하단내용</th>
					<td><textarea type="editor" name="bo_content_tail" hname="게시판 하단내용" style="height:200px;"><?php echo stripslashes($bo_row['bo_content_tail']);?></textarea></td>
				</tr>
				<tr>
					<th>글작성 기본 내용</th>
					<td><textarea type="editor" name="bo_insert_content" hname="글작성 기본 내용" style="height:200px;"><?php echo stripslashes($bo_row['bo_insert_content']);?></textarea></td>
				</tr>
				<tr>
					<th>금지 단어 설정<span style="font-size:12px; color:#808080; margin-top:8px; display:block;">입력시 쉼표(,)로 구분</span></th>
					<td>
						<?php
						$bo_filter = $bo_row['bo_filter'];
						if(!$bo_filter) $bo_filter = '18아,18놈,18새끼,18년,18뇬,18노,18것,18넘,개년,개놈,개뇬,개새,개색끼,개세끼,개세이,개쉐이,개쉑,개쉽,개시키,개자식,개좆,게색기,게색끼,광뇬,뇬,눈깔,뉘미럴,니귀미,니기미,니미,도촬,되질래,뒈져라,뒈진다,디져라,디진다,디질래,병쉰,병신,뻐큐,뻑큐,뽁큐,삐리넷,새꺄,쉬발,쉬밸,쉬팔,쉽알,스패킹,스팽,시벌,시부랄,시부럴,시부리,시불,시브랄,시팍,시팔,시펄,실밸,십8,십쌔,십창,싶알,쌉년,썅놈,쌔끼,쌩쑈,썅,써벌,썩을년,쎄꺄,쎄엑,쓰바,쓰발,쓰벌,쓰팔,씨8,씨댕,씨바,씨발,씨뱅,씨봉알,씨부랄,씨부럴,씨부렁,씨부리,씨불,씨브랄,씨빠,씨빨,씨뽀랄,씨팍,씨팔,씨펄,씹,아가리,아갈이,엄창,접년,잡놈,재랄,저주글,조까,조빠,조쟁이,조지냐,조진다,조질래,존나,존니,좀물,좁년,좃,좆,좇,쥐랄,쥐롤,쥬디,지랄,지럴,지롤,지미랄,쫍빱,凸,퍽큐,뻑큐,빠큐,ㅅㅂㄹㅁ';
						?>
						<textarea name="bo_filter" cols="" rows="10"><?php echo stripslashes($bo_filter);?></textarea>
					</td>
				</tr>
			</table>

			<div class="flex_btn">
				<button type="submit" class="save_btn"><?php echo $bo_row ? '수정' : '저장';?>하기</button>
			</div>
		</div>
		</form>
		<!--//conbox-->


		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->