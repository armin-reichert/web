<div class="afdMenu">
<?php for ($i = 0; $i < count($items); ++$i): ?>
<?php if ($i == $selectedIndex): ?>
<span class="afdMenuItem selected"> <?= $items[$i]['text'] ?></span>
<?php else: ?>
<a class="afdMenuItem" href="<?= $items[$i]['url'] ?>"><?= $items[$i]['text'] ?></a>
<?php endif; ?>
<?php endfor;?>
</div>
