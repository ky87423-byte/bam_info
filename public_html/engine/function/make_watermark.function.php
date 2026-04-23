<?php
/**
* watermark Making (워터마크 만들기)
* $org_image :: 워터마크가 찍힐 원본 이미지 n_news의 경우 /n_news/peg/news/org/ 에 존재한다
*/
function make_watermark( $org_image ){

	global $alice, $utility;
	global $news_config_control;


		$_org = pathinfo($org_image);	// 원본 파일 경로

		$get_config = $news_config_control->get_config(1);	 // 워터마크 설정 정보

		$opacity = $get_config['wr_watermark_opacity'];		// 투명도
		$padding = $get_config['wr_watermark_margin'];		// 여백
		$align = $get_config['wr_watermark_align'];				// 0 : 상단왼쪽, 1 : 상단중앙, 2 : 상단오른쪽, 3 : 가운데왼쪽, 4 : 정중앙, 5 : 가운오른쪽, 6 : 하단왼쪽, 7 : 하단중앙, 8 : 하단오른쪽

		$mark_bg = $get_config['wr_watermark_bg'];			// 투시색상 지정 (0:투명/1:흰색/2:검정색/3:사용자지정색)
		$bg_color = $get_config['wr_watermark_color'];		// 사용자지정색 
		$water_mark = $alice['data_tmp_path'] . "/" . $get_config['wr_watermark_file'];		// 워터마크 이미지

		$wmark_info = @getimagesize($water_mark);	// 워터마크 이미지 정보
		$wmark_width = $wmark_info[0]; 
		$wmark_height = $wmark_info[1];
		
		/* 워터마크 배경색 지정 */
		if($mark_bg){
			if($mark_bg=='1'){	// 흰색
				$rgb_color = "FFFFFF";
			} else if($mark_bg=='2'){	// 검정색
				$rgb_color = "000000";
			} else if($mark_bg=='3'){	// 사용자지정색
				$rgb_color = $bg_color;
			}

			switch($wmark_info[2]){		// 워터마크 리소스
				case 1 :	 $fill_img = @imagecreatefromgif($water_mark); $_ext = 'gif'; break;
				case 2 :	 $fill_img = @imagecreatefromjpeg($water_mark); $_ext = 'jpg'; break;
				case 3 :	 $fill_img = @imagecreatefrompng($water_mark); $_ext = 'png'; break;
			}

			/* 관리자 설정 옵션에 따라 워터마크 이미지 생성 */

				/* 배경 이미지 생성 */
				$new_image = imagecreatetruecolor($wmark_width, $wmark_height);
				$rgb = $utility->watermark_my_image_hex_to_rgb($rgb_color);
				$background_color = imagecolorallocate($new_image, $rgb[0], $rgb[1], $rgb[2]);
				imagefill($new_image, 0, 0, $background_color);
				$_file = $alice['data_tmp_path'] . "/wmark/";
				switch($_ext){
					case 'gif' : 
						$_file .= 'background.gif';
						$_mark = "wmark.gif";
						$_result = @imagegif($new_image, $_file);
						@chmod($_file,0707);
						if($_result) $back_img = @imagecreatefromgif($_file);
						$mark_img = @imagecreatefromgif($water_mark);
					break;
					case 'jpg' :
					case 'jpeg' :
						$_file .= 'background.jpg';
						$_mark = "wmark.jpg";
						$_result = @imagejpeg($new_image, $_file);
						@chmod($_file,0707);
						if($_result) $back_img = @imagecreatefromjpeg($_file);
						$mark_img = @imagecreatefromjpeg($water_mark);
					break;
					default :
						$_file .= 'background.png';
						$_mark = "wmark.png";
						$_result = @imagepng($new_image, $_file);
						@chmod($_file,0707);
						if($_result) $back_img = @imagecreatefrompng($_file);
						$mark_img = @imagecreatefrompng($water_mark);
				}

				// 배경 이미지와 워터마크 merge
				$result_background = @imagecopymerge($back_img, $mark_img, 0, 0, 0, 0, $wmark_width, $wmark_height, 100);
				//$result_background = imagecopyresampled($back_img, $mark_img, $_x, $_y, 0, 0, $wmark_width, $wmark_height, $wmark_width, $wmark_height );

				if($result_background){
					$mark_save_file = $alice['data_tmp_path'] . "/wmark/";
					switch($_ext){
						case 'gif' : 
							$mark_save_file .= $_mark;
							$marks = @imagegif($back_img, $mark_save_file); 
						break;
						case 'jpg' :
						case 'jpeg' :
							$mark_save_file .= $_mark;
							$marks = @imagejpeg($back_img, $mark_save_file);
						break;
						default : //확장자 png 또는 확장자가 없는 경우, 정의되지 않는 확장자인 경우는 모두 png로 저장
							$mark_save_file .= $_mark;
							$marks = @imagepng($back_img, $mark_save_file);
					}

					@imagedestroy($back_img);
					@imagedestroy($mark_img);  

				}
				/* //배경 이미지 생성 */

			/* //관리자 설정 옵션에 따라 워터마크 이미지 생성 */

			switch($wmark_info[2]){	 // 워터마크 리소스를 받아온다.
				case 1 : $wmark_img = @imagecreatefromgif($mark_save_file); break;		// gif
				case 2 : $wmark_img = @imagecreatefromjpeg($mark_save_file); break;	// jpg
				case 3 : $wmark_img = @imagecreatefrompng($mark_save_file); break;	// png
			}

		} else {

			switch($wmark_info[2]){	 // 워터마크 리소스를 받아온다.
				case 1 : $wmark_img = @imagecreatefromgif($water_mark); break;		// gif
				case 2 : $wmark_img = @imagecreatefromjpeg($water_mark); break;	// jpg
				case 3 : $wmark_img = @imagecreatefrompng($water_mark); break;	// png
			}

		}
		/* //워터마크 배경색 지정 */


		/* 원본의 이미지 리소스를 받아온다. */
		$org_size = @getimagesize($org_image);
		switch($org_size[2]){
			case 1 : $org_img = @imagecreatefromgif($org_image); $org_ext = 'gif'; break;		// gif
			case 2 : $org_img = @imagecreatefromjpeg($org_image); $org_ext = 'jpg'; break;	// jpg
			case 3 : $org_img = @imagecreatefrompng($org_image); $org_ext = 'png'; break;		// png
		}
		/* //원본의 이미지 리소스를 받아온다. */
	
		/* 워터마크를 찍을 좌표 설정 */
		$src_w = $org_size[0];
		$src_h = $org_size[1];

		$src_w_small = ceil($src_w / 2);
		settype($src_w_small, 'int');//ceil의 결과값은 int형이 아니라 float 형이기 때문에 강제 형변환을 해준다.
		$src_h_small = $utility->get_size_by_rule($src_w, $src_h, $src_w_small);

		switch($align){
			case '1':	// 상단중앙
				$dest_x = ceil($src_w - $wmark_width) / 2;
				$dest_y = 0;
			break;
			case '2':	// 상단오른쪽
				$dest_x = ceil($src_w - $wmark_width);
				$dest_y = 0;
			break;
			case '3':	// 가운데왼쪽
				$dest_x = 0;
				$dest_y = ceil($src_h - $wmark_height) / 2;
			break;
			case '4':	// 정중앙
				$dest_x = ceil($src_w - $wmark_width) / 2;
				$dest_y = ceil($src_h - $wmark_height) / 2;
			break;
			case '5':	// 가운오른쪽
				$dest_x = ceil($src_w - $wmark_width);
				$dest_y = ceil($src_h - $wmark_height) / 2;
			break;
			case '6':	// 하단왼쪽
				$dest_x = 0;
				$dest_y = ceil($src_h - $wmark_height);
			break;
			case '7':	// 하단중앙
				$dest_x = ceil($src_w - $wmark_width) / 2;
				$dest_y = ceil($src_h - $wmark_height);
			break;
			case '8':	// 하단오른쪽
				$dest_x = ceil($src_w - ($wmark_width+10));
				$dest_y = ceil($src_h - ($wmark_height+10));
			break;
			default :	// 상단왼쪽
				$dest_x = 0;	 // 가로
				$dest_y = 0;	 // 세로
			break;
		}
		$_x = $dest_x + $padding;
		$_y = $dest_y + $padding;
		/* //워터마크를 찍을 좌표 설정 */

		//$org_x = imagesx($org_img);
		//$org_y = imagesy($org_img);

		//imagealphablending($wmark_img, false);
		//imagefilter($wmark_img, IMG_FILTER_BRIGHTNESS, $opacity);
		//imagecolortransparent($wmark_img, imagecolorat($wmark_img,0,0));

		$wmark_info_mime = array("gif","jpg","jpeg");

		if(stristr($wmark_info['mime'],'gif') || stristr($wmark_info['mime'],'jpg') || stristr($wmark_info['mime'],'jpeg')){
			// imagecopymerge() 함수는 투명도 조절이 가능하다
			$result_watermark = imagecopymerge($org_img, $wmark_img, $_x, $_y, 0, 0, $wmark_width, $wmark_height, $opacity);
		} else {
			$result_watermark = $this->imagecopymerge_alpha($org_img, $wmark_img, $_x, $_y, 0, 0, $wmark_width, $wmark_height, $opacity);
			// imagecopyresampled() 함수는 투명도 조절이 불가능 하다
			//$result_watermark = imagecopyresampled($org_img, $wmark_img, $_x, $_y, 0, 0, $wmark_width, $wmark_height, $wmark_width, $wmark_height );
		}
		@imagedestroy($wmark_img);


		if($result_watermark){
			
			$path_save_file = $org_image;

			switch($org_ext){
				case 'gif' : 
					$result = @imagegif($org_img, $path_save_file); 
				break;
				case 'jpg' :
				case 'jpeg' :
					$result = @imagejpeg($org_img, $path_save_file);
				break;
				default : //확장자 png 또는 확장자가 없는 경우, 정의되지 않는 확장자인 경우는 모두 png로 저장
					$result = @imagepng($org_img, $path_save_file);
			}

			@imagedestroy($org_img);
			@imagedestroy($wmark_img);  

		}

	
	return array("result" => $result, "file_name" => $_org['basename'], "save_file" =>$path_save_file, "realpath" => $_org );

}
?>