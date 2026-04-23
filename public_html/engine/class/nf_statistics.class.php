<?php
class nf_statistics extends nf_util {

	var $time = 3; // 수집시간

	function __construct(){
	}


	// : 로그분석 저장
	function visit() {
		global $db;
		$arr = array();

		if($_COOKIE['visit_ip']!=$_SERVER['REMOTE_ADDR']) {

			$this->cookie_save('visit_ip', $_SERVER['REMOTE_ADDR'], 'yes', 3600*$this->time);

			$tmp_row = $db->query_fetch(" select max(`no`) as `max_no` from `nf_visit`");
			$visit_no = $tmp_row['max_no'] + 1;

			// $_SERVER 배열변수 값의 변조를 이용한 SQL Injection 공격을 막는 코드입니다. 110810
			$remote_addr = $_SERVER['REMOTE_ADDR'];
			$referer = $this->page_back();
			$user_agent = $_SERVER['HTTP_USER_AGENT'];

			$_val = array();
			$_val['no'] = $visit_no;
			$_val['visit_ip'] = $remote_addr;
			$_val['visit_date'] = today;
			$_val['visit_time'] = today_his;
			$_val['visit_referer'] = $referer;
			$_val['visit_referer_cnt'] = 1;
			$_val['visit_agent'] = $user_agent;
			$q = $db->query_q($_val);
			$arr['q'] = " insert into `nf_visit` set ".$q;
			$result = $db->_query($arr['q'], $_val);

			if($result) {
				// 1개월 이내 데이터 검색해서 중복되는 ip, referer 쿼리 => 카운트 업데이트
				$db->_query(" update `nf_visit` set `visit_ip_cnt`=`visit_ip_cnt`+1 where (`visit_date`>LAST_DAY(now()-interval 1 month) and `visit_date`<=LAST_DAY(now())) and `visit_ip`=?", array($remote_addr));

				$db->_query(" update `nf_visit` set `visit_referer_cnt`=`visit_referer_cnt`+1 where ( `visit_date`>LAST_DAY(now()-interval 1 month) and `visit_date`<=LAST_DAY(now()) ) and `visit_referer`=?", array($referer));

				$sum_query = $db->query_fetch(" select * from `nf_visit_sum` where  `visit_date` = '".today."'");

				if($sum_query) { //해당 날짜가 있을경우
					$db->_query(" update `nf_visit_sum` set `visit_count` = visit_count + 1 where `visit_date` = '".today."' ");
				}else{
					$db->_query(" insert `nf_visit_sum` ( `visit_count`, `visit_date`) values ( 1, '".today."' ) ");
				}

				// 오늘
				$row = $db->query_fetch(" select `visit_count` as cnt from `nf_visit_sum` where `visit_date` = '".today."'");
				$visit_today = intval($row['cnt']);

				// 어제
				$row = $db->query_fetch(" select `visit_count` as cnt from `nf_visit_sum` where `visit_date` = DATE_SUB('".today."', INTERVAL 1 DAY) ");
				$visit_yesterday = intval($row['cnt']);

				// 최근 1주일
				$row = $db->query_fetch(" select `visit_count` as cnt from `nf_visit_sum` where `visit_date` > DATE_ADD(now(), INTERVAL -7 DAY) ");
				$visit_week = intval($row['cnt']);

				// 최대
				$row = $db->query_fetch(" select max(`visit_count`) as cnt from `nf_visit_sum` ");
				$visit_max = intval($row['cnt']);

				// 전체
				$row = $db->query_fetch(" select sum(`visit_count`) as total from `nf_visit_sum` "); 
				$visit_sum = intval($row['total']);

				// 가장 많이 접속한 날짜
				$row = $db->query_fetch(" select * from `nf_visit_sum` order by `visit_count` desc limit 1 ");
				$visit_date = $row['visit_date'];

				// 가장 많이 접속한 요일
				$row_visit_ddate = date("w", strtotime($visit_date)); // 1:월,2:화,3:수,4:목,5:금,6:토.7:일  
				$visit_weeks = $this->week_arr[$row_visit_ddate];


				$visit = "today:$visit_today,yesterday:$visit_yesterday,1week:$visit_week,max:$visit_max,total:$visit_sum,max_date:$visit_date,max_week:$visit_weeks";

				// 기본설정 테이블에 방문자수를 기록한 후 
				// 방문자수 테이블을 읽지 않고 출력한다.
				// 쿼리의 수를 상당부분 줄임
				$db->_query(" update `nf_config` set `wr_visit_count`= ?", array($visit));
			}
		}

		return $arr;
	}

	// 사이트 접속현황 추출
	function get_visits( ){
		global $env;
		$visit_info = explode(',',$env['wr_visit_count']);
		$visit_count = count($visit_info);

		$result = array();
		for($i=0;$i<$visit_count;$i++){
			$visit_field = explode(':',$visit_info[$i]);
			switch($visit_field[0]){
				case 'max_date':	// 가장 많이 접속한 날짜
					$max_date = explode('-',$visit_field[1]);
					$result[$visit_field[0]] = $max_date[0] . "년 " . $max_date[1] . "월 " . $max_date[2] . "일";
				break;
				case 'max_week':
					$result[$visit_field[0]] = $visit_field[1] . "요일";
				break;
				default:
					$result[$visit_field[0]] = $visit_field[1];
				break;
			}
		}
		return $result;
	}


	function get_domain_int($day='') {
		global $db;
		if(!$day) $day_txt = today;
		else $day_txt = date("Y-m-d", strtotime($day));

		$_field = "*";
		$_where = " and `visit_date` = '".$day_txt."'";

		if($day=='this_month') {
			$_field = "distinct visit_referer, visit_referer_cnt";
			$_where = " and `visit_date`>='".date("Y-m-01")."'"; // : 이번달
		}
		if($day=='this_week') { // : 이번주
			$_field = "distinct visit_referer, visit_referer_cnt";
			$week_int = date("Y-m-d", strtotime("-".(date("w")-1).' day'));
			$week_int = date("Y-m-d", strtotime("-6 day"));
			$_where = " and `visit_date`>='".$week_int."'"; // : 이번주
		}

		$get_data = $db->_query(" select ".$_field." from `nf_visit` where 1 ".$_where." group by `visit_referer` order by `visit_referer_cnt` desc limit 10");
		return $get_data;
	}


	function get_ip_int($day='') {
		global $db;
		if(!$day) $day_txt = today;
		else $day_txt = date("Y-m-d", strtotime($day));

		$_field = "*";
		$_where = " and `visit_date` = '".$day_txt."'";
		if($day=='this_month') {
			$_field = "distinct visit_ip, visit_ip_cnt";
			$_where = " and `visit_date`>='".date("Y-m-01")."'"; // : 이번달
		}
		if($day=='this_week') { // : 이번주
			$_field = "distinct visit_ip, visit_ip_cnt";
			$week_int = date("Y-m-d", strtotime("-".(date("w")-1).' day'));
			$week_int = date("Y-m-d", strtotime("-6 day"));
			$_where = " and `visit_date`>='".$week_int."'"; // : 이번주
		}

		$get_data = $db->_query(" select ".$_field." from `nf_visit` where 1 ".$_where." group by `visit_ip` order by `visit_ip_cnt` desc limit 10");
		return $get_data;
	}
}
?>