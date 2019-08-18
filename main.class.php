<?php

Class main {

	private $db_links = false;
	private $set = array();
	private $tmpl = array();
	private $copyright = "<div style=\"font: 10px sans-serif; text-align: right; color: #8DB3C2;\"><a href=\"http://zfilin.org.ua/link/rlinkf\" style=\"color: #8DB3C2;\"><b>rlinkf</b></a> / <a href=\"http://zfilin.org.ua/\" style=\"color: #8DB3C2;\">&copy; Green FiLin, 2010</a></div>";

	function errorlog($text) {
		echo $text;
	}

	function initialize_i18n($locale) {
		$locales_root='./locale';
    	putenv('LANG='.$locale);
    	setlocale(LC_ALL,"");
    	@setlocale(LC_MESSAGES,$locale);
    	@setlocale(LC_CTYPE,$locale);
    	$domains = glob($locales_root.'/'.$locale.'/LC_MESSAGES/messages-*.mo');
    	$current = basename($domains[0],'.mo');
    	$timestamp = preg_replace('{messages-}i','',$current);
    	bindtextdomain($current,$locales_root);
    	textdomain($current);
	}

	function __construct() {
		header('Content-Type: text/html; charset=UTF-8');

		require_once('settings.php');
		$this->set=$settings;

		$this->initialize_i18n($this->set['locale']);

		require_once('db_'.$this->set['db_type'].'.class.php');

		$this->db_links = new DBLinks($this->set['db_options']);
		if(!$this->db_links->Connect())
		{
			$this->errorlog(sprintf(_('Can`t connect to links database because: %s'),$db_links->error_message));
			return false;
		}

		//file_put_contents('server.txt',var_export( $_SERVER ,true));

		require_once('theme/template.php');
		$this->tmpl['header'] = $header;
		$this->tmpl['footer'] = $footer;
		$this->tmpl['dynamic_list'] = $dynamic_list;

		//---Select script mode---
		if(@isset($_GET['json']))
			$this->AJAXmode();
		else
			$this->PAGEmode();

		$this->db_links->Disconnect();
	}

	function is_authorized() {
		list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':', base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
		if(@$_SERVER['PHP_AUTH_USER']!=$this->set['admin_user'] || @$_SERVER['PHP_AUTH_PW']!=$this->set['admin_password']) return false;
		return true;
	}

	function AJAXmode() {
		if(parse_url($_SERVER['HTTP_REFERER'],PHP_URL_HOST)!=$_SERVER['SERVER_NAME']) {
			$this->errorlog(_("Access forbidden!"));
			return false;
		}
		if(parse_url($_SERVER['HTTP_REFERER'],PHP_URL_PATH)!=dirname($_SERVER['PHP_SELF']).'/_admin_') {
			$this->errorlog(_("Access forbidden!"));
			return false;
		}
		if(!$this->is_authorized()) {
			$this->errorlog(_("Access forbidden!"));
			return false;
		}

		$action=$_GET['action'];
		$list_id=$_GET['list_id'];

		//file_put_contents('debug_get.txt',var_export($_GET,true));

		$str_ajax_error=_('AJAX query error: ');

		switch ($action) {
    		case 'get_templates':
				echo json_encode($this->tmpl['dynamic_list']);
				break;
    		case 'get_all':
				$all_links = $this->db_links->GetAllLinks();
				if($all_links!==false) echo json_encode($all_links);
				else echo json_encode($str_ajax_error.$this->db_links->error_message);
				break;
    		case 'delete':
				$item_id=htmlspecialchars(strip_tags($_GET['item_id']));
				$res=$this->db_links->DelLink($item_id);
				if(!$res) echo $str_ajax_error.$this->db_links->error_message;
				else echo 0;
				break;
    		case 'add':
				$item_id=htmlspecialchars(strip_tags($_GET['item_id']));$content=htmlspecialchars(strip_tags($_GET['content']));
				if($item_id=="") echo json_encode(_('You must set link tag!'));
				else
				{
					$res=$this->db_links->PutLink($item_id,$content);
					if(!$res) echo json_encode($str_ajax_error.$this->db_links->error_message);
					else echo json_encode(array('item_id'=>$item_id));
				}
				break;
    		case 'edit';
    			$item_id=htmlspecialchars(strip_tags($_GET['item_id']));$content=htmlspecialchars(strip_tags($_GET['content']));
				if($item_id=="") echo _('You must set link tag!');
				else
				{
					$res=$this->db_links->PutLink($item_id,$content,true);
					if(!$res) echo $str_ajax_error.$this->db_links->error_message;
					else echo 0;
				}
    			break;
		}
		return true;
	}

	function PAGEmode() {

		//------------------Get url-tag------------------
		$request_uri = str_replace("..", "", $_SERVER['REQUEST_URI']);
		$base_uri = dirname($_SERVER['PHP_SELF']);
		$tag = parse_url(substr($request_uri, strlen($base_uri)+1),PHP_URL_PATH);

		//------------------Start script------------------

		$admin_mode=false;

		//Admin login
		if($tag=='_admin_') {
			if(isset($_GET['logout'])) {
				header("Location: http://x:x@".$_SERVER['SERVER_NAME'].$base_uri.'/'.$tag);
				return true;
			} elseif(!$this->is_authorized()) {
				header('WWW-Authenticate: Basic realm="'._("Please login to enter to admin area").'"');
				header('HTTP/1.0 401 Unauthorized');
				$this->errorlog(_("Access forbidden!"));
				return false;
			}
			$admin_mode=true;
		}

		if(!$admin_mode)
		{
			//Redirecting or...
			$link=$this->db_links->GetLink($tag);
			if($link===false || $tag=='/') {
				if($this->set['empty_redirect']!==false) {
					header("Location: ".$this->set['empty_redirect']);
					exit;
				}
			} else {
				header("Location: ".$link);
				exit;
			}
			//...show list page
			$this->PAGEmode_list();
		}
		else
			$this->PAGEmode_admin($base_uri.'/'.$tag);

		return true;
	}

	function PAGEmode_list() {
		echo $this->tmpl['header'];
		echo "<h1>"._('Link list')."</h1>\n";
		echo "<div class=\"list_block\">\n";
		echo "<div id=\"top_frame\">&nbsp;</div>";

		echo "<ul>\n";
		foreach($this->db_links->GetAllLinks() as $tag=>$link) {
			echo "\t<li>";
			echo '<div class="item_id">'.$tag.'</div>';
			echo '<div><a href="'.$link.'">'.$link.'</a></div>';
			echo "</li>\n";
		}
		echo "</ul>\n";

		echo "<div id=\"bottom_frame\">&nbsp;</div>";
		echo "</div>\n";
		echo $this->copyright;
		echo $this->tmpl['footer'];
	}

	function PAGEmode_admin($logout_uri) {
		echo $this->tmpl['header'];
		echo "<h1>"._('Link list')."</h1><h2>"._('(admin area)')."</h2>";
		echo "<div class=\"list_block\">\n";
		echo "<div id=\"top_frame\">&nbsp;</div>";

		echo "<ul class=\"dynamic_list\" id=\"links\">\n";
		echo "</ul>\n";

		echo "<div id=\"logout\"><a href=\"".$logout_uri."?logout\">"._('logout')."</a></div>";
		echo "<div id=\"bottom_frame\">&nbsp;</div>";
		echo "</div>\n";
		echo $this->copyright;
		echo $this->tmpl['footer'];
	}

}

?>