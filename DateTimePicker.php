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
use common\components\Util;

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
    public $containerOptions;
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
    public $from,$to;
    public $valueTo;
    private $isRange = false;
    public $separate = ' ถึง ';
    /**
     * @var string the input value.
     * This value will be converted using [[\yii\i18n\Formatter::asDate()|`Yii::$app->formatter->asDate()`]]
     * with the [[dateFormat]] if it is not null.
     */
    public $value;
    

    /*
     * @array for plugin jquery datepicker
     */    
   
    public $pluginOptions;
    
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
        
        //check is from to attribute
        $isFrom = $isTo = false ; 
        $this->isRange = true;
        if ($this->from !== null && $this->from !== ''  ) $isFrom = true;
        if ($this->to !== null && $this->to !== ''  ) $isTo = true;
        
        if ( $isFrom && !$isTo ) $this->to = $this->from;
        elseif ( !$isFrom && $isTo ) $this->from = $this->to;
        elseif ( !$isFrom && !$isTo )  $this->isRange = false;

        //config maxDate
        if ( isset($this->pluginOptions['maxDate'] ) && $this->pluginOptions['maxDate'] !== null 
        		&& $this->pluginOptions['maxDate'] !== ''  ){
        	$this->pluginOptions['maxDate'] = $this->getMaxMin($this->pluginOptions['maxDate']);
        }
        
        //config minDate
        if ( isset($this->pluginOptions['minDate'] ) && $this->pluginOptions['minDate'] !== null 
        		&& $this->pluginOptions['minDate'] !== ''  ){
        	$this->pluginOptions['minDate'] = $this->getMaxMin($this->pluginOptions['minDate'],false);
        }
        
        //config maxDate
        if ( isset($this->pluginOptions['maxDateTo'] ) && $this->pluginOptions['maxDateTo'] !== null
        		&& $this->pluginOptions['maxDateTo'] !== ''  ){
        			$this->pluginOptions['maxDateTo'] = $this->getMaxMin($this->pluginOptions['maxDateTo']);
        }
        
        //config minDate
        if ( isset($this->pluginOptions['minDateTo'] ) && $this->pluginOptions['minDateTo'] !== null
        		&& $this->pluginOptions['minDateTo'] !== ''  ){
        			$this->pluginOptions['minDateTo'] = $this->getMaxMin($this->pluginOptions['minDateTo'],false);
        }
        
        //merge pluginOptions to clientOptions
       $this->clientOptions = array_merge($this->clientOptions,$this->pluginOptions);
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        echo ($this->isRange?$this->renderWidgetDateRange():$this->renderWidget()) . "\n";
        
        $containerID = $this->inline ? $this->containerOptions['id'] : $this->options['id'];
        $language = $this->language ? $this->language : Yii::$app->language;
        $language = strtolower( substr($language, 0,2));

        if (strncmp($this->dateFormat, 'php:', 4) === 0) {
            $this->clientOptions['dateFormat'] = FormatConverter::convertDatePhpToJui(substr($this->dateFormat, 4));
        } else {
            $this->clientOptions['dateFormat'] = FormatConverter::convertDateIcuToJui($this->dateFormat, 'date', $language);
        }
		
        if ($language !== 'en-US' && $language !== 'en' && $language !== 'EN' ) {
            $view = $this->getView();
            //$assetBundle = DatePickerLanguageAsset::register($view);
            $assetBundle = DateTimePickerAsset::register($view);
           	$assetBundle->language = $language;
            $options = Json::htmlEncode($this->clientOptions);
            $language = Html::encode($language);
            
            $picker = "date".($this->isDateTime?"time":"")."picker";

            	if (!$this->isRange){
            		$script = "$('#{$containerID}').{$picker}($.extend({}, $.datepicker.regional['{$language}'], $options));";
            		
            	}else {
            		$script = "$('#{$this->from}').{$picker}($.extend({}, $.datepicker.regional['{$language}'], $options))";
            		$script .= ".on( 'change', function() { $('#{$this->to}').{$picker}( 'option', 'minDate', getDate( this ) );}),";
            		
            		//parsing maxDateTo and minDateTo to clientOptions afterward jsonencode to $options
            		//Util::viewArr( $this->clientOptions);
            		$this->clientOptions['maxDate'] = isset($this->clientOptions['maxDateTo'])?$this->clientOptions['maxDateTo']:'' ;
            		$this->clientOptions['minDate'] =  isset($this->clientOptions['minDateTo'])? $this->clientOptions['minDateTo']:'';
            		$options = Json::htmlEncode($this->clientOptions);
            		
            		$script .= "$('#{$this->to}').{$picker}($.extend({}, $.datepicker.regional['{$language}'], $options))";
            		$script .= ".on( 'change', function() { $('#{$this->from}').{$picker}( $.extend({}, $.datepicker.regional['{$language}'], $options) );});";
            		
            		$script .= "
            				function getDate( element ) {
						      var date;
						      try {
						        date = $.datepicker.parseDate( dateFormat, element.value );
						      } catch( error ) {
						        date = null;
						      }
						 
						      return date;
						    }";
            	}
	
            $view->registerJs($script);
            	$bundle = $this->getView()->assetManager->getBundle('wattanapong\datetime\DateTimePickerAsset' );
            	$bundle->js[] = ['js/locales/jquery.ui.datepicker-'.$language.'.js'];

            	if ($language == 'th')
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
        $value = $this->value;
        if ( ($value == null || $value == '' )  && $this->hasModel() ) {
        		$value = Html::getAttributeValue($this->model, $this->attribute);
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
    
    /**
     * Renders the DatePicker widget in DateRange Mode. 
     * @return string the rendering result.
     */
    protected function renderWidgetDateRange()
    {
    	$contents = [];
    	$content2s = [];
    
    	$value = $this->value;
    	$valueTo= $this->valueTo;
    	
    	//send value to hasModel() in InputWidget
    	$this->attribute = $this->from;
    	
    	// get formatted date value
    	if ( ($value == null || $value == '' )  && $this->hasModel() ) {
    		$value = Html::getAttributeValue($this->model, $this->from);
    	}
    	
    	
    	if ( ($valueTo == null || $valueTo == '' )  && $this->hasModel() ) {
    		$valueTo = Html::getAttributeValue($this->model, $this->to);
    	}
    
    	if ($value !== null && $value !== '') {
    		// format value according to dateFormat
    		try {
    			$value = Yii::$app->formatter->asDate($value, $this->dateFormat);
    		} catch(InvalidParamException $e) {
    			// ignore exception and keep original value if it is not a valid date
    		}
    	}
    	
    	if ($valueTo !== null && $valueTo !== '') {
    		// format value according to dateFormat
    		try {
    			$valueTo = Yii::$app->formatter->asDate($valueTo, $this->dateFormat);
    		} catch(InvalidParamException $e) {
    			// ignore exception and keep original value if it is not a valid date
    		}
    	}
    	
    	$options = $this->options;
    	$options['value'] = $value;
    
    	if ($this->inline === false) {  
    		// render a text input
    		if ($this->hasModel()) {
    			$options['id'] = $this->from;
    			$contents[] = Html::activeTextInput($this->model, $this->from, $options);
    			
    			$options['id'] = $this->to;
    			$options['value'] = $valueTo;
    			$content2s[] = Html::activeTextInput($this->model, $this->to, $options);
    		} else {
    			$options['id'] = $this->from;
    			$contents[] = Html::textInput($this->from, $value, $options);
    			
    			$options['value'] = $valueTo;
    			$options['id'] = $this->to;
    			$content2s[] = Html::textInput($this->to, $valueTo, $options);
    		}
    	} else { 
    		// render an inline date picker with hidden input
    		if ($this->hasModel()) {
    			$contents[] = Html::activeHiddenInput($this->model, $this->from, $options);
    			$options['value'] = $valueTo;
    			$content2s[] = Html::activeHiddenInput($this->model, $this->to, $options);
    		} else {
    			$contents[] = Html::hiddenInput($this->from, $value, $options);
    			$options['value'] = $valueTo;
    			$content2s[] = Html::hiddenInput($this->to, $valueTo, $options);
    		}
    		$this->clientOptions['defaultDate'] = $value;
    		$this->clientOptions['altField'] = '#' . $this->from;
    		$contents[] = Html::tag('div', null, $this->containerOptions);
    		
    		$this->clientOptions['defaultDate'] = $valueTo;
    		$this->clientOptions['altField'] = '#' . $this->to;
    		$content2s[] = Html::tag('div', null, $this->containerOptions);
    	}
    
    	return '<div class="inline"><div class="input-group">'.
    	implode("\n", $contents).
    	'<div class="input-group-addon"> '.$this->separate.' </div>'.
    	implode("\n", $content2s).
    	'</div></div>';
    }
    
    private function getMaxMin($datetime,$isMax=true){
    	$start = new \DateTime('now');// (new \DateTime())->format('d M Y'); 
    	$end = new \DateTime($datetime);
    	
    	if ($isMax) 
    		$end->add(new \DateInterval('P1D'));
    	
    	$interval = $start->diff($end);
    	return $interval->format('%R%yY %R%mM %R%dD');
    }
}

