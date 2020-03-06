<?php

class Model_Content extends Model_Abstract {

	function __construct(){
		$this->db = $this->getConnection();
	}

	function newContent($idUser, $data, $contentType){ 
		if ($data['file_name']=="")
			$data['file_name'] = $data['file'];
		$ftp = 1;
		if (isset($data['ftp']))
			$ftp = $data['ftp'];
		$mustMd5 = array(TYPE_VIDEO,TYPE_AUDIO,TYPE_IMAGE,TYPE_BACKGROUND);			
		$md5hash = NULL;
		if (in_array($contentType, $mustMd5))
			$md5hash = $data['md5'];
		$stmt = $this->db->prepare("insert into Content (idCustomer, name, content, idContentType, ftp, version, md5, createTime, updateTime) values (?,?,?,?,?,?,?,now(),now())");
		$stmt->execute(array($data['idCustomer'],$data['file_name'],$data['file'],$contentType,$ftp,1,$md5hash));	
		$result = $this->db->lastInsertId();
		//actualizar background a 1 en todos los display del cliente		
		if ($contentType == TYPE_BACKGROUND) {
			$stmt = $this->db->prepare("update Display inner join Zone on Zone.idCustomer = ? inner join Store on Store.idZone = Zone.id SET Display.background = 1 where Display.idStore = Store.id");
			$stmt->execute(array($data['idCustomer']));	
		}
		$stmt = $this->db->prepare("insert into AuditContent (idUser, idContent, idStatus, date) values (?,?,?,now())");
		$stmt->execute(array($idUser,$result,STATUS_NEW_CONTENT));	
		return $result;
	}

	function newYoutube($idContent,$idYoutube,$type){
		$stmt = $this->db->prepare("insert into YouTube (idContent, idYouTube, type, createTime, updateTime) values (?,?,?,now(),now())");
		$stmt->execute(array($idContent,$idYoutube,$type));
	}

	function newLabel($idContent,$idVideo,$text,$x,$y,$opacity,$color,$size){
		$stmt = $this->db->prepare("insert into Label (idContent, idVideo, text, position_x, position_y, opacity, color, size, createTime, updateTime) values (?,?,?,?,?,?,?,?,now(),now())");
		$stmt->execute(array($idContent,$idVideo,$text,$x,$y,$opacity,$color,$size));
	}

	function newWatermark($idContent,$idVideo,$idImage,$x,$y,$opacity){
		$stmt = $this->db->prepare("insert into Watermark (idContent, idVideo, idImage, position_x, position_y, opacity, createTime, updateTime) values (?,?,?,?,?,?,now(),now())");
		$stmt->execute(array($idContent,$idVideo,$idImage,$x,$y,$opacity));
	}

	function editContent($idUser,$data,$contentType,$version){
		if ($data['file_name']=="")
			$data['file_name'] = $data['file'];
		$mustMd5 = array(TYPE_VIDEO,TYPE_AUDIO,TYPE_IMAGE,TYPE_BACKGROUND);		
		$md5hash = NULL;
		if (in_array($contentType, $mustMd5))
			$md5hash = $data['md5'];
		if (!isset($data['file']) or $data['file'] == "") {
			$stmt = $this->db->prepare("update Content set name = ?, version = ?, updateTime = now() where id = ?");
			$stmt->execute(array($data['file_name'],$version,$data['editId']));	
		} else {
			$stmt = $this->db->prepare("update Content set name = ?, version = ?, content = ?, md5 = ?, updateTime = now() where id = ?");
			$stmt->execute(array($data['file_name'],$version,$data['file'],$md5hash, $data['editId']));		
		}
		//actualizar background a 1 en todos los display del cliente
		if ($contentType == TYPE_BACKGROUND) {
			$stmt = $this->db->prepare("update Display inner join Zone on Zone.idCustomer = ? inner join Store on Store.idZone = Zone.id SET Display.background = 1 where Display.idStore = Store.id");
			$stmt->execute(array($data['idCustomer']));
		}
		$stmt = $this->db->prepare("insert into AuditContent (idUser, idContent, idStatus, date) values (?,?,?,now())");
		$stmt->execute(array($idUser,$data['editId'],STATUS_EDIT_CONTENT));
	}

	function editYoutube($idContent,$idYoutube,$type){
		$stmt = $this->db->prepare("update YouTube set idYouTube = ?, type = ?, updateTime = now() where idContent = ?");
		$stmt->execute(array($idYoutube,$type,$idContent));	
	}

	function editLabel($idContent,$idVideo,$text,$x,$y,$opacity,$color,$size){
		$stmt = $this->db->prepare("update Label set idVideo = ?, text = ?, position_x = ?, position_y = ?, opacity = ?, color = ?, size = ?, updateTime = now() where idContent = ?");
		$stmt->execute(array($idVideo,$text,$x,$y,$opacity,$color,$size,$idContent));	
	}

	function editWatermark($idContent,$idVideo,$idImage,$x,$y,$opacity){
		$stmt = $this->db->prepare("update Watermark set idVideo = ?, idImage = ?, position_x = ?, position_y = ?, opacity = ?, updateTime = now() where idContent = ?");
		$stmt->execute(array($idVideo,$idImage,$x,$y,$opacity,$idContent));	
	}

	function deleteContent($idUser,$id){
		$stmt = $this->db->prepare("update Content set deleted = 1, updateTime = now() where id = ?");
		$stmt->execute(array($id));
		$stmt = $this->db->prepare("update YouTube set deleted = 1, updateTime = now() where idContent = ?");
		$stmt->execute(array($id));
		$stmt = $this->db->prepare("update Label set deleted = 1, updateTime = now() where idContent = ?");
		$stmt->execute(array($id));
		$stmt = $this->db->prepare("update Watermark set deleted = 1, updateTime = now() where idContent = ?");
		$stmt->execute(array($id));
		$stmt = $this->db->prepare("insert into AuditContent (idUser, idContent, idStatus, date) values (?,?,?,now())");
		$stmt->execute(array($idUser,$id,STATUS_DELETE_CONTENT));
	}

	function getContent($id){
		$row = "";
		$stmt = $this->db->prepare("select Content.name, Content.content, Content.version, Content.idContentType, ContentType.name as contentTypeName, Content.createTime, Content.updateTime from Content inner join ContentType on ContentType.id = Content.idContentType where Content.id = ?");	
		$stmt->execute(array($id));
		$rowData = $stmt->fetch(PDO::FETCH_ASSOC); 
		$row =array('id'=>$id,'name'=>$rowData['name'],'content'=>$rowData['content'],'version'=>$rowData['version'],'idContentType'=>$rowData['idContentType'],'contentTypeName'=>$rowData['contentTypeName'],'createTime'=>$rowData['createTime'],'updateTime'=>$rowData['updateTime']);
		return $row;
	}

	function getYoutube($idContent){
		$row = "";
		$stmt = $this->db->prepare("select id, idYouTube, type from YouTube where idContent = ?");	
		$stmt->execute(array($idContent));
		$rowData = $stmt->fetch(PDO::FETCH_ASSOC); 
		$row =array('id'=>$rowData['id'],'idYouTube'=>$rowData['idYouTube'],'type'=>$rowData['type']);
		return $row;
	}

	function getLabel($idContent){
		$row = "";
		$stmt = $this->db->prepare("select Label.id, Label.text, Label.position_x, Label.position_y, Label.opacity, Label.color, Label.size, Content.name, Content.id as idVideo from Label inner join Content on Content.id = Label.idVideo and Content.deleted = 0 where Label.idContent = ? and Label.deleted = 0");	
		$stmt->execute(array($idContent));
		$rowData = $stmt->fetch(PDO::FETCH_ASSOC); 
		$row =array('id'=>$rowData['id'],'text'=>$rowData['text'],'position_x'=>$rowData['position_x'],'position_y'=>$rowData['position_y'],'opacity'=>$rowData['opacity'],'color'=>$rowData['color'],'size'=>$rowData['size'],'video'=>$rowData['name'],'idVideo'=>$rowData['idVideo']);
		return $row;
	}

	function getWatermark($idContent){
		$row = "";
		$stmt = $this->db->prepare("select Watermark.id, Watermark.position_x, Watermark.position_y, Watermark.opacity, Content.id as idVideo, Content.name as videoName, Image.id as idImage, Image.name as imageName from Watermark inner join Content on Content.id = Watermark.idVideo and Content.deleted = 0 inner join Content as Image on Image.id = Watermark.idImage and Content.deleted = 0 where Watermark.idContent = ? and Watermark.deleted = 0");	
		$stmt->execute(array($idContent));
		$rowData = $stmt->fetch(PDO::FETCH_ASSOC); 
		$row =array('id'=>$rowData['id'],'position_x'=>$rowData['position_x'],'position_y'=>$rowData['position_y'],'opacity'=>$rowData['opacity'],'video'=>$rowData['videoName'],'idVideo'=>$rowData['idVideo'],'image'=>$rowData['imageName'],'idImage'=>$rowData['idImage']);
		return $row;
	}

	function getContentsForBlocks($idCustomer){	
		$stmt = $this->db->prepare("select Content.id, Content.name, Content.content, Content.version, Content.idContentType, Content.createTime, Content.updateTime, ContentType.name as contentTypeName from Content inner join ContentType on ContentType.id = Content.idContentType where Content.idCustomer = ? and Content.deleted = 0 and Content.idContentType not in (?,?) order by Content.name");
		$stmt->execute(array($idCustomer,TYPE_ICON,TYPE_BACKGROUND));	
		$rowData = $stmt->fetchAll(); 
		foreach ($rowData as $rowContent) {
				$rows[] = array('id'=>$rowContent['id'],'name'=>$rowContent['name'],'version'=>$rowContent['version'],'createTime'=>$rowContent['createTime'],'updateTime'=>$rowContent['updateTime'],'contentTypeName'=>$rowContent['contentTypeName'], 'content'=>$rowContent['content']);
		}
		return $rows;
	}

	function getContentsByCustomerWithDelete($idCustomer){
		$rows = array();		
		$stmt = $this->db->prepare("select Content.id, Content.name, Content.content, Content.version, Content.idContentType, Content.createTime, Content.updateTime, ContentType.name as contentTypeName, ScheduleBlockItem.idScheduleBlock, Playlist.id as idPlaylist, Label.id as idLabel, Watermark.id as idWatermark from Content inner join ContentType on ContentType.id = Content.idContentType left join ScheduleBlockItem on ScheduleBlockItem.id = (select ScheduleBlockItem.id from ScheduleBlockItem where ScheduleBlockItem.idContent = Content.id and ScheduleBlockItem.deleted = 0 limit 1) left join Playlist on Playlist.id = (select id from Playlist where idContent = Content.id and Playlist.deleted = 0 limit 1) left join Label on Label.id = (select id from Label where idVideo = Content.id and Label.deleted = 0 limit 1) left join Watermark on Watermark.id = (select id from Watermark where idVideo = Content.id or idImage = Content.id and Watermark.deleted = 0 limit 1) where Content.idCustomer = ? and Content.deleted = 0 order by Content.name");
		$stmt->execute(array($idCustomer));	
		$rowData = $stmt->fetchAll(); 
		foreach ($rowData as $rowContent) {
			$delete = false;
			if ((is_null($rowContent['idScheduleBlock']))&&(is_null($rowContent['idPlaylist']))&&(is_null($rowContent['idLabel']))&&(is_null($rowContent['idWatermark'])))
				$delete = true;
			$rows[] = array('id'=>$rowContent['id'],'name'=>$rowContent['name'],'version'=>$rowContent['version'],'createTime'=>$rowContent['createTime'],'updateTime'=>$rowContent['updateTime'],'idContentType'=>$rowContent['idContentType'],'contentTypeName'=>$rowContent['contentTypeName'], 'content'=>$rowContent['content'],'delete'=>$delete);
		}
		return $rows;
	}

	function getContentsByType($idCustomer,$idContentType){	
		$rows = array();	
		$stmt = $this->db->prepare("select Content.id, Content.name, Content.content, Content.version, Content.idContentType, Content.createTime, Content.updateTime, ContentType.name as contentTypeName from Content inner join ContentType on ContentType.id = Content.idContentType where Content.idCustomer = ? and Content.deleted = 0 and Content.idContentType = ? order by Content.name");
		$stmt->execute(array($idCustomer,$idContentType));	
		$rowData = $stmt->fetchAll(); 
		foreach ($rowData as $rowContent) {
			$rows[] = array('id'=>$rowContent['id'],'name'=>$rowContent['name'],'version'=>$rowContent['version'],'createTime'=>$rowContent['createTime'],'updateTime'=>$rowContent['updateTime'],'contentTypeName'=>$rowContent['contentTypeName'], 'content'=>$rowContent['content']);
		}
		return $rows;
	}

	function getContentsByTypeWithDelete($idCustomer,$idContentType){	
		$rows = array();	
		$stmt = $this->db->prepare("select Content.id, Content.name, Content.content, Content.version, Content.idContentType, Content.createTime, Content.updateTime, ContentType.name as contentTypeName, ScheduleBlockItem.idScheduleBlock from Content inner join ContentType on ContentType.id = Content.idContentType left join ScheduleBlockItem on ScheduleBlockItem.id = (select ScheduleBlockItem.id from ScheduleBlockItem where ScheduleBlockItem.idContent = Content.id and ScheduleBlockItem.deleted = 0 limit 1) where Content.idCustomer = ? and Content.deleted = 0 and Content.idContentType = ? order by Content.name");
		$stmt->execute(array($idCustomer,$idContentType));	
		$rowData = $stmt->fetchAll(); 
		foreach ($rowData as $rowContent) {
			$delete = false;
			if (is_null($rowContent['idScheduleBlock'])) 
				$delete = true;
			$rows[] = array('id'=>$rowContent['id'],'name'=>$rowContent['name'],'version'=>$rowContent['version'],'createTime'=>$rowContent['createTime'],'updateTime'=>$rowContent['updateTime'],'contentTypeName'=>$rowContent['contentTypeName'], 'content'=>$rowContent['content'],'delete'=>$delete);
		}
		return $rows;
	}

	function getContentVersion($id){	
		$stmt = $this->db->prepare("select version from Content where id = ?");
		$stmt->execute(array($id));	
		$rowData = $stmt->fetch(PDO::FETCH_ASSOC); 		
		return $rowData['version'];
	}

}