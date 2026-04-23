<?php
include_once "../engine/_core.php";
$nf_member->check_login('individual');
?>
<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
  <script src="/_helpers/_js/jquery-3.5.1.js"></script>
 </head>
<body>
<style type="text/css">
@media print {
	.readBtn.clearfix { display:none; }
}
</style>
<script type="text/javascript">
var proof_click = function(code) {
	var form = document.forms['femail'];
	var para = $(form).serialize();
	$.post("<?php echo NFE_URL;?>/include/regist.php", para+"&mode=proof_email_send&code="+code, function(data){
		data = $.parseJSON(data);
		if(data.msg) alert(data.msg);
		if(data.move) location.href = data.move;
		if(data.js) eval(data.js);
	});
}
</script>

<?php
include NFE_PATH.'/include/job/proof_resume.inc.php';
?>

</body>
</html>