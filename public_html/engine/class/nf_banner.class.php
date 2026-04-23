<?php
class nf_banner extends nf_util {

	var $get_group = array();
	var $banner_extension = array( 'jpg', 'gif', 'png', 'swf' );	// 업로드 가능 파일 확장자
	var $banner_type = array('image'=>'이미지업로드', 'self'=>'직접입력', 'adsense'=>'구글애드센스');
	var $size_set = array(0=>'원래이미지크기', 1=>'사용자지정크기');

	var $attach = "/data/banner";

	var $roll_info = array( // 필요한 경우 사용 하세요
		"roll_type" => array( 0 => "고정배너", 1 => "롤링배너" ),
		"roll_direction" => array( 0 => "좌측 방향", 1 => "우측 방향", 2 => "위 방향", 3 => "아래 방향" ),
		"roll_turn" => array( 0 => "순차번경", 1 => "랜덤변경" ),
	);

	var $roll_direction_txt = array(0=>'scrollHorz', 1=>'scrollHorz', 2=>'scrollVert', 3=>'scrollVert');

	var $banner_title = array(
		'common'=>'사이트 공통',
		'main' => '메인페이지',
		'main_category' => '메인 업종카테고리',
		'sub' => '서브페이지',
		'sub_etc' => '서브 기타',
		'board' => '커뮤니티',
	);

	/* 메인우측 공지사항,설문조사,SNS각각 배너는 안넣기로 함 */
	var $banner_lists = array(
		'common'=>array( //공통배너
			'common_A'=>array('name' => 'A영역', 'width' => '145', 'height' => '&' ),					//좌측 스크롤배너
			'common_B'=>array('name' => 'B영역', 'width' => '145', 'height' => '&' ),					//우측  스크롤배너
			'common_C'=>array('name' => 'C영역', 'width' => '450', 'height' => '352' ),					//로그인 우측배너
			'common_D'=>array('name' => 'D영역', 'width' => '200', 'height' => '&' ),					//마이페이지 좌측메뉴 하단배너
			'common_E'=>array('name' => 'E영역', 'width' => '200', 'height' => '&' ),					//회사소개 좌측메뉴 하단배너
		),
		'main' => array( // 메인페이지
			'main_A' => array('name' => 'A영역', 'width' => '1400', 'height' => '&' ),				//상단 중앙 롤링배너
			'main_B' => array('name' => 'B영역', 'width' => '1400', 'height' => '&' ),					//프리미엄업체 상단배너
			'main_C' => array('name' => 'C영역', 'width' => '1400', 'height' => '&' ),					//추천업체 상단배너
			'main_D' => array('name' => 'D영역', 'width' => '1400', 'height' => '&' ),					//스마트업체 상단배너
			'main_E' => array('name' => 'E영역', 'width' => '1400', 'height' => '&' ),					//그랜드업체 상단배너
			'main_F' => array('name' => 'F영역', 'width' => '1400', 'height' => '&' ),					//그랜드업체 하단배너
			'main_G' => array('name' => 'G영역', 'width' => '1400', 'height' => '&' ),					//메인게시판 상단배너
			'main_H' => array('name' => 'H영역', 'width' => '1400', 'height' => '&' ),					//메인게시판 하단배너
		),


		'sub' => array( // 서브페이지(지역별, 테마별, 업종별, 내주변)
			'sub_A' => array('name' => 'A영역', 'width' => '1400', 'height' => '&' ),					//프리미엄업체 상단배너
			'sub_B' => array('name' => 'B영역', 'width' => '1400', 'height' => '&' ),					//추천업체 상단배너
			'sub_C' => array('name' => 'C영역', 'width' => '1400', 'height' => '&' ),					//스마트업체 상단배너
			'sub_D' => array('name' => 'D영역', 'width' => '1400', 'height' => '&' ),					//그랜드업체 상단배너
			'sub_E' => array('name' => 'E영역', 'width' => '1400', 'height' => '&' ),					//그랜드업체 하단배너
			'sub_F' => array('name' => 'F영역', 'width' => '1400', 'height' => '&' ),					//제휴업체 리스트 하단배너			
		),

		'sub_etc' => array( // 서브페이지 기타페이지(이용후기, 할인쿠폰, 입점문의, 서비스안내)
			'sub_etc_A' => array('name' => 'A영역', 'width' => '1400', 'height' => '&' ),					//BEST후기 상단
			'sub_etc_B' => array('name' => 'B영역', 'width' => '1400', 'height' => '&' ),					//BEST후기 하단
			'sub_etc_C' => array('name' => 'C영역', 'width' => '1400', 'height' => '&' ),					//이용후기 하단
			'sub_etc_D' => array('name' => 'D영역', 'width' => '1400', 'height' => '&' ),					//할인쿠폰 상단
			'sub_etc_E' => array('name' => 'E영역', 'width' => '1400', 'height' => '&' ),					//할인쿠폰 하단
			'sub_etc_F' => array('name' => 'F영역', 'width' => '1400', 'height' => '&' ),					//입점문의 상단
			'sub_etc_G' => array('name' => 'G영역', 'width' => '1400', 'height' => '&' ),					//입점문의 하단
			'sub_etc_H' => array('name' => 'H영역', 'width' => '1400', 'height' => '&' ),					//서비스안내 상단
			'sub_etc_I' => array('name' => 'I영역', 'width' => '1400', 'height' => '&' ),					//서비스안내 하단
		),

		'board' => array( // 커뮤니티
			'board_A' => array('name' => 'A영역', 'width' => '200', 'height' => '&' ),				//좌측메뉴 하단배너
			'board_B' => array('name' => 'B영역', 'width' => '1400', 'height' => '&' ),				//커뮤니티 상단배너
			'board_C' => array('name' => 'C영역', 'width' => '1400', 'height' => '&' ),				//커뮤니티 하단배너
		),
	);

	function __construct(){
		$this->get_group = $this->get_banner_group();
		if (!is_array($this->get_group)) $this->get_group = array('group' => array(), 'length' => array());
	}

	function get_banner($row) {
		if($row) {
			$arr['target'] = $row['wr_target'] ? ' target="'.$row['wr_target'].'"' : '';

			if($row['wr_padding']) $arr['padding_css'] = "padding-".@implode(":10px !important; padding-", explode(",", $row['wr_padding'])).":10px !important;";


			$img_size = array();
			if($row['wr_width']) $img_size[] = 'width:'.intval($row['wr_width']).'px;';
			if($row['wr_height']) $img_size[] = 'height:'.intval($row['wr_height']).'px;';
			if($img_size[0]) $img_size_css = implode(";", $img_size);
			$arr['css'] = $arr['padding_css'];

			switch($row['wr_type']) {
				case "image":
					$arr['content'] = '<img src="'.NFE_URL.$this->attach.'/'.$row['wr_content'].'" style="'.$img_size_css.'" />';
					break;
				default:
					$arr['content'] = stripslashes($row['wr_content']);
					break;
			}
		}

		return $arr;
	}

	function get_banner_group() {
		global $db;
		$arr = array();
		$arr['group'] = array();
		$arr['length'] = array();
		if (!$db->is_connected()) return $arr;
		$group_query = $db->_query("select count(*) as c, wr_position, wr_g_name from nf_banner group by wr_position, wr_g_name");
		if (!$group_query) return $arr;
		while($row=$db->afetch($group_query)) {
			if(!$row['wr_g_name']) continue;
			if(!$arr['group'][$row['wr_position']]) {
				$arr['group'][$row['wr_position']] = array();
				$arr['length'][$row['wr_position']] = array();
			}
			$arr['group'][$row['wr_position']][$row['wr_g_name']] = $row['wr_g_name']; // : 배너 위치별 그룹정보
			$arr['length'][$row['wr_position']][$row['wr_g_name']] = $row['c']; // : 배너 위치별 그룹 개수
		}
		return $arr;
	}


	function banner_view($position, $no="") {
		global $db;
		if($no) $_where = " and `no`=".intval($no);
		$query = $db->_query("select * from nf_banner where `wr_position`='".addslashes($position)."' and `wr_view`=1 ".$_where." group by `wr_g_name` order by `wr_g_rank` asc");

		ob_start();
		?>
		<div class="banner_list_ banner_<?php echo $position;?>_list_">
		<?php
		$group_name = "";
		$group_count = 0;
		while($row=$db->afetch($query)) {
			if($group_name!=$row['wr_g_name']) {
				$group_count++;
			}
			$get_banner = $this->get_banner($row);
			$_where = " and `wr_position`='".$row['wr_position']."' and `wr_g_name`='".$row['wr_g_name']."' and `wr_view`=1";
			$_order = $row['wr_roll_turn'] ? "rand()" : "`wr_rank` asc";
			$banner_query = $db->_query("select * from nf_banner where 1 ".$_where." order by ".$_order);
			$max_height = $db->query_fetch("select max(wr_height) as height from nf_banner where 1 ".$_where);
			include NFE_PATH.'/include/banner/roll_type_'.$row['wr_roll_type'].'.inc.php';
			$count++;
			$group_name = $row['wr_g_name'];
		}
		?>
		</div>
		<?php
		$arr['tag'] = ob_get_clean();

		return $arr;
	}
}
?>