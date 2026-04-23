<?php
include_once "../engine/_core.php";

$_site_title_ = '설문조사';
include '../include/header_meta.php';
include '../include/header.php';

$q = "nf_poll as np where 1 ".$$q_where_var;
$order = " order by np.`no` desc";
$total = $db->query_fetch("select count(*) as c from ".$q);

$_arr = array();
$_arr['num'] = 15;
$_arr['total'] = $total['c'];
$paging = $nf_util->_paging_($_arr);

$poll_query = $db->_query("select * from ".$q.$order." limit ".$paging['start'].", ".$_arr['num']);

$m_title = '설문조사';
include NFE_PATH.'/include/m_title.inc.php';
?>
<script type="text/javascript">
var click_poll = function(el, no, code) {
	var form = document.forms['fwrite'];

	var view_txt = $(el).closest(".qa-body-").attr("view");

	var obj = $(form).find("[name='poll["+no+"]']:checked");
	if(view_txt=='vote' && code=='vote') {
		if(!obj.val()) {
			alert("설문조사를 선택하셔야 투표할 수 있습니다.");
			return;
		}
	}

	$.post(root+"/include/regist.php", "mode=click_poll&code="+code+"&no="+no+"&val="+obj.val()+"&view="+view_txt, function(data){
		data = $.parseJSON(data);
		if(data.msg) alert(data.msg);
		if(data.move) location.href = data.move;
		if(data.js) eval(data.js);
	});
}
</script>

<div class="wrap1260 MAT20">
	<form name="fwrite" action="<?php echo NFE_URL;?>/include/regist.php" method="post">
	<input type="hidden" name="mode" value="" />
	<section class="poll sub">
		<p class="s_title">설문조사</p>
		<?php
		switch($_arr['total']<=0) {
			case true:
		?>
		<div class="no_poll">
			<p><i class="axi axi-exclamation-triangle"></i></p>
			<p>등록된 설문조사가 없습니다.</p>
		</div>
		<?php
			break;


			default:
				while($row=$db->afetch($poll_query)) {
					$poll_content = unserialize($row['poll_content']);
		?>
		<div class="qa qa-body-" view="vote">
			<div class="question">
				<dl>
					<dt>Q</dt>
					<dd><?php echo $nf_util->get_text($row['poll_subject']);?></dd>
				</dl>
				<span><?php echo $row['poll_wdate'];?> ~ <?php echo $row['poll_edate'];?></span>
			</div>
			<div class="answer">
				<ul class="answer-body-">
					<?php
					if(is_array($poll_content)) { foreach($poll_content as $k=>$v) {
					?>
					<li><label ><input type="radio" name="poll[<?php echo $row['no'];?>]" value="<?php echo ($k+1);?>"><?php echo $v;?></label></li>
					<?php
					} }?>
				</ul>
				<ul>
					<?php if($row['poll_wdate']<=today && $row['poll_edate']>=today) {?>
					<li class="vote btn-vote-"><button type="button" onClick="click_poll(this, '<?php echo $row['no'];?>', 'vote')">투표하기</button></li>
					<li class="result btn-result-"><button type="button" onClick="click_poll(this, '<?php echo $row['no'];?>', 'result')">결과보기</button></li>
					<?php } else {?>
					<li>투표가 마감되었습니다.</li>
					<?php }?>
				</ul>
			</div>
		</div>
		<?php
		/*
		<div class="qa"><!--결과보기 버튼 눌렀을때 표기-->
			<div class="question">
				<dl>
					<dt>Q</dt>
					<dd>다음중근무능률을 올리는데 가장 큰 영향을 미치는 것은? 결과보기 버튼 눌렀을때 표기</dd>
				</dl>
				<span><?php echo $row['poll_wdate'];?> ~ <?php echo $row['poll_edate'];?></span>
			</div>
			<div class="answer">
				<ul>
					<li>자율적인 출퇴근시간<p><span></span></p><em>85% [5표]</em></li>
					<li>자율적인 출퇴근시간<p><span></span></p><em>5% [3표]</em></li>
					<li>자율적인 출퇴근시간<p><span></span></p><em>0% [0표]</em></li>
					<li>자율적인 출퇴근시간<p><span></span></p><em>0% [0표]</em></li>
				</ul>
				<ul>
					<?php if($row['poll_wdate']<=today && $row['poll_edate']>=today) {?>
					<li class="vote"><button>투표하기</button></li>
					<?php } else {?>
					<li>투표가 마감되었습니다.</li>
					<?php }?>
				</ul>
			</div>
		</div>
		<?php
		*/
		}?>
		<?php
			break;
		}
		?>
	</section>
	</form>
</div>

<!--푸터영역-->
<?php //include '../include/footer.php'; ?>

