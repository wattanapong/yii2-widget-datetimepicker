#yii2-widget-datetimepicker
--------------
--------------

This is thai buddhist calendar datetimepicker of Yii2 extension.
modified from https://code.google.com/archive/p/jquery-ui-datepicker-extension-buddhist-era/
by sorajate@gmail.com

created by wattanapong suttapak
email me wattanapong.su@up.ac.th

This version 1.0.0
It's completed version of datetimepicker work on jquery-ui less than version 1.9 .
This version support datepicker and datetimepicker via Datetimepicker::Classname

## Installation
--------------
--------------

To install, either run

$ composer require wattanapong/yii2-widget-datetimepicker "@dev"

or add

"wattanapong/yii2-widget-datetimepicker" : "@dev"

## Usage
All of usage base on jquery-ui

```php
use wattanapong\datetime\DateTimePicker;

// usage without model
echo '<label>Check Issue Date</label>';
echo DateTimePicker::widget([
	'name' => 'attributename', 
	'value' => date('d M Y', strtotime('+2 days')),
	'options' => ['placeholder' => 'Select date ...'],
	'pluginOptions' => [
		'format' => 'dd-M-yyyy',
		'todayHighlight' => true,
		'isBE' => true,	
		'timeFormat' => 'hh:mm:ss',
		'buttonImageOnly'=> true,
		'maxDate' => date('d M Y',strtotime('+2 days')),
		'minDate' => date('d M Y',strtotime('-10 days')),
	]
]);
```

<?= $form->field($model, 'attributename')->widget(DateTimePicker::className(),
    [
    	'dateFormat' => 'php:d M yy',
		'isDateTime' => false,
		'name' => 'attributename',
		'value' => date('d M Y', strtotime('+2 days')),
		'options' => ['class'=>'form-control','placeholder' => 'Select date ...'],
		'pluginOptions' =>[
			'isBE' => true,	
			'timeFormat' => 'hh:mm:ss',
			'buttonImageOnly'=> true,
			'maxDate' => date('d M Y',strtotime('+2 days')),
			'minDate' => date('d M Y',strtotime('-10 days')),
		]
   	]
)?>
```