<?php

require_once('./Lib/Common/MailRemind.class.php');
require_once('./Lib/Common/DiskModelFactory.class.php');

class LostEfficacyRemindAction extends DkAction {
	function index(){

		//ƥ����ȡ�룬����Ϲ������˳�
		$extractCode = isset($_GET['code']) ? trim($_GET['code']) : "";
		if(!preg_match('/^'.C('CODE_PREFIX').'\d+$/i',$extractCode)){
			return;
		}
		
		//�޳�ǰ׺���õ�ID
		$archivesId = substr($extractCode,strlen(C('CODE_PREFIX')));
		
		$archivesModel = D('BaseDiskArchives');

		//���ID��ѯ���
		$archives = $archivesModel->find($archivesId);


		//ԭ��״̬Ϊ��Ч�ŷ��ͣ�ȷ��ֻ����һ��
		if(is_array($archives) && !empty($archives['email']) && $archives['iseffective'] != 0)
		{
			//ʵ�����ģ��
			$diskMode = DiskModelFactory::getDiskModel($archives['diskname']);

			$param['url'] = $archives['diskurl'];
			
			//����Ƿ�ʧЧ
			if( $diskMode->checkLostEfficacy($param) ){
				
				$archivesModel->setEffective(0,$archivesId);
				
				MailRemind::diskLoseEfficacy($archives);
				echo "Mail sent!";
			}
			else {
				echo "Need not remind!";
			}
		}
	}
}