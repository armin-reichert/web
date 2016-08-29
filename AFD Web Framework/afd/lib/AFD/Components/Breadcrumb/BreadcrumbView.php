<div class="afdBreadcrumb">&raquo;
<?php for ($i = 0; $i < count($path); ++$i): ?>
<a href="<?= $path[$i]['url'] ?>"><?= $path[$i]['text'] ?></a>
<?php if ($i < count($path) - 1): ?>&raquo;&nbsp;<?php endif; ?>
<?php endfor; ?>
</div>