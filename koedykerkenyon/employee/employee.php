<?php

class Employee {
	public $oName = '';
	public $oStartTime = '';
	public $oStopTime = '';
	public $oTotalTime = '';
	public $oCost = '';
	public $oWage = '';
	public $oCrew_name = '';
	public $oEmail = '';
	public $oIsSupervisor = '';
	public $oIsForman = '';
	public $oIsMason = '';
	public $oIsApprentice = '';
	public $oIsLabor = '';
	public $oIsDriver = '';
	public $oIsOperator = '';
	public $oIsFooter = '';
	
	function setName($aName) {
		$this->oName = $aName;
	}
	
	function setStartTime($aStartTime) {
		$this->oStartTime = $aStartTime;
	}
	
	function setStopTime($aStopTime) {
		$this->oStopTime = $aStopTime;
	}
	
	function setTotalTime($aTotalTime) {
		$this->oTotalTime = $aTotalTime;
	}
	
	function setCost($aCost) {
		$this->oCost = $aCost;
	}
	
	function setWage($aWage) {
		$this->oWage = $aWage;
	}
	
	function setIsForman($aIsForman) {
		$this->oIsForman = $aIsForman;
	}
	
	function setIsMason($aIsMason) {
		$this->oIsMason = $aIsMason;
	}
	
	function setIsApprentice($aIsApprentice) {
		$this->oIsApprentice = $aIsApprentice;
	}
	
	function setIsLabor($aIsLabor) {
		$this->oIsLabor = $aIsLabor;
	}
	
	function setIsDriver($aIsDriver) {
		$this->oIsDriver = $aIsDriver;
	}
	
	function setIsOperator($aIsOperator) {
		$this->oIsOperator = $aIsOperator;
	}
	
	function toString() {
		return $this->oName . "," . $this->oWage . "," . $this->oCrew_name;
	}
}

?>