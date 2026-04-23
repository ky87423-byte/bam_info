<script type="text/javascript">
var member_kind_array = {"company":"기업회원", "individual":"개인회원"};
</script>

<?php
if(in_array('naver', $env['sns_login_feed_arr'])) {
?>
<!-- 네이버 스크립트 -->
	<script src="https://static.nid.naver.com/js/naveridlogin_js_sdk_2.0.2.js" charset="utf-8"></script>

	<script type="text/javascript">

	var naverLogin = new naver.LoginWithNaverId({
		clientId: "<?php echo $env['naver_id'];?>", //내 애플리케이션 정보에 cliendId를 입력해줍니다.
		callbackUrl: "<?php echo $env['naver_redirect_uri'];?>", // 내 애플리케이션 API설정의 Callback URL 을 입력해줍니다.
		isPopup: true,
		callbackHandle: true
	});

	naverLogin.init();

	window.addEventListener('load', function () {
		naverLogin.getLoginStatus(function (status) {
			if (status) {
				var email = naverLogin.user.getEmail(); // 필수로 설정할것을 받아와 아래처럼 조건문을 줍니다.
				
				console.log(naverLogin.user); 
				
				if( email == undefined || email == null) {
					alert("이메일은 필수정보입니다. 정보제공을 동의해주세요.");
					naverLogin.reprompt();
					return;
				}
			} else {
				console.log("callback 처리에 실패하였습니다.");
			}
		});
	});


	var testPopUp;
	function openPopUp() {
		testPopUp= window.open("https://nid.naver.com/nidlogin.logout", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,width=1,height=1");
	}
	function closePopUp(){
		testPopUp.close();
	}

	function naverLogout() {
		openPopUp();
		setTimeout(function() {
			closePopUp();
		}, 1000);
	}
	</script>
<?php
}


if(in_array('kakao_talk', $env['sns_login_feed_arr'])) {
?>
	<script type="text/javascript" src="https://developers.kakao.com/sdk/js/kakao.js"></script>
	<script type="text/javascript">
	Kakao.init("<?php echo $env['kakao'];?>"); //발급받은 키 중 javascript키를 사용해준다.
	console.log(Kakao.isInitialized()); // sdk초기화여부판단
	//카카오로그인
	function kakaoLogin() {
		Kakao.Auth.login({
		  success: function (response) {
			Kakao.API.request({
			  url: '/v2/user/me',
			  success: function (response) {
				var response_text = JSON.stringify(response);
				$.post(root+"/plugin/login/regist.php", "json="+encodeURIComponent(response_text)+"&mode=sns_login_process&engine=kakao", function(data){
					data = $.parseJSON(data);
					if(data.msg) alert(data.msg);
					if(data.move) location.href = data.move;
				});
			  },
			  fail: function (error) {
				alert("카카오 로그인이 실패되었습니다.");
			  },
			})
		  },
		  fail: function (error) {
			console.log(error)
		  },
		});
	  }
	//카카오로그아웃  
	function kakaoLogout() {
		if (Kakao.Auth.getAccessToken()) {
		  Kakao.API.request({
			url: '/v1/user/unlink',
			success: function (response) {
				console.log(response)
			},
			fail: function (error) {
			  console.log(error)
			},
		  })
		  Kakao.Auth.setAccessToken(undefined)
		}
	  }
	</script>
<?php
}

if(in_array('facebook', $env['sns_login_feed_arr'])) {
?>
	<script>
	//기존 로그인 상태를 가져오기 위해 Facebook에 대한 호출
	function statusChangeCallback(res){
		statusChangeCallback(response);
	}

	function fnFbCustomLogin(){
		FB.login(function(response) {
			if (response.status === 'connected') {
				FB.api('/me', 'get', {fields: 'name,email'}, function(r) {
					console.log(r);
				})
			} else if (response.status === 'not_authorized') {
				// 사람은 Facebook에 로그인했지만 앱에는 로그인하지 않았습니다.
				alert('앱에 로그인해야 이용가능한 기능입니다.');
			} else {
				// 그 사람은 Facebook에 로그인하지 않았으므로이 앱에 로그인했는지 여부는 확실하지 않습니다.
				alert('페이스북에 로그인해야 이용가능한 기능입니다.');
			}
		}, {scope: 'public_profile,email'});
	}

	window.fbAsyncInit = function() {
		FB.init({
			appId      : '<?php echo $env['facebook_appid'];?>', // 내 앱 ID를 입력한다.
			cookie     : true,
			xfbml      : true,
			version    : 'v16.0'
		});
		FB.AppEvents.logPageView();
	};
	</script>

	<!--반드시 중간에 본인의 앱아이디를 입력하셔야 합니다.-->
	<script async defer crossorigin="anonymous" src="https://connect.facebook.net/ko_KR/sdk.js#xfbml=1&version=v16.0&appId=<?php echo $env['facebook_appid'];?>" nonce="SiOBIhLG"></script>
<?php
}?>