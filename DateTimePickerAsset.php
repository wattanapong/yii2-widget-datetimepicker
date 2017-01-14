<?php

/**
 * @copyright Copyright &copy; Wattanapong Suttapak, 2017
 * @version 1.0.0
 */

namespace wattanapong\datetime;
use \yii\web\AssetBundle;

/**
 * Asset bundle for DateTimePicker Widget
 *
 * @author Wattanapong Suttapak <wattanapong.su@up.ac.th>
 * @since 1.0
 */
class DateTimePickerAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/assets';
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
    		'yii\jui\JuiAsset' 
    ];
    
    public $js = [
    		'js/jquery.ui.datetimepicker.js',
    		'js/jquery.ui.datepicker.ext.be.js'
    ];

    /**
     * @inheritdoc
     */
    public function registerAssetFiles($view)
    {
        if ($this->autoGenerate) {     
            $this->js[] = "js/jquery.ui.datetimepicker.js";
            $this->js[] = "js/jquery.ui.datepicker.ext.be.js";
        }
        parent::registerAssetFiles($view);
    }
    
    public function init()
    {
    	
    	parent::init();
    	$this->publishOptions['forceCopy'] = true;
    }
}