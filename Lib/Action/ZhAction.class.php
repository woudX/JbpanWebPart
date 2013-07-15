<?php

class ZhAction extends Action {
	
	function index(){	

		$allresult=array();
		$hanhuagroup = M("hanhuagroup");
	
		$hanhuagrouplist = $hanhuagroup->where("groupid")->limit(1000)->select();

		$this->assign('hanhuagrouplist',$hanhuagrouplist);
		//$this->assign('filelist', $filelist);
		$this->display();		
		
	}


	function hhzfile(){	

		$groupcode= $_GET['code'];
	
		$allresult=array();
		$hanhuagroup = M("hanhuagroup");
		
	
		$hanhuagroup = $hanhuagroup->where("groupcode=".'"'.$groupcode.'"')->find();
	
		$file = M("hanhuafile");

		$filelist = $file->where("fileid and groupcode=".'"'.$groupcode.'"')->limit(1000)->order('fileid desc')->select();


		$this->assign('hanhuagroup',$hanhuagroup);
		$this->assign('filelist', $filelist);
		$this->display();	
		
	}
}