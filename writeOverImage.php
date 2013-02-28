<?php
namespace writeOverImage;

class writeOverImage
{
	public $backgroundImagePath = ''; // image path with trailing slash
	public $backgroundImage = ''; // image name
	public $stringColorRed = 255;
	public $stringColorGreen = 255;
	public $stringColorBlue = 255;
	public $fontSize = 10;
	public $stringAngle = 0;
	public $startX = 0;
	public $startY = 0;
	public $fontName = 'helvetica.ttf'; // other free fonts here: http://www.free-fonts-ttf.org/true-type-fonts/
	public $stringToWrite = ''; // UTF-8 encoded string
	public $newFile = false; // if false overwrite base file, if true create a new file with provided filename
	public $newFileName = ''; // just the filename. extension will be added automatically
	public $outputDirectly = false; // if true send image directly, if false save image file
	
	protected $image;
	protected $mime;
	
	protected $output;
	
	function __construct()
	{
		try {
			putenv('GDFONTPATH=' . realpath('.'));
		} catch (Exception $e) {
			echo 'ERROR: '.$e->getMessage();
		}
	}
	

	
	/**
	 * Get base image with GD
	 * 
	 * @param string $imagePath
	 * @throws Exception
	 */
	private function getBaseImage($imagePath)
	{
		$info = getimagesize($imagePath);
		
		switch ($info['mime']) {
			case 'image/gif':
				$im = imagecreatefromgif($imagePath);
				break;
			case 'image/jpeg':
				$im = imagecreatefromjpeg($imagePath);
				break;
			case 'image/png':
				$im = imagecreatefrompng($imagePath);
				break;
			case 'image/bmp':
				$im = imagecreatefromwbmp($imagePath);
				break;
			default:
				throw new Exception('Mime type not supported');
				break;
		}
		
		$this->mime = $info['mime'];
		
		return $im;
	}
	

	/**
	 * Create image. Overwrite existing file or create new or output directly
	 * 
	 * @throws Exception
	 */
	function createImage()
	{

		$this->image = $this->getBaseImage($this->backgroundImagePath.$this->backgroundImage);
		$this->backgroundImage = substr($this->backgroundImage, 0, strpos($this->backgroundImage, '.') -1);
		$stringColor = $this->createStringColor();
		$ext = $this->getFileExtension();
		
		try {
			// write text over the image
			if (!Imagettftext($this->image, $this->fontSize, $this->stringAngle, $this->startX, $this->startY, $stringColor, $this->fontName, $this->stringToWrite))
			{
				die('no');
			}
			
			// manage output
			if ($this->outputDirectly)
			{
				// directly stream of data
				$this->output = NULL;
			}
			else {
				// save data in a file (new or overwrite)
				$this->output = ($this->newFile) ? $this->backgroundImagePath.$this->newFileName.$ext : $this->backgroundImagePath.$this->backgroundImage.$ext;
			}
			
			
			switch ($this->mime)
			{
				case 'image/gif':
					imagegif($this->image, $this->output);
					break;
				case 'image/jpeg':
					imagejpeg($this->image, $this->output, 75);
					break;
				case 'image/png':
					imagepng($this->image, $this->output, 7, PNG_ALL_FILTERS);
					break;
				case 'image/bmp':
					imagewbmp($this->image, $this->output);
					break;
				default:
					throw new Exception('Mime type not supported');
					break;
			}
						
			ImageDestroy($this->image);
			
		} catch (Exception $e) {
			echo 'ERROR: '.$e->getMessage();
		}
		
	}
	
	
	
	/**
	 * Create color value for the string
	 * 
	 * @return number
	 */
	private function createStringColor()
	{
		try {
			$color = ImageColorAllocate($this->image, $this->stringColorRed, $this->stringColorGreen, $this->stringColorBlue);
		} catch (Exception $e) {
			echo 'ERROR: '.$e->getMessage();
		}
		
		return $color;
	}
	
	
	
	/**
	 * Return a string with file extension based on mimetype (eg.: .gif)
	 * 
	 * @throws Exception
	 */
	private function getFileExtension()
	{
		switch ($this->mime)
		{
			case 'image/gif':
				return '.gif';
				break;
			case 'image/jpeg':
				return '.jpg';
				break;
			case 'image/png':
				return '.png';
				break;
			case 'image/bmp':
				return '.bmp';
				break;
			default:
				throw new Exception('Mime type not supported');
				break;
		}
	}
}