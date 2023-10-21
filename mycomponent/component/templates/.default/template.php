<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>

<ul>
    <?php foreach ($arResult["ITEMS"] as $iblockId => $items): ?>
        <li><strong>Инфоблок <?= $iblockId ?>:</strong></li>
        <ul>
            <?php foreach ($items as $item): ?>
                <li><?= $item["NAME"] ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endforeach; ?>
</ul>