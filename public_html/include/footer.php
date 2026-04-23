	<footer>
		<div class="main_notice">
			<div class="wrap1400">
				<h3><a href="<?php echo NFE_URL;?>/board/notice_list.php">공지사항</a></h3>
				<ul class="notice_list cycle-slideshow"
				data-cycle-fx=scrollVert
				data-cycle-timeout=3000
				data-reverse="true"
				data-cycle-slides=">li"
				data-cycle-carousel-vertical=true
				data-cycle-prev=".btn_wrap .axi-keyboard-arrow-up"
				data-cycle-next=".btn_wrap .axi-keyboard-arrow-down"
				>
					<?php
					if(is_array($notice_array_query)) { foreach($notice_array_query as $k=>$v) {
					?>
					<li>
						<dl>
							<dt><a href="<?php echo NFE_URL;?>/board/notice_view.php?no=<?php echo $v['no'];?>" class="line1"><?php echo $v['wr_subject'];?></a></dt>
							<dd><?php echo date("Y.m.d", strtotime($v['wr_date']));?></dd>
						</dl>
					</li>
					<?php
					} }
					?>
				 </ul>
				 <div class="btn_wrap">
					 <button type="button"><i class="axi-keyboard-arrow-up"></i></button>
					 <button type="button"><i class="axi-keyboard-arrow-down"></i></button>
				</div>
			</div>
		</div>
		<div class="b_wrap">
			<div class="wrap1400">
				<div class="b_main_menu">
				<div class="b_menu">
					<p><a href="<?php echo NFE_URL;?>" class="footer_logo_link"><span class="footer_logo">BAM<em>tube</em></span></a></p>
						<ul>
							<li><a href="<?php echo NFE_URL;?>/service/company.php">회사소개</a></li>
							<li><a href="<?php echo NFE_URL;?>/service/advert.php">입점문의</a></li>
							<li><a href="<?php echo NFE_URL;?>/service/terms.php">이용약관</a></li>
							<li><a href="<?php echo NFE_URL;?>/service/board_criterion.php">게시판관리기준</a></li>
							<li class="boho"><a href="<?php echo NFE_URL;?>/service/privacy_policy.php">개인정보처리방침</a></li>
							<li><a href="<?php echo NFE_URL;?>/service/location_policy.php">위치정보수집</a></li>
						</ul>
					</div>
					<div class="customer_service">
						<h2>고객센터</h2>
						<p class="tell"><?php echo stripslashes($env['call_center']);?></p>
						<p class="business_hours">
							<?php echo stripslashes(nl2br($env['call_time']));?>
						</p>
					</div>
					<div class="inquiry">
						<div class="one_and_one">
							<a href="<?php echo NFE_URL;?>/service/cs_center.php">
								<div>
									<h2>1:1 문의하기</h2>
									<p>1:1 문의를 남겨주시면 성심성의껏 답변 드리겠습니다.</p>
								</div>
								<span></span>
							</a>
						</div>
						<div class="">
							<a href="<?php echo NFE_URL;?>/service/advert.php">
								<div>
									<h2>입점문의</h2>
									<p>홈페이지 입점으로 홍보효과를 누려보세요.</p>
								</div>
								<span></span>
							</a>
						</div>
					</div>
				</div>
				<div class="address"><?php echo stripslashes($env['content_bottom_site']);?></div>
				<div class="main_con_wrap3">
					<p><a href="https://netfu.co.kr/" target="_blank"><img src="../images/default/p_by.png" alt="넷퓨 업체홍보사이트, 업체홍보사이트제작, 업체홍보커뮤니티, 홈페이지제작, https://netfu.co.kr"></a></p>
				</div>
			</div>
			<!--//wrap1400-->
		</div>
		<!--//b_wrap-->
	</footer>
	<script type="text/javascript">
	nf_util.read_statistics();
	</script>
</div>
<!--//header_meta.php에 있는 sticky_wrap  div 마침-->

<?php if(is_demo) { ?>
<div class="sample_info">
	<p>
		현재 보고계신 샘플 사이트는<br>
		<b class="col1"> 넷퓨에서 제작한 사이트</b>입니다.
	</p>
	<div>
		<a href="https://netfu.co.kr/homepage/index.php?code=introduce3" target="_blank"><em>홈페이지 기능 안내<span>GO</span></em></a>
		<a class="netfu_go" href="https://netfu.co.kr/homepage/all_homepage.php?group=shop" target="_blank"><em>넷퓨 홈페이지 <span>GO</span></em></a>
		<p>문의전화 : <b>1544 - 9638</b></p>
	</div>
</div>
<?php } ?>
</body>
</html>

<?php
// : 점프함수 사이트 뜨고 나서 읽히기 위해서 [ 자동점프입니다. ]
job_jump_func("12 hour");
?>