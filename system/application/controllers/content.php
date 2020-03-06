<?php 

class content extends Controller_Abstract{ 

	public function __construct(){
		parent::__construct();
		$this->content = new Model_Content;	
		$this->customer = new Model_Customer;
		$this->schedule = new Model_Schedule;			
	}

	public function index($parameters){
		$data['idCustomer'] = $_SESSION['idCustomer'];
		$data['customer'] = $this->customer->getCustomer($data['idCustomer']);
		$data['contents'] = $this->content->getContentsByCustomerWithDelete($data['idCustomer']);
		$breadcrumb = array("Contenidos",$data['customer']['name']); 
		$data['main_view'] = APPPATH.'views/content/viewContents.php';
		require_once(APPPATH."views/layout.php");	
	}
		
	public function bigUpload($parameters){
		include_once(APPPATH.'helpers/bigUpload.php');
		//Instantiate the class
		$bigUpload = new BigUpload;
		//Set the temporary filename
		$key = $parameters['parameters3'];
		$tempName = null;
		if (isset($key))
			$tempName = $key;
		if (isset($_POST['key']))
			$tempName = $_POST['key'];
		$bigUpload->setTempName($tempName);
		$action = $parameters['parameters'];
		switch($action) {
			case 'upload':		
				$hashBlob = $parameters['parameters2'];
				$key = $parameters['parameters3'];
				print $bigUpload->uploadFile($hashBlob);
				break;
			case 'abort':
				print $bigUpload->abortUpload();
				break;
			case 'finish':		
				$computed_hash = $parameters['parameters2'];		
				print $bigUpload->finishUpload($_POST['name'],$_POST['customerId'],$computed_hash);				
				break;
			case 'post-unsupported':
				print $bigUpload->postUnsupported();
				break;
		}	
	}

	public function loadFile($contentType,$version){
		$data = array();
		$data['alert'] = false;	
		$arr_file = pathinfo($_POST['bigUploadFile']);
		$filename = $arr_file['filename'];
		if (array_key_exists('extension', $arr_file))
			$extension = $arr_file['extension'];
		else {
			$arr_file = explode(".",$_POST['bigUploadFile']);
			if (count($arr_file)>1)
				$extension = end($arr_file);
			else
				return $data;
		}
		if (($version>0) && ($contentType!=TYPE_BACKGROUND))
			$tbeeContentName = $filename."_".$version.".";						
		else
			$tbeeContentName = $filename.".";
		$tbeeFile = $tbeeContentName.$extension;
		$data['alert'] = true;
		$data['file'] = $tbeeFile;			
		return $data;
	}

	public function addFile($parameters){
		$data['idCustomer'] = $_SESSION['idCustomer'];	
		if (isset($_POST['selectContentType'])) {
			$contentType = $_POST['selectContentType'];		
			//CARGA DE ARCHIVO
			if ($contentType == TYPE_WEB) {	//si el tipo de contenido es URL no hay carga de archivos
				$data['alert'] = false;
				if (isset($_POST['url'])) {
					$data['file'] = $_POST['url'];	
					$data['file_name'] = $_POST['name'];
					$data['ftp'] = 0;
					$this->content->newContent($_SESSION['idUser'],$data,$contentType);
					$result['alert'] = true;
				}
			} else if ($contentType == TYPE_YOUTUBE) {	//si el tipo de contenido es YouTube no hay carga de archivos
				$data['alert'] = false;
				if (isset($_POST['youtubeID'])) {
					$youtubeVideo = 1;
					if (isset($_POST['youtubePlaylist'])){
						if ($_POST['youtubePlaylist']=="true") 
							$youtubeVideo = 0;
					}
					$idYoutube = $_POST['youtubeID'];
					$data['file'] = BASE_SERVER."youtube.html?isVideo=".$youtubeVideo."&id=".$idYoutube;
					$data['file_name'] = $_POST['name'];
					$data['ftp'] = 0;
					$idNewContent = $this->content->newContent($_SESSION['idUser'],$data,$contentType);
					$this->content->newYoutube($idNewContent,$idYoutube,$youtubeVideo);
					$result['alert'] = true;
				}
			} else if ($contentType == TYPE_LABEL) {	//si el tipo de contenido es Label no hay carga de archivos
				$data['alert'] = false;
				if (isset($_POST['video_label'])) {
					$data['file'] = $_POST['name'];
					$data['file_name'] = $_POST['name'];
					$data['ftp'] = 0;
					$idNewContent = $this->content->newContent($_SESSION['idUser'],$data,$contentType);
					$this->content->newLabel($idNewContent,$_POST['video_label'],$_POST['text_label'],$_POST['position_x_label'],$_POST['position_y_label'],$_POST['opacity_label'],$_POST['colorPicker_label'],$_POST['size_label']);
					$result['alert'] = true;
				}
			} else if ($contentType == TYPE_WATERMARK) {	//si el tipo de contenido es Watermark no hay carga de archivos
				$data['alert'] = false;
				if (isset($_POST['video_watermark'])) {
					$data['file'] = $_POST['name'];
					$data['file_name'] = $_POST['name'];
					$data['ftp'] = 0;
					$idNewContent = $this->content->newContent($_SESSION['idUser'],$data,$contentType);
					$this->content->newWatermark($idNewContent,$_POST['video_watermark'],$_POST['image_watermark'],$_POST['position_x_watermark'],$_POST['position_y_watermark'],$_POST['opacity_watermark']);
					$result['alert'] = true;
				}
			} else if ($contentType == TYPE_MENU or $contentType == TYPE_LIST) {	//si el tipo de contenido es menu o lista
				$data['alert'] = false;
				if(isset($_POST['name'])){				
					$data['file_name'] = $_POST['name'];
					if ($contentType == TYPE_MENU)
						$pagina = "menu";
					else if ($contentType == TYPE_LIST)
						$pagina = "list";
					$data['file'] = BASE_SERVER . $pagina . "/index.html";
					$data['ftp'] = 0;
					$this->content->newContent($_SESSION['idUser'],$data,$contentType);
					$result['alert'] = true;
				}
			} else {	
				//intenta cargar la imagen en la carpeta upload
				$result = $this->loadFile($contentType,0); 
				// SI todo funciono correctamente, alta en base de datos
				if (isset($result['alert']) and $result['alert'] == true) {
					$data['file_name'] = $_POST['name'];					
					$data['file'] = $result['file'];	
					$data['md5'] = $_POST['computed_hash'];
					$this->content->newContent($_SESSION['idUser'],$data,$contentType);				
				}		
			}
			//RESULTADO DE LA CARGA
			$data['alert'] = $result['alert'];
		}
		$data['contents'] = $this->content->getContentsByType($_SESSION['idCustomer'],TYPE_VIDEO);
		$breadcrumb = array("Contenido","Nuevo contenido"); 		
		$data['main_view'] = APPPATH.'views/content/uploadContent.php';		
		require_once(APPPATH."views/layout.php");
	}

	public function newContent($parameters){		
		$data['idCustomer'] = $_SESSION['idCustomer'];
		$data['videos'] = $this->content->getContentsByType($_SESSION['idCustomer'],TYPE_VIDEO);
		$data['images'] = $this->content->getContentsByType($_SESSION['idCustomer'],TYPE_IMAGE);
		$breadcrumb = array("Contenido","Nuevo contenido"); 		
		$data['main_view'] = APPPATH.'views/content/uploadContent.php';
		require_once(APPPATH."views/layout.php");		
	}

	public function deleteContent($parameters){
		$this->content->deleteContent($_SESSION['idUser'],$_POST['id']);
	}

	public function editContent($parameters){
		$data['id'] = $parameters['parameters'];
		$data['videos'] = $this->content->getContentsByType($_SESSION['idCustomer'],TYPE_VIDEO);
		$data['images'] = $this->content->getContentsByType($_SESSION['idCustomer'],TYPE_IMAGE);
		$data['content'] = $this->content->getContent($data['id']);	
		$data['youtube'] = $this->content->getYoutube($data['id']);
		$data['label'] = $this->content->getLabel($data['id']);
		$data['watermark'] = $this->content->getWatermark($data['id']);
		$data['idCustomer'] = $_SESSION['idCustomer'];	
		$breadcrumb = array("Editar Contenido",$data['content']['name']);
		$data['main_view'] = APPPATH.'views/content/editContent.php';		
		require_once(APPPATH."views/layout.php");	
	}

	public function viewContent($parameters){		
		$data['id'] = $parameters['parameters'];	
		$data['content'] = $this->content->getContent($data['id']);
		$data['idCustomer'] = $_SESSION['idCustomer'];
		$breadcrumb = array("Ver Contenido",$data['content']['name']); 	
		$data['main_view'] = APPPATH.'views/content/viewContent.php';		
		require_once(APPPATH."views/layout.php");
	}

	public function infoContent($parameters){		
		$data['id'] = $parameters['parameters'];	
		$data['content'] = $this->content->getContent($data['id']);	
		$data['blocks'] = $this->schedule->getBlocksByContent($data['id']);	
		$data['youtube'] = $this->content->getYoutube($data['id']);
		$data['label'] = $this->content->getLabel($data['id']);
		$data['watermark'] = $this->content->getWatermark($data['id']);
		$data['idCustomer'] = $_SESSION['idCustomer'];
		$breadcrumb = array("Info Contenido"); 	
		$data['main_view'] = APPPATH.'views/content/infoContent.php';		
		require_once(APPPATH."views/layout.php");
	}

	public function editFile($parameters){
		$data['idCustomer'] = $_SESSION['idCustomer'];
		if (isset($_POST['selectContentType'])) {
			$contentType = $_POST['selectContentType'];	
			$data['id'] = $parameters['parameters']; 
			$result = array();			
			$result['file'] = "";
			$version = $this->content->getContentVersion($data['id']) + 1;
			if ($contentType == TYPE_WEB) {				
				if (isset($_POST['url'])) {
					$data['file'] = $_POST['url'];	
					$data['file_name'] = $_POST['name'];
					$data['ftp'] = 0;
					$data['editId'] = $data['id'];
					$this->content->editContent($_SESSION['idUser'],$data,$contentType,$version);
				}
			} else if ($contentType == TYPE_YOUTUBE) {
				if (isset($_POST['youtubeID'])) {
					$youtubeVideo = 1;
					if (isset($_POST['youtubePlaylist'])){
						if ($_POST['youtubePlaylist']=="true") 
							$youtubeVideo = 0;
					}
					$idYoutube = $_POST['youtubeID'];
					$data['file'] = BASE_SERVER."youtube.html?isVideo=".$youtubeVideo."&id=".$idYoutube;
					$data['file_name'] = $_POST['name'];
					$data['ftp'] = 0;
					$data['editId'] = $data['id'];
					$this->content->editContent($_SESSION['idUser'],$data,$contentType,$version);
					$this->content->editYoutube($data['id'],$idYoutube,$youtubeVideo);
				}
			} else if ($contentType == TYPE_LABEL) {
				$data['file'] = $_POST['name'];
				$data['file_name'] = $_POST['name'];
				$data['ftp'] = 0;
				$data['editId'] = $data['id'];
				$this->content->editContent($_SESSION['idUser'],$data,$contentType,$version);
				$this->content->editLabel($data['id'],$_POST['video_label'],$_POST['text_label'],$_POST['position_x_label'],$_POST['position_y_label'],$_POST['opacity_label'],$_POST['colorPicker_label'],$_POST['size_label']);
			} else if ($contentType == TYPE_WATERMARK) {
				$data['file'] = $_POST['name'];
				$data['file_name'] = $_POST['name'];
				$data['ftp'] = 0;
				$data['editId'] = $data['id'];
				$this->content->editContent($_SESSION['idUser'],$data,$contentType,$version);
				$this->content->editWatermark($data['id'],$_POST['video_watermark'],$_POST['image_watermark'],$_POST['position_x_watermark'],$_POST['position_y_watermark'],$_POST['opacity_watermark']);
			} else {
				$result = $this->loadFile($contentType,$version); 
				if (isset($result['alert']) and $result['alert'] == true) {  	
					$data['file_name'] = $_POST['name'];
					$data['file'] = $result['file'];		
					$data['md5'] = $_POST['computed_hash'];
					$data['editId'] = $data['id'];
					$this->content->editContent($_SESSION['idUser'],$data,$contentType,$version);						
				}
			}
		}
		header("Location: ".FRONT_CONTROLLER."content/");
		die();
	}

}