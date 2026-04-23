<?php
$count = 0;
if(is_array($nf_util->sns_arr)) { foreach($nf_util->sns_arr as $k=>$v) {
	$count++;
	if(!in_array($k, $env['sns_feed_arr'])) continue;
?>
<li><a href="javascript:void(0)" id="btn_sns_<?php echo $k;?>" onClick="nf_util.share_sns(this, '<?php echo $k;?>')"><img src="../images/icon/sns_<?php echo $k;?>.png" alt="<?php echo $v;?> 공유"></a></li>
<?php
} }
?>
<li><a class="a2a_dd" href="https://www.addtoany.com/share"><img src="../images/icon/sns_more.png" alt="sns 공유 더보기"></a></li>

<script>
	var a2a_config = a2a_config || {};
	a2a_config.locale = "ko";
	a2a_config.icon_color = "transparent";
	a2a_config.onclick = 1;
	a2a_config.num_services = 10;
	a2a_config.icon_color = "unset,#fff"; /* #d0d0d0  original */
	a2a_config.prioritize = ["facebook_messenger", "google_plus", "tumblr", "wordpress", "google_gmail", "evernote", "sms", "instapaper", "Linkedln", "facebook"];
</script>
<script async src="https://static.addtoany.com/menu/page.js"></script>

<?php if(in_array('kakao_talk', $env['sns_feed_arr'])) {?>
<script type="text/javascript">
// 카카오링크 버튼 생성
Kakao.Link.createDefaultButton({
	container: '#btn_sns_kakao_talk', // HTML에서 작성한 ID값
	objectType: 'feed',
	content: {
	title: _subject, // 보여질 제목
	description: _description, // 보여질 설명
	imageUrl: _img, // 콘텐츠 URL
	link: {
		mobileWebUrl: _link,
		webUrl: _link
	}
	}
});
</script>
<?php }?>