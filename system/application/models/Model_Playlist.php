<?php

class Model_Playlist extends Model_Abstract {

	function __construct(){
		$this->db = $this->getConnection();
	}

	function deletePlaylist($idPlaylist){
		$stmt = $this->db->prepare("update Playlist set deleted = 1, updateTime = now() where idPlaylist = ? and deleted = 0");	
		$stmt->execute(array($idPlaylist));
	}

	function addPlaylistItem($data){	
		$stmt = $this->db->prepare("select max(Playlist.order) as orden from Playlist where idPlaylist = ? and deleted = 0");
		$stmt->execute(array($data['idPlaylist']));	
		$rowData = $stmt->fetch(PDO::FETCH_ASSOC); 		
		$orden = $rowData['orden'] + 1;
		$stmt = $this->db->prepare("insert into Playlist (idPlaylist, idContent, Playlist.order, createTime, updateTime) values (?,?,?,now(),now())");
		$resultquery = $stmt->execute(array($data['idPlaylist'],$data['idContent'],$orden));
		return $this->db->lastInsertId();
	}

	function getPlaylistItems($idPlaylist){
		$rows="";
		$stmt = $this->db->prepare("select Playlist.id, Content.name, Playlist.idContent, Playlist.order from Playlist inner join Content on Content.id=Playlist.idContent and Content.deleted = 0 where Playlist.idPlaylist = ? and Playlist.deleted = 0 order by Playlist.order");
		$stmt->execute(array($idPlaylist));
		$rowData = $stmt->fetchAll(); 
		foreach ($rowData as $row) {		
			$rows[] =array('id'=>$row['id'],'name'=>$row['name'],'idContent'=>$row['idContent'],'order'=>$row['order']);
		}
		return $rows;
	}

	function deletePlaylistItem($id, $orden, $idPlaylist){
		// Elimina el elemento
		$stmt = $this->db->prepare("update Playlist set deleted = 1, updateTime = now() where id = ?");
		$stmt->execute(array($id));
		// Actualiza el orden de los elementos restantes
		$stmt = $this->db->prepare("select id from Playlist where Playlist.order > ? and Playlist.idPlaylist = ? and Playlist.deleted = 0 order by Playlist.order");
		$stmt->execute(array($orden,$idPlaylist));	
		$data = $stmt->fetchAll(); 
		foreach ($data as $rowData) {
			$sql = $this->db->prepare("update Playlist SET Playlist.order = Playlist.order - 1, updateTime = now() where id = ?");
			$sql->execute(array($rowData['id']));						
		}
	}

	function editPlaylistItemOrder($order,$id){
		$stmt = $this->db->prepare("update Playlist set Playlist.order = ?, updateTime = now() where id = ?");
		$stmt->execute(array($order,$id));	
	}
	
}