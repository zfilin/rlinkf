<?php

$header = <<<END
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="theme/main.css" media="all" />
<script type="text/javascript" src="lib/jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="lib/dynamic_list.js"></script>
<title>rlinkf</title>
</head>
<body>
<div class="main_center">
END;

$footer = <<<END
</div>
</body>
</html>
END;

$btn_edit=_('Edit');
$btn_delete=_('Delete');
$btn_ok=_('Ok');
$btn_cancel=_('Cancel');
$btn_add=_('Add');

$dynamic_list = array(
'item' => <<<END
	<li id="%%index%%">
	<div class="item_id"><a href="%%data%%">%%index%%</a></div>
	<div class="item_content">%%data%%</div>
	<div class="controls"><a href="#" class="edit"><img src="theme/edt.gif" width="17" height="17" border="0" />&nbsp;$btn_edit</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" class="delete"><img src="theme/del.gif" width="16" heigth="16" border="0" />&nbsp;$btn_delete</a></div>
	</li>
END
,
'add' => <<<END
	<li class="add_item">
	<input class="item_id" type="text" value=""><br />
	<input class="item_content" type="text" value="">
	<div class="controls"><a href="#" class="add"><img src="theme/add.gif" width="17" height="17" border="0" />&nbsp;$btn_add</a></div>
	</li>
END
,
'edit' => <<<END
	<li class="editing" id="%%index%%">
	<div class="item_id"><a href="%%data%%">%%index%%</a></div>
	<input class="item_content" type="text" value="%%data%%">
	<div class="controls"><a href="#" class="ok"><img src="theme/ok.gif" width="16" height="16" border="0" />&nbsp;$btn_ok</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" class="cancel"><img src="theme/cnc.gif" width="16" height="16" border="0" />&nbsp;$btn_cancel</a></div>
	</li>
END
);
?>