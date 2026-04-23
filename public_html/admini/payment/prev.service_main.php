<?php
$top_menu_code = '500104';
include '../include/header.php';

if(!$_GET['type']) {
	$_GET['code'] = 'employ';
	$_GET['type'] = '0_0';
}
?>


<!-- 서비스별금액설정 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>- 유료 상품 사용여부 및 금액/기간을 설정하는 페이지입니다.</li>
					<li>- 각각의 유료상품 서비스별 유무료설정 및 기간, 할인율등을 설정하실수 있습니다.</li>
					<li>- 채용, 이력서 옵션서비스에서 아이콘은 직접 등록/수정/삭제 가능하며, 형광펜은 색상 설정이 가능합니다.</li>
				</ul>
			</div>

			<div class="ass_list">
				<table>
					<colgroup>
						<col width="10%">
					</colgroup>
					<tr>
						<th>메인 페이지 채용정보</th>
						<td>
							<ul>
								<?php
								$count = 0;
								if(is_array($nf_job->service_name['employ']['main'])) { foreach($nf_job->service_name['employ']['main'] as $k=>$v) {
									$service_k = '0_'.$k;
									$on = $service_k==$_GET['type'] && $_GET['code']=='employ' ? 'on' : '';
								?>
								<li class="<?php echo $on;?>"><a href="./service_pay_config.php?code=employ&type=<?php echo $service_k;?>"><?php echo $v;?></a></li>
								<?php
									$count++;
								} }
								$service_k = '0_'.$k;
								$on = $service_k==$_GET['type'] && $_GET['code']=='employ' ? 'on' : '';
								?>
								<li class="<?php echo $on;?>"><a href="./service_pay_config.php?code=employ&type=<?php echo $service_k;?>">일반리스트</a></li>
								<?php
								if(is_array($nf_job->service_etc)) { foreach($nf_job->service_etc as $k=>$v) {
									$service_k = '0_'.$k;
									$on = $service_k==$_GET['type'] && $_GET['code']=='employ' ? 'on' : '';
								?>
								<li class="<?php echo $on;?>"><a href="./service_pay_config.php?code=employ&type=<?php echo $service_k;?>"><?php echo $v;?></a></li>
								<?php
								} }
								?>
							</ul>
						</td>
					</tr>
					<tr>
						<th>메인 페이지 인재정보</th>
						<td>
							<ul>
								<?php
								$count = 0;
								if(is_array($nf_job->service_name['resume']['main'])) { foreach($nf_job->service_name['resume']['main'] as $k=>$v) {
									$service_k = '0_'.$k;
									$on = $service_k==$_GET['type'] && $_GET['code']=='resume' ? 'on' : '';
								?>
								<li class="<?php echo $on;?>"><a href="./service_pay_config.php?code=resume&type=<?php echo $service_k;?>"><?php echo $v;?></a></li>
								<?php
									$count++;
								} }
								$service_k = '0_'.$k;
								$on = $service_k==$_GET['type'] && $_GET['code']=='resume' ? 'on' : '';
								?>
								<li class="<?php echo $on;?>"><a href="./service_pay_config.php?code=resume&type=<?php echo $service_k;?>">일반리스트</a></li>
								<?php
								if(is_array($nf_job->service_etc)) { foreach($nf_job->service_etc as $k=>$v) {
									$service_k = '0_'.$k;
									$on = $service_k==$_GET['type'] && $_GET['code']=='resume' ? 'on' : '';
								?>
								<li class="<?php echo $on;?>"><a href="./service_pay_config.php?code=resume&type=<?php echo $service_k;?>"><?php echo $v;?></a></li>
								<?php
								} }
								?>
							</ul>
						</td>
					</tr>
					<tr>
						<th>서브 페이지 채용정보</th>
						<td>
							<ul>
								<?php
								$count = 0;
								if(is_array($nf_job->service_name['employ']['sub'])) { foreach($nf_job->service_name['employ']['sub'] as $k=>$v) {
									$service_k = '1_'.$k;
									$on = $service_k==$_GET['type'] && $_GET['code']=='employ' ? 'on' : '';
								?>
								<li class="<?php echo $on;?>"><a href="./service_pay_config.php?code=employ&type=<?php echo $service_k;?>"><?php echo $v;?></a></li>
								<?php
									$count++;
								} }
								$service_k = '1_'.$k;
								$on = $service_k==$_GET['type'] && $_GET['code']=='employ' ? 'on' : '';
								?>
								<li class="<?php echo $on;?>"><a href="./service_pay_config.php?code=employ&type=<?php echo $service_k;?>">일반리스트</a></li>
								<?php
								if(is_array($nf_job->service_etc)) { foreach($nf_job->service_etc as $k=>$v) {
									$service_k = '1_'.$k;
									$on = $service_k==$_GET['type'] && $_GET['code']=='employ' ? 'on' : '';
								?>
								<li class="<?php echo $on;?>"><a href="./service_pay_config.php?code=employ&type=<?php echo $service_k;?>"><?php echo $v;?></a></li>
								<?php
								} }
								?>
							</ul>
						</td>
					</tr>
					<tr>
						<th>서브 페이지 인재정보</th>
						<td>
							<ul>
								<?php
								$count = 0;
								if(is_array($nf_job->service_name['resume']['main'])) { foreach($nf_job->service_name['resume']['main'] as $k=>$v) {
									$service_k = '1_'.$k;
									$on = $service_k==$_GET['type'] && $_GET['code']=='resume' ? 'on' : '';
								?>
								<li class="<?php echo $on;?>"><a href="./service_pay_config.php?code=resume&type=<?php echo $service_k;?>"><?php echo $v;?></a></li>
								<?php
									$count++;
								} }
								$service_k = '1_'.$k;
								$on = $service_k==$_GET['type'] && $_GET['code']=='resume' ? 'on' : '';
								?>
								<li class="<?php echo $on;?>"><a href="./service_pay_config.php?code=resume&type=<?php echo $service_k;?>">일반리스트</a></li>
								<?php
								if(is_array($nf_job->service_etc)) { foreach($nf_job->service_etc as $k=>$v) {
									$service_k = '1_'.$k;
									$on = $service_k==$_GET['type'] && $_GET['code']=='resume' ? 'on' : '';
								?>
								<li class="<?php echo $on;?>"><a href="./service_pay_config.php?code=resume&type=<?php echo $service_k;?>"><?php echo $v;?></a></li>
								<?php
								} }
								?>
							</ul>
						</td>
					</tr>
					<tr>
						<th>채용정보 옵션서비스</th>
						<td>
							<ul>
								<?php
								if(is_array($nf_job->etc_service)) { foreach($nf_job->etc_service as $k=>$v) {
									$service_k = $k;
									$on = $service_k==$_GET['type'] && $_GET['code']=='employ' ? 'on' : '';
								?>
								<li class="<?php echo $on;?>"><a href="./service_pay_config.php?code=employ&type=<?php echo $service_k;?>"><?php echo $v;?></a></li>
								<?php
								} }
								?>
							</ul>
						</td>
					</tr>
					<tr>
						<th>인재정보 옵션서비스</th>
						<td>
							<ul>
								<?php
								if(is_array($nf_job->etc_service)) { foreach($nf_job->etc_service as $k=>$v) {
									$service_k = $k;
									$on = $service_k==$_GET['type'] && $_GET['code']=='resume' ? 'on' : '';
								?>
								<li class="<?php echo $on;?>"><a href="./service_pay_config.php?code=resume&type=<?php echo $service_k;?>"><?php echo $v;?></a></li>
								<?php
								} }
								?>
							</ul>
						</td>
					</tr>
					<tr>
						<th>기타 서비스</th>
						<td>
							<ul>
								<?php
								if(is_array($nf_job->member_service)) { foreach($nf_job->member_service as $k=>$v) {
									$service_k = $k;
									$on = $service_k==$_GET['type'] && $_GET['code']=='resume' ? 'on' : '';
								?>
								<li><a href="./service_pay_config.php?code=member&type=<?php echo $service_k;?>">이력서열람권</a></li>
								<?php
								} }
								?>
							</ul>
						</td>
					</tr>
				</table>
			</div>

			
			<h6>메인 페이지 채용정보 > 프라임</h6>
			<table class="">
				<colgroup>
					<col width="10%">
					<col width="%">
				</colgroup>
				<tr>
					<th>사용/미사용 설정</th>
					<td>
						<label for=""><input type="radio">사용</label>
						<label for=""><input type="radio">미사용</label>
					</td>
				</tr>
				<tr>
					<th>서비스 설정</th>
					<td>
						<input type="text" class="input5" placeholder="0">
						<select name="" id="" class="select5">
							<option value="">일</option>
						</select>
						<input type="text" class="input5 MAL10" placeholder="0">원,
						할인율 <input type="text" class="input5" placeholder="0"> %
						<button class="blue common"><strong>+</strong> 등록 or 수정</button> <!--리스트 수정버튼 눌렀을때, '수정'텍스트로 변환-->
						<span>* 금액을 '0'으로 등록시 무료로 설정 됩니다</span>
					</td>
				</tr>
				<tr>
					<th>서비스금액</th>
					<td>
						<table class="table3 tac">
							<colgroup>
								<col width="5%">
								<col width="40">
								<col width="40">
								<col width="5%">
								<col width="10%">
							</colgroup>
							<tr>
								<th class="tac">순서</th>
								<th class="tac">설정값</th>
								<th class="tac">금액</th>
								<th class="tac">할인율</th>
								<th class="tac">편집</th>
							</tr>
							<tr>
								<td><input type="text"></td>
								<td>1일</td>
								<td><b class="red">무료</b>, 30,000원 , <span class="line-through">120,000</span> => 108,000원</td>
								<td>0%</td>
								<td>
									<button class="common gray"><i class="axi axi-plus2"></i>수정하기</button>
									<button class="common gray"><i class="axi axi-minus2"></i>삭제하기</button>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

			<h6>메인 페이지 채용정보 > 일반리스트</h6>
			<table class="">
				<colgroup>
					<col width="10%">
					<col width="%">
				</colgroup>
				<tr>
					<th>사용/미사용 설정</th>
					<td>
						<label for=""><input type="radio">유료</label>
						<label for=""><input type="radio">무료</label>
						<label for=""><input type="radio">심사후무료</label>
					</td>
				</tr>
				<tr>
					<th>서비스 설정</th>
					<td>
						<input type="text" class="input5" placeholder="0">
						<select name="" id="" class="select5">
							<option value="">일</option>
						</select>
						<input type="text" class="input5 MAL10" placeholder="0">원,
						할인율 <input type="text" class="input5" placeholder="0"> %
						<button class="blue common"><strong>+</strong> 등록 or 수정</button> <!--리스트 수정버튼 눌렀을때, '수정'텍스트로 변환-->
						<span>* 금액을 '0'으로 등록시 무료로 설정 됩니다</span>
					</td>
				</tr>
				<tr>
					<th>서비스금액</th>
					<td>
						<table class="table3 tac">
							<colgroup>
								<col width="5%">
								<col width="40">
								<col width="40">
								<col width="5%">
								<col width="10%">
							</colgroup>
							<tr>
								<th class="tac">순서</th>
								<th class="tac">설정값</th>
								<th class="tac">금액</th>
								<th class="tac">할인율</th>
								<th class="tac">편집</th>
							</tr>
							<tr>
								<td><input type="text"></td>
								<td>1일</td>
								<td><b class="red">무료</b>, 30,000원 , <span class="line-through">120,000</span> => 108,000원</td>
								<td>0%</td>
								<td>
									<button class="common gray"><i class="axi axi-plus2"></i>수정하기</button>
									<button class="common gray"><i class="axi axi-minus2"></i>삭제하기</button>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>


			<h6>기타 서비스 > 이력서열람권</h6>
			<table class="">
				<colgroup>
					<col width="10%">
					<col width="%">
				</colgroup>
				<tr>
					<th>사용/미사용 설정</th>
					<td>
						<label for=""><input type="radio">사용</label>
						<label for=""><input type="radio">미사용</label>
					</td>
				</tr>
				<tr>
					<th>서비스 설정</th>
					<td>
						<input type="text" class="input5" placeholder="0">
						<select name="" id="" class="select5">
							<option value="">일</option>
							<option value="">개월</option>
							<option value="">년</option>
							<option value="">건</option>
						</select>
						<input type="text" class="input5 MAL10" placeholder="0">원,
						사용기간 <input type="text" class="input5" placeholder="0">
						<select name="" id="" class="select5">
							<option value="">일</option>
							<option value="">개월</option>
							<option value="">년</option>
							<option value="">건</option>
						</select>
						할인율 <input type="text" class="input5" placeholder="0"> %
						<button class="blue common"><strong>+</strong> 등록 or 수정</button> <!--리스트 수정버튼 눌렀을때, '수정'텍스트로 변환-->
						<span>* 금액을 '0'으로 등록시 무료로 설정 됩니다</span>
					</td>
				</tr>
				<tr>
					<th>서비스금액</th>
					<td>
						<table class="table3 tac">
							<colgroup>
								<col width="5%">
								<col width="40">
								<col width="40">
								<col width="5%">
								<col width="10%">
							</colgroup>
							<tr>
								<th class="tac">순서</th>
								<th class="tac">설정값</th>
								<th class="tac">금액</th>
								<th class="tac">할인율</th>
								<th class="tac">편집</th>
							</tr>
							<tr>
								<td><input type="text"></td>
								<td>1일</td>
								<td><b class="red">무료</b>, 30,000원 , <span class="line-through">120,000</span> => 108,000원</td>
								<td>0%</td>
								<td>
									<button class="common gray"><i class="axi axi-plus2"></i>수정하기</button>
									<button class="common gray"><i class="axi axi-minus2"></i>삭제하기</button>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>


		</div>
		<!--//conbox-->

		
		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->