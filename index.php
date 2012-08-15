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
</head>
<body id="nosn">
	<? include('header.php'); ?>
	<div class="container-fluid" id="dynPageBody">
	<div id="wrap">
	<? include('pages/' . $_POST['sec'] . '.php'); ?>
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
