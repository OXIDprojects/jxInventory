<?php
/*
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

/**
 * Admin article inventory manager.
 * Collects such information about article as stock quantity, delivery status,
 * stock message, etc; Updates information (on user submit).
 * Admin Menu: Manage Products -> Articles -> Inventory.
 */
class Article_jxInventory extends oxAdminView
{
    protected $_sThisTemplate = "article_jxinventory.tpl";

    /**
     * Loads article inventory information, passes it to Smarty engine and
     * returns name of template file "article_jxinventory.tpl".
     *
     * @return string
     */
    public function render()
    {
        $myConfig = $this->getConfig();

        parent::render();

        $this->_aViewData["edit"] = $oArticle = oxNew( "oxarticle");

        $soxId = $this->getConfig()->getRequestParameter( "oxid");
        if ( $soxId != "-1" && isset( $soxId)) {

            // load object
            $oArticle->loadInLang( $this->_iEditLang, $soxId );

            // load object in other languages
            $oOtherLang = $oArticle->getAvailableInLangs();
            if (!isset($oOtherLang[$this->_iEditLang])) {
                // echo "language entry doesn't exist! using: ".key($oOtherLang);
                $oArticle->loadInLang( key($oOtherLang), $soxId );
            }

            foreach ( $oOtherLang as $id => $language) {
                $oLang= new oxStdClass();
                $oLang->sLangDesc = $language;
                $oLang->selected = ($id == $this->_iEditLang);
                $this->_aViewData["otherlang"][$id] =  clone $oLang;
            }


            // variant handling
            if ( $oArticle->oxarticles__oxparentid->value) {
                $oParentArticle = oxNew( "oxarticle");
                $oParentArticle->load( $oArticle->oxarticles__oxparentid->value);
                $this->_aViewData["parentarticle"] =  $oParentArticle;
                $this->_aViewData["oxparentid"] =  $oArticle->oxarticles__oxparentid->value;
            }

            $sShopID = $myConfig->getShopID();
            if ( $oArticle->oxarticles__oxvarcount->value == 0 ) {
                $sSql = "SELECT a.oxtitle, '-' AS oxvarselect, "
                        . "a.oxid, s.jxartid, oxartnum, a.oxstockflag, (a.oxbprice * s.jxinvstock) as invbuysum, "
                        . "s.jxinvstock, s.jxinvsite, s.jxinvstore, s.jxinvrack, s.jxinvlevel "
                        . "FROM oxarticles a, jxinvarticles s "
                        . "WHERE "
                            . "s.jxartid = '$soxId' "
                            . "AND a.oxshopid = '$sShopID' "
                            . "AND a.oxid = s.jxartid ";
                
            } else {
                $sSql = "SELECT "
                        . "(SELECT b.oxtitle FROM oxarticles b where b.oxid = '$soxId') AS oxtitle, "
                        . "(IF(oxbprice=0,(SELECT b.oxbprice FROM oxarticles b where b.oxid = '$soxId'),oxbprice) * jxinvstock) AS invbuysum, "
                        . "oxid, jxartid, oxartnum, oxvarselect, oxstockflag, jxinvsite, jxinvstore, jxinvrack, jxinvlevel, jxinvstock "
                        . "FROM oxarticles "
                        . "LEFT JOIN jxinvarticles ON oxarticles.oxid=jxinvarticles.jxartid "
                        . "WHERE oxparentid = '$soxId' " 
                            . "AND oxshopid = '$sShopID' "
                        . "ORDER BY oxsort";
            }
            
            $aInventory = array();

            $oDb = oxDb::getDb( oxDB::FETCH_MODE_ASSOC );
            $rs = $oDb->Execute($sSql);
            if (!empty($rs)){
                while (!$rs->EOF) {
                    array_push($aInventory, $rs->fields);
                    $rs->MoveNext();
                }
            }

            if (count($aInventory) == 0) {
                $sSql = "SELECT oxtitle, 0 AS invbuysum, oxid, '' AS invoxid, oxartnum, '-' AS oxvarselect, oxstockflag, "
                    . "'' as invsite, '' AS invstore, '' as invrack, '' AS invlevel, 0 AS invstock "
                    . "FROM oxarticles "
                    . "WHERE oxid = '$soxId' "
                        . "AND oxshopid = '$sShopID' ";
                $rs = $oDb->Execute($sSql);
                array_push($aInventory, $rs->fields);
            }
            $this->_aViewData["aInventory"] = $aInventory;
         
        }

        return $this->_sThisTemplate;
    }

    /**
     * Save all inventory data
     * 
     * @param type $sOXID
     * @param type $aParams 
     */
    public function saveinventory($sOXID = null, $aParams = null)
    {
        if ( !isset( $sOXID ) && !isset( $aParams ) ) {
            $sOXID   = $this->getConfig()->getRequestParameter( "voxid" );
            $aParams = $this->getConfig()->getRequestParameter( "editval" );
        }
        
        // varianthandling
        $soxparentId = $this->getConfig()->getRequestParameter( "oxid" );
        if ( isset( $soxparentId) && $soxparentId && $soxparentId != "-1" ) {
            $aParams['oxarticles__oxparentid'] = $soxparentId;
        } else {
            unset( $aParams['oxarticles__oxparentid'] );
        }
        
        $oDb = oxDb::getDb();
        
        $iRows = $this->getConfig()->getRequestParameter( "rownum" );
        for ($i = 1; $i <= $iRows; $i++) {
            $sInvID = $this->getConfig()->getRequestParameter( "invoxid_$i" );
            if (!empty($sInvID)) {
                // update the existing record
                $sSql = "UPDATE jxinvarticles "
                        . "SET "
                            . "jxinvsite = ".$oDb->quote($this->getConfig()->getRequestParameter( "invsite_$i" )).", "
                            . "jxinvstore = ".$oDb->quote($this->getConfig()->getRequestParameter( "invstore_$i" )).", "
                            . "jxinvrack = ".$oDb->quote($this->getConfig()->getRequestParameter( "invrack_$i" )).", "
                            . "jxinvlevel = ".$oDb->quote($this->getConfig()->getRequestParameter( "invlevel_$i" )).", "
                            . "jxinvstock = ".$oDb->quote($this->getConfig()->getRequestParameter( "invstock_$i" ))." "
                        . "WHERE "
                            . "jxartid = ".$oDb->quote($this->getConfig()->getRequestParameter( "oxid_$i" ))." ";
            } else {
                // insert a new record
                $sSql = "INSERT INTO jxinvarticles "
                        . "(jxartid, jxinvsite, jxinvstore, jxinvrack, jxinvlevel, jxinvstock) "
                        . "VALUES ("
                            . $oDb->quote($this->getConfig()->getRequestParameter( "oxid_$i" )) . ", "
                            . $oDb->quote($this->getConfig()->getRequestParameter( "invsite_$i" )) . ", "
                            . $oDb->quote($this->getConfig()->getRequestParameter( "invstore_$i" )) . ", "
                            . $oDb->quote($this->getConfig()->getRequestParameter( "invrack_$i" )) . ", "
                            . $oDb->quote($this->getConfig()->getRequestParameter( "invlevel_$i" )) . ", "
                            . $oDb->quote($this->getConfig()->getRequestParameter( "invstock_$i" )) 
                            . ") ";
            }
            $oDb->execute($sSql);
        }

    }
    

}

?>
