<?php

class jxInventory_OrderOverview extends order_overview
{
    public function sendorder()
    {
        $result = parent::sendorder();
        /*echo "<br>*** jxInventory_OrderOverview ***";
        $fp = fopen("w:/test/logfile.txt", "a+");
        fputs($fp,  date("Y-m-d H:i:s").chr(13) );
        fclose($fp);*/
        $oInventory = oxNew( "jxInventory_SendOrder" );
        //$oInventory->logfile();
        $oInventory->sendOrder();
    }
}

/*class jxInventory_SendOrder extends jxInventory_SendOrder_Parent
{
    public function logifile()
    {
        $fp = fopen("w:/test/logfile.txt", "a+");
        fputs($fp,  '---'.date("H:i:s").chr(13) );
        fclose($fp);
        
    }
    
    public function sendorder()
    {
        //$oInventory = oxNew( "myinventory" );
        $oOrderArticles = $oOrder->getOrderArticles();
        $oOrderId = $oOrder->getId();
        foreach ( $oOrderArticles as $sOxid => $oArticle ) {
             if ( $oArticle->oxorderarticles__oxstorno->value != 1 ) {
                 $oInventory->updateInventory( $oOrderId, $oArticle->oxorderarticles__oxartid->value, $oArticle->oxorderarticles__oxamount->value );
             }
        }
        $oInventory->saveShippingAction( $oOrderId, $oOrder->oxorder__oxsenddate->value, $oOrder->oxorder__oxordernr->value );
        
    }
}*/

?>