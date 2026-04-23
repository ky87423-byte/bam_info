<!DOCTYPE HTML>
<html lang="ko" id="no-fouc">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible">
		<meta name="Generator" content="">
		<meta name="Author" content="">
		<meta name="Keywords" content="">
		<meta name="Description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<script src="../../_helpers/_js/jquery-3.5.1.js"></script>
		<link rel="stylesheet" type="text/css" href="../../css/admin_style.css">
		<link rel="stylesheet" type="text/css" href="../../css/default.css">

	</head>
	
	<script type="text/javascript">
	$(function(){
		var para = location.href.split("#");
		var para2 = para[1].split("-");
		$(".menu_1d > li").removeClass("on");
		$(".menu_1d").find("ul.menu_2d").css({"display":"none"});
		$(".menu_1d > li#"+para2[0]+'-head').addClass("on");
		$(".menu_1d > li#"+para2[0]+'-head').find("ul.menu_2d").css({"display":"block"});
	});
	</script>
	<body>
		<div class="guide_wrap">
			<section class="guide_menu">
				<h2>메뉴얼</h2>
				<ul class="menu_1d">
					<li class="on" id="guide1-head"><span>상품관리</span>
						<ul class="menu_2d">
							<li><em class="col1"></em>상품관리</li>
							<li><a href="#guide1-1">상품관리 검색</a></li>
							<li><a href="#guide1-2">상품관리 안내</a></li>
							<li><a href="#guide1-3">상품등록</a></li>
							<li><a href="#guide1-4">이용후기 관리</a></li>
							<li><a href="#guide1-5">쿠폰사용 관리</a></li>
							<li><a href="#guide1-6">상품마감안내메일</a></li>
						</ul>
					</li>
					<li id="guide2-head"><span>환경설정</span>
						<ul class="menu_2d">
							<li><em class="col2"></em>환경설정</li>
							<li><a href="#guide2-1">기본정보설정</a></li>
							<li><a href="#guide2-2">등록폼 관리</a></li>
						</ul>
					</li>
					<li id="guide3-head"><span>회원관리</span>
						<ul class="menu_2d">
							<li><em class="col3"></em>회원관리</li>
							<li><a href="#guide3-1">전체회원 관리</a></li>
							<li><a href="#guide3-2">휴면회원 관리</a></li>
							<li><a href="#guide3-3">회원등급/포인트 설정</a></li>
							<li><a href="#guide3-4">회원메일발송</a></li>
							<li><a href="#guide3-5">회원문자발송</a></li>
							<li><a href="#guide3-6">회원쪽지발송</a></li>
						</ul>
					</li>
					<li id="guide4-head"><span>디자인관리</span>
						<ul class="menu_2d">
							<li><em class="col4"></em>디자인관리</li>
							<li><a href="#guide4-1">사이트 디자인 설정</a></li>
							<li><a href="#guide4-2">상품 서비스명 설정</a></li>
							<li><a href="#guide4-3">상품 등록 아이콘 설정</a></li>
							<li><a href="#guide4-4">메인 중앙 아이콘 설정</a></li>
							<li><a href="#guide4-5">배너 관리</a></li>
							<li><a href="#guide4-6">메일스킨 관리</a></li>
						</ul>
					</li>
					<li id="guide5-head"><span>결제관리</span>
						<ul class="menu_2d">
							<li><em class="col5"></em>결제관리</li>
							<li><a href="#guide5-1">결제페이지 설정</a></li>
							<li><a href="#guide5-2">서비스별 금액 설정</a></li>
						</ul>
					</li>
					<li id="guide6-head"><span>커뮤니티관리</span>
						<ul class="menu_2d">
							<li><em class="col6"></em>커뮤니티관리</li>
							<li><a href="#guide6-1">게시판관리</a></li>
							<li><a href="#guide6-2">게시판 노출 설정</a></li>
						</ul>
					</li>
				</ul>
			</section>
			
			<section class="guide_design">

				<!--상품관리-->
				<h3>상품관리</h3>				
				<div class="box_wrap" id="guide1-1">
					<dl>
						<dt class="col1">상품관리 검색</dt>
						<dd></dd>
					</dl>
					<div class="img_area">
						<img src="../../images/admini/guide1-1.gif" alt="">
					</div>
				</div>

				<div class="box_wrap" id="guide1-2">
					<dl>
						<dt class="col1">상품관리 안내</dt>
						<dd></dd>
					</dl>
					<div class="img_area">
						<img src="../../images/admini/guide1-2.gif" alt="">
					</div>
				</div>

				<div class="box_wrap" id="guide1-3">
					<dl>
						<dt class="col1">상품등록</dt>
						<dd></dd>
					</dl>
					<div class="img_area">
						<img src="../../images/admini/guide1-3.gif" alt="">
					</div>
				</div>

				<div class="box_wrap" id="guide1-4">
					<dl>
						<dt class="col1">이용후기 관리</dt>
						<dd></dd>
					</dl>
					<div class="img_area">
						<img src="../../images/admini/guide1-4.gif" alt="">
					</div>
				</div>

				<div class="box_wrap" id="guide1-5">
					<dl>
						<dt class="col1">쿠폰사용 관리</dt>
						<dd></dd>
					</dl>
					<div class="img_area">
						<img src="../../images/admini/guide1-5.gif" alt="">
					</div>
				</div>

				<div class="box_wrap" id="guide1-6">
					<dl>
						<dt class="col1">상품마감안내메일</dt>
						<dd></dd>
					</dl>
					<div class="img_area">
						<img src="../../images/admini/guide1-6.gif" alt="">
					</div>
				</div>

				<!--환경설정-->
				<h3>환경설정</h3>
				<div class="box_wrap" id="guide2-1">
					<dl>
						<dt class="col2">기본정보설정</dt>
						<dd></dd>
					</dl>
					<div class="img_area">
						<img src="../../images/admini/guide2-1.gif" alt="">
					</div>
				</div>

				<div class="box_wrap" id="guide2-2">
					<dl>
						<dt class="col2">등록폼 관리</dt>
						<dd></dd>
					</dl>
					<div class="img_area">
						<img src="../../images/admini/guide2-2.gif" alt="">
					</div>
				</div>


				<h3>회원관리</h3>
				<div class="box_wrap" id="guide3-1">
					<dl>
						<dt class="col3">전체회원 관리</dt>
						<dd></dd>
					</dl>
					<div class="img_area">
						<img src="../../images/admini/guide3-1.gif" alt="">
					</div>
				</div>

				<div class="box_wrap" id="guide3-2">
					<dl>
						<dt class="col3">휴면회원 관리</dt>
						<dd></dd>
					</dl>
					<div class="img_area">
						<img src="../../images/admini/guide3-2.gif" alt="">
					</div>
				</div>

				<div class="box_wrap" id="guide3-3">
					<dl>
						<dt class="col3">회원등급/포인트 설정</dt>
						<dd></dd>
					</dl>
					<div class="img_area">
						<img src="../../images/admini/guide3-3.gif" alt="">
					</div>
				</div>

				<div class="box_wrap" id="guide3-4">
					<dl>
						<dt class="col3">회원메일발송</dt>
						<dd></dd>
					</dl>
					<div class="img_area">
						<img src="../../images/admini/guide3-4.gif" alt="">
					</div>
				</div>

				<div class="box_wrap" id="guide3-5">
					<dl>
						<dt class="col3">회원문자발송</dt>
						<dd></dd>
					</dl>
					<div class="img_area">
						<img src="../../images/admini/guide3-5.gif" alt="">
					</div>
				</div>

				<div class="box_wrap" id="guide3-6">
					<dl>
						<dt class="col3">회원쪽지발송</dt>
						<dd></dd>
					</dl>
					<div class="img_area">
						<img src="../../images/admini/guide3-6.gif" alt="">
					</div>
				</div>

				<!--디자인관리-->

				<h3>디자인관리</h3>
				<div class="box_wrap" id="guide4-1">
					<dl>
						<dt class="col4">사이트 디자인 설정</dt>
						<dd></dd>
					</dl>
					<div class="img_area">
						<img src="../../images/admini/guide4-1.gif" alt="">
					</div>
				</div>

				<div class="box_wrap" id="guide4-2">
					<dl>
						<dt class="col4">상품 서비스명 설정</dt>
						<dd></dd>
					</dl>
					<div class="img_area">
						<img src="../../images/admini/guide4-2.gif" alt="">
					</div>
				</div>

				<div class="box_wrap" id="guide4-3">
					<dl>
						<dt class="col4">상품 등록 아이콘 설정</dt>
						<dd></dd>
					</dl>
					<div class="img_area">
						<img src="../../images/admini/guide4-3.gif" alt="">
					</div>
				</div>

				<div class="box_wrap" id="guide4-4">
					<dl>
						<dt class="col4">메인 중앙 아이콘 설정</dt>
						<dd></dd>
					</dl>
					<div class="img_area">
						<img src="../../images/admini/guide4-4.gif" alt="">
					</div>
				</div>

				<div class="box_wrap" id="guide4-5">
					<dl>
						<dt class="col4">배너 관리</dt>
						<dd></dd>
					</dl>
					<div class="img_area">
						<img src="../../images/admini/guide_banner.jpg" alt="">
					</div>
				</div>

				<div class="box_wrap" id="guide4-6">
					<dl>
						<dt class="col4">메일스킨 관리</dt>
						<dd></dd>
					</dl>
					<div class="img_area">
						<img src="../../images/admini/guide4-6.gif" alt="">
					</div>
				</div>

				<!--결제관리-->

				<h3>결제관리</h3>
				<div class="box_wrap" id="guide5-1">
					<dl>
						<dt class="col5">결제페이지 설정</dt>
						<dd></dd>
					</dl>
					<div class="img_area">
						<img src="../../images/admini/guide5-1.gif" alt="">
					</div>
				</div>
					
				<div class="box_wrap" id="guide5-2">
					<dl>
						<dt class="col5">서비스별 금액 설정</dt>
						<dd></dd>
					</dl>
					<div class="img_area">
						<img src="../../images/admini/guide5-2.gif" alt="">
					</div>
				</div>

				<!--커뮤니티관리-->

				<h3>커뮤니티관리</h3>
				<div class="box_wrap" id="guide6-1">
					<dl>
						<dt class="col6">게시판 관리</dt>
						<dd></dd>
					</dl>
					<div class="img_area">
						<img src="../../images/admini/guide6-1.gif" alt="">
					</div>
				</div>
					
				<div class="box_wrap" id="guide6-2">
					<dl>
						<dt class="col6">게시판 노출 설정</dt>
						<dd></dd>
					</dl>
					<div class="img_area">
						<img src="../../images/admini/guide6-2.gif" alt="">
					</div>
				</div>
				
			</section>
		</div>

		<script>
			$(".menu_1d>li").mouseover(function(){
				$(".menu_1d>li").removeClass("on");
				$(this).addClass("on");
				$(this).children('ul').show()
				$(this).siblings().children('ul').hide()
			});
		</script>
	</body>
</html>

