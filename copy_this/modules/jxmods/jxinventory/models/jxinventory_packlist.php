<?php
/*
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

class jxinventory_packlist extends jxinventory_packlist_parent 
{

    public function jxGetInventoryLocation()
    {

        $sSql = "SELECT * FROM jxinvarticles, oxorderarticles WHERE oxid = '$this->oxorderarticles__oxid' AND jxartid = oxartid ";

        $oDb = oxDb::getDb( oxDB::FETCH_MODE_ASSOC );
        $rs = $oDb->Execute($sSql);

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

        return;
    }
}

?>