<?php
if(!$env['google_recaptcha']) return false;
?>
<script src="https://www.google.com/recaptcha/api.js?render=<?php echo $nf_util->get_text($env['google_recaptcha_site']);?>"></script>
<input type="hidden" id="g-recaptcha" name="g-recaptcha">
<script type="text/javascript">
grecaptcha.ready(function() {
	grecaptcha.execute('<?php echo $nf_util->get_text($env['google_recaptcha_site']);?>', {action: 'homepage'}).then(function(token) {
		document.getElementById('g-recaptcha').value = token;
	});
});
</script>