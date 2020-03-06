<?php
class BigUpload
{
	/**
	 * Temporary directory for uploading files
	 */
	const TEMP_DIRECTORY = '../content/tmp/';
	/**
	 * Directory files will be moved to after the upload is completed
	 */
	const MAIN_DIRECTORY = '../content/';
	/**
	 * Max allowed filesize. This is for unsupported browsers and
	 * as an additional security check in case someone bypasses the js filesize check.
	 *
	 * This must match the value specified in main.js
	 */
	const MAX_SIZE = 268435456;
	/**
	 * Temporary directory
	 * @var string
	 */
	private $tempDirectory;
	/**
	 * Directory for completed uploads
	 * @var string
	 */
	private $mainDirectory;
	/**
	 * Name of the temporary file. Used as a reference to make sure chunks get written to the right file.
	 * @var string
	 */
	private $tempName;
	/**
	 * Constructor function, sets the temporary directory and main directory
	 */
	public function __construct() {
		$this->setTempDirectory(self::TEMP_DIRECTORY);
		$this->setMainDirectory(self::MAIN_DIRECTORY);
	}
	/**
	 * Create a random file name for the file to use as it's being uploaded
	 * @param string $value Temporary filename
	 */
	public function setTempName($value = null) {
		if($value) {
			$this->tempName = $value;
		}
		else {
			$this->tempName = mt_rand().'.tmp';
		}
	}
	/**
	 * Return the name of the temporary file
	 * @return string Temporary filename
	 */
	public function getTempName() {
		return $this->tempName;
	}
	/**
	 * Set the name of the temporary directory
	 * @param string $value Temporary directory
	 */
	public function setTempDirectory($value) {
		$this->tempDirectory = $value;
		return true;
	}
	/**
	 * Return the name of the temporary directory
	 * @return string Temporary directory
	 */
	public function getTempDirectory() {
		return $this->tempDirectory;
	}
	/**
	 * Set the name of the main directory
	 * @param string $value Main directory
	 */
	public function setMainDirectory($value) {
		$this->mainDirectory = $value;
	}
	/**
	 * Return the name of the main directory
	 * @return string Main directory
	 */
	public function getMainDirectory() {
		return $this->mainDirectory;
	}
	/**
	 * Function to upload the individual file chunks
	 * @return string JSON object with result of upload
	 */
	public function uploadFile($hashBlob) {
		//Make sure the total file we're writing to hasn't surpassed the file size limit
		if(file_exists($this->getTempDirectory().$this->getTempName())) {
			if(filesize($this->getTempDirectory().$this->getTempName()) > self::MAX_SIZE) {
				$this->abortUpload();
				return json_encode(array(
						'errorStatus' => 1,
						'errorText' => 'El archivo supera el tamaño máximo permitido.'
					));
			}
		}
		//Open the raw POST data from php://input
		$fileData = file_get_contents('php://input');
		//Write the actual chunk to the larger file
		$handle = fopen($this->getTempDirectory().$this->getTempName(),'a');
		fwrite($handle, $fileData);
		fclose($handle);
		//Asegurar que no se corrompio el archivo en la transmision
		$hash_cliente = $hashBlob;
		$hash_server = md5(utf8_encode($fileData));
		if (!$hash_cliente == $hash_server) {			
				$this->abortUpload();
				return json_encode(array(
						'errorStatus' => 1,
						'errorText' => 'Falla en la transmision, no coinciden hashes.',
						'hashServer' => $hash_server
					));			
		}	
		return json_encode(array(
			'key' => $this->getTempName(),
			'errorStatus' => 0,
			'hashServer' => $hash_server
		));
	}
	/**
	 * Function for cancelling uploads while they're in-progress; deletes the temp file
	 * @return string JSON object with result of deletion
	 */
	public function abortUpload() {
		if (unlink($this->getTempDirectory().$this->getTempName()))
			return json_encode(array('errorStatus' => 0));
		else {

			return json_encode(array(
				'errorStatus' => 1,
				'errorText' => 'No se ha podido eliminar el archivo temporal.'
			));
		}
	}
	/**
	 * Function to rename and move the finished file
	 * @param  string $final_name Name to rename the finished upload to
	 * @return string JSON object with result of rename
	 */
	public function finishUpload($finalName,$customerId,$computed_hash) {
		$pathToContent = $this->getMainDirectory().$customerId."/".$finalName;	
		if (rename($this->getTempDirectory().$this->getTempName(),$pathToContent))
			return json_encode(array('errorStatus' => 0,'computed_hash' => $computed_hash));
		else {
			return json_encode(array(
				'errorStatus' => 1,
				'errorText' => 'No se pudo mover el archivo una vez finalizada la carga.',
				'computed_hash' => $computed_hash
			));
		}
	}
	/**
	 * Basic php file upload function, used for unsupported browsers. 
	 * The output on success/failure is very basic, and it would be best to have these errors return the user to index.html
	 * with the errors printed on the form, but that is beyond the scope of this project as it is very application specific.
	 * @return string Success or failure of upload
	 */
	public function postUnsupported() {
		$name = $_FILES['bigUploadFile']['name'];
		$size = $_FILES['bigUploadFile']['size'];
		$tempName = $_FILES['bigUploadFile']['tmp_name'];
		if (filesize($tempName) > self::MAX_SIZE)
			return 'El archivo supera el tamaño máximo permitido.';
		if (move_uploaded_file($tempName,$this->getMainDirectory().$name))
			return 'Archivo subido.';
		else
			return 'Ocurrió un error mientras se cargaba el archivo.';
	}

}