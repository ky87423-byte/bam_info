<?php
class nf_payment extends nf_util {

	var $pg;
	var $pg_demo = false;
	var $pg_config;
	var $pg_test = false;
	var $attach = "/data/pg";

	var $option_arr = array();
	var $payment_basic_code = array('direct'=>'다이렉트결제');

	var $pg_type = array('nicepay'=>'나이스페이', 'kcp'=>'KCP', 'toss'=>'토스');
	var $pay_kind = array("admin"=>"관리자수동등록", "card"=>"신용카드", "direct"=>"실시간 계좌이체", "hphone"=>"핸드폰", "bank"=>"무통장입금");
	var $pay_status = array("0"=>"입금대기", "1"=>"결제완료"); // , "-1"=>"예약취소", "-2"=>"결제중", "-3"=>"예약취소요청", "-4"=>"입금대기초과정보", "-5"=>"결제실패"
	var $tax_status = array("0"=>"신청안함", "1"=>"신청중", "2"=>"처리완료", "-1"=>"취소", "-2"=>"불가");
	var $pay_tax_type = array('1'=>'소득공제용(일반개인용)', '2'=>'지출증빙용(사업자용)');
	var $pay_tax_num_type = array('0'=>'주민등록번호', '1'=>'휴대폰번호', '2'=>'카드번호');

	var $pg_oid_name = array('kcp'=>'ordr_idxx', 'nicepay'=>'Moid'); // : 주문번호 name값 이걸로 결제 주키값 가져옴
	var $pg_price_arr = array('kcp'=>'good_mny', "nicepay"=>"Amt", "toss"=>"amount"); // : post로 넘어온 pg사의 결제금액
	var $kcp_api = array("card"=>"100000000000", "direct"=>"010000000000", "hphone"=>"000010000000");
	var $kcp_m_api1 = array("card"=>"CARD", "direct"=>"BANK", "hphone"=>"MOBX");
	var $kcp_m_api2 = array("card"=>"card", "direct"=>"acnt", "hphone"=>"mobx");
	var $toss_api = array("card"=>"카드", "direct"=>"계좌이체", "hphone"=>"휴대폰");

	var $nicepay_api = array('card'=>'CARD', 'direct'=>'BANK', 'hphone'=>'CELLPHONE');

	function __construct() {
		global $db;

		// : 서비스정보
		$this->service_kind_arr = array();
		$service_query = $db->_query("select * from nf_service");
		while($row=$db->afetch($service_query)) {
			$this->service_kind_arr[$row['code']][$row['type']] = $row;
		}
	}

	function get_service_name($kind, $type, $service_k) {
		$class_k = 'nf_'.$kind;
		global $$class_k;
		$service_k_arr = explode("_", $service_k);

		$arr = array();
		$arr['txt'] = array();
		$first_service_txt = $$class_k->service_name_k[$type][$service_k_arr[0]];

		// : 열람권은 반대 [ 기업은 인재정보 봐야하고 개인은 채용정보 봐야하니 기업은 resume, 개인은 employ로 처리 ]
		$etc_service = $$class_k->etc_service;
		if(in_array($kind, array('shop'))) $etc_service['read'] = $$class_k->kind_of[$$class_k->kind_of_flip[$type]].'정보 열람권';

		if(array_key_exists($service_k_arr[0], $etc_service)) {
			$arr['txt'][] = $etc_service[$service_k_arr[0]];
		} else if(is_array($$class_k->service_etc) && array_key_exists($service_k_arr[1], $$class_k->service_etc)) {
			$arr['txt'][] = $$class_k->service_name_k_txt[$first_service_txt];
			$arr['txt'][] = $$class_k->service_etc[$service_k_arr[1]];
		} else {
			$arr['txt'][] = $$class_k->service_name_k_txt[$first_service_txt];
			$arr['txt'][] = $$class_k->service_name[$type][$first_service_txt][$service_k_arr[1]];
		}

		$arr['service_txt'] = implode(" ", $arr['txt']);
		return $arr;
	}

	function get_package($kind, $package_row) {
		$class_k = 'nf_'.$kind;
		global $db, $$class_k;
		$arr = array();
		$arr['service_txt_arr'] = array();

		$package_arr = $this->get_unse($package_row['wr_service']);

		if(is_array($package_arr)) { foreach($package_arr as $k=>$v) {
			if(!$v['use']) continue;
			$service_name = $this->get_service_name($kind, $package_row['wr_type'], $k);
			$arr['service_txt_arr'][] = $service_name['service_txt'].' <b>'.strtr(@implode("", $v['date']), $this->date_count_arr).'</b>';
		} }

		return $arr;
	}

	function get_service_info($row, $code) {
		$service_arr = array();
		$service_arr['date'] = array();
		$service_arr['text'] = array();
		$service_arr['count'] = 0;
		if(is_array($row)) { foreach($row as $k=>$v) {
			if(strpos($k, 'wr_service')!==false && strpos($k, '_value')===false && $row[$k]>=today && strpos($k, 'jump')===false) {
				if(in_array($k, array('wr_service_last'))) continue; // : 이 필드명은 남은서비스 값 출력하지 않습니다. 서비스를 사용하는지 안하는지 체크하는 기능
				$service_k = substr($k, 10);
				if(substr($service_k, 0, 1)=='_') $service_k = substr($service_k, 1);
				$service_name = $this->get_service_name('shop', $code, $service_k);
				$service_arr['date'][$service_k] = $row[$k];
				$service_arr['text'][$service_k] = $service_name['service_txt'];
				$service_arr['count']++;
			}
		} }
		return $service_arr;
	}

	function pg_config() {
		global $db, $env;
		
		$this->pg = $env['pg'];
		if(!$env['pg']) $this->pg = $env['pg'] = 'nicepay';
		$this->pg_method_arr = explode(",", $env['pg_method']);

		$pg_config_arr = unserialize(stripslashes($env['pg_config']));
		if(is_array($this->pg_type)) { foreach($this->pg_type as $k=>$v) {
			$pg_config_arr[$k] = (array)$pg_config_arr[$k];
		} }

		// : 서비스형인경우 아이디 서비스아이디로 수정
		if($this->pg_test && strpos($_SERVER['PHP_SELF'], NFE_URL.'/admini/payment/pg.php')===false) {
			$pg_config_arr['nicepay']['id'] = 'nicepay00m';
			$pg_config_arr['nicepay']['pw'] = '';
			$pg_config_arr['nicepay']['key'] = 'EYzu8jGGMfqaDEp76gSckuvnaHHu+bC4opsSN6lHv3b2lurNYkVXrZ7Z1AoqQnXI3eLuaUFyoRNC6FkrzVjceg==';

			$pg_config_arr['kcp']['id'] = 'T0000';
			$pg_config_arr['kcp']['key'] = '4Ho4YsuOZlLXUZUdOxM1Q7X__';
			$pg_config_arr['kcp']['cert'] = '-----BEGIN CERTIFICATE-----
MIIDgTCCAmmgAwIBAgIHBy4lYNG7ojANBgkqhkiG9w0BAQsFADBzMQswCQYDVQQGEwJLUjEOMAwGA1UECAwFU2VvdWwxEDAOBgNVBAcMB0d1cm8tZ3UxFTATBgNVBAoMDE5ITktDUCBDb3JwLjETMBEGA1UECwwKSVQgQ2VudGVyLjEWMBQGA1UEAwwNc3BsLmtjcC5jby5rcjAeFw0yMTA2MjkwMDM0MzdaFw0yNjA2MjgwMDM0MzdaMHAxCzAJBgNVBAYTAktSMQ4wDAYDVQQIDAVTZW91bDEQMA4GA1UEBwwHR3Vyby1ndTERMA8GA1UECgwITG9jYWxXZWIxETAPBgNVBAsMCERFVlBHV0VCMRkwFwYDVQQDDBAyMDIxMDYyOTEwMDAwMDI0MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAppkVQkU4SwNTYbIUaNDVhu2w1uvG4qip0U7h9n90cLfKymIRKDiebLhLIVFctuhTmgY7tkE7yQTNkD+jXHYufQ/qj06ukwf1BtqUVru9mqa7ysU298B6l9v0Fv8h3ztTYvfHEBmpB6AoZDBChMEua7Or/L3C2vYtU/6lWLjBT1xwXVLvNN/7XpQokuWq0rnjSRThcXrDpWMbqYYUt/CL7YHosfBazAXLoN5JvTd1O9C3FPxLxwcIAI9H8SbWIQKhap7JeA/IUP1Vk4K/o3Yiytl6Aqh3U1egHfEdWNqwpaiHPuM/jsDkVzuS9FV4RCdcBEsRPnAWHz10w8CX7e7zdwIDAQABox0wGzAOBgNVHQ8BAf8EBAMCB4AwCQYDVR0TBAIwADANBgkqhkiG9w0BAQsFAAOCAQEAg9lYy+dM/8Dnz4COc+XIjEwr4FeC9ExnWaaxH6GlWjJbB94O2L26arrjT2hGl9jUzwd+BdvTGdNCpEjOz3KEq8yJhcu5mFxMskLnHNo1lg5qtydIID6eSgew3vm6d7b3O6pYd+NHdHQsuMw5S5z1m+0TbBQkb6A9RKE1md5/Yw+NymDy+c4NaKsbxepw+HtSOnma/R7TErQ/8qVioIthEpwbqyjgIoGzgOdEFsF9mfkt/5k6rR0WX8xzcro5XSB3T+oecMS54j0+nHyoS96/llRLqFDBUfWn5Cay7pJNWXCnw4jIiBsTBa3q95RVRyMEcDgPwugMXPXGBwNoMOOpuQ==
-----END CERTIFICATE-----';
		}

		$this->pg_config = $pg_config_arr;
	}

	function get_oid($kind, $pno) {
		return $kind.'_'.$pno.'_'.today2;
	}

	function pg_start($arr) {
		global $env, $member, $pay_oid;
		if(in_Array($this->pg, array('kcp'))) $pg_m = is_mobile ? '.m' : '';

		ob_start();
			include NFE_PATH.'/plugin/PG/include/'.$this->pg.$pg_m.'.inc.php';
		$arr['tag'] = ob_get_clean();

		switch($this->pg) {
			case "kcp":
				if(is_mobile) {
					$arr['js'] .= '
					$(".pg_tag_put_").html(data.tag);
					call_pay_form();
					';
				} else {
					$arr['js'] .= '
					$(".pg_tag_put_").find("form").html(data.tag);
					var form = document.forms["order_info"];
					jsf__pay(form);
					';
				}

			break;


			case "nicepay":
				$ediDate = date("YmdHis");
				$hashString = bin2hex(hash('sha256', $ediDate.$this->pg_config[$this->pg]['id'].$arr['price'].$this->pg_config[$this->pg]['key'], true));

				$arr['js'] = '
				$(".pg_tag_put_").html(data.tag);
				var form = document.forms["payForm"];
				form.param_opt_2.value = "'.$arr['pno'].'";
				';

				if(is_mobile) {
					$arr['js'] .= '
					nicepayStart();
					';
				} else {
					$arr['js'] .= '
					nicepayStart();
					';
				}
			break;


			case "toss":
				$arr['js'] = '
				$(".pg_tag_put_").html(data.tag);
				toss_var();
				';
			break;
		}

		return $arr;
	}

	function get_pay_row($pno) {
		global $db;
		return $pay_row = $db->query_fetch("select * from nf_payment where `no`=".intval($pno));
	}

	function pay_info($row) {
		global $nf_util;
		if($row['no']) {
			$arr = $row;
			$arr['post_unse'] = $nf_util->get_unse($row['post_text']);
			$arr['pg_unse'] = $nf_util->get_unse($row['pg_text']);
			$arr['price_unse'] = $nf_util->get_unse($row['price_text']);
			$arr['pay_method_txt'] = ($row['pay_price']-$row['pay_dc'])<=0 && $row['pay_dc']>0 ? '포인트결제' : $this->pay_kind[$row['pay_method']];
			if(($row['pay_price']-$row['pay_dc'])<=0 && $row['pay_dc']<=0) $arr['pay_method_txt'] = '무료';

			switch($row['pay_type']) {
				case "shop":
					$arr['info_link'] = NFE_URL."/shop/index.php?no=".$row['pay_no'];
				break;
			}
		}

		return $arr;
	}


	// : pg값 저장하기
	function pg_process($pay_row, $pg="") {
		global $db;

		$update = $db->_query("update nf_payment set pg_text=? where `no`=".intval($pay_row['no']), array(serialize($pg)));

		return $arr;
	}
}
?>