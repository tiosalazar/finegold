jQuery(document).ready(function() {

console.log('Jodeeeeeeeeeeeeeeeee!!');
$('.date_custom').on('focus',function () {
	this.type='date';
});
$('.date_custom').on('blur',function () {
	this.type='text';
});
});