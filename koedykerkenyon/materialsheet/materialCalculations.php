<?php

$materialOutputMessage = '';

$courses = 0;
$lotBW = 0;
$lotLW = 0;
$lotRW = 0;

$bwNetFullBlock = 0;
$lwNetFullBlock = 0;
$rwNetFullBlock = 0;
$lrwNetFullBlock = 0;
$rrwNetFullBlock = 0;

$bwABlockNeeded = 0;
$lrABlockNeeded = 0;
$rrABlockNeeded = 0;

$bwHBlockNeeded = 0;
$lwHBlockNeeded = 0;
$rwHBlockNeeded = 0;
$lrHBlockNeeded = 0;
$rrHBlockNeeded = 0;

$lrLBlockNeeded = 0;
$rrLBlockNeeded = 0;

$bwHBlockNeeded = 0;

$bwCapMath = 0;
$lwCapMath = 0;
$rwCapMath = 0;
$lrwCapMath = 0;
$rrwCapMath = 0;

$lfLeftReturn = 0;
$lfRightReturn = 0;

$fullBlockQuantity = 0;
$aBlockQuantity = 0;
$hBlockQuantity = 0;
$lBlockQuantity = 0;
$capsQuantity = 0;
$latterWireQuantity = 0;

$blockPrice = 0;
$aBlockPrice = 0;
$hBlockPrice = 0;
$lBlockPrice = 0;
$tBlockPrice = 0;
$capsPrice = 0;
$decoBlockPrice = 0;
$block4Price = 0;

$fullBlockAmount = 0;
$aBlockAmount = 0;
$hBlockAmount = 0;
$lBlockAmount = 0;
$tBlockAmount = 0;
$capsAmount = 0;
$latterWireAmount = 0;
$decosAmount = 0;
$block4Amount = 0;
$palletsAmount = 0;

function calculateBackWallMaterials($layout) {
   global $materialOutputMessage;
   global $courses;
   global $lotBW;
   global $bwNetFullBlock;
   global $bwABlockNeeded;
   global $bwHBlockNeeded;
   global $bwTBlockNeeded;
   global $bwCapMath;

   $courses = $layout->getCourses();
   $endLot = $layout->getEndLot();
   $lotBW = $layout->getBackWall();

   $hBlockColumns = 0;
   $bwNetFullBlock = 0;
   $bwHBlockNeeded = 0;
   $bwTBlockNeeded = 0;
   $bwABlockNeeded = 0;
   $bwCapMath = 0;

   # TODO Create constant for magic number 12.
   if ($lotBW > 0) {
      if ($lotBW >= 12) {
         $hBlockColumns = round($lotBW/12 - 1);
//          echo 'hBlockColumns=' . $hBlockColumns;
      }

      # TODO Create constant for magic number 1.34.
      $bwNetFullBlock = round(($lotBW - $hBlockColumns)/1.34 * $courses);
//       echo ' bwNetFullBlock=' . $bwNetFullBlock;

      $bwHBlockNeeded = $hBlockColumns * $courses;
//       echo ' bwHBlockNeeded=' . $bwHBlockNeeded;

      if (strcasecmp($endLot,'R') == 0 || strcasecmp($endLot,'L') == 0) {
         $bwTBlockNeeded = $courses;
      } else {
         $bwTBlockNeeded = $courses * 2;
      }

//       echo ' bwTBlockNeeded=' . $bwTBlockNeeded;

      # TODO Revise bwABlockNeeded as it seems it's always 0.
//       echo ' bwABlockNeeded=' . $bwABlockNeeded;

      $bwCapMath = ($bwHBlockNeeded + $bwTBlockNeeded + $bwABlockNeeded) / $courses;
//       echo ' bwCapMath=' . $bwCapMath;
   }
}

function calculateLeftWallMaterials($layout) {
   global $materialOutputMessage;
   global $courses;
   global $lotLW;
   global $lwNetFullBlock;
   global $lwHBlockNeeded;
   global $lwCapMath;

   $courses = $layout->getCourses();
   $lotLW = $layout->getLeftWall();

   $hBlockColumns = 0;
   $lwNetFullBlock = 0;
   $lwHBlockNeeded = 0;
   $lwCapMath = 0;

   # TODO Create constant for magic number 12.
   if ($lotLW > 0) {
      if ($lotLW >= 12) {
         $hBlockColumns = round($lotLW/12 - 1, 0);
//          echo ' lwHBlockColumns=' . $hBlockColumns;
      }

      $lwHBlockNeeded = $hBlockColumns * $courses;
//       echo ' lwHBlockNeeded=' . $lwHBlockNeeded;

      # TODO Create constant for magic number 1.34.
      $lwNetFullBlock = round(($lotLW - $hBlockColumns)/1.34 * $courses);
//       echo ' lwNetFullBlock=' . $lwNetFullBlock;

      $lwCapMath = $lwHBlockNeeded / $courses;
//       echo ' lwCapMath=' . $lwCapMath;
   }
}

function calculateRightWallMaterials($layout) {
   global $materialOutputMessage;
   global $courses;
   global $rwNetFullBlock;
   global $rwHBlockNeeded;
   global $rwCapMath;
   global $lotRW;

   $courses = $layout->getCourses();
   $lotRW = $layout->getRightWall();

   $hBlockColumns = 0;
   $rwNetFullBlock = 0;
   $rwHBlockNeeded = 0;
   $rwCapMath = 0;

   # TODO Create constant for magic number 12.
   if ($lotRW > 0) {
      if ($lotRW >= 12) {
         $hBlockColumns = round($lotRW/12 - 1, 0);
//          echo 'hBlockColumns=' . $hBlockColumns;
      }

      $rwHBlockNeeded = $hBlockColumns * $courses;
//       echo ' rwHBlockNeeded=' . $rwHBlockNeeded;

      # TODO Create constant for magic number 1.34.
      $rwNetFullBlock = round(($lotRW - $hBlockColumns)/1.34 * $courses);
//       echo ' rwNetFullBlock=' . $rwNetFullBlock;

      $rwCapMath = $rwHBlockNeeded / $courses;
//       echo ' rwCapMath=' . $rwCapMath;
   }
}

function calculateLeftReturnWallMaterials($layout) {
   global $materialOutputMessage;
   global $courses;
   global $lrwNetFullBlock;
   global $lrABlockNeeded;
   global $lrHBlockNeeded;
   global $lrLBlockNeeded;
   global $lrwCapMath;
   global $lfLeftReturn;

   $courses = $layout->getCourses();
   $gate = $layout->getGate();
   $endLot = $layout->getEndLot();
   $backWall = $layout->getBackWall();
   $leftWall = $layout->getLeftWall();
   $rightWall = $layout->getRightWall();
   $leftReturn = $layout->getLeftReturn();
   $rightReturn = $layout->getRightReturn();

   $lfLeftReturn = 0;
   $hBlockWallColumns = 0;
   $hBlockReturnColumns = 0;
   $lrwNetFullBlock = 0;
   $lrHBlockNeeded = 0;
   $lrABlockNeeded = 0;
   $lrLBlockNeeded = 0;
   $lrwCapMath = 0;

   if ($leftReturn > 0) {
      if (strcasecmp($gate,'L') == 0) {
         # TODO Create constant for magic number 5.
         $lfLeftReturn = $leftReturn - 5;
      } else {
         $lfLeftReturn = $leftReturn;
      }
   }
//    echo 'lfLeftReturn=' . $lfLeftReturn;

   # TODO Create constant for magic number 12.
   if ($lfLeftReturn >= 12) {
      $hBlockWallColumns = round($lfLeftReturn/12);
   }
//    echo ' hBlockWallColumns=' . $hBlockWallColumns;

   if ($lfLeftReturn > 0) {
      $hBlockReturnColumns = $backWall + $leftWall + $rightWall + $leftReturn + $rightReturn;

      if ($hBlockReturnColumns > 0) {
         if (strcasecmp($gate,'L') == 0) {
            $hBlockReturnColumns = 0;
         } else {
            $hBlockReturnColumns = 1;
         }
      }
   }
//    echo ' hBlockReturnColumns=' . $hBlockReturnColumns;

   if ($lfLeftReturn > 0) {
      # TODO Create constant for magic number 1.34.
      $lrwNetFullBlock = round(($lfLeftReturn - $hBlockWallColumns - $hBlockReturnColumns - 1)/1.34 * $courses);
   }
//    echo ' lrwNetFullBlock=' . $lrwNetFullBlock;

   $lrHBlockNeeded = ($hBlockWallColumns + $hBlockReturnColumns) * $courses;
//    echo ' lrHBlockNeeded=' . $lrHBlockNeeded;

   if (strcasecmp($gate,'L&R') == 0 || strcasecmp($gate,'R&L') == 0 || strcasecmp($gate,'L') == 0) {
      $lrABlockNeeded = $courses * 2;
   }
//    echo ' lrABlockNeeded=' . $lrABlockNeeded;

   if ($lfLeftReturn > 0) {
      if (strcasecmp($endLot,'L') == 0) {
         $lrLBlockNeeded = $courses;
      }
   }
//    echo ' lrLBlockNeeded=' . $lrLBlockNeeded;

   $lrwCapMath = ($lrHBlockNeeded + $lrABlockNeeded + $lrLBlockNeeded) / $courses;
//    echo ' lrwCapMath=' . $lrwCapMath;
}

function calculateRightReturnWallMaterials($layout) {
   global $materialOutputMessage;
   global $courses;
   global $rrwNetFullBlock;
   global $rrABlockNeeded;
   global $rrHBlockNeeded;
   global $rrLBlockNeeded;
   global $rrwCapMath;
   global $lfRightReturn;

   $courses = $layout->getCourses();
   $gate = $layout->getGate();
   $endLot = $layout->getEndLot();
   $backWall = $layout->getBackWall();
   $leftWall = $layout->getLeftWall();
   $rightWall = $layout->getRightWall();
   $leftReturn = $layout->getLeftReturn();
   $rightReturn = $layout->getRightReturn();

   $lfRightReturn = 0;
   $hBlockWallColumns = 0;
   $hBlockReturnColumns = 0;
   $rrwNetFullBlock = 0;
   $rrHBlockNeeded = 0;
   $rrABlockNeeded = 0;
   $rrLBlockNeeded = 0;
   $rrwCapMath = 0;

   if ($rightReturn > 0) {
      if (strcasecmp($gate,'R') == 0) {
         # TODO Create constant for magic number 5.
         $lfRightReturn = $rightReturn - 5;
      } else {
         $lfRightReturn = $rightReturn;
      }
   }
//    echo 'lfRightReturn=' . $lfRightReturn;

   # TODO Create constant for magic number 12.
   if ($lfRightReturn >= 12) {
      $hBlockWallColumns = round($lfRightReturn/12);
   }
//    echo ' hBlockWallColumns=' . $hBlockWallColumns;

   if ($lfRightReturn > 0) {
      $hBlockReturnColumns = $backWall + $leftWall + $rightWall + $leftReturn + $rightReturn;

      if ($hBlockReturnColumns > 0) {
         if (strcasecmp($gate,'R') == 0) {
            $hBlockReturnColumns = 0;
         } else {
            $hBlockReturnColumns = 1;
         }
      }
   }
//    echo ' hBlockReturnColumns=' . $hBlockReturnColumns;

   if ($lfRightReturn > 0) {
      # TODO Create constant for magic number 1.34.
      $rrwNetFullBlock = round(($lfRightReturn - $hBlockWallColumns - 1)/1.34 * $courses);
   }
//    echo ' rrwNetFullBlock=' . $rrwNetFullBlock;

   $rrHBlockNeeded = ($hBlockWallColumns + $hBlockReturnColumns) * $courses;
//    echo ' rrHBlockNeeded=' . $rrHBlockNeeded;

   if (strcasecmp($gate,'L&R') == 0 || strcasecmp($gate,'R&L') == 0 || strcasecmp($gate,'R') == 0) {
      $rrABlockNeeded = $courses * 2;
   }
//    echo ' rrABlockNeeded=' . $rrABlockNeeded;

   if ($lfRightReturn > 0) {
      if (strcasecmp($endLot,'R') == 0) {
         $rrLBlockNeeded = $courses;
      }
   }
//    echo ' rrLBlockNeeded=' . $rrLBlockNeeded;

   $rrwCapMath = ($rrHBlockNeeded + $rrABlockNeeded + $rrLBlockNeeded) / $courses;
//    echo ' rrwCapMath=' . $rrwCapMath;
}

function calculateMaterialQuantities() {
   global $bwNetFullBlock;
   global $lwNetFullBlock;
   global $rwNetFullBlock;
   global $lrwNetFullBlock;
   global $rrwNetFullBlock;

   global $bwABlockNeeded;
   global $lrABlockNeeded;
   global $rrABlockNeeded;

   global $bwHBlockNeeded;
   global $lwHBlockNeeded;
   global $rwHBlockNeeded;
   global $lrHBlockNeeded;
   global $rrHBlockNeeded;

   global $lrLBlockNeeded;
   global $rrLBlockNeeded;

   global $bwTBlockNeeded;

   global $bwCapMath;
   global $lwCapMath;
   global $rwCapMath;
   global $lrwCapMath;
   global $rrwCapMath;

   global $courses;
   global $lotBW;
   global $lotLW;
   global $lotRW;
   global $lfLeftReturn;
   global $lfRightReturn;

   global $fullBlockQuantity;
   global $aBlockQuantity;
   global $hBlockQuantity;
   global $lBlockQuantity;
   global $capsQuantity;
   global $latterWireQuantity;

   $fullBlockQuantity = $bwNetFullBlock + $lwNetFullBlock + $rwNetFullBlock + $lrwNetFullBlock + $rrwNetFullBlock;
//    echo ' fullBlockQuantity=' . $fullBlockQuantity;

   $aBlockQuantity = $bwABlockNeeded + $lrABlockNeeded + $rrABlockNeeded;
//    echo ' aBlockQuantity=' . $aBlockQuantity;

   $hBlockQuantity = $bwHBlockNeeded + $lwHBlockNeeded + $rwHBlockNeeded + $lrHBlockNeeded + $rrHBlockNeeded;
//    echo ' hBlockQuantity=' . $hBlockQuantity;

   $lBlockQuantity = $lrLBlockNeeded + $rrLBlockNeeded;
//    echo ' lBlockQuantity=' . $lBlockQuantity;

//    echo ' bwTBlockNeeded=' . $bwTBlockNeeded;

   $capsQuantity = $bwCapMath + $lwCapMath + $rwCapMath + $lrwCapMath + $rrwCapMath;
//    echo ' capsQuantity=' . $capsQuantity;

   # TODO Create constant for magic number 9.33 and 2.
   $latterWireQuantity = round(($lotBW + $lotLW + $lotRW + $lfLeftReturn + $lfRightReturn)/9.33 * ($courses - 2) / 2);
//    echo ' latterWireQuantity=' . $latterWireQuantity;
}

function calculateMaterialTotals() {
   global $materialOutputMessage;

   global $extraAddedPercentage;

   global $fullBlockQuantity;
   global $aBlockQuantity;
   global $hBlockQuantity;
   global $lBlockQuantity;
   global $bwTBlockNeeded;
   global $capsQuantity;
   global $latterWireQuantity;
   global $decosQuantity;
   global $block4Quantity;
   global $palletsQuantity;
   global $blockColor;

   global $vendorBlockPrice;
   global $vendorABlockPrice;
   global $vendorHBlockPrice;
   global $vendorLBlockPrice;
   global $vendorTBlockPrice;
   global $vendorCapsPrice;
   global $vendorLatterWirePrice;
   global $vendorDecoBlockPrice;
   global $vendorBlock4Price;
   global $vendorPalletsPrice;

   global $fullBlockAmount;
   global $aBlockAmount;
   global $hBlockAmount;
   global $lBlockAmount;
   global $tBlockAmount;
   global $capsAmount;
   global $latterWireAmount;
   global $decosAmount;
   global $block4Amount;
   global $palletsAmount;

   global $deliveryCharge;

   global $materialSubTotal;
   global $salesTaxPercentage;
   global $salesTax;
   global $materialTotal;

      if ($vendorBlockPrice > 0) {
         $fullBlockQuantity = round($fullBlockQuantity*(1+($extraAddedPercentage/100)));
         $fullBlockAmount = round($fullBlockQuantity*$vendorBlockPrice, 2);
//          echo ' fullBlock=' . $fullBlockQuantity . ' unitPrice=' . $vendorBlockPrice . ' total=' . $fullBlockAmount;
      }

      if ($vendorABlockPrice > 0) {
         $aBlockQuantity = round($aBlockQuantity*(1+($extraAddedPercentage/100)));
         $aBlockAmount = round($aBlockQuantity*$vendorABlockPrice, 2);
//          echo ' aBlock=' . $aBlockQuantity . ' unitPrice=' . $aBlockPrice . ' total=' . $aBlockAmount;
      }

      if ($vendorHBlockPrice > 0) {
         $hBlockQuantity = round($hBlockQuantity*(1+($extraAddedPercentage/100)));
         $hBlockAmount = round($hBlockQuantity*$vendorHBlockPrice, 2);
//          echo ' hBlock=' . $hBlockQuantity . ' unitPrice=' . $hBlockPrice . ' total=' . $hBlockAmount;
      }

      if ($vendorLBlockPrice > 0) {
         $lBlockQuantity = round($lBlockQuantity*(1+($extraAddedPercentage/100)));
         $lBlockAmount = round($lBlockQuantity*$vendorLBlockPrice, 2);
//          echo ' lBlock=' . $lBlockQuantity . ' unitPrice=' . $lBlockPrice . ' total=' . $lBlockAmount;
      }

      if ($vendorTBlockPrice > 0) {
         $bwTBlockNeeded = round($bwTBlockNeeded*(1+($extraAddedPercentage/100)));
         $tBlockAmount = round($bwTBlockNeeded*$vendorTBlockPrice, 2);
//          echo ' tBlock=' . $bwTBlockNeeded . ' unitPrice=' . $tBlockPrice . ' total=' . $tBlockAmount;
      }

      if ($vendorCapsPrice > 0) {
         $capsQuantity = round($capsQuantity*(1+($extraAddedPercentage/100)));
         $capsAmount = round($capsQuantity*$vendorCapsPrice, 2);
//          echo ' caps=' . $capsQuantity . ' unitPrice=' . $capsPrice . ' total=' . $capsAmount;
      }

      if ($vendorLatterWirePrice > 0) {
         $latterWireQuantity = round($latterWireQuantity*(1+($extraAddedPercentage/100)));
         $latterWireAmount = round($latterWireQuantity*$vendorLatterWirePrice, 2);
//          echo ' latterWire=' . $latterWireQuantity . ' unitPrice=' . $latterWirePrice . ' total=' . $latterWireAmount;
      }

      if ($vendorDecoBlockPrice > 0) {
         $decosQuantity = 0;
         if ($fullBlockQuantity > 0) {
            $decosQuantity = 1;
         }
         $decosAmount = round($decosQuantity*$vendorDecoBlockPrice, 2);
//          echo ' decos=' . $decosQuantity . ' unitPrice=' . $decoBlockPrice . ' total=' . $decosAmount;
      }

      if ($vendorBlock4Price > 0) {
//          $block4Quantity = 0;
//          if (!empty($_POST["block4"])) {
//             $block4Quantity = $_POST["block4"];
//          }

         $block4Amount = round($block4Quantity*$vendorBlock4Price, 2);
//          echo ' block4=' . $block4Quantity . ' unitPrice=' . $block4Price . ' total=' . $block4Amount;
      }

      if ($vendorPalletsPrice > 0) {
//          $palletsQuantity = 0;
//          if (!empty($_POST["pallets"])) {
//             $palletsQuantity = $_POST["pallets"];
//          }

         $palletsAmount = round($palletsQuantity*$vendorPalletsPrice, 2);
//          echo ' pallets=' . $palletsQuantity . ' unitPrice=' . $palletsPrice . ' total=' . $palletsAmount;
      }
//    }

//    $deliveryCharge = 0;
//    if (!empty($_POST["deliveryCharge"])) {
//       $deliveryCharge = round($_POST["deliveryCharge"], 2);
//    }
//    echo ' deliveryCharge=' . $deliveryCharge;

   $materialSubTotal = round($fullBlockAmount + $aBlockAmount + $hBlockAmount + $lBlockAmount + $tBlockAmount + $capsAmount + $latterWireAmount + $decosAmount + $block4Amount + $palletsAmount + $deliveryCharge, 2);
//    echo ' subTotal=' . $subTotal;

   $salesTax = round($materialSubTotal * ($salesTaxPercentage/100), 2);
//    echo ' salesTax=' . $salesTax;

   $materialTotal = round($materialSubTotal + $salesTax, 2);
//    echo ' total=' . $total;
}

?>