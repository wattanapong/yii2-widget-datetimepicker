<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 * http://www.yiiframework.com/doc-2.0/yii-jui-datepicker.html
 */
/**
 * Copyright (c) 2012 Paul Bakaus, http://jqueryui.com/
 */
/**
/*! jQuery Timepicker Addon not yet - v1.6.3 - 2016-04-20
* http://trentrichardson.com/examples/timepicker
* Copyright (c) 2016 Trent Richardson; Licensed MIT */
/**
 * @copyright Copyright &copy; Wattanapong Suttapak, 2017
 * @version 1.0.0
 */

namespace wattanapong\datetime;

use Yii;
use yii\base\InvalidParamException;
use yii\helpers\FormatConverter;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\jui\InputWidget;
use yii\jui\JuiAsset;
use yii\jui\DatePickerLanguageAsset;

/**
 * DateTimePicker widget is a Yii2 wrapper for the Bootstrap DateTimePicker plugin by smalot
 * This is a fork of the DatePicker plugin by @eternicode and adds the time functionality.
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 * @see http://www.malot.fr/bootstrap-datetimepicker/
 */
class DateTimePicker extends InputWidget
{
    /**
     * @var string the locale ID (e.g. 'fr', 'de', 'en-GB') for the language to be used by the date picker.
     * If this property is empty, then the current application language will be used.
     *
     * Since version 2.0.2 a fallback is used if the application language includes a locale part (e.g. `de-DE`) and the language
     * file does not exist, it will fall back to using `de`.
     */
    public $language;
    /**
     * @var boolean If true, shows the widget as an inline calendar and the input as a hidden field.
     */
    public $inline = false;
    /**
     * @var array the HTML attributes for the container tag. This is only used when [[inline]] is true.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $containerOptions = [];
    /**
     * @var string the format string to be used for formatting the date value. This option will be used
     * to populate the [[clientOptions|clientOption]] `dateFormat`.
     * The value can be one of "short", "medium", "long", or "full", which represents a preset format of different lengths.
     *
     * It can also be a custom format as specified in the [ICU manual](http://userguide.icu-project.org/formatparse/datetime#TOC-Date-Time-Format-Syntax).
     * Alternatively this can be a string prefixed with `php:` representing a format that can be recognized by the
     * PHP [date()](http://php.net/manual/de/function.date.php)-function.
     *
     * For example:
     *
     * ```php
     * 'MM/dd/yyyy' // date in ICU format
     * 'php:m/d/Y' // the same date in PHP format
     * ```
     *
     * If not set the default value will be taken from `Yii::$app->formatter->dateFormat`.
     */
    public $dateFormat;
    /**
     * @var string the model attribute that this widget is associated with.
     * The value of the attribute will be converted using [[\yii\i18n\Formatter::asDate()|`Yii::$app->formatter->asDate()`]]
     * with the [[dateFormat]] if it is not null.
     */
    public $attribute;
    /**
     * @var string the input value.
     * This value will be converted using [[\yii\i18n\Formatter::asDate()|`Yii::$app->formatter->asDate()`]]
     * with the [[dateFormat]] if it is not null.
     */
    public $value;

    /*
     * @var for datetimepicker
     */    
    public $timeFormat= 'hh:mm:ss';
    public $isBE = true;
    public $changeMonth = true;
    public $changeYear = true;
    public $showButtonPanel = true;

    public $isDateTime = true;
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->inline && !isset($this->containerOptions['id'])) {
            $this->containerOptions['id'] = $this->options['id'] . '-container';
        }
        if ($this->dateFormat === null) {
            $this->dateFormat = Yii::$app->formatter->dateFormat;
        }
        
        $this->clientOptions = [
        		'timeFormat'=> $this->timeFormat,
        		'isBE'=>$this->isBE,
        		'changeMonth'=>$this->changeMonth,
        		'changeYear'=>$this->changeYear,
        		'showButtonPanel'=>$this->showButtonPanel,
        		'clientOptions' => $this->options
        ];

    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        echo $this->renderWidget() . "\n";
        
        $containerID = $this->inline ? $this->containerOptions['id'] : $this->options['id'];
        $language = $this->language ? $this->language : Yii::$app->language;

        if (strncmp($this->dateFormat, 'php:', 4) === 0) {
            $this->clientOptions['dateFormat'] = FormatConverter::convertDatePhpToJui(substr($this->dateFormat, 4));
        } else {
            $this->clientOptions['dateFormat'] = FormatConverter::convertDateIcuToJui($this->dateFormat, 'date', $language);
        }
		
        if ($language !== 'en-US') {
            $view = $this->getView();
            //$assetBundle = DatePickerLanguageAsset::register($view);
            $assetBundle = DateTimePickerAsset::register($view);
           	$assetBundle->language = $language;
            $options = Json::htmlEncode($this->clientOptions);
            $language = Html::encode($language);
            if ($this->isDateTime )
            	$view->registerJs("$('#{$containerID}').datetimepicker($.extend({}, $.datepicker.regional['{$language}'], $options));");
            else 
            	$view->registerJs("$('#{$containerID}').datepicker($.extend({}, $.datepicker.regional['{$language}'], $options));");
            	
            	$bundle = $this->getView()->assetManager->getBundle('wattanapong\datetime\DateTimePickerAsset' );
            	$bundle->js[] = ['js/locales/jquery.ui.datepicker-'.$language.'.js'];

            	if ($this->language == 'th')
            		$bundle->js[] = ['js/locales/jquery.ui.datetimepicker-'.$language.'.js'];
        } else {
            $this->registerClientOptions('datetimepicker', $containerID);
        }

        $this->registerClientEvents('datetimepicker', $containerID);
       
        $bundle = $this->getView()->assetManager->getBundle('yii\jui\JuiAsset' );
        $bundle->css = [
        						'themes/flick/jquery-ui.css',
        ];

        JuiAsset::register($this->getView());
    }

    /**
     * Renders the DatePicker widget.
     * @return string the rendering result.
     */
    protected function renderWidget()
    {
        $contents = [];

        // get formatted date value
        if ($this->hasModel()) {
            $value = Html::getAttributeValue($this->model, $this->attribute);
        } else {
            $value = $this->value;
        }
        if ($value !== null && $value !== '') {
            // format value according to dateFormat
            try {
                $value = Yii::$app->formatter->asDate($value, $this->dateFormat);
            } catch(InvalidParamException $e) {
                // ignore exception and keep original value if it is not a valid date
            }
        }
        $options = $this->options;
        $options['value'] = $value;

        if ($this->inline === false) {
            // render a text input
            if ($this->hasModel()) {
                $contents[] = Html::activeTextInput($this->model, $this->attribute, $options);
            } else {
                $contents[] = Html::textInput($this->name, $value, $options);
            }
        } else {
            // render an inline date picker with hidden input
            if ($this->hasModel()) {
                $contents[] = Html::activeHiddenInput($this->model, $this->attribute, $options);
            } else {
                $contents[] = Html::hiddenInput($this->name, $value, $options);
            }
            $this->clientOptions['defaultDate'] = $value;
            $this->clientOptions['altField'] = '#' . $this->options['id'];
            $contents[] = Html::tag('div', null, $this->containerOptions);
        }

        return implode("\n", $contents);
    }
}

