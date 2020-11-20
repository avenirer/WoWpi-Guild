<?php
namespace WowpiGuild\Includes;

use WowpiGuild\Config\Settings;

class ImageManipulation {

	private $image;

	function __construct() {
		$this->image = new \stdClass();
	}

	public function setSource($imageSource)
	{
		$imageSource = esc_url_raw($imageSource);
		$this->image->source = $imageSource;
		$imageSourceArr = explode('/', $imageSource);
		$this->image->name = $imageSourceArr[sizeof($imageSourceArr)-1];
		return $this;
	}

	public function setDir($imageDir) {
		$imageDir = filter_var($imageDir, FILTER_SANITIZE_URL);
		$imagePath = Settings::pluginPath().'assets'.DIRECTORY_SEPARATOR.$imageDir;
		$imageUrlDir = Settings::pluginUrl().'assets/'.$imageDir.'/';

		if($this->checkCreateDir($imagePath)) {
			$this->image->dir = $imagePath;
			$this->image->urlDir = $imageUrlDir;
		}
		return $this;
	}

	public function setFileName($name) {
		$imageArr = explode('.', $this->image->name);
		$this->image->name = $name . '.' . $imageArr[1];
		return $this;
	}

	public function getInternalUrl() {
		$this->saveImage();
		return $this->image->url;

	}

	private function saveImage() {
		if($this->get_http_response_code($this->image->source) == "200") {
			$imageGet = file_get_contents($this->image->source);
			if ( $imageGet !== false ) {
				try{
					if(! file_put_contents( $this->image->dir . DIRECTORY_SEPARATOR . $this->image->name, $imageGet )) {
						throw new \Exception('Cannot save image in the assets directory: '.json_encode($this->image));
					}
					$this->image->url = $this->image->urlDir.$this->image->name;
				}
				catch (\Exception $exception) {
					echo $exception->getMessage();
					error_log($exception->getMessage());
					$this->image->url = '';
				}
			}
		}
	}



	private function checkCreateDir($path) {

		try {
			if ( ! file_exists( $path )  && ! wp_mkdir_p( $path )) {
				throw new \Exception('Cannot create directory in path '. $path);
	        }
		}
		catch (\Exception $exception) {
			echo $exception->getMessage();
			error_log($exception->getMessage());
			return false;
		}

		return true;
	}

	private function get_http_response_code($url) {
		$headers = get_headers($url);
		return substr($headers[0], 9, 3);
	}

}