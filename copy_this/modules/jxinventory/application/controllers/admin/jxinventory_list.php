<?php
/**
 *    This file is part of the module jxInventory for OXID eShop Community Edition.
 *
 *    OXID eShop Community Edition is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    OXID eShop Community Edition is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with OXID eShop Community Edition.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      https://github.com/job963/jxInventory
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 * @copyright (C) Joachim Barthel 2012-2013
 * 
 */

class jxinventory_list extends Article_List
{
    protected $_sThisTemplateList = "jxinventory_list.tpl";
    protected $_sThisTemplateSummary = "jxinventory_summary.tpl";


    public function render()
    {
        parent::render();
        $oSmarty = oxUtilsView::getInstance()->getSmarty();
        $oSmarty->assign( "oViewConf", $this->_aViewData["oViewConf"]);
        $oSmarty->assign( "shop", $this->_aViewData["shop"]);

        $sWhere = "";
        if ( is_array( $aWhere = oxConfig::getParameter( 'jxwhere' ) ) ) {
            $sWhere = $this->_defineWhere( $aWhere );
        }
        
        $dispMode = oxConfig::getParameter( 'dispmode' );
        $sortCol = oxConfig::getParameter( 'sortcol' );
        
        if (empty($dispMode))
            $dispMode = 'details';

        if (empty($sortCol)) {
            if ($dispMode == 'details')
                $sortCol = 'oxartnum';
            else
                $sortCol = 'manutitle';
        }

        $sSort = $this->_defineOrderBy( $dispMode, $sortCol );

        $aInventory = array();
        $aInventory = $this->_retrieveData( $dispMode, $sWhere, $sSort );
        
        $oSmarty->assign("aInventory", $aInventory);
        $oSmarty->assign("aWhere", $aWhere);
        $oSmarty->assign("sortcol", $sortCol);
        $oSmarty->assign("dispmode", $dispMode);

        if ($dispMode == 'details')
            return $this->_sThisTemplateList;
        else
            return $this->_sThisTemplateSummary;
    }

    
    public function downloadResult()
    {
        $sWhere = "";
        if ( is_array( $aWhere = oxConfig::getParameter( 'jxwhere' ) ) ) {
            $sWhere = $this->_defineWhere( $aWhere );
        }
        
        $dispMode = oxConfig::getParameter( 'dispmode' );
        $sortCol = oxConfig::getParameter( 'sortcol' );
        
        if (empty($dispMode))
            $dispMode = 'details';

        if (empty($sortCol)) {
            if ($dispMode == 'details')
                $sortCol = 'oxartnum';
            else
                $sortCol = 'manutitle';
        }

        $sSort = $this->_defineOrderBy( $dispMode, $sortCol );

        $aInventory = array();
        $aInventory = $this->_retrieveData( $dispMode, $sWhere, $sSort );
        
        $sContent = '';
        foreach ($aInventory as $aProduct) {
            $sContent .= '"' . implode('","', $aProduct) . '"' . chr(13);
        }

        header("Content-Type: text/plain");
        header("content-length: ".strlen($sContent));
        if ($dispMode == 'details')
            header("Content-Disposition: attachment; filename=\"inventory-list.csv\"");
        else
            header("Content-Disposition: attachment; filename=\"inventory-summary.csv\"");
        echo $sContent;

        return;
    }

    
    public function copyInv2Shop()
    {
        $sId = oxConfig::getParameter( 'oxid' );
        
        $oDb = oxDb::getDb( oxDB::FETCH_MODE_ASSOC );
        
        $sSql = "SELECT jxinvstock FROM jxinvarticles WHERE jxartid = '$sId' ";
        $rs = $oDb->Execute($sSql);
        $iStock = $rs->fields['jxinvstock'];
        
        $sSql = "UPDATE oxarticles SET oxstock = $iStock WHERE oxid = '$sId' ";
        $rs = $oDb->Execute($sSql);
        
        return;
    }
    
    
    private function _defineWhere( $aWhere )
    {
        if ($aWhere['oxartnum'] != '')
            $sWhere .= "AND a.oxartnum LIKE '%".$aWhere['oxartnum']."%' ";
        if ($aWhere['oxtitle'] != '')
            $sWhere .= "AND IF(a.oxparentid = '', a.oxtitle, (SELECT b.oxtitle FROM oxarticles b where b.oxid = a.oxparentid)) LIKE '%".$aWhere['oxtitle']."%' ";
        if ($aWhere['oxvarselect'] != '')
            $sWhere .= "AND IF(a.oxvarselect = '', '-', a.oxvarselect) LIKE '%".$aWhere['oxvarselect']."%' ";
        if ($aWhere['invsite'] != '')
            $sWhere .= "AND s.jxinvsite LIKE '%".$aWhere['invsite']."%' ";
        if ($aWhere['invstore'] != '')
            $sWhere .= "AND s.jxinvstore LIKE '%".$aWhere['invstore']."%' ";
        
        return $sWhere;
    }

    
    private function _defineOrderBy( $dispMode, $sortCol )
    {
        if ($dispMode == 'details') {
            if ($sortCol == 'oxartnum')
                $sSort = "a.oxartnum";
            if ($sortCol == 'oxtitle')
                $sSort = "IF(a.oxparentid = '', a.oxtitle, (SELECT b.oxtitle FROM oxarticles b where b.oxid = a.oxparentid)), IF(a.oxvarselect = '', '-', a.oxvarselect)";
            if ($sortCol == 'oxvarselect')
                $sSort = "IF(a.oxvarselect = '', '-', a.oxvarselect), IF(a.oxparentid = '', a.oxtitle, (SELECT b.oxtitle FROM oxarticles b where b.oxid = a.oxparentid))";
            if ($sortCol == 'invsite')
                $sSort = "s.jxinvsite, s.jxinvstore, s.jxinvrack, s.jxinvlevel";
            if ($sortCol == 'invstore')
                $sSort = "s.jxinvstore, s.jxinvrack, s.jxinvlevel";
            }
        else {
            if ($sortCol == 'manutitle')
                $sSort = "m.oxtitle ASC";
            if ($sortCol == 'invstock')
                $sSort = "jxinvstock DESC";
            if ($sortCol == 'invbuysum')
                $sSort = "jxinvbuysum DESC";
        }

        return $sSort;
    }
    
    
    private function _retrieveData( $dispMode, $sWhere, $sSort )
    {
        if ($dispMode == 'details')
            $sSql = "SELECT "
                    . "a.oxid AS oxid, IF(a.oxparentid = '', a.oxtitle, (SELECT b.oxtitle FROM oxarticles b where b.oxid = a.oxparentid)) AS oxtitle, "
                    . "(IF(a.oxbprice=0,(SELECT b.oxbprice FROM oxarticles b where b.oxid = a.oxparentid),a.oxbprice) * jxinvstock) AS invbuysum, "
                    . "(a.oxprice * jxinvstock) AS invsellsum, "
                    . "IF(oxbprice=0,(SELECT b.oxbprice FROM oxarticles b where b.oxid = a.oxparentid),oxbprice) AS oxbprice, "
                    . "a.oxactive, a.oxartnum, IF(a.oxvarselect = '', '-', a.oxvarselect) AS oxvarselect, a.oxstock, a.oxstockflag, s.jxinvstock, s.jxinvsite, s.jxinvstore, s.jxinvrack, s.jxinvlevel "
                . "FROM oxarticles a, jxinvarticles s "
                . "WHERE a.oxid = s.jxartid "
                    . "AND oxvarcount = 0 "
                    . "AND jxinvstock != 0 "
                    . $sWhere
                . "ORDER BY $sSort ";
        else
            $sSql = "SELECT "
                    . "m.oxactive AS oxactive, m.oxtitle AS manutitle, "
                    . "SUM(IF(a.oxbprice=0,(SELECT b.oxbprice FROM oxarticles b where b.oxid = a.oxparentid),a.oxbprice) * jxinvstock) AS invbuysum, "
                    . "SUM(a.oxprice * jxinvstock) AS invsellsum, SUM(s.jxinvstock) AS invstock "
                . "FROM oxarticles a, jxinvarticles s, oxmanufacturers m "
                . "WHERE a.oxid = s.jxartid "
                        . " AND IF(a.oxparentid = '', a.oxmanufacturerid = m.oxid, (SELECT b.oxmanufacturerid FROM oxarticles b where b.oxid = a.oxparentid) = m.oxid)  "
                        . "AND jxinvstock != 0 "
                . "GROUP BY m.oxtitle "
                . "ORDER BY $sSort ";

        $aInventory = array();
        $oDb = oxDb::getDb( oxDB::FETCH_MODE_ASSOC );
        $rs = $oDb->Execute($sSql);
        if (!empty($rs)){
            while (!$rs->EOF) {
                array_push($aInventory, $rs->fields);
                $rs->MoveNext();
            }
        }
        
        return $aInventory;
    }
    
}


?>
