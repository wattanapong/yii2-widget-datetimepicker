<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2016
 * @package yii2-widgets
 * @subpackage yii2-widget-datetimepicker
 * @version 1.4.2
 */

namespace wattanapong\datetime;
use \yii\web\AssetBundle;

/**
 * Asset bundle for DateTimePicker Widget
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class DateTimePickerAsset extends AssetBundle
{
    public $sourcePath = '@bower/jquery-ui';
    public $extSourcePath = __DIR__ . '/assets';
    /**
     * @var boolean whether to automatically generate the needed language js files.
     * If this is true, the language js files will be determined based on the actual usage of [[DatePicker]]
     * and its language settings. If this is false, you should explicitly specify the language js files via [[js]].
     */
    public $autoGenerate = true;
    /**
     * @var string language to register translation file for
     */
    public $language;
    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\jui\JuiAsset',
    ];


    /**
     * @inheritdoc
     */
    public function registerAssetFiles($view)
    {
        if ($this->autoGenerate) {
            $language = $this->language;
            $fallbackLanguage = substr($this->language, 0, 2);
            if ($fallbackLanguage !== $this->language && !file_exists(Yii::getAlias($this->sourcePath . "/ui/i18n/datepicker-{$language}.js"))) {
                $language = $fallbackLanguage;
            }
            $this->js[] = "ui/i18n/datepicker-$language.js";
        }
        parent::registerAssetFiles($view);
    }
}