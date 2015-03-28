<?php
/**
 *    This file is part of the module jxInventory for OXID eShop Community Edition.
 *
 *    The module jxInventory for OXID eShop Community Edition is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    The module jxInventory for OXID eShop Community Edition is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with OXID eShop Community Edition.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      https://github.com/job963/jxInventory
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 * @copyright (C) 2012-2015 Joachim Barthel
 * @author    Joachim Barthel <jobarthel@gmail.com>
 * 
 */

class jxInventory_SendOrder extends oxAdminDetails
{
    
    public function sendOrder()
    {
        $oOrder = oxNew( "oxorder" );
        if ( $oOrder->load( $this->getEditObjectId() ) ) {
            $oOrderArticles = $oOrder->getOrderArticles();
            $oOrderId = $oOrder->getId();
            foreach ( $oOrderArticles as $sOxid => $oArticle ) {
                 if ( $oArticle->oxorderarticles__oxstorno->value != 1 ) {
                     $this->updateInventory( $oOrderId, $oArticle->oxorderarticles__oxartid->value, $oArticle->oxorderarticles__oxamount->value );
                 }
            }
            
            // Get unformatted date
            $sSql = "SELECT oxsenddate FROM oxorder WHERE oxid = '$oOrderId' ";
            $rs = oxDb::getDb(true)->Execute($sSql);
            $sSendDate = $rs->fields[0];

            $this->saveShippingAction( $oOrderId, $sSendDate, $oOrder->oxorder__oxordernr->value );
        }
        
    }

      
    public function updateInventory( $OrderId, $ArticleID, $Amount )
    {
        $sSql = "SELECT jxinvorderid FROM jxinvshipping WHERE jxinvorderid = '$OrderId' ";
        $rs = oxDb::getDb(true)->Execute($sSql);

        if ($rs->EOF) {
            $sSql = "UPDATE jxinvarticles SET jxinvstock = jxinvstock - $Amount WHERE jxartid = '$ArticleID' AND jxinvstock > 0 ";
            $rs = oxDb::getDb(true)->Execute($sSql);
        }
        
        return;
    }

    
    public function saveShippingAction( $OrderId, $SendDate, $OrderNr )
    {
        $sSql = "INSERT IGNORE INTO jxinvshipping (jxinvorderid, jxinvsenddate, jxinvordernr) "
                . "VALUES ('$OrderId', '$SendDate', '$OrderNr')";

        $rs = oxDb::getDb(true)->Execute($sSql);
        
        return;
    }    
    
}

?>
