<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if ($arResult["isFormErrors"] == "Y"):?><?=$arResult["FORM_ERRORS_TEXT"];?><?php endif;?>
<?=$arResult["FORM_NOTE"]?>
<?php if ($arResult["isFormNote"] != "Y")
{
    ?>
    <?=$arResult["FORM_HEADER"]?>

    <div class="contact-form">
        <div class="contact-form__head">
            <?php if ($arResult["isFormDescription"] == "Y" || $arResult["isFormTitle"] == "Y" || $arResult["isFormImage"] == "Y") { ?>
                <?php if ($arResult["isFormTitle"]) { ?>
                    <div class="contact-form__head-title"><?=$arResult["FORM_TITLE"]?></div>
                <?php }
                if ($arResult["isFormImage"] == "Y") { ?>
                    <a href="<?=$arResult["FORM_IMAGE"]["URL"]?>" target="_blank" alt="<?=GetMessage("FORM_ENLARGE")?>"><img src="<?=$arResult["FORM_IMAGE"]["URL"]?>" <?php if($arResult["FORM_IMAGE"]["WIDTH"] > 300):?>width="300"<?php elseif($arResult["FORM_IMAGE"]["HEIGHT"] > 200):?>height="200"<?php else:?><?=$arResult["FORM_IMAGE"]["ATTR"]?><?php endif;?> hspace="3" vscape="3" border="0" /></a>
                    <?php //=$arResult["FORM_IMAGE"]["HTML_CODE"]?>
                <?php } ?>
                <div class="contact-form__head-text"><?=$arResult["FORM_DESCRIPTION"]?></div>
            <?php } ?>
        </div>


        <form class="contact-form__form" name="<?=$arResult["WEB_FORM_NAME"]?>" action="<?=POST_FORM_ACTION_URI?>" method="POST" enctype="multipart/form-data">
			 <input type="hidden" name="web_form_submit" value="Y">
            <div class="contact-form__form-inputs">
                <?
                foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion) {?>
                    <? if(in_array($arQuestion['STRUCTURE'][0]['FIELD_TYPE'], ["text", "email"])){ ?>
                        <div class="input contact-form__input">
                            <label class="input__label" for="<?= $FIELD_SID ?>">
                                <div class="input__label-text"><?=$arQuestion["CAPTION"]?><?if ($arQuestion["REQUIRED"] == "Y"):?><?=$arResult["REQUIRED_SIGN"];?><?endif;?></div>
								<input class="input__input" id="<?= $FIELD_SID ?>" name="<?= $FIELD_SID ?>">

                                <?if (is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS'])):?>
                                    <div class="input__notification" title="<?=htmlspecialcharsbx($arResult["FORM_ERRORS"][$FIELD_SID])?>"></div>
                                <?endif;?>
                                <?=$arQuestion["IS_INPUT_CAPTION_IMAGE"] == "Y" ? "<br />".$arQuestion["IMAGE"]["HTML_CODE"] : ""?>
                            </label>
                        </div>
                        <?
                    }
                    elseif ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden') {
                        echo $arQuestion["HTML_CODE"];
                    }
                } //endforeach?>
            </div>
            <? foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion) {?>
                <? if($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] =="textarea") {?>
                    <div class="contact-form__form-message">
                        <div class="input">
                            <label class="input__label" for="<?= $FIELD_SID ?>">
                                <div class="input__label-text"><?=$arQuestion["CAPTION"]?><?if ($arQuestion["REQUIRED"] == "Y"):?><?=$arResult["REQUIRED_SIGN"];?><?endif;?></div>
                                <textarea class="input__input" type="text" id="<?= $FIELD_SID ?>" name="<?= $FIELD_SID ?>"></textarea>
                                <div class="input__notification"></div>
                            </label>
                        </div>
                    </div>
                <? }
            } ?>

            <div  class="contact-form__bottom">
                <div class="contact-form__bottom-policy">Нажимая &laquo;Отправить&raquo;, Вы подтверждаете, что ознакомлены,
                    полностью согласны и принимаете условия &laquo;Согласия на обработку персональных данных&raquo;.
                </div>
                <button class="form-button contact-form__bottom-button" data-success="Отправлено"
                        data-error="Ошибка отправки">
                    <div <?=(intval($arResult["F_RIGHT"]) < 10 ? "disabled=\"disabled\"" : "");?> type="submit" name="web_form_submit" class="form-button__title" value=""><?=htmlspecialcharsbx(trim($arResult["arForm"]["BUTTON"]) == '' ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]);?></div>
                </button>
            </div>
    </form>
</div>

    <?=$arResult["FORM_FOOTER"]?>
    <?
} //endif (isFormNote)
