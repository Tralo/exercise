<?php
class XP_Xpbase_Helper_Images extends Mage_Core_Helper_Abstract {

   
 public function getCustomSizeImageUrl($imageFile, $width = 100, $height = 100 , $basepath = 'media/catalog/category/',$media_path_target = '')
    {
       $screenSize = $width . 'x' . $height;
       $media_path = "";
       if($media_path_target == "" ){
        $media_path =  'custom';
       }
        $media_path = Mage::getBaseDir('media') . DS .$media_path_target . DS . $screenSize;

        $customDir = $media_path;
        $ioFile = new Varien_Io_File();
        $ioFile->checkAndCreateFolder($customDir);
        $filePath = $basepath. DS . $imageFile;
        
        $isImagePng = true;
        // echo $filePath;
        if (!$ioFile->fileExists($filePath)) {
            return false;
        }

        $originalImageType = $this->_getImageType($filePath);
        if ($originalImageType !== IMAGETYPE_PNG) {
            $imageFile = $this->_convertFileExtensionToPng($imageFile);
            $isImagePng = false;
        }

        $customSizeFile = $customDir . DS . $imageFile;
        if (!file_exists($customSizeFile)) {
            if (!$isImagePng) {
                $filePath = $this->_forcedConvertPng($filePath, $customSizeFile, $originalImageType);
            }
         
            $image = new Varien_Image($filePath);
            $widthOriginal = $image->getOriginalWidth();
            $heightOriginal = $image->getOriginalHeight();

            if($height == 0){
                $height = floor($heightOriginal/$widthOriginal*$width);
            }elseif($width == 0){
                $width = floor($widthOriginal/$heightOriginal*$height);
            }


            if ($width != $widthOriginal) {
                $widthOriginal = $width;
            }

            if ($height != $heightOriginal) {
                $heightOriginal = $height;
            }




            if (($widthOriginal != $image->getOriginalWidth()) || ($heightOriginal != $image->getOriginalHeight()) ) {
                $image->keepTransparency(true);
                $image->keepFrame(true);
                $image->keepAspectRatio(true);
                $image->backgroundColor(array(255, 255, 255));
                $image->resize($widthOriginal, $heightOriginal);
                $image->save($customDir, basename($imageFile));
            } else {
                $ioFile->cp($filePath, $customSizeFile);
            }
        }
        // return Mage::getUrl("media/custom/{$screenSize}/" ). basename($imageFile);
        return  Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA)."/{$media_path_target}/{$screenSize}/". basename($imageFile);
    }
  /**
     * Get image type
     *
     * @param string $filePath
     * @return int
     */
    protected function _getImageType($filePath)
    {
        list(,, $originalImageType) = getimagesize($filePath);
        return $originalImageType;
    }
      /**
     * Convert image file extension to PNG
     *
     * @param string $fileName
     * @return string
     */
    protected function _convertFileExtensionToPng($fileName)
    {
        $dotPosition = strrpos($fileName, '.');
        if ($dotPosition !== false) {
            $fileName = substr($fileName, 0 , $dotPosition);
        }
        $fileName .= '.png';

        return $fileName;
    }
    /**
     * Convert uploaded file to PNG
     *
     * @param string $originalFile
     * @param string $destinationFile
     * @param int|null $originalImageType
     * @return string
     */
    protected function _forcedConvertPng($originalFile, $destinationFile, $originalImageType = null)
    {
        switch ($originalImageType) {
            case IMAGETYPE_GIF:
                $img = imagecreatefromgif($originalFile);
                imagealphablending($img, false);
                imagesavealpha($img, true);
                break;
            case IMAGETYPE_JPEG:
                $img = imagecreatefromjpeg($originalFile);
                break;
            case IMAGETYPE_WBMP:
                $img = imagecreatefromwbmp($originalFile);
                break;
            case IMAGETYPE_XBM:
                $img = imagecreatefromxbm($originalFile);
                break;
            default:
                return '';
        }
        imagepng($img, $destinationFile);
        imagedestroy($img);

        return $destinationFile;
    }


}