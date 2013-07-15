<?php
class IndexAction extends DkAction
{

    public function index()
    {
		$clientIp = BaseDiskArchivesModel::getClientIp();
		$this->assign('clientIp',$clientIp);
		$this->assign("isLogin", $this->isLogin);
		$this->assign("loginData",$this->loginData);
		$this->display();
    }

	public function ArchivesList()
	{
		$p = isset($_GET['p']) ? intval($_GET['p']) : 1;
		
		/*$archivesList = D('BaseDiskArchives')->getList($page);
		$this->assign('page', $page);
		$this->assign('archivesList', $archivesList);
		$this->assign('codePrefix',C('CODE_PREFIX'));
		$this->assign("isLogin", $this->isLogin);
		$this->assign("loginData",$this->loginData);
		echo json_encode($archivesList);*/
		
		$User = M("Archives");
	
		import("ORG.Util.Page");
		$count = $User->where("id>1")->count();

		$Page = new Page($count, C('SEARCH_PAGESIZE'));
		$show = $Page->show();
		$Page->setConfig('header', '员');
		$list = $User->where('id>1')->order('dateline')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('archivesList',$list);
		$this->assign('codePrefix',C('CODE_PREFIX'));

	$show=array (
		nowPage=>$Page->nowPage,
		totalPages=>$Page->totalPages,
		rollPage=>$Page->rollPage,
		nextPage=>$Page->nowPage+1,
		
	);
	$this->assign('show',$show);
// var_dump($show);
		$this->display();
	}
	
	function test() {
		$all=D("DkUser");
		//var_dump($all);
		
		
	}
	
}
?>