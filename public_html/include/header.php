<style type="text/css">
	.quick_my {
		display: none;
	}

	.m-submenu-child- {
		display: none;
	}

	.m-submenu-child-menu-child- {
		display: none;
	}
</style>


<header>
	<form action="<?php echo NFE_URL; ?>/include/location_view.php">
		<input type="hidden" name="code" value="location" />
		<div class="wrap1400">
			<p><a href="<?php echo NFE_URL; ?>/include/category_view.php?code=area"><i class="axi axi-location-on"></i> 업체 목록</a></p>
			<div class="search_top bt-search-box" style="width:32%;background:none;border:none;padding:0;">
				<input type="text" name="top_keyword" value="<?php echo $nf_util->get_html($_GET['top_keyword']); ?>"
					placeholder="업체명·지역 검색" style="height:36px;background:rgba(255,255,255,0.07);border:1px solid #333;border-radius:4px;color:#eee;padding:0 1rem;font-size:13px;width:calc(100% - 42px);">
				<button type="submit" style="width:38px;height:36px;background:var(--primary, #ff385c);border-radius:4px;color:#fff;font-size:1.6rem;margin-left:4px;"><i class="axi axi-search"></i></button>
			</div>
			<ul>
				<?php
				if (sess_user_uid) {
					?>
					<li><a href="<?php echo NFE_URL; ?>/board/chulsuk.php">출석체크</a></li>
					<li><a href="<?php echo NFE_URL; ?>/mypage/index.php">마이페이지</a></li>
					<li><a href="<?php echo NFE_URL; ?>/include/regist.php?mode=logout">로그아웃</a></li>
					<li style="color:#ff385c;font-weight:600;"><?php echo htmlspecialchars($member['mb_nick'] ?: $member['mb_id']); ?>님</li>
					<?php
				} else {
					?>
					<li><a href="<?php echo NFE_URL; ?>/member/login.php">로그인</a></li>
					<li><a href="<?php echo NFE_URL; ?>/member/register.php" style="color:#ff385c;font-weight:600;">회원가입</a></li>
					<?php
				} ?>
			</ul>
		</div>
	</form>
</header>

<!--모바일에서 돋보기 버튼 클릭시 노출되는 search 박스-->
<section class="m_search_bg" style="display:none;">
	<form action="<?php echo NFE_URL; ?>/include/location_view.php">
		<input type="hidden" name="code" value="location" />
		<div class="m_search">
			<input type="text" name="top_keyword" value="<?php echo $nf_util->get_html($_GET['top_keyword']); ?>"
				placeholder="업체명을 입력해주세요.">
			<div class="hot_search">
				<p>인기검색어</p>
				<ul class="hot_search_list">
					<?php
					if (is_array($top_search_arr)) {
						foreach ($top_search_arr as $k => $v) {
							?>
							<li><a
									href="<?php echo NFE_URL; ?>/include/location_view.php?code=location&top_keyword=<?php echo urlencode($v); ?>"><?php echo $v; ?></a>
							</li>
							<?php
						}
					} ?>
				</ul>
			</div>
		</div>
		<ul class="result">
			<li class="close"><button type="button" onClick="nf_util.openWin('.m_search_bg', 'none')">닫기</li>
			<li class="search_o"><button type="submit">검색</button></li>
		</ul>
	</form>
</section>


<!--모바일메뉴-->
<?php
$_mb_type_ = $member['mb_type'] ? $member['mb_type'] : 'company';
?>
<section class="m_nav m-menu-body-" style="display:none;"> <!-- style="display:none;" -->
	<div class="m_menu">
		<div class="m_top">
			<div class="">
				<?php if ($member['no']) { ?>
					<p><?php echo $member['mb_name']; ?>님 <span><a
								href="<?php echo NFE_URL; ?>/include/regist.php?mode=logout">로그아웃</a></span></p> <!--로그인 후-->
				<?php } else { ?>
					<p>로그인해 주세요<span><a href="<?php echo NFE_URL; ?>/member/login.php">로그인</a></span></p> <!--로그인 전-->
				<?php } ?>
				<button type="button" class="m_menu_close" onClick="nf_util.noneWin('.m-menu-body-')"><i
						class="axi axi-ion-close"></i></button>
			</div>
			<ul>
				<li style=""><a href="<?php echo NFE_URL; ?>/service/advert.php"><i class="axi axi-pencil"></i>입점문의</a>
				</li>
				<li style=""><a href="<?php echo NFE_URL; ?>/service/index.php"><i class="axi axi-paper"></i>서비스안내</a>
				</li>
				<li><a href="<?php echo NFE_URL; ?>/mypage/index.php">마이페이지</a></li>
			</ul>
		</div>
		<ul class="m_nav_1d">
			<li style=""><a href="#none"
					class="<?php echo $_COOKIE['_m-submenu-'] === (string) $k_cnt ? 'on' : ''; ?> m-submenu-" k=0>출석체크</a>
				<ul class="m-submenu-child- m_nav_2d"
					style="display:<?php echo $_COOKIE['_m-submenu-'] === (string) $k_cnt ? 'block' : 'none'; ?>;">
					<li><a href="<?php echo NFE_URL; ?>/board/chulsuk.php" class="arrow_no">출석체크 바로가기</a></li>
				</ul>
			</li>
			<?php $k_cnt++;?>
			<li><a href="#none" class="<?php echo !$_COOKIE['_m-submenu-'] ? 'on' : ''; ?> m-submenu-" k=<?php echo $k_cnt; ?>>지역별</a>
				<ul class="m-submenu-child- m_nav_2d"
					style="display:<?php echo !$_COOKIE['_m-submenu-'] ? 'block' : 'none'; ?>;">
					<li><a href="<?php echo NFE_URL; ?>/include/category_view.php?code=area" class="arrow_no">업체 전체보기</a>
					</li>

					<?php
					$k_cnt2 = 0;
					if (is_array($cate_p_array['area'][0])) {
						foreach ($cate_p_array['area'][0] as $k => $v) {
							?>
							<li><a href="#none" class="on m-submenu-child-menu-" k=<?php echo $k_cnt2; ?> parent="ul"><?php echo $v['wr_name']; ?></a>
								<ul class="m_nav_3d m-submenu-child-menu-child-"
									style="display:<?php echo $_COOKIE['_m-submenu-child-menu-'] === (string) $k_cnt2 ? 'block' : 'none'; ?>;">
									<li><a
											href="<?php echo NFE_URL; ?>/include/category_view.php?code=area&wr_area[]=<?php echo $v['wr_name']; ?>#employ-list-start-">-
											&nbsp;<?php echo $v['wr_name']; ?> 전체</a></li>
									<?php
									$area1 = $v['wr_name'];
									$nf_category->get_area($area1);
									$area_array = $cate_area_array['SI'][$area1];
									if (is_array($area_array)) {
										foreach ($area_array as $k2 => $v2) {
											?>
											<li><a
													href="<?php echo NFE_URL; ?>/include/category_view.php?code=area&wr_area[]=<?php echo $v['wr_name']; ?>&wr_area[]=<?php echo $v2['wr_name']; ?>#employ-list-start-">-
													&nbsp;<?php echo $v2['wr_name']; ?></a></li>
											<?php
										}
									} ?>
								</ul>
							</li>
							<?php
							$k_cnt2++;
						}
					} ?>
				</ul>
			</li>
			<?php
			$k_cnt = 1;
			if ($env['use_shop_tema']) {
				$k_cnt++;
				?>
				<li><a href="javascript:void(0)"
						class="<?php echo $_COOKIE['_m-submenu-'] === (string) $k_cnt ? 'on' : ''; ?> m-submenu-" k=<?php echo $k_cnt; ?>>테마별</a>
					<ul class="m-submenu-child- m_nav_2d"
						style="display:<?php echo $_COOKIE['_m-submenu-'] === (string) $k_cnt ? 'block' : 'none'; ?>;">
						<li><a href="<?php echo NFE_URL; ?>/include/category_view.php?code=tema" class="arrow_no">업체 전체보기</a>
						</li>
						<?php
						if (is_array($cate_p_array['job_tema'][0])) {
							foreach ($cate_p_array['job_tema'][0] as $k => $v) {
								?>
								<li><a href="<?php echo NFE_URL; ?>/include/category_view.php?code=tema&tema[]=<?php echo $k; ?>"
										class="on arrow_no"><?php echo $v['wr_name']; ?>(<?php echo $job_tema_cnt[$k]['c']; ?>)</a>
								</li>
								<?php
							}
						} ?>
					</ul>
				</li>
				<?php
			}
			if ($env['use_shop_industry']) {
				$k_cnt++;
				?>
				<li style=""><a href="#none"
						class="<?php echo $_COOKIE['_m-submenu-'] === (string) $k_cnt ? 'on' : ''; ?> m-submenu-" k=<?php echo $k_cnt; ?>>업종별</a>
					<ul class="m-submenu-child- m_nav_2d"
						style="display:<?php echo $_COOKIE['_m-submenu-'] === (string) $k_cnt ? 'block' : 'none'; ?>;">
						<li><a href="<?php echo NFE_URL; ?>/include/category_view.php?code=category" class="arrow_no">업체
								전체보기</a></li>
						<?php
						if (is_array($cate_p_array['job_part'][0])) {
							foreach ($cate_p_array['job_part'][0] as $k => $v) {
								?>
								<li><a href="<?php echo NFE_URL; ?>/include/category_view.php?code=category&category[]=<?php echo $k; ?>"
										class="arrow_no on"><?php echo $v['wr_name']; ?>(<?php echo $job_part_cnt[$k]['c']; ?>)</a>
								</li>
								<?php
							}
						} ?>
					</ul>
				</li>
				<?php
			}
			?>

			<?php /*<li style=""><a href="#none" class="<?php echo $_COOKIE['_m-submenu-']===(string)$k_cnt ? 'on' : '';?> m-submenu-" k=<?php echo $k_cnt;?>>내주변</a> ?>
			   <ul class="m-submenu-child- m_nav_2d" style="display:<?php echo $_COOKIE['_m-submenu-']===(string)$k_cnt ? 'block' : 'none';?>;">
				   <li><a href="<?php echo NFE_URL;?>/include/location_view.php?code=location" class="arrow_no">내주변 바로가기</a></li>
			   </ul>
		   </li>*/ ?>
			<?php
			if ($env['use_shop_menu_map']) {
				
				?>
				<li><a href="#none" class="<?php echo $_COOKIE['_m-submenu-'] === (string) $k_cnt ? 'on' : ''; ?> m-submenu-"
						k=<?php echo $k_cnt; ?>>지도검색</a>
					<ul class="m-submenu-child- m_nav_2d"
						style="display:<?php echo $_COOKIE['_m-submenu-'] === (string) $k_cnt ? 'block' : 'none'; ?>;">
						<li><a href="<?php echo NFE_URL; ?>/map/index.php" class="arrow_no">지도검색 바로가기</a></li>
					</ul>
				</li>
				
			<?php $k_cnt++;}

			if ($env['use_shop_guide']) {
				$k_cnt++;
				?>
				<li style=""><a href="#none"
						class="<?php echo $_COOKIE['_m-submenu-'] === (string) $k_cnt ? 'on' : ''; ?> m-submenu-" k=<?php echo $k_cnt; ?>>이용후기</a>
					<ul class="m-submenu-child- m_nav_2d"
						style="display:<?php echo $_COOKIE['_m-submenu-'] === (string) $k_cnt ? 'block' : 'none'; ?>;">
						<li><a href="<?php echo NFE_URL; ?>/include/review.php" class="arrow_no">이용후기 바로가기</a></li>
					</ul>
				</li>
				<?php
			}

			if (is_array($nf_board->board_menu_view[0])) {
				foreach ($nf_board->board_menu_view[0] as $k => $v) {
					$child_bo_table = $nf_board->board_botable_arr[$k];
					if (count($child_bo_table) <= 0)
						continue;
					$k_cnt++;
					?>
					<li><a href="javascript:void(0)"
							class="<?php echo $_COOKIE['_m-submenu-'] === (string) $k_cnt ? 'on' : ''; ?> m-submenu-" k=<?php echo $k_cnt; ?>><?php echo stripslashes($v['wr_name']); ?></a>
						<ul class="m-submenu-child- m_nav_2d"
							style="display:<?php echo $_COOKIE['_m-submenu-'] === (string) $k_cnt ? 'block' : 'none'; ?>;">
							<li><a
									href="<?php echo NFE_URL; ?>/board/index.php?cno=<?php echo $k; ?>"><?php echo stripslashes($v['wr_name']); ?></a>
								<ul class="m_nav_3d">
									<?php
									if (is_array($nf_board->board_botable_arr[$k])) {
										foreach ($nf_board->board_botable_arr[$k] as $k2 => $v2) {
											if (!$nf_board->board_menu[$k][$v2['code']]['wr_view'])
												continue; // : 게시판 2차메뉴 사용여부
											?>
											<li><a
													href="<?php echo NFE_URL; ?>/board/list.php?bo_table=<?php echo $v2['bo_table']; ?>"><?php echo stripslashes($v2['bo_subject']); ?></a>
											</li>
											<?php
										}
									} ?>
								</ul>
							</li>
						</ul>
					</li>
					<?php
				}
			}
			$k_cnt++;
			?>
			<li style=""><a href="#none"
					class="<?php echo $_COOKIE['_m-submenu-'] === (string) $k_cnt ? 'on' : ''; ?> m-submenu-" k=<?php echo $k_cnt; ?>>랭킹</a>
				<ul class="m-submenu-child- m_nav_2d"
					style="display:<?php echo $_COOKIE['_m-submenu-'] === (string) $k_cnt ? 'block' : 'none'; ?>;">
					<li><a href="<?php echo NFE_URL; ?>/board/ranking.php" class="arrow_no">랭킹 바로가기</a></li>
				</ul>
			</li>
			<?php /*<li><a href="#none" class="<?php echo $_COOKIE['_m-submenu-']===(string)$k_cnt ? 'on' : '';?> m-submenu-" k=<?php echo $k_cnt?>>할인쿠폰</a>
			   <ul class="m-submenu-child- m_nav_2d" style="display:<?php echo $_COOKIE['_m-submenu-']===(string)$k_cnt ? 'block' : 'none';?>;">
				   <li><a href="<?php echo NFE_URL;?>/service/event_list.php">할인쿠폰 바로가기</a></li> 
			   </ul>
		   </li>*/ ?>
			<?php
			$k_cnt++;
			?>
			<li style="display:<?php echo ($member['no']) ? 'block' : 'none'; ?>;"><a href="#none"
					class="<?php echo $_COOKIE['_m-submenu-'] === (string) $k_cnt ? 'on' : ''; ?> m-submenu-" k=<?php echo $k_cnt ?>>마이페이지</a>
				<ul class="m-submenu-child- m_nav_2d"
					style="display:<?php echo $_COOKIE['_m-submenu-'] === (string) $k_cnt ? 'block' : 'none'; ?>;">
					<li><a href="<?php echo NFE_URL; ?>/mypage/index.php" class="arrow_no">마이페이지 홈</a></li>
					<?php
					if ($member['mb_type'] == 'company') {
						if ($env['use_shop_write']) { ?>
							<li><a href="<?php echo NFE_URL; ?>/mypage/regist.php" class="arrow_no" k=0 parent="ul">업체등록</a></li>
						<?php } ?>
						<li><a href="<?php echo NFE_URL; ?>/mypage/list.php" class="arrow_no" k=1 parent="ul">업체등록현황</a></li>
					<?php } ?>
					<li><a href="<?php echo NFE_URL; ?>/mypage/mycoupon.php" class="arrow_no" k=2 parent="ul">할인쿠폰</a>
					</li>
					<?php if ($env['use_shop_guide']) { ?>
						<li><a href="<?php echo NFE_URL; ?>/mypage/review.php" class="arrow_no" k=3 parent="ul">이용후기</a></li>
					<?php } ?>
					<?php if ($env['use_message']) { ?>
						<li><a href="<?php echo NFE_URL; ?>/member/mail.php" class="arrow_no" k=4 parent="ul">쪽지관리</a></li>
					<?php } ?>
					<?php if ($env['use_shop_qna']) { ?>
						<li><a href="<?php echo NFE_URL; ?>/mypage/qna.php" class="arrow_no" k=4 parent="ul">Q&A</a></li>
					<?php } ?>
					<li><a href="<?php echo NFE_URL; ?>/mypage/scrap.php" class="arrow_no" k=4 parent="ul">스크랩</a></li>
					<li><a href="<?php echo NFE_URL; ?>/member/update_form.php" class="arrow_no" k=4
							parent="ul">회원정보수정</a></li>
				</ul>
			</li>
		</ul>
		<script type="text/javascript">
			nf_util.click_tab(".m-submenu-");
			nf_util.click_tab(".m-submenu-child-menu-");
		</script>
		<div class="m_bottom">
			<ul>
				<li>
					<a href="<?php echo NFE_URL; ?>/board/notice_list.php">
						<p><i class="axi axi-notifications-none"></i></p>
						<em>공지사항</em>
					</a>
				</li>
				<li>
					<a href="<?php echo NFE_URL; ?>/service/cs_center.php">
						<p><i class="axi axi-ion-headphone"></i></p>
						<em>고객문의</em>
					</a>
				</li>
				<?php if (is_array($nf_board->board_menu_view[0])) { ?>
					<li>
						<a href="<?php echo NFE_URL; ?>/board/index.php">
							<p><i class="axi axi-ion-chatbubbles"></i></p>
							<em>커뮤니티</em>
						</a>
					</li>
				<?php } ?>
			</ul>
		</div>
	</div>
</section>
<!--//모바일메뉴-->

<?php
include NFE_PATH . '/include/header_menu.php';
?>