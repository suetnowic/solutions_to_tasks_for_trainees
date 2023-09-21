<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if ($arResult["isFormErrors"] == "Y"):?><?=$arResult["FORM_ERRORS_TEXT"];?><?endif;?>
<?=$arResult["FORM_NOTE"]?>
<?if ($arResult["isFormNote"] != "Y")
{
?>
<div class="contact-form">
    <div class="contact-form__head">
	<?
	if ($arResult["isFormDescription"] == "Y" || $arResult["isFormTitle"] == "Y" || $arResult["isFormImage"] == "Y")
	{
	?>
		<? if ($arResult["isFormTitle"])
		{
		?>
			<div class="contact-form__head-title"><?=$arResult["FORM_TITLE"]?></div>
		<?
		} //endif ;?>

        <div class="contact-form__head-text"><?=$arResult["FORM_DESCRIPTION"]?></div>
    </div>
	<!--<form class="contact-form__form">-->
	<?=str_replace('<form', '<form class="contact-form__form"', $arResult["FORM_HEADER"]);

	if ($arResult["isFormImage"] == "Y")
	{
	?>
	<a href="<?=$arResult["FORM_IMAGE"]["URL"]?>" target="_blank" alt="<?=GetMessage("FORM_ENLARGE")?>"><img src="<?=$arResult["FORM_IMAGE"]["URL"]?>" <?if($arResult["FORM_IMAGE"]["WIDTH"] > 300):?>width="300"<?elseif($arResult["FORM_IMAGE"]["HEIGHT"] > 200):?>height="200"<?else:?><?=$arResult["FORM_IMAGE"]["ATTR"]?><?endif;?> hspace="3" vscape="3" border="0" /></a>
	<?//=$arResult["FORM_IMAGE"]["HTML_CODE"]?>
	<?
	} //endif
	}
	?>

	<div class="contact-form__form-inputs">
	<?
	foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion)
		{
			if (in_array($arQuestion['STRUCTURE'][0]['FIELD_TYPE'], ["text","email"]))
			{
			?>
			<div class="input contact-form__input">
                <label class="input__label" for="medicine_name">
                    <div class="input__label-text"><?=$arQuestion["CAPTION"]?><?if ($arQuestion["REQUIRED"] == "Y"):?><?=$arResult["REQUIRED_SIGN"];?><?endif;?></div>
                    <!--<input class="input__input" type="text" id="medicine_name" name="medicine_name" value="" required="">-->
					<?=$arQuestion["HTML_CODE"]?>
					<?if (is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS'])):?>
                    <div class="input__notification">Поле должно содержать не менее 3-х символов</div>
					<?endif;?>
                </label>
            </div>
			<? }
		} //endwhile ?>
	</div>
	<?
	foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion)
		{
			if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == "textarea")
			{
			?>
			<div class="contact-form__form-message">
            <div class="input">
                <label class="input__label" for="medicine_message">
                    <div class="input__label-text"><?=$arQuestion["CAPTION"]?><?if ($arQuestion["REQUIRED"] == "Y"):?><?=$arResult["REQUIRED_SIGN"];?><?endif;?></div>
                    <!--<textarea class="input__input" type="text" id="medicine_message" name="medicine_message" value=""></textarea>-->
					<?=$arQuestion["HTML_CODE"]?>
                    <div class="input__notification"></div>
                </label>
            </div>
        </div>
			<? }
		} //endwhile ?>

	<?
	if($arResult["isUseCaptcha"] == "Y")
	{
	?>
	<?=GetMessage("FORM_CAPTCHA_TABLE_TITLE")?>
	><input type="hidden" name="captcha_sid" value="<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" /><img src="/bitrix/tools/captcha.php?captcha_sid=<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" width="180" height="40" />
	<?=GetMessage("FORM_CAPTCHA_FIELD_TITLE")?><?=$arResult["REQUIRED_SIGN"];?>
	<input type="text" name="captcha_word" size="30" maxlength="50" value="" class="inputtext" />
	<?
	} // isUseCaptcha
	?>

	<div  class="contact-form__bottom">
        <div class="contact-form__bottom-policy">Нажимая &laquo;Отправить&raquo;, Вы подтверждаете, что ознакомлены,
            полностью согласны и принимаете условия &laquo;Согласия на обработку персональных данных&raquo;.
        </div>
		<input <?=(intval($arResult["F_RIGHT"]) < 10 ? "disabled=\"disabled\"" : "");?> class="form-button contact-form__bottom-button" data-success="Отправлено"
                data-error="Ошибка отправки" type="submit" name="web_form_submit" value="<?=htmlspecialcharsbx(trim($arResult["arForm"]["BUTTON"]) == '' ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]);?>"> />
    </div>


<?=$arResult["FORM_FOOTER"]?>
<?
} //endif (isFormNote)
