<?php

$query = $db->_query("
    SELECT 
        mb_id,
        mb_nick,
        mb_point,
        mb_level
    FROM nf_member
    WHERE mb_left = 0
    ORDER BY mb_point DESC
");

$rank = 1;

// 3) 출력
while ($row = $db->afetch($query)) {

    if ($rank >= 100)
        break; // 100위까지만

    ?>
    <tr class="alert" role="alert">
        <th scope="row"><?php echo $rank++; ?></th>
        <td>
            <img src="<?php echo NFE_URL; ?>../data/member_level/<?php
              echo $env['member_level_arr'][$row['mb_level']]['icon'];
              ?>" alt="">
            <?php echo $row['mb_nick']; ?>
        </td>
        <td><?php echo number_format($row['mb_point']); ?></td>
    </tr>
    <?php
}
?>