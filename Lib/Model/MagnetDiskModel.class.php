<?php
require_once('./Lib/Common/DiskAjaxResopnseFactory.class.php');

class MagnetDiskModel extends BaseDiskArchivesModel {
	
	function getRealFileUrl($param) {
		$realFileUrl = array($param['url']);
		return DiskAjaxResopnseFactory::getSuccessResponse($realFileUrl);
	}
	
	/* (non-PHPdoc)
	 * @see iBaseDiskArchives::checkLostEfficacy()
	 */
	public function checkLostEfficacy($param) {
		
	}

	/* (non-PHPdoc)
	 * @see iBaseDiskArchives::checkcodeDeal()
	 */
	public function checkcodeDeal($param) {
		
	}


}