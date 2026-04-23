<?php
class nf_tag extends nf_util {

	var $date_btn = array("0"=>"오늘", "this week"=>"이번주", "this month"=>"이번달", "1 week"=>"1주일", "15 day"=>"15일", "1 month"=>"1개월", "3 month"=>"3개월", "6 month"=>"6개월");

	function __construct(){
	
	}

	function date_search($btn='') {
		ob_start();
?>
<input type="text" name="date1" class="datepicker_inp" value="<?php echo $this->get_html($_GET['date1']);?>" style="width:80px"> ~
<input type="text" name="date2" class="datepicker_inp" value="<?php echo $this->get_html($_GET['date2']);?>" style="width:80px">
<button type="button" class="black day" onClick="nf_util.date_search_click(this, '', '')">전체</button><?php $arr['date_tag'] = ob_get_clean();
	ob_start();
	$count = 0;
	if(is_array($this->date_btn)) { foreach($this->date_btn as $k=>$v) {
		switch($k) {
			case "0":
				$date1 = today;
				$date2 = today;
				break;
			case "this week":
				$date1 = date("Y-m-d", strtotime("-".(date("w")-1)." day"));
				$date2 = date("Y-m-d", strtotime($date1." 6 day"));
				break;
			case "this month":
				$date1 = date("Y-m-01");
				$date2 = date("Y-m-").date("t");
				break;
			default:
				$date1 = date("Y-m-d", strtotime("-".$k));
				$date2 = today;
				break;
		}

		if($btn && $btn<$count) break;

		?><button type="button" class="black day" onClick="nf_util.date_search_click(this, '<?php echo $date1;?>', '<?php echo $date2;?>')"><?php echo $v;?></button><?php
		$count++;
	} }
?>
<?php
		$arr['date_btn'] = ob_get_clean();
		$arr['tag'] = $arr['date_tag'].$arr['date_btn'];

		return $arr;
	}
}
?>