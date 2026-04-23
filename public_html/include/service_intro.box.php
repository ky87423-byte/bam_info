<script type="text/javascript">
var intro_service = function(el, code, service) {
	$(".popup_layer.product").css({"display":"none"});
	$(".service-"+code+"-"+service+"-").css({"display":"block"});
}

var intro_service_close = function(el) {
	$(el).closest(".popup_layer.product").css({"display":"none"});
}
</script>

<?php
// : 기업회원 서비스 모음
?>

<div class="popup_layer product service-shop-main-" style="display:none;">
	<div class="h6wrap">
		<h6 class="s_title">상품 미리보기</h6>
		<button type="button" onClick="intro_service_close(this)"><i class="axi axi-ion-close-round"></i></button>
	</div>
	<div class="scroll">
		<img src="../images/shop_main_product.jpg" alt="">
	</div>
	<ul class="btn">
		<li><button type="button" onClick="intro_service_close(this)">닫기</button></li>
	</ul>
</div>




<div class="popup_layer product hurry service-shop-jump-" style="display:none;">
	<div class="h6wrap">
		<h6 class="s_title">점프서비스 상품 미리보기</h6>
		<button type="button" onClick="intro_service_close(this)"><i class="axi axi-ion-close-round"></i></button>
	</div>
	<div class="scroll">
		<img src="../images/shop_jump_product.jpg" alt="">
	</div>
	<ul class="btn">
		<li><button type="button" onClick="intro_service_close(this)">닫기</button></li>
	</ul>
</div>




