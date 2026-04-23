<?php
$_code = 'main';
if(strpos($_SERVER['PHP_SELF'], 'shop')!==false) $_code = 'shop';
?>
<div class="ass_list">
	<table>
		<colgroup>
			<col width="8%">
		</colgroup>
		<tr>
			<th>서비스 영역</th>
			<td>
				<ul>
					<li class="<?php echo $_code=='main' ? 'on' : '';?>"><a href="./service_name(main).php">메인/서브 서비스명 설정</a></li>
				</ul>
			</td>
		</tr>
	</table>
</div>