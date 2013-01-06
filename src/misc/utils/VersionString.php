<?php

/*

           -
         /   \
      /         \
   /   PocketMine  \
/          MP         \
|\     @shoghicp     /|
|.   \           /   .|
| ..     \   /     .. |
|    ..    |    ..    |
|       .. | ..       |
\          |          /
   \       |       /
      \    |    /
         \ | /

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.


*/


class VersionString{
	public static $stageOrder = array(
		"alpha" => 0,
		"beta" => 1,
		"final" => 2,
	);
	private $stage, $major, $release, $minor;
	public function __construct($version = MAJOR_VERSION){
		if(is_int($version)){
			$this->minor = $version & 0x1F;
			$this->major = ($version >> 5) & 0x0F;
			$this->generation = ($version >> 9) & 0x0F;
			$this->stage = array_search(($version >> 13) & 0x0F, VersionString::$stageOrder, true);
		}else{
			$version = preg_split("/([A-Za-z]*)[ _\-]([0-9]*)\.([0-9]*)\.{0,1}([0-9]*)/", $version, -1, PREG_SPLIT_DELIM_CAPTURE);
			$this->stage = strtolower($version[1]); //0-15
			$this->generation = (int) $version[2]; //0-15
			$this->major = (int) $version[3]; //0-15
			$this->minor = (int) $version[4]; //0-31
		}
	}

	public function getNumber(){
		return (int) (VersionString::$stageOrder[$this->stage] << 13) + ($this->generation << 9) + ($this->major << 5) + $this->minor;
	}

	public function getStage(){
		return $this->stage;
	}

	public function getGeneration(){
		return $this->generation;
	}

	public function getMajor(){
		return $this->major;
	}

	public function getMinor(){
		return $this->minor;
	}

	public function getRelease(){
		return $this->generation . "." . $this->major . "." . $this->minor;
	}

	public function __toString(){
		return ucfirst($this->stage) . "_" . $this->generation . "." . $this->major . "." . $this->minor;
	}

	public function compare($target, $diff = false){
		if(($target instanceof VersionString) === false){
			$target = new VersionString($target);
		}
		$number = $this->getNumber();
		$tNumber = $target->getNumber();
		if($diff === true){
			return $tNumber - $number;
		}
		if($number > $tNumber){
			return -1; //Target is older
		}elseif($number < $tNumber){
			return 1; //Target is newer
		}else{
			return 0; //Same version
		}
	}
}