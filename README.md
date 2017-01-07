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

#Installation
--------------
--------------

To install, either run

$ composer require wattanapong/yii2-widget-datetimepicker "@dev"

or add

"wattanapong/yii2-widget-datetimepicker" : "@dev"

#How to use
All of usage base on jquery-ui
--------------
--------------
example
<?= $form->field($model, 'attributename')->widget(DateTimePicker::className(),
    [
    	'language' => 'th',
    	'dateFormat' => 'php:d M yy',
    	'isDateTime' => true,
    	'timeFormat' => 'h:m:s',
    	'options' => ['class'=>'form-control'],
   	]
)?>