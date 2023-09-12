<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?
$this->addExternalCss("/css/common.css");
?>

<div id="barba-wrapper">
    <div class="article-list">
        <? foreach ($arResult["ITEMS"] as $arItem): ?>
        <?
            $this->AddEditAction(
                    $arItem['ID'],
                    $arItem['EDIT_LINK'],
                    CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT")
            );
            $this->AddDeleteAction(
                    $arItem['ID'],
                    $arItem['DELETE_LINK'],
                    CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"),
                    array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM'))
            );
        ?>
        <a class="article-item article-list__item" href="<?php echo $arItem["DETAIL_PAGE_URL"] ?>" data-anim="anim-3">
            <div class="article-item__background">
                <img src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>" data-src="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?>" alt=""/>
            </div>
            <div class="article-item__wrapper">
                <div class="article-item__title"><?php echo $arItem["NAME"] ?></div>
                <?php if ($arParams["DISPLAY_PREVIEW_TEXT"] != "N" && $arItem["PREVIEW_TEXT"]): ?>
                <div class="article-item__content"><?php echo $arItem["PREVIEW_TEXT"]; ?></div
                <?php endif; ?>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</div>