<?php 

class playlist extends Controller_Abstract{ 

	public function __construct(){
		parent::__construct();
		$this->playlist = new Model_Playlist;
		$this->content = new Model_Content;
	}

	public function index($parameters){
		$data['idCustomer'] = $_SESSION['idCustomer'];	
		$data['playlists'] = $this->content->getContentsByTypeWithDelete($data['idCustomer'],TYPE_PLAYLIST);
		$breadcrumb = array("Playlists");
		$data['main_view'] = APPPATH.'views/playlist/viewPlaylists.php';
		require_once(APPPATH."views/layout.php");
	}

	public function newPlaylistVideo($parameters){
		//Vista de creación de playlist
		if (!isset($_POST['name']) && !isset($_POST['idContent'])) {
			$data['contents'] = $this->content->getContentsByType($_SESSION['idCustomer'],TYPE_VIDEO);
			$data['playlistItems'] = "";		
			$data['idPlaylist'] = "";	
			$data['playlistName'] = "";
			$breadcrumb = array("Playlist","Nuevo Playlist");	
			$data['main_view'] = APPPATH.'views/playlist/newPlaylistVideo.php';
			require_once(APPPATH."views/layout.php");				
		} else {
			$dataContent['idCustomer'] = $_SESSION['idCustomer'];
			$dataContent['file'] = 'video';
			$dataContent['file_name'] = $_POST['name'];
			$dataContent['ftp'] = 0;
			$idPlaylist = $this->content->newContent($_SESSION['idUser'],$dataContent,TYPE_PLAYLIST);
			$parameters['parameters'] = $idPlaylist;
			$this->editPlaylist($parameters);				
		}		
	}

	public function newPlaylistAudio($parameters){
		//Vista de creación de playlist
		if (!isset($_POST['name']) && !isset($_POST['idContent'])) {
			$data['contents'] = $this->content->getContentsByType($_SESSION['idCustomer'],TYPE_AUDIO);
			$data['playlistItems'] = "";		
			$data['idPlaylist'] = "";	
			$data['playlistName'] = "";
			$breadcrumb = array("Playlist","Nuevo Playlist");	
			$data['main_view'] = APPPATH.'views/playlist/newPlaylistAudio.php';
			require_once(APPPATH."views/layout.php");				
		} else {
			$dataContent['idCustomer'] = $_SESSION['idCustomer'];
			$dataContent['file'] = 'audio';
			$dataContent['file_name'] = $_POST['name'];
			$dataContent['ftp'] = 0;
			$idPlaylist = $this->content->newContent($_SESSION['idUser'],$dataContent,TYPE_PLAYLIST);
			$parameters['parameters'] = $idPlaylist;
			$this->editPlaylist($parameters);				
		}		
	}

	public function editPlaylist($parameters){
		$data['idPlaylist'] = $parameters['parameters'];
		$data['content'] = $this->content->getContent($data['idPlaylist']);		
		$data['playlistName'] = $data['content']['name'];	
		$data['playlistItems'] = $this->playlist->getPlaylistItems($data['idPlaylist']);
		$data['idCustomer'] = $_SESSION['idCustomer'];
		if ($data['content']['content'] == 'video')
			$data['contents'] = $this->content->getContentsByType($_SESSION['idCustomer'],TYPE_VIDEO);
		else if ($data['content']['content'] == 'audio')
			$data['contents'] = $this->content->getContentsByType($_SESSION['idCustomer'],TYPE_AUDIO);
		if (isset($_POST['idContent'])) {
			$data['idPlaylist'] = $data['idPlaylist'];	
			$data['idContent'] = $_POST['idContent'];
			$this->playlist->addPlaylistItem($data);
			$data['playlistItems'] = $this->playlist->getPlaylistItems($data['idPlaylist']);	
			$data['blockName'] = $data['playlistName'];					
		}
		$breadcrumb = array("Playlist","Editar Playlist",$data['playlistName']);	
		$data['main_view'] = APPPATH.'views/playlist/editPlaylist.php';
		require_once(APPPATH."views/layout.php");		
	}

	public function editPlaylistName($parameters){
		$data['idCustomer'] = $_SESSION['idCustomer'];
		$content = $this->content->getContent($parameters['parameters']);
		if (isset($_POST['name'])) {
			$data['id'] = $parameters['parameters']; 
			$result = array();			
			$result['file'] = "";
			$version = $this->content->getContentVersion($data['id']) + 1;
			$data['file'] = $_POST['name'];	
			$data['file_name'] = $_POST['name'];
			$data['ftp'] = 0;
			$data['editId'] = $data['id'];
			$this->content->editContent($_SESSION['idUser'],$data,TYPE_PLAYLIST,$version);
			$data['alert'] = true;
		}
		$breadcrumb = array("Playlist","Cambiar nombre Playlist"); 
		$data['main_view'] = APPPATH.'views/playlist/editPlaylistName.php';			
		require_once(APPPATH."views/layout.php");
	}

	public function deletePlaylistItem($parameters){
		$id = $_POST['id'];	
		$orden = $_POST['orden'];
		$idPlaylist = $_POST['idPlaylist'];
		$this->playlist->deletePlaylistItem($id, $orden, $idPlaylist);
	}

	public function editPlaylistItemOrder($parameters){
		$this->playlist->editPlaylistItemOrder($_POST['order'],$_POST['id']);
	}

	public function deletePlaylist($parameters){
		$this->playlist->deletePlaylist($_POST['id']);
		$this->content->deleteContent($_SESSION['idUser'],$_POST['id']);
	}

}