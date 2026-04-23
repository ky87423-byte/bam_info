<div class="ass_list">
	<table>
		<colgroup>
			<col width="7%">
		</colgroup>
		<tr>
			<th>통계분류</th>
			<td>
				<ul>
					<li class="<?php echo strpos($_SERVER['PHP_SELF'], '/statistics/index.php')!==false ? 'on' : '';?>"><a href="./index.php">접속통계</a></li>
					<li class="<?php echo strpos($_SERVER['PHP_SELF'], '/statistics/index(time).php')!==false ? 'on' : '';?>"><a href="./index(time).php">시간별 통계</a></li>
					<li class="<?php echo strpos($_SERVER['PHP_SELF'], '/statistics/index(week).php')!==false ? 'on' : '';?>"><a href="./index(week).php">요일별 통계</a></li>
					<li class="<?php echo strpos($_SERVER['PHP_SELF'], '/statistics/index(date).php')!==false ? 'on' : '';?>"><a href="./index(date).php">일별 통계</a></li>
					<li class="<?php echo strpos($_SERVER['PHP_SELF'], '/statistics/index(month).php')!==false ? 'on' : '';?>"><a href="./index(month).php">월별 통계</a></li>
					<li class="<?php echo strpos($_SERVER['PHP_SELF'], '/statistics/index(domain).php')!==false ? 'on' : '';?>"><a href="./index(domain).php">접속전 도메인</a></li>
					<li class="<?php echo strpos($_SERVER['PHP_SELF'], '/statistics/index(ip).php')!==false ? 'on' : '';?>"><a href="./index(ip).php">접속 IP</a></li>
					<li class="<?php echo strpos($_SERVER['PHP_SELF'], '/statistics/index(browser).php')!==false ? 'on' : '';?>"><a href="./index(browser).php">접속 브라우저</a></li>
					<li class="<?php echo strpos($_SERVER['PHP_SELF'], '/statistics/index(os).php')!==false ? 'on' : '';?>"><a href="./index(os).php">접속OS</a></li>
				</ul>
			</td>
		</tr>
	</table>
</div>

<form name="fsearch1" action="<?php echo $_SERVER['PHP_SELF'];?>">
<div class="search">
	 <table>
		<colgroup>
		</colgroup>
		<tbody>
			<tr>
				<td colspan="3" class="tac">
					<?php
					$date_tag = $nf_tag->date_search();
					echo $date_tag['tag'];
					?><input class="MAL5 MAR5" type="submit" class="blue"  value="검색"><button class="black" >초기화</button>
				</td> 
			</tr>
		</tbody>
	 </table>
</div>
</form>