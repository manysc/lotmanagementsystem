<?php

class Layout {

   private $builder = '';
   private $subdivision = '';
   private $lot = 0;
   private $supervisor = 0;
   private $courses = 0;
   private $gate = '';
   private $endLot = 0;

   private $backWall = 0;
   private $leftWall = 0;
   private $rightWall = 0;

   private $leftReturn = 0;
   private $rightReturn = 0;

   function setBuilder($aBuilder) {
      $this->builder = $aBuilder;
   }

   function getBuilder() {
      return $this->builder;
   }

   function setSubdivision($aSubdivision) {
      $this->subdivision = $aSubdivision;
   }

   function getSubdivision() {
      return $this->subdivision;
   }

   function setLot($aLot) {
      $this->lot = $aLot;
   }

   function getLot() {
      return $this->lot;
   }

   function setSupervisor($aSupervisor) {
      $this->supervisor = $aSupervisor;
   }

   function getSupervisor() {
      return $this->supervisor;
   }

   function setCourses($aCourses) {
      $this->courses = $aCourses;
   }

   function getCourses() {
      return $this->courses;
   }

   function setGate($aGate) {
      $this->gate = $aGate;
   }

   function getGate() {
      return $this->gate;
   }

   function setEndLot($aEndLot) {
      $this->endLot = $aEndLot;
   }

   function getEndLot() {
      return $this->endLot;
   }

   function setBackWall($aBackWall) {
      $this->backWall = $aBackWall;
   }

   function getBackWall() {
      return $this->backWall;
   }

   function setLeftWall($aLeftWall) {
      $this->leftWall = $aLeftWall;
   }

   function getLeftWall() {
      return $this->leftWall;
   }

   function setRightWall($aRightWall) {
      $this->rightWall = $aRightWall;
   }

   function getRightWall() {
      return $this->rightWall;
   }

   function setLeftReturn($aLeftReturn) {
      $this->leftReturn = $aLeftReturn;
   }

   function getLeftReturn() {
      return $this->leftReturn;
   }

   function setRightReturn($aRightReturn) {
      $this->rightReturn = $aRightReturn;
   }

   function getRightReturn() {
      return $this->rightReturn;
   }
}

?>