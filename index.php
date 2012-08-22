<?

include_once('php/global/ranvier.php');

?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link href="/css/base.css" rel="stylesheet">
	<script language="javascript" src="/js/jquery.js"></script>
	<script language="javascript" src="/js/bootstrap.min.js"></script>
	<script language="javascript" src="/js/jquery.doTimeout.min.js"></script>
	<script language="javascript" src="/js/ranvier.js"></script>
	<script language="javascript" src="/js/sprintf.js"></script>
	<script language="javascript" src="/js/jquery-ui.min.js"></script>
	<script language="javascript" src="/js/jquery-touch-punch.js"></script>
	<script language="javascript" src="/js/json2.js"></script>
	<script language="javascript" src="/js/jqTouch.js"></script>
	<script src="/js/ace/ace.js" type="text/javascript" charset="utf-8"></script>
	<script src="/js/ace/theme-ranvier.js" type="text/javascript" charset="utf-8"></script>
	<script src="/js/ace/mode-javascript.js" type="text/javascript" charset="utf-8"></script>
	<script src="/js/mdetect.js" type="text/javascript" charset="utf-8"></script>
</head>
<body id="nosn">
	<? include('header.php'); ?>
	<div class="container-fluid" id="dynPageBody">
	<div id="wrap">
	<? include('pages/' . $_POST['sec'] . '.php'); ?>
	</div>
	</div>

<div class="modal hide fade" id="scriptEditor">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">x</button>
		<h3>Script Editor</h3>
	</div>
	<div class="modal-body">
		<div class="row-fluid">
			<div class="span4">Editor For<input type="hidden" id="fileID"></div>
			<div class="span8"><span id="editorTitle"></span></div>
		</div>
		<div class="row-fluid">
			<div id="editorTextPlacement"></textarea></div>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn" data-dismiss="modal">Close Without Saving</a>
		<a href="#" class="btn btn-primary" onClick="saveEditor()">Save changes</a>
	</div>
</div>
</body>
<script>
$(document).ready(function() {
	$("body").on({
		click: function (e) {
			e.preventDefault();
			var p = $(this).attr('href').split('?');
			var action = p[0];
			var params = p[1].split('&');
			var form = $(document.createElement('form')).attr('action', action).attr('method','post');
			$('body').append(form);
			for (var i in params) {
				var tmp= params[i].split('=');
				var key = tmp[0], value = tmp[1];
				$(document.createElement('input')).attr('type', 'hidden').attr('name', key).attr('value', value).appendTo(form);
			}
			$(form).submit();
			return false;
		}
	}, 'a.post');
});
</script>
</html>
