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
 * @copyright (C) 2012-2015 Joachim Barthel
 * @author    Joachim Barthel <jobarthel@gmail.com>
 * 
 */

class jxInventory_OrderOverview extends order_overview
{
    public function sendorder()
    {
        $result = parent::sendorder();

        $oInventory = oxNew( "jxInventory_SendOrder" );

        $oInventory->sendOrder();
    }
}

?>