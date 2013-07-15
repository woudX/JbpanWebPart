<?php

class SearchAction extends DkAction {
	
	function index(){		

		$key = isset($_GET['key']) ? trim($_GET['key']) : "";
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		
		$searchResult = array();

		if($key != ""){
			$archives = D('BaseDiskArchives');
			
			$searchResult = $archives->search(htmlspecialchars($key),$page);
		}
		
		$this->assign('key', htmlspecialchars(stripslashes($key)));
		$this->assign('page', $page);
		$this->assign('archivesList', $searchResult);
		$this->assign('codePrefix',C('CODE_PREFIX'));
		$this->display();
	}
	
}

?>