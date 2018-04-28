<style>@import url('<?php echo base_url('assets/datetimepicker/bootstrap-datetimepicker.min.css');?>')</style>
<script type="text/javascript" src="<?php echo base_url('assets/datetimepicker/moment.min.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/datetimepicker/hu.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/datetimepicker/bootstrap-datetimepicker.min.js');?>"></script>

<script type="text/javascript" src="<?php echo base_url('assets/tinymce/tinymce.min.js');?>"></script>
<script type="text/javascript">
	$(document).ready(function () {
		tinymce.init({
			selector:'textarea',
			language: 'hu_HU',
			language_url : '<?php echo base_url('assets/tinymce/langs/hu_HU.js');?>',
			relative_urls: false,
			entity_encoding: "raw",
			toolbar: [
				'undo redo | bold italic underline | forecolor backcolor | alignleft aligncenter alignright alignjustify | link unlink',
				 'paste pastetext pasteword removeformat | code preview | image jbimages',
			],
			plugins: [
				'advlist autolink lists link image imagetools charmap preview anchor',
				'searchreplace visualblocks code textcolor colorpicker',
				'media table contextmenu paste jbimages wordcount'
			],
			imagetools_toolbar: 'alignleft aligncenter alignright alignjustify | imageoptions'
		});
	});
</script>