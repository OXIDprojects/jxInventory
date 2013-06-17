<?php

class jxInventory_SendOrder extends oxAdminDetails
{
    
    public function sendOrder()
    {
        //$oInventory = oxNew( "myinventory" );
        $oOrder = oxNew( "oxorder" );
        if ( $oOrder->load( $this->getEditObjectId() ) ) {
            $oOrderArticles = $oOrder->getOrderArticles();
            $oOrderId = $oOrder->getId();
            //echo "<br>".$oOrderId;
            foreach ( $oOrderArticles as $sOxid => $oArticle ) {
                 if ( $oArticle->oxorderarticles__oxstorno->value != 1 ) {
                     $this->updateInventory( $oOrderId, $oArticle->oxorderarticles__oxartid->value, $oArticle->oxorderarticles__oxamount->value );
                     //echo "<br>".$oArticle->oxorderarticles__oxartid->value;
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
            $sSql = "UPDATE jxinvarticles SET jxinvstock = jxinvstock - $Amount WHERE jxartid = '$ArticleID'";
            $rs = oxDb::getDb(true)->Execute($sSql);
        }
        
        return; // $this->_sThisTemplate;
    }

    
    public function saveShippingAction( $OrderId, $SendDate, $OrderNr )
    {
        //echo "<br>SendDate: ".$SendDate;
        $sSql = "INSERT IGNORE INTO jxinvshipping (jxinvorderid, jxinvsenddate, jxinvordernr) "
                . "VALUES ('$OrderId', '$SendDate', '$OrderNr')";
        //echo "<br>".$sSql;
        $rs = oxDb::getDb(true)->Execute($sSql);
        
        return;
    }    
    
}

?>
