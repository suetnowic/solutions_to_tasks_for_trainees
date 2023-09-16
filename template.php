<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
$this->addExternalCss("/css/common.css");
?>

<div class="contact-form">
    <div class="contact-form__head">
        <div class="contact-form__head-title"><?= $arResult["FORM_TITLE"] ?></div>
        <div class="contact-form__head-text"><?= $arResult["FORM_DESCRIPTION"] ?></div>
    </div>

    <form class="contact-form__form" action="<?= POST_FORM_ACTION_URI ?>" method="POST">
        <input type="hidden" name="WEB_FORM_ID" value="<?=$arParams["WEB_FORM_ID"]?>"/>
        <?= bitrix_sessid_post() ?>
        <div class="contact-form__form-inputs">
            <? foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion): ?>
                <? if ($arQuestion["STRUCTURE"][0]["FIELD_TYPE"] !== "textarea"): ?>
                    <div class="input contact-form__input">
                        <label class="input__label" for="<?= $FIELD_SID ?>">
                            <div class="input__label-text"><?= $arQuestion["CAPTION"] ?><? if ($arQuestion["REQUIRED"] == "Y"): ?><?= $arResult["REQUIRED_SIGN"]; ?><? endif; ?></div>
                            <? if ($FIELD_SID == "medicine_phone"): ?>
                                <input class="input__input" type="tel" id="<?= $FIELD_SID ?>"
                                       data-inputmask="'mask': '+79999999999', 'clearIncomplete': 'true'" maxlength="12"
                                       x-autocompletetype="phone-full" name="<?= $FIELD_SID ?>" value="" required="">
                            <? elseif ($FIELD_SID == "medicine_email"): ?>
                                <input class="input__input" type="email" id="<?= $FIELD_SID ?>" name="<?= $FIELD_SID ?>"
                                       value="" required="">
                            <? else: ?>
                                <input class="input__input" type="text" id="<?= $FIELD_SID ?>" name="<?= $FIELD_SID ?>"
                                       value="" required="">
                            <? endif ?>
                            <div class="input__notification"><?= (is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult["FORM_ERRORS"])) ? $arResult["FORM_ERRORS"][$FIELD_SID] : "" ?></div>
                        </label>
                    </div>
                <? endif ?>
            <? endforeach ?>

            <div class="contact-form__form-message">
                <div class="input">
                    <label class="input__label" for="<?= $FIELD_SID ?>">
                        <div class="input__label-text"><?= $arQuestion["CAPTION"] ?></div>
                        <textarea class="input__input" type="text" id="<?= $FIELD_SID ?>" name="<?= $FIELD_SID ?>"
                                  value=""></textarea>
                        <div class="input__notification"></div>
                    </label>
                </div>
                <div class="contact-form__bottom">
                    <div class="contact-form__bottom-policy">Нажимая &laquo;Отправить&raquo;, Вы подтверждаете, что
                        ознакомлены,
                        полностью согласны и принимаете условия &laquo;Согласия на обработку персональных данных&raquo;.
                    </div>
                    <button class="form-button contact-form__bottom-button" data-success="Отправлено"
                            data-error="Ошибка отправки">
                        <div class="form-button__title"><?= htmlspecialcharsbx(trim($arResult["arForm"]["BUTTON"]) == '' ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]); ?></div>
                    </button>
                </div>
            </div>
        </div>
    </form>
    <?= $arResult["REQUIRED_SIGN"]; ?> - <?= GetMessage("FORM_REQUIRED_FIELDS") ?>
</div>

<?= $arResult["FORM_FOOTER"] ?>




