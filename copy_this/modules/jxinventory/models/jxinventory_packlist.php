<?php

class jxinventory_packlist extends jxinventory_packlist_parent // oxOrderArticle
{
    /**
     * Executes parent method parent::render(), fetches order info from DB,
     * passes it to Smarty engine and returns name of template file.
     * "order_package.tpl"
     *
     * @return string
     */
    /*public function render()
    {
        $myConfig = $this->getConfig();
        parent::render();

        $aOrders = oxNew('oxlist');
        $aOrders->init('oxorder');
        $aOrders->selectString( "select * from oxorder where oxorder.oxsenddate = '0000-00-00 00:00:00' and oxorder.oxshopid = '".$myConfig->getShopId()."' order by oxorder.oxorderdate asc limit 5000" );

        $this->_aViewData['resultset'] = $aOrders;

        return "order_package.tpl";
    }*/
    
    public function jxGetInventoryLocation()
    {

        $sSql = "SELECT * FROM jxinvarticles, oxorderarticles WHERE oxid = '$this->oxorderarticles__oxid' AND jxartid = oxartid ";
        //echo $sSql;

        $oDb = oxDb::getDb( oxDB::FETCH_MODE_ASSOC );
        $rs = $oDb->Execute($sSql);
        //echo '<pre>'.print_r($rs).'</pre>';

        if (!$rs->EOF) {
            $aTemp = array();
            array_push($aTemp, $rs->fields);
            $aInventoryLocation['Site']  = $aTemp[0]['jxinvsite'];
            $aInventoryLocation['Store'] = $aTemp[0]['jxinvstore'];
            $aInventoryLocation['Rack']  = $aTemp[0]['jxinvrack'];
            $aInventoryLocation['Level'] = $aTemp[0]['jxinvlevel'];
            return $aInventoryLocation;
        }
        else {
            return null;
        }

        //return $aInventoryLocation;
        return;
    }
}

?>