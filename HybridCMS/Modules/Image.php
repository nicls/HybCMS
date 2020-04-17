<?php

namespace HybridCMS\Modules;

/**
 * class Image - Class to crop and scale images.
 *
 * @package Modules
 * @version 1.2
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class Image {

    /**
     * path to the original image
     * @var String
     */
    private $originalPathToImage;

    /**
     * path to Folder of the original image
     * @var String
     */
    private $originalImageFolder;

    /**
     * Filetype of the original Image
     * @var String
     */
    private $originalFiletype;

    /**
     * Filename of the original Image
     * @var String
     */
    private $originalFilename;

    /**
     * path to the new image
     * @var String
     */
    private $newPathToImage;

    /**
     * Height of the original Iamge in Pixel
     * @var Integer
     */
    private $originalPxHeight;

    /**
     * Width of the original Iamge in Pixel
     * @var Integer
     */
    private $originalPxWidth;

    /**
     * Height of the new Iamge in Pixel
     * @var Integer
     */
    private $newPxHeight;

    /**
     * Width of the new Iamge in Pixel
     * @var Integer
     */
    private $newPxWidth;
    
    /**
     * Quality of the new Image by percent.
     * @var Integer Initial set to 70%
     */
    private $newImgQuality = 70;
    
    /**
     *
     * @var resource - Identifier representing the original image
     */
    private $objOriginalImage;

    /**
     *
     * @var resource- image identifier representing
     * a black image of a specified size
     */
    private $canvasNewImage;

    /**
     * Destination Filetype
     * @var String
     */
    private $destFiletype;

    /**
     * List of allowed Filetypes
     * @var String[]
     */
    private $allowedFiletypes = array('.jpg', '.png');

    /**
     * __construct
     * 
     * @param String $originalPathToImage
     * @throws \Exception
     */
    public function __construct($originalPathToImage) 
    {
        try 
        {                   
            //set original path to Iamge
            $this->setOriginalPathToImage($originalPathToImage);

            //set filetpye
            $this->setOriginalFiletype('.' . $this->getFileType());

            //set original pixel width
            $this->setOriginalPxWidth($this->getPxWidth());

            //set orignal pixel height
            $this->setOriginalPxHeight($this->getPxHeight());

            //get original Filename
            $arrImageData = explode('/', $this->originalPathToImage);
            list($originalFilename, ) = explode('.', 
                    $arrImageData[count($arrImageData) - 1]);

            //set orignal filename
            $this->setOriginalFilename($originalFilename);

            array_pop($arrImageData);

            //get image folder of the original image
            $originalImageFolder = implode('/', $arrImageData);

            //set original image Folder
            $this->setOrignalImageFolder($originalImageFolder);
                    
        } 
        catch (\Exception $e)
        {
            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");
        }
    }

    /**
     * scale - Scale orifinal Image to new Width in Pixel
     * 
     * @param Integer $newPxWidth
     * @return String - Returns the url of the new scaled Image
     */
    public function scale($newPxWidth, $destFiletype = null) 
    {                  
        if (true === isset($destFiletype)) 
        {
            //set destination filetype
            $this->setDestFiletype($destFiletype);
        } 
        else 
        {
            //set destination filetype to original filetype
            $this->setDestFiletype($this->originalFiletype);
        }             

        //set new width
        $this->setNewPxWidth($newPxWidth);

        //calculate scaling-factor of width
        $factorWidth = $this->newPxWidth / $this->originalPxWidth;

        //calcualte heightThumb
        $this->newPxHeight = $this->originalPxHeight * $factorWidth;
                       
        //build new path to image
        $this->buildNewPathToImage(); 
               
        //check if file does exist
        if (false === file_exists($this->newPathToImage)) 
        {
            
            $this->createOriginalImage();
            
            //create new Canvas
            $this->setCanvasNewImage($this->newPxWidth, $this->newPxHeight);

            assert(true === isset($this->objOriginalImage));

            //Scale the image
            imagecopyresampled(
                    $this->canvasNewImage, //canvas of the new Image 
                    $this->objOriginalImage, //identifier representing the orig. Image
                    0, //destination-start pixel of the x-axis
                    0, //destination-start pixel of the y-axis
                    0, //source-start-pixel of the x-axis
                    0, //source-start-pixel of the y-axis
                    $this->newPxWidth, //Pixel-width of the new Image
                    $this->newPxHeight, //Pixel-height of the new Image
                    $this->originalPxWidth, //Pixel-width of the original Image
                    $this->originalPxHeight //Pixel-height of the original Image
            );

            $this->writeImageToFile();
        } 
        
        return '/' . str_replace(HYB_ROOT, '', $this->newPathToImage);
    }

    /**
     * crop - Crop image to new height and Width
     * 
     * @param Integer $newPxWidth
     * @param Integer $newPxHeight
     * @return String - Returns path to croped Image
     */
    public function crop(
            $newPxWidth, 
            $newPxHeight, 
            $destFiletype = null, 
            $startPxHeight = null, 
            $startPxWidth = null) 
    {

            //set new height and width
            $this->setNewPxHeight($newPxHeight);
            $this->setNewPxWidth($newPxWidth);

            if (false === empty($destFiletype)) 
            {
                //set destination filetype
                $this->setDestFiletype($destFiletype); 
            } 
            else 
            {
                //set destination filetype to original filetype
                $this->setDestFiletype($this->originalFiletype);
            }

            //calculate start-pixel of new croped image
            if (false === empty($startPxHeight)
                ||
                false === empty($startPxWidth)) 
            {
                $arrStartPx = $this->calcCenterClippingStartPx
                        ($newPxWidth, $newPxHeight);
            } 
            else 
            {
                if (false === is_numeric($startPxHeight) && $startPxHeight > 0) 
                {
                    throw new \Exception(
                    "Error Processing Request: crop(),
                            startPxHeight must be an Integer 
                            greater than 0", 1);
                }

                if (false === is_numeric($startPxWidth) && $startPxWidth > 0) 
                {
                    throw new \Exception(
                    "Error Processing Request: crop(),
                            startPxWidth must be an Integer greater than 0", 1);
                }

                $arrStartPx['height'] = $startPxHeight;
                $arrStartPx['width'] = $startPxWidth;
            }
            
            //build new path to image
            $this->buildNewPathToImage();             

            //check if file does exist
            if (false === file_exists($this->newPathToImage)) 
            {
                
                $this->createOriginalImage();
                            
                //create new Canvas
                $this->setCanvasNewImage($this->newPxWidth, $this->newPxHeight);        
                
                assert(true === isset($this->objOriginalImage));

                //Resample the image
                imagecopyresampled(
                        $this->canvasNewImage, //canvas of the new Image 
                        $this->objOriginalImage, //identifier representing the orig. Image
                        0, //Destination-start-pixel of the x-Axis
                        0, //Destination-start-pixel of the y-Axis
                        $arrStartPx['width'], //Source-start-pixel of the image-width
                        $arrStartPx['height'], //Source-start-pixel of the image-height
                        $this->newPxWidth, //width of the new Image
                        $this->newPxHeight, //height of the new Image
                        $this->originalPxWidth, //Pixel-width of the original Image
                        $this->originalPxHeight //Pixel-height of the original Image
                        );          

                $this->writeImageToFile();
            }
            
            return '/' . str_replace(HYB_ROOT, '', $this->newPathToImage);
    }
    
    /**
     * createOriginalImage
     * @throws \Exception
     */
    private function createOriginalImage()
    {
        assert(false === empty($this->originalFiletype));
        assert(false === empty($this->originalPathToImage));
        
        if ($this->originalFiletype == '.jpg') {

            //get identifier representing the original jpg-image
            $this->objOriginalImage = imagecreatefromjpeg(
                    $this->originalPathToImage);
        } 
        else if ($this->originalFiletype == '.png') 
        {

            //get identifier representing the original png-image
            $this->objOriginalImage = imagecreatefrompng(
                    $this->originalPathToImage);
        } 
        else 
        {
            throw new \Exception(
            "Error Processing Request: __construct(),
                        Imagefile has no valid filetype.", 1);
        }  
    }

    /**
     * calcCenterClippingStartPx - calculates the center clipping of an image
     * 
     * @param Integer $newPxWidth
     * @param Integer $newPxHeight
     * @return Integer[] - Returns start-pixel for height and width 
     * to crop the image to get the center Clipping
     */
    private function calcCenterClippingStartPx($newPxWidth, $newPxHeight) 
    {
        //initially set start-pixel of height an width to 0
        $arrStartPx['height'] = $startPx['width'] = 0;

        //calculate start-pixel for the width
        if ($this->originalPxWidth / 2 - $newPxWidth / 2 > 0) {
            $arrStartPx['width'] = 
                    $this->originalPxWidth / 2 - $newPxWidth / 2;
        }

        //calculate start-pixel for the height
        if ($this->originalPxHeight / 2 - $newPxHeight / 2 > 0) {
            $arrStartPx['height'] = 
                    $this->originalPxHeight / 2 - $newPxHeight / 2;
        }

        return $arrStartPx;
    }

    /**
     * writeImageToFile as jpg or png-file
     */
    private function writeImageToFile() 
    {
        assert(true === isset($this->newImgQuality));  
        
        //Write canvas to file
        if ($this->originalFiletype == '.jpg') {

            //write a jpg-file
            imagejpeg($this->canvasNewImage, $this->newPathToImage, 
                    $this->newImgQuality);
        } 
        else if ($this->originalFiletype == '.png') 
        {
            
            //adapt quality value
            $newQualityPng = 70;
            
            if($this->newImgQuality === 0)
            {
                $newQualityPng = 0;
            } 
            else if($this->newImgQuality === 100)
            {
                $newQualityPng = 9;
            }
            else 
            {
                //take the nearest integer below
                $newQualityPng = floor($this->newImgQuality / 10);
            }
            
            //write a png-file
            imagepng($this->canvasNewImage, $this->newPathToImage, 
                    $newQualityPng, PNG_NO_FILTER);
        }

        imagedestroy($this->canvasNewImage);
    }

    /**
     * buildNewPathToImage
     * @param String $originalPathToImageFolder
     * @param String $originalFilename
     */
    private function buildNewPathToImage() 
    {

        assert(isset(
                $this->originalImageFolder, 
                $this->originalFilename, 
                $this->newPxWidth, 
                $this->newPxHeight, 
                $this->destFiletype));

        //build path to thumb
        $this->newPathToImage = $this->originalImageFolder
                . '/'
                . $this->removeDimExtention()
                . '-'
                . $this->newPxWidth
                . 'x'
                . (int) $this->newPxHeight
                . $this->destFiletype;
    }

    /**
     * rmDimExtention - removes dimension-extensions in Filenames
     * e.g. filename-640x200.jpg or filename_640x200.png
     * @return String
     */
    private function removeDimExtention() 
    {
        assert(isset($this->originalFilename));

        return preg_replace('/(-|_)\d{1,4}x\d{1,4}/', '', 
                $this->originalFilename);
    }

    /**
     * Return the mime-type of the original Image
     * @param String $filename
     * @return String
     */
    private function getFileType() {

        assert(isset($this->originalPathToImage));

        $filetype = false;

        list(,, $mimetype, ) = getimagesize($this->originalPathToImage);

        //ckeck if image is a jpg or an png-file
        if ($mimetype == 2) 
        {
            $filetype = 'jpg';
        } 
        else if ($mimetype == 3) 
        {
            $filetype = 'png';
        } 
        else 
        {
            throw new \Exception(
            "Error Processing Request: getFileType(), "
            . "Imagefile " . htmlspecialchars($this->originalPathToImage)
            . " has no valid mimetype (" 
                    . htmlspecialchars($mimetype) . ").", 1);
        }

        return $filetype;
    }

    /**
     * getPxHeight
     * @param String $pathToImage
     * @throws \Exception
     * @return Integer
     */
    private function getPxHeight() 
    {

        assert(true === isset($this->originalPathToImage));

        $pxHeight = 0;

        //Get dimensions and filetype of the original image
        list(, $pxHeight,, ) = getimagesize($this->originalPathToImage);

        if (!is_numeric($pxHeight) || $pxHeight < 1) 
        {
            throw new \Exception(
            "Error Processing Request: getPxHeight(),
                            Image-Height is not valid", 1);
        }

        return $pxHeight;
    }

    /**
     * getPxWidth
     * @param String $pathToImage
     * @throws \Exception
     * @return Integer
     */
    private function getPxWidth() 
    {
        assert(isset($this->originalPathToImage));

        $pxWidth = 0;

        //Get dimensions and filetype of the original image
        list($pxWidth,,, ) = getimagesize($this->originalPathToImage);

        if (!is_numeric($pxWidth) || $pxWidth < 1)
        {
            throw new \Exception(
            "Error Processing Request: getPxWidth(),
                            Image-Width is not valid", 1);
        }

        return $pxWidth;
    }

    /**
     * setDestFiletype
     * @param String $destFiletype
     * @throws \Exception
     */
    public function setDestFiletype($destFiletype) 
    {

        if (!in_array($destFiletype, $this->allowedFiletypes))
        {
            throw new \Exception(
            "Error Processing Request: setDestFiletype(),
                            Image-filetype is not allowed", 1);
        }

        $this->destFiletype = $destFiletype;
    }

    /**
     * setOriginalPathToImage
     * @param type $originalPathToImage
     * @throws \Exception
     */
    private function setOriginalPathToImage($originalPathToImage) 
    {
        if (!file_exists($originalPathToImage)) 
    {
            throw new \Exception(
            "Error Processing Request: __construct(),
                            Imagefile " 
                    . htmlspecialchars($originalPathToImage) 
                    . " does not exists.", 1);
        }

        $this->originalPathToImage = $originalPathToImage;
    }

    /**
     * setOriginalFiletype
     * @param String $originalFiletype
     * @throws \Exception
     */
    public function setOriginalFiletype($originalFiletype) 
    {
        if (!in_array($originalFiletype, $this->allowedFiletypes)) 
        {
            throw new \Exception(
            "Error Processing Request: setOriginalFiletype(),
                            Image-filetype: " 
                    . htmlspecialchars($originalFiletype) 
                    . " is not allowed", 1);
        }

        $this->originalFiletype = $originalFiletype;
    }

    /**
     * setNewPxWidth
     * @param Integer $newPxWidth
     * @throws \Exception
     */
    private function setNewPxWidth($newPxWidth) {

        if (!is_numeric($newPxWidth) || $newPxWidth <= 0) 
        {
            throw new \Exception(
            "Error Processing Request: setNewPxWidth(),
                        newPxWidth must be an Integer greater than 0", 1);
        }

        $this->newPxWidth = $newPxWidth;
    }
    
    /**
     * Sets the output quality of an image
     * @param Integer $newImgQuality
     * @throws \Exception
     */
    public function setNewImgQuality($newImgQuality) 
    {
        if (false === is_numeric($newImgQuality) 
            || $newImgQuality < 0
            || $newImgQuality > 100) 
        {
            throw new \Exception(
            'Error Processing Request: setNewImgQuality(),
                        $newImgQuality must be an Integer 
                        between 0 and 100', 1);
        } 
        
        $this->newImgQuality = $newImgQuality;
    }

    /**
     * setNewPxHeight
     * @param Integer $newPxHeight
     * @throws \Exception
     */
    private function setNewPxHeight($newPxHeight) 
    {

        if (!is_numeric($newPxHeight) || $newPxHeight <= 0) 
        {
            throw new \Exception(
            "Error Processing Request: setNewPxHeight(),
                        newPxHeight must be an Integer greater than 0", 1);
        }

        $this->newPxHeight = $newPxHeight;
    }

    /**
     * setOriginalPxWidth
     * @param Integer $orignalPxWidth
     * @throws \Exception
     */
    private function setOriginalPxWidth($orignalPxWidth) 
    {
        if (!is_numeric($orignalPxWidth) || $orignalPxWidth <= 0) {
            throw new \Exception(
            "Error Processing Request: setOriginalPxWidth(),
                        orignalPxWidth must be an Integer greater than 0", 1);
        }

        $this->originalPxWidth = $orignalPxWidth;
    }

    /**
     * setOriginalPxHeight
     * @param Integer $originalPxHeight
     * @throws \Exception
     */
    private function setOriginalPxHeight($originalPxHeight) 
    {
        if (!is_numeric($originalPxHeight) || $originalPxHeight <= 0) {
            throw new \Exception(
            "Error Processing Request: setOriginalPxHeight(),
                        originalPxHeight must be an Integer greater than 0", 1);
        }

        $this->originalPxHeight = $originalPxHeight;
    }

    /**
     * setOriginalFilename
     * @param String $originalFilename
     * @throws \Exception
     */
    private function setOriginalFilename($originalFilename) 
    {
        if (!preg_match('/^[a-zA-Z0-9_\-]+$/', $originalFilename)) {
            throw new \Exception(
            "Error Processing Request: setOriginalFilename(),
                        originalFilename is not valid.", 1);
        }

        $this->originalFilename = $originalFilename;
    }

    /**
     * setOrignalImageFolder
     * @param String $originalImageFolder
     * @throws \Exception
     */
    private function setOrignalImageFolder($originalImageFolder) 
    {
        if (!preg_match('|^[a-zA-Z0-9/_\-\.]+$|', $originalImageFolder)) 
        {
            throw new \Exception(
            "Error Processing Request: setOrignalImageFolder(),
                        originalImageFolder: " 
                    . htmlspecialchars($originalImageFolder) 
                    . " is not valid.", 1);
        }

        $this->originalImageFolder = $originalImageFolder;
    }

    /**
     * setCanvasNewImage
     * @param Integer $width
     * @param Integer $height
     * @throws \Exception
     */
    private function setCanvasNewImage($width, $height) 
    {
        if (!is_numeric($width) || $width <= 0) {
            throw new \Exception(
            "Error Processing Request: setCanvasNewIamge(),
                        width: " . htmlspecialchars($width) 
                    . " must be greater than 0.", 1);
        }

        if (!is_numeric($height) || $height <= 0) 
        {
            throw new \Exception(
            "Error Processing Request: setCanvasNewIamge(),
                        height: " 
                    . htmlspecialchars($height) 
                    . " must be grater than 0.", 1);
        }

        //create canvas to hold new image
        $this->canvasNewImage = imagecreatetruecolor($width, $height);
    }
}
?>
