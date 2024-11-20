var base_url = location.origin + "/artmin/";
var site_url = location.origin + "/";

function remove_non_ascii(str) {
	if ((str || "") !== "") {
		return String(str).replace(/[^\x20-\x7E]/g, "");
	}
	return str;
}

function default_script() {

	$('input').change(function(e) {
		$(e.target).val(remove_non_ascii(e.target.value));
	});
	$('textarea').change(function(e) {

		if (($(e.target).attr('allow-ascii') ?? "") === 'true') {
			return;
		}

		$(e.target).val(remove_non_ascii(e.target.value));

	});

	$('.number').on('click', function(){
		$(this).select();
	});

	$(document)
	.ajaxStart(function(){
		$(".loading").fadeIn(300);
	})
	.ajaxStop(function(){
		$(".loading").fadeOut(300);
	})
	.ajaxError(function(){
		$(".loading").fadeOut(300);
	})
	.ajaxComplete(function(){
		$(".loading").fadeOut(300);
	})
	.ajaxSuccess(function(){
		$(".loading").fadeOut(300);
	});

	$(".select-input").click(function() {
		$(this).select();
	});

	// In your Javascript (external .js resource or <script> tag)
	$(document).ready(function() {
	    $('.select2').select2();
	});

	$("#calendar-home").datepicker();

	$('.datepicker').datepicker({ format: 'dd-mm-yyyy', todayHighlight:'true', autoclose: 'true', disableTouchKeyboard: 'true' });
	$('.dob-datepicker').datepicker({ format: 'dd-mm-yyyy', todayHighlight:'true', autoclose: 'true', disableTouchKeyboard: 'true', startView: 2, endDate: new Date()});

	$('.datetimepicker').datetimepicker({
		format: 'DD/MM/YYYY HH:mm'
	});

	$('input[name=username]').focus();

	//Price Format
	if ($.fn.priceFormat) {
		$('.money').priceFormat({
			prefix: '',
			thousandsSeparator: '.',
			centsLimit: 0
		});
	}

	$(".number").keypress(function (e) {
		//if the letter is not digit then display error and don't type anything
		if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
			if(e.which != 13){
				return false;
			}
		}
	});

	// Handle showing password field when reset button pressed
	$('#reset-btn').click(function(e){
		e.preventDefault();
		$('#statuspass').attr("value", "1");
		$('#pass-container').removeClass('hide');
	});

	// Handle hiding password field when cancel button pressed
	$('#cancel-reset-btn').click(function(e){
		e.preventDefault();
		$('#statuspass').attr("value", "0");
		$('#pass-container').addClass('hide');
	});

	//Enable iCheck plugin for checkboxes iCheck for checkbox and radio inputs

	if ($.fn.iCheck) {
		$('.icheck').iCheck({
			checkboxClass: 'icheckbox_flat-blue',
			radioClass: 'iradio_flat-blue'
		});

		// Enable check and uncheck all functionality
		$(".checkbox-toggle").click(function () {
			var clicks = $(this).data('clicks');
			if (clicks) {
				$("input[type='checkbox']").iCheck("uncheck");
				$(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
			} else {
				$("input[type='checkbox']").iCheck("check");
				$(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
			}
			$(this).data("clicks", !clicks);
		});

		//iCheck for checkbox and radio inputs
		$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
			checkboxClass: 'icheckbox_minimal-blue',
			radioClass: 'iradio_minimal-blue'
		});

	}

	// $('input[type="checkbox"], input[type="radio"]').iCheck({
	// 	checkboxClass: 'icheckbox_flat-blue',
	// 	radioClass: 'iradio_flat-blue'
	// });

	// $('.data-table, .data-table-ns').destroy();

	// Data Table

	if ($.fn.DataTable) {
		var table = $('.data-table').DataTable( {
			responsive: true,
			destroy: true,
			retrieve: true,
		} );

		$('.data-table-ns').DataTable({
			"ordering": false,
			"pageLength": 50
		});
	}

	//bootstrap WYSIHTML5 - text editor
	if ($.fn.wysihtml5) {
		$(".simple-editor").wysihtml5();
	}

	//Change password button
	var form = $("#change-password-container");
	$("#btn-change-password").click(function(e){
		form.removeClass("hide");
		$("#currPass").prop("required", true);
		$("#newPass").prop("required", true);
		$("#confPass").prop("required", true);
		document.getElementById('statuspass').value =  "1";
	});

	$("#btn-cancel-change-password").click(function(e){
		var btn = $("btn-change-password");
		form.addClass("hide");
		btn.removeClass("hide");
		$("#currPass").prop("required", false);
		$("#newPass").prop("required", false);
		$("#confPass").prop("required", false);
		document.getElementById('statuspass').value =  "0";
		document.getElementById('currPass').value =  "";
		document.getElementById('newPass').value = "";
		document.getElementById('confPass').value = "";
		document.getElementById('rsp_curr').innerHTML =  "";
		document.getElementById('rsp_new').innerHTML = "";
		document.getElementById('rsp_conf').innerHTML = "";
	});

	$('.sidebar-toggle').click(function(){
		$.post(base_url + "sidebar/");
	});

	// Tiny MCE
	tinymce.init({
		selector: '.editor',
		height: 200,
		plugins: [
			"advlist autolink link image lists charmap print preview hr anchor pagebreak code",
			"searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
			"table contextmenu directionality emoticons paste textcolor responsivefilemanager"
		],
		toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
		toolbar2: "responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
		image_advtab: true,
		content_css: [
			'//www.tinymce.com/css/codepen.min.css',
			site_url + 'assets/css/style.css'
		],
		external_filemanager_path:site_url + "fmanager/",
		filemanager_title:"File Manager" ,
		filemanager_access_key: "R4z5OEYf1h29rzVepedx",
		external_plugins: { "filemanager" : site_url + "fmanager/plugin.min.js"}

	});

	$('#title').on("keyup", function(){
		var str = $('#title').val();
		str = str.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-').toLowerCase();
		$('#url').val(str);
	});

	function changeURL(value){
        var str = value;
        str = str.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-').toLowerCase();
        $('#slug').val(str);
    }

}

$(document).ready(function(){
	default_script();
});

function changeURL(value){
	var str = value;
	str = str.replace(/\s+/g, '-').toLowerCase();
	$('#ph_url').val(str);
}

function readURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();

		reader.onload = function (e) {
			$('#temp').attr('src', e.target.result);
			$('#tempURL').attr('href', e.target.result);
			$('#preview').removeClass('hide');
		}

		reader.readAsDataURL(input.files[0]);
	}
}

function readidURL(id, input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();

		reader.onload = function (e) {
			$('#' + id).attr('src', e.target.result);
			$('#' + id + '-url').attr('href', e.target.result);
		}

		reader.readAsDataURL(input.files[0]);
	}
}

function read_message(id){
	$('#message-content').load(base_url + 'mail/read/' + id);
}


$('.iframe-btn').fancybox({
	'width'		: 1000,
	'height'	: 800,
	'type'		: 'iframe',
	'autoScale'    : true
});

function responsive_filemanager_callback(field_id){
	console.log(field_id);
	// var url=jQuery('#'+field_id).val();
	var url=$('#'+field_id).val();
	$('#pdf').trigger("change");
	$('#preview_'+field_id).attr("src", url);
	// alert('update '+field_id+" with "+url);
	//your code
}

function number_input(){

	$(".number").keypress(function (e) {
		//if the letter is not digit then display error and don't type anything
		if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
			if(e.which != 13){
				return false;
			}
		}
	});
}
