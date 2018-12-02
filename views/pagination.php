<?php if ($prev_page = $paging->getPrevPage()): ?>
    <a href="?page=<?= $prev_page ?>"> < </a> |</a>
<?php endif; ?>
<?php for($i=0;$i<count($pager);$i++): ?>
    <?php if ($pager[$i] == $page): ?>
        <?= $pager[$i] ?> |
    <?php else: ?>
        <a href="?page=<?= $pager[$i] ?>"><?= $pager[$i] ?></a> |
    <?php endif;?>
<?php endfor; ?>
<?php if ($next_page = $paging->getNextPage()): ?>
    <a href="?page=<?= $next_page ?>"> > </a>
<?php endif; ?>