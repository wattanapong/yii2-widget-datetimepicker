/* Thai initialisation for the jQuery UI date picker plugin. */
/* Written by pipo (pipo@sixhead.com). */
/*datepicker.regional['th'] was inherit from jquery.ui.datepicker-th.js */
/* modefied by wattanapong suttapak */
/* wattanapong.su@up.ac.th */
jQuery(function($){
	$.timepicker.regional['th'] = {
		timeText: 'เวลา',
		hourText: 'ชั่วโมง',
		minuteText: 'นาที',
		secondText: 'วินาที',
		currentText: 'เวลาปัจจุบัน',
		closeText: 'ตกลง',
		};
	$.timepicker.setDefaults($.timepicker.regional['th']);
});