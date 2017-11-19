<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016 - 2017 RSGallery2
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

/**
 * New Subcontroller \com_example\controllers\forajax.php
 *
 */
/**
require_once JPATH_COMPONENT.'/controller.php';
class ExampleControllerForAjax extends ExampleController
{
    public function MyTaskName()
    {
        $app = JFactory::getApplication();

        $data['myRequest'] =$_REQUEST;
        $data['myFile'] =__FILE__;
        $data['myLine'] ='Line '.__LINE__;

        $app->enqueueMessage('This part was reached at line ' . __LINE__);
        $app->enqueueMessage('Then this part was reached at line ' . __LINE__);
        $app->enqueueMessage('Here was a small warning at line ' . __LINE__, 'warning');
        $app->enqueueMessage('Here was a big warning at line ' . __LINE__, 'error');

        $task_failed = false;
        echo new JResponseJson($data, 'My main response message',$task_failed);

        $app->close();
    }
}
/**/
/**
Rendered JSON Output
{
    success: true,
    message: "My main response message",
    messages: {
    message: [
        "This part was reached at line 26",
        "Then this part was reached at line 27"
    ],
        warning: [
        "Here was a small warning at line 28"
    ],
        error: [
        "Here was a big warning at line 29"
    ]
    },
    data: {
    myRequest: {
        option: "com_example",
            task: "mytaskname",
            Itemid: null
        },
    myFile: "C:\mysite\components\com_example\controllers\forajax.php",
        myLine: "Line 24"
    }
}
/**/


/**
Valentin's answer is good.

I prefer a json controller that handles the encoding and error handling for this I created a json base class:
class itrControllerJson extends JControllerLegacy {

  /** @var array the response to the client *
  protected $response = array();

  public function addResponse($type, $message, $status=200) {

    array_push($this->response, array(
      'status' => $status,
      'type' => $type,
      'data' => $message
    ));

  }

  /**
   * Outputs the response
   * @return JControllerLegacy|void
   *
  public function display() {

    $response = array(
      'status' => 200,
      'type' => 'multiple',
      'count' => count($this->response),
      'messages' => $this->response
    );

    echo json_encode($response);
    jexit();
  }

}
/**/

/**
This controller get extended by the controller class that do the work, something like this:
require_once __DIR__.'json.php';

class componentControllerAddress extends itrControllerJson {
  public function get() {

    try {
      if (!JSession::checkToken()) {
        throw new Exception(JText::_('JINVALID_TOKEN'), 500);
      }
      $app = JFactory::getApplication();

      $id = $app->input->get('id', null, 'uint');
      if (is_null($id)) {
        throw new Exception('Invalid Parameter', 500);
      }

      $db = JFactory::getDbo();
      $query = $db->getQuery(true);
      $query->select('*');
      $query->from('#__table');
      $query->where('id = '.$db->quote($id));
      $db->setQuery($query);
      $response = $db->loadObject();

      $this->addResponse('message', $response, 200);

    } catch (Exception $e) {
    $this->addResponse('error', $e->getMessage(), 500);
}

    $this->display();
  }
}
/**/
/**
and you call the request like this:
index.php?option=com_component&task=address.get&format=json&id=1234&tokenhash=1


    The token hash get generated by JSession::getFormToken(). So the complete complete call could be look like this:
$link = JRoute::_('index.php?option=com_component&task=address.get&format=json&id=1234&'.JSession::getFormToken().'=1', false);


The second parameter is set to "false" so we can use this in javascript calls without xml rewrite.
/**/


/*=======================================================================================*/
defined('_JEXEC') or die;

global $Rsg2DebugActive;

if ($Rsg2DebugActive)
{
	// Include the JLog class.
	jimport('joomla.log.log');

	// identify active file
	JLog::add('==> ctrl.upload.php ');
}

jimport('joomla.application.component.controllerform');

/**
 * Functions supporting upload
 *
 * @since 4.3.0
 */
class Rsgallery2ControllerUpload extends JControllerForm
{

	/**
	 * Constructor.
	 *
	 * @param   array $config An optional associative array of configuration settings.
	 *
	 * @see     JController
	 * @since
	 *
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
    /**/

    /**
     * Proxy for getModel.
     */
    public function getModel($name = 'Upload', $prefix = 'Rsgallery2Model', $config = array('ignore_request' => true))
    {
        return  parent::getModel($name, $prefix, $config);
    }


    /**
     *
     *
     * @since 4.3.2
     */
    function uploadFromZip()
    {
        global $Rsg2DebugActive, $rsgConfig;

        $msg     = "uploadFromZip: ";
        $msgType = 'notice';

        $msg .= '!!! Not implemented yet !!!';
        
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Access check
        $canAdmin = JFactory::getUser()->authorise('core.admin', 'com_rsgallery2');
        if (!$canAdmin)
        {
            $msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        }
        else {
            try {
                //Retrieve data from submit form
                $input = JFactory::getApplication()->input;
                //	$zip_file       = $input->files->get('zip_file', array(), 'FILES');
                // 'FILES' is ignored as a *.zip file marked bad from function  isSafeFile inside get
                $zip_file = $input->files->get('zip_file', array(), 'raw');
                $isInOneGallery = $input->get('isInOneGallery', null, 'INT');
                $galleryId = $input->get('GalleryId', null, 'INT');

                if ($Rsg2DebugActive) {
                    $Delim = " ";
                    // show active parameters
                    $DebTxt = "==> upload.uploadFromZip.php$Delim----------$Delim";
                    // array
                    $DebTxt = $DebTxt . "\$zip_file: " . json_encode($zip_file) . "$Delim";;
                    $DebTxt = $DebTxt . "\$isInOneGallery: " . $isInOneGallery . "$Delim";
                    $DebTxt = $DebTxt . "\$galleryId: " . $galleryId . "$Delim";

                    JLog::add($DebTxt); //, JLog::DEBUG);
                }

                $app = JFactory::getApplication();
                $app->setUserState('com_rsgallery2.last_used_uploaded_zip', $zip_file);
                // $rsgConfig->setLastUsedZipFile($zip_file);
                $rsgConfig->setLastUpdateType('upload_zip_pc');

                // Model tells if successful
                $model = $this->getModel('Upload');
                $isUploaded = $model->uploadFromZip ($zip_file, $galleryId, $isInOneGallery);
                if ($isUploaded) {
                    // ToDo: Use FTP upload successful ?
                    $msg .= JText::_('COM_RSGALLERY2_ITEM_UPLOADED_SUCCESFULLY');
                }
                else
                {
                    // COM_RSGALLERY2_ERROR_IMAGE_UPLOAD
                    $msg .= JText::_('Upload from Zip file failed');
                    $msgType = 'error';
                }

            } catch (RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing uploadFromZip: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = JFactory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $app = JFactory::getApplication();
        $app->enqueueMessage(JText::_('uploadFromZip'));

        //$this->setRedirect('index.php?option=com_rsgallery2&view=upload', $msg, $msgType);
        $this->setRedirect('index.php?option=com_rsgallery2&amp;view=upload&amp;layout=UploadSingle', $msg, $msgType);
    }
    /**/


    /**
     *
     *
     * @since 4.3
     */
    function uploadFromFtpFolder()
    {
        global $Rsg2DebugActive, $rsgConfig;

        $msg     = "";
        $msgType = 'notice';

        $msg .= '!!! Not implemented yet !!!';
        
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Access check
        $canAdmin = JFactory::getUser()->authorise('core.admin', 'com_rsgallery2');
        if (!$canAdmin) {
            $msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            try {
                //Retrieve data from submit form
                $input = JFactory::getApplication()->input;
                // One gallery for all image:
                // ToDo: rename in view and here
                $isInOneGallery = $input->get('selcat', null, 'INT');
                // image ID:
                // ToDo: rename in view and here
                $galleryId = $input->get('xcat', null, 'INT');
                $ftpPath = $input->get('ftppath', null, 'RAW');
                // Path should end with '\\'
                if (substr($ftpPath, -1) != '/' && substr($ftpPath, -1) == '\\') {
                    $ftpPath .= '/';
                }

                if ($Rsg2DebugActive) {
                    $Delim = " ";
                    // show active parameters
                    $DebTxt = "==> upload.uploadFromZip.php$Delim----------$Delim";
                    $DebTxt = $DebTxt . "\$ftpPath: " . $ftpPath . "$Delim";
                    $DebTxt = $DebTxt . "\$isInOneGallery: " . $isInOneGallery . "$Delim";
                    $DebTxt = $DebTxt . "\$galleryId: " . $galleryId . "$Delim";

                    JLog::add($DebTxt); //, JLog::DEBUG);
                }

                $app = JFactory::getApplication();
                $app->setUserState('com_rsgallery2.last_used_ftp_path', $ftpPath);
                $rsgConfig->setLastUsedFtpPath($ftpPath);
                $rsgConfig->setLastUpdateType('upload_folder_server');

                // Model tells if successful
                $model = $this->getModel('Upload');
                $isUploaded = $model->uploadFromFtpFolder ($ftpPath, $galleryId, $isInOneGallery);
                if ($isUploaded) {
                    // ToDo: Use FTP upload successful ?
                    $msg .= JText::_('COM_RSGALLERY2_ITEM_UPLOADED_SUCCESFULLY');
                }
                else
                {
                    // COM_RSGALLERY2_ERROR_IMAGE_UPLOAD
                    $msg .= JText::_('Upload from FTP folder failed');
                    $msgType = 'error';
                }

            } catch (RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing uploadFromFtpFolder: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = JFactory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $app = JFactory::getApplication();
        $app->enqueueMessage(JText::_('uploadFromFtpFolder'));

        $this->setRedirect('index.php?option=com_rsgallery2&view=upload', $msg, $msgType);
    }
    /**/

    /**
     * Todo move to model
     *
     * @since 4.3
     */
    function uploadAjaxSingleFile()
    {
	    global $Rsg2DebugActive;

	    $IsMoved = false;
	    $msg     = 'uploadAjaxSingleFile';

	    if ($Rsg2DebugActive)
	    {
		    // identify active file
		    JLog::add('==> uploadAjaxSingleFile');
	    }

	    $app = JFactory::getApplication();

	    /**
	    // Send json mime type.
	    $app->mimeType = 'application/json';
	    $app->setHeader('Content-Type', $this->app->mimeType . '; charset=' . $this->app->charSet);
	    $app->sendHeaders();
		/**/

	    // ToDO: check for user rights ...

	    /**
	    // Check if user token is valid.
	    if (!JSession::checkToken('get'))
	    {
		    $this->app->enqueueMessage(JText::_('JINVALID_TOKEN'), 'error');
		    echo new JResponseJson;
		    $app->close();
	    }

	    // Check if the user is authorized to do this.
	    if (!JFactory::getUser()->authorise('core.admin'))
	    {
		    $this->app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');
		    echo new JResponseJson;
		    $app->close();
	    }
		/**/

	    try
	    {
		    /**
		     * // Access check
		     * $canAdmin = JFactory::getUser()->authorise('core.admin', 'com_rsgallery2');
		     * if (!$canAdmin)
		     * {
		     * $msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
		     * $msgType = 'warning';
			     * // replace newlines with html line breaks.
		     * str_replace('\n', '<br>', $msg);
		     * }
		     * else {
		     * /**/
		    /**
		     *         var fd = new FormData();
		     *         fd.append('file', files[i]);
		     * data.append('upload_type', 'single');
		     * data.append(token, 1);
		     * /**/

		    /**
		     * $data = array();
		     *
		     * if(isset($_GET['files']))
		     * {
		     * $error = false;
		     * $files = array();
		     *
		     * $uploaddir = './uploads/';
		     * foreach($_FILES as $file)
		     * {
		     * if(move_uploaded_file($file['tmp_name'], $uploaddir .basename($file['name'])))
		     * {
		     * $files[] = $uploaddir .$file['name'];
		     * }
		     * else
		     * {
		     * $error = true;
		     * }
		     * }
		     * $data = ($error) ? array('error' => 'There was an error uploading your files') : array('files' => $files);
		     * }
		     * else
		     * {
		     * $data = array('success' => 'Form was submitted', 'formData' => $_POST);
		     * }
		     *
		     * echo json_encode($data);
		     * /**/

		    /**
		     * $fileName = $_FILES['file']['name'];
		     * $fileType = $_FILES['file']['type'];
		     * $fileError = $_FILES['file']['error'];
		     * $fileContent = file_get_contents($_FILES['file']['tmp_name']);
		     *
		     * if($fileError == UPLOAD_ERR_OK){
		     * //Processes your file here
		     * }else{
		     * switch($fileError){
		     * case UPLOAD_ERR_INI_SIZE:
		     * $message = 'Error al intentar subir un archivo que excede el tamaño permitido.';
		     * break;
		     * case UPLOAD_ERR_FORM_SIZE:
		     * $message = 'Error al intentar subir un archivo que excede el tamaño permitido.';
		     * break;
		     * case UPLOAD_ERR_PARTIAL:
		     * $message = 'Error: no terminó la acción de subir el archivo.';
		     * break;
		     * case UPLOAD_ERR_NO_FILE:
		     * $message = 'Error: ningún archivo fue subido.';
		     * break;
		     * case UPLOAD_ERR_NO_TMP_DIR:
		     * $message = 'Error: servidor no configurado para carga de archivos.';
		     * break;
		     * case UPLOAD_ERR_CANT_WRITE:
		     * $message= 'Error: posible falla al grabar el archivo.';
		     * break;
		     * case  UPLOAD_ERR_EXTENSION:
		     * $message = 'Error: carga de archivo no completada.';
		     * break;
		     * default: $message = 'Error: carga de archivo no completada.';
		     * break;
		     * }
		     * echo json_encode(array(
		     * 'error' => true,
		     * 'message' => $message
		     * ));
		     * }
		     * /**/

		    if ($Rsg2DebugActive)
		    {
			    JLog::add('1:');
		    }


		    // $files = $input->files->get('files');
		    $input = JFactory::getApplication()->input;
		    if ($Rsg2DebugActive)
		    {
			    JLog::add('2:');
		    }

		    $files = $input->files->get('upload_file', array(), 'raw');
		    if ($Rsg2DebugActive)
		    {
			    JLog::add('3:');
		    }

		    $fileInfo = json_encode($files);
		    if ($Rsg2DebugActive)
		    {
			    JLog::add('4:');
		    }

		    if ($Rsg2DebugActive)
		    {
			    // identify active file
			    JLog::add('$fileInfo: "' . $fileInfo . '"');
		    }


		    $fileTmpName = $files['tmp_name'];
		    $fileName    = $files['name'];
		    $fileType    = $files['type'];
		    $fileError   = $files['error'];
		    $fileSize    = $files['size'];

		    if ($Rsg2DebugActive)
		    {
			    // identify active file
			    JLog::add('$fileTmpName: "' . $fileTmpName . '"');
			    JLog::add('$fileName : "' . $fileName . '"');
			    JLog::add('$fileType: "' . $fileType . '"');
			    JLog::add('$fileError: "' . $fileError . '"');
			    JLog::add('$fileSize: "' . $fileSize . '"');
		    }

		    // $file_session_id = $input->get('session_id', 0, 'INT');
		    $file_session_id = $input->get('token', '', 'STRING');
		    $session_id      = JFactory::getSession();

		    if ($Rsg2DebugActive)
		    {
			    JLog::add('$file_session_id: ' . $file_session_id);
			    // JLog::add('$session_id: ' . $session_id);
		    }

		    $gallery_id = $input->get('gallery_id', 0, 'INT');
		    if ($Rsg2DebugActive)
		    {
			    // identify active file
			    JLog::add('$gallery_id: "' . $gallery_id . '"');
		    }

		    $dstFolder = JPATH_ROOT . '/media/rsgallery2_' . $gallery_id . '_' . $file_session_id;
		    // folder does not exist
		    if (!is_dir($dstFolder))
		    {
			    mkdir($dstFolder, 0755);
			    //echo "The directory $dstFolder was successfully created.";
			    //exit;
		    }
		    else
		    {
		    } //echo "The directory $dstFolder exists.";

		    $dstFile = $dstFolder . '/' . $fileName;
		    if (is_dir($dstFolder))
		    {
			    if (move_uploaded_file($fileTmpName, $dstFile))
			    {
				    // echo '<b>Upload ok!</b>';
				    $msg     .= '<b>Upload ok!</b>';
				    $IsMoved = true;
			    }
			    else
			    {
				    //echo '<b>Upload failed!</b>';
				    $msg .= '<b>Upload failed!</b>';
			    }

		    }


		    // images.php:: batchupload
		    // --> ::extractArchive
		    //    --> !JFile::upload

// move_uploadedFile_to_orignalDir

		    // Clean filename
//        $basename = JFile::makeSafe($parts['basename']);

		    // Destination file exists ? avoid race condition -> filename with date time of server ...
//        if (JFile::exists(JPATH_DISPLAY . DS . $basename) || JFile::exists(JPATH_ORIGINAL . DS . $basename)) {

//        move_uploaded_file
		    /*
				$baseDir = JPATH_SITE . '/media';

				if (file_exists($baseDir)) {
					if (is_writable($baseDir)) {
						if (move_uploaded_file($filename, $baseDir . $userfile_name)) {
							// Try making the file writeable first.
							// if (JClientFtp::chmod( $baseDir . $userfile_name, 0777 )) {
							//if (JPath::setPermissions( $baseDir . $userfile_name, 0777 )) {
							if (JPath::setPermissions($baseDir . $userfile_name)) {
								return true;
							} else {
								$msg = JText::_('COM_RSGALLERY2_FAILED_TO_CHANGE_THE_PERMISSIONS_OF_THE_UPLOADED_FILE');
							}
						} else {
							$msg = JText::_('COM_RSGALLERY2_FAILED_TO_MOVE_UPLOADED_FILE_TO_MEDIA_DIRECTORY');
						}
					} else {
						$msg = JText::_('COM_RSGALLERY2_UPLOAD_FAILED_AS_MEDIA_DIRECTORY_IS_NOT_WRITABLE');
					}
				} else {
					$msg = JText::_('COM_RSGALLERY2_UPLOAD_FAILED_AS_MEDIA_DIRECTORY_DOES_NOT_EXIST');
				}

				return false;
			/**/

		    JLog::add('--- ajax retun data');

		    // Link: https://docs.joomla.org/JSON_Responses_with_JResponseJson
		    $ajaxImgObject['file'] = $fileName; // $dstFile;

		    // JResponseJson (JasonData, General message, IsErrorFound);
		    echo new JResponseJson($ajaxImgObject, $msg, !$IsMoved);
		    //echo new JResponseJson($ajaxImgObject, $msg,  $IsMoved);


		    if ($Rsg2DebugActive)
		    {
			    JLog::add('<== uploadAjaxSingleFile');
		    }

	    }
	    catch (Exception $e)
	    {
		    echo new JResponseJson($e);
	    }

	    $app->close();
    }

	/**
        // function saveUploadedItem()

		// JFile::upload -> Moves an uploaded file to a destination folder

		//Clean up filename to get rid of strange characters like spaces etc
        $filename = JFile::makeSafe($file['name']);

		//Set up the source and destination of the file
        $src = $file['tmp_name'];
        $dest = JPATH_COMPONENT . DS . "uploads" . DS . $filename;

		//First check if the file has the right extension, we need jpg only
        if (strtolower(JFile::getExt($filename)) == 'jpg')
        {
            // TODO: Add security checks

            if (JFile::upload($src, $dest))
            {
                //Redirect to a page of your choice
            }
            else
            {
                //Redirect and throw an error message
            }
        }
        else
        {
            //Redirect and notify user file is not right extension
        }

        /**/




}

