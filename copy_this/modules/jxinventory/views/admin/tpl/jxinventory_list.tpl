[{*debug*}]
[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign box=" "}]

<script type="text/javascript">
  if(top)
  {
    top.sMenuItem    = "[{ oxmultilang ident="custservice_module" }]";
    top.sMenuSubItem = "[{ oxmultilang ident="custservice_menu" }]";
    top.sWorkArea    = "[{$_act}]";
    top.setTitle();
  }

function editThis( sID )
{
    [{assign var="shMen" value=1}]

    [{foreach from=$menustructure item=menuholder }]
      [{if $shMen && $menuholder->nodeType == XML_ELEMENT_NODE && $menuholder->childNodes->length }]

        [{assign var="shMen" value=0}]
        [{assign var="mn" value=1}]

        [{foreach from=$menuholder->childNodes item=menuitem }]
          [{if $menuitem->nodeType == XML_ELEMENT_NODE && $menuitem->childNodes->length }]
            [{ if $menuitem->getAttribute('id') == 'mxorders' }]

              [{foreach from=$menuitem->childNodes item=submenuitem }]
                [{if $submenuitem->nodeType == XML_ELEMENT_NODE && $submenuitem->getAttribute('cl') == 'admin_order' }]

                    if ( top && top.navigation && top.navigation.adminnav ) {
                        var _sbli = top.navigation.adminnav.document.getElementById( 'nav-1-[{$mn}]-1' );
                        var _sba = _sbli.getElementsByTagName( 'a' );
                        top.navigation.adminnav._navAct( _sba[0] );
                    }

                [{/if}]
              [{/foreach}]

            [{ /if }]
            [{assign var="mn" value=$mn+1}]

          [{/if}]
        [{/foreach}]
      [{/if}]
    [{/foreach}]

    var oTransfer = document.getElementById("transfer");
    oTransfer.oxid.value=sID;
    oTransfer.cl.value='article';
    oTransfer.submit();
}

</script>

<div class="center">

    <form name="transfer" id="transfer" action="[{ $shop->selflink }]" method="post">
        [{ $shop->hiddensid }]
        <input type="hidden" name="oxid" value="[{ $oxid }]">
        <input type="hidden" name="cl" value="">
        <input type="hidden" name="updatelist" value="1">
        <input type="hidden" name="language" value="[{ $actlang }]">
        <input type="hidden" name="editlanguage" value="[{ $actlang }]">
    </form>

    <h2>[{ oxmultilang ident="JXINVENTORY_STORELIST" }]
        <input class="edittext" style="position:relative; top:-2px;" type="submit" 
            onClick="javascript:document.forms['showinv'].elements['fnc'].value = 'downloadResult';" 
            value=" [{ oxmultilang ident="JXSALES_DOWNLOAD" }] " [{ $readonly }]>
    </h2>
          
    [{assign var="totalbuysum" value="0.0"}]
    [{assign var="totalsellsum" value="0.0"}]
    [{assign var="stocksum" value="0"}]
    <div id="liste">
    
    <form name="showinv" id="showinv" action="[{ $shop->selflink }]" method="post">
    [{ $shop->hiddensid }]
    <input type="hidden" name="cl" value="jxinventory_list">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="sortcol" value="[{ $sortcol }]">
    <input type="hidden" name="dispmode" value="[{ $dispmode }]">
    <input type="hidden" name="oxid" value="">
    <input type="hidden" name="language" value="[{ $actlang }]">
    <input type="hidden" name="editlanguage" value="[{ $actlang }]">
	
    <div style="position:absolute; top:20px; left:300px;">
        <a href="javascript:document.forms.showinv.fnc.value='';document.forms.showinv.sortcol.value='';document.forms.showinv.dispmode.value='summary';document.forms.showinv.submit();" class="listheader">
            <span style="">[{ oxmultilang ident="JXINVENTORY_SHOWSUMMARY" }]</span>
        </a>
    </div>

    <table cellspacing="0" cellpadding="0" border="0" width="99%">
        <tr>
            <td valign="top" class="listfilter first" align="right">
                <div class="r1"><div class="b1">&nbsp;</div></div>
            </td>
            <td class="listfilter"><div class="r1"><div class="b1">
                <input class="listedit" type="text" size="10" maxlength="128" name="jxwhere[oxartnum]" value="[{ $aWhere.oxartnum }]">
                </div></div></td>
            <td class="listfilter"><div class="r1"><div class="b1">
                <input class="listedit" type="text" size="20" maxlength="128" name="jxwhere[oxtitle]" value="[{ $aWhere.oxtitle }]">
                </div></div></td>
            <td class="listfilter"><div class="r1"><div class="b1">
                <input class="listedit" type="text" size="12" maxlength="128" name="jxwhere[oxvarselect]" value="[{ $aWhere.oxvarselect }]">
                </div></div></td>
            <td class="listfilter"><div class="r1"><div class="b1"></div></div></td>
            <td class="listfilter"><div class="r1"><div class="b1"></div></div></td>
            <td class="listfilter"><div class="r1"><div class="b1"></div></div></td>
            <td class="listfilter"><div class="r1"><div class="b1"></div></div></td>
            <td class="listfilter"><div class="r1"><div class="b1">
                <input class="listedit" type="text" size="10" maxlength="128" name="jxwhere[invsite]" value="[{ $aWhere.invsite }]">
                </div></div></td>
            <td class="listfilter"><div class="r1"><div class="b1">
                <input class="listedit" type="text" size="10" maxlength="128" name="jxwhere[invstore]" value="[{ $aWhere.invstore }]">
                </div></div></td>
            <td class="listfilter"><div class="r1"><div class="b1"></div></div></td>
            <td class="listfilter"><div class="r1"><div class="b1"><div class="find">
                <input class="listedit" type="submit" name="submitit" value="[{ oxmultilang ident="GENERAL_SEARCH" }]">
                </div></div></div></td>
        </tr>
        <tr>
            <td class="listheader first" height="15" width="30" align="center">
                [{ oxmultilang ident="GENERAL_ACTIVTITLE" }]
            </td>
            <td class="listheader">
                <a href="javascript:document.forms.showinv.sortcol.value='oxartnum';document.forms.showinv.submit();" class="listheader">
                    [{ oxmultilang ident="GENERAL_ARTNUM" }]
                </a>
                [{ if $sortcol == 'oxartnum' }]<span style="font-size:1.5em;">&nbsp;&nbsp;&blacktriangle;</span>[{/if}]
            </td>
            <td class="listheader">
                <a href="javascript:document.forms.showinv.sortcol.value='oxtitle';document.forms.showinv.submit();" class="listheader">
                    [{ oxmultilang ident="GENERAL_TITLE" }]
                </a>
                [{ if $sortcol == 'oxtitle' }]<span style="font-size:1.5em;">&nbsp;&nbsp;&blacktriangle;</span>[{/if}]
            </td>
            <td class="listheader">
                <a href="javascript:document.forms.showinv.sortcol.value='oxvarselect';document.forms.showinv.submit();" class="listheader">
                    [{ oxmultilang ident="tbclarticle_variant" }]
                </a>
                [{ if $sortcol == 'oxvarselect' }]<span style="font-size:1.5em;">&nbsp;&nbsp;&nbsp;&blacktriangle;</span>[{/if}]
            </td>
            <td class="listheader" style="text-align:right; padding-right:20px;">
                <table cellspacing="0" cellpadding="0" width="100%" border="0"><tr>
                    <td width="35%">[{ oxmultilang ident="JXINVENTORY_INVENTORY" }]</td>
                    <td>/&nbsp;&nbsp;</td>
                    <td width="35%">[{ oxmultilang ident="JXINVENTORY_SHOP" }]</td>
                </tr></table>
            </td>
            <td class="listheader">[{ oxmultilang ident="JXINVENTORY_BUYVALUE" }]</td>
            <td class="listheader">[{ oxmultilang ident="JXINVENTORY_SELLVALUE" }]</td>
            <td class="listheader">[{ oxmultilang ident="GENERAL_ARTICLE_OXSTOCKFLAG" }]</td>
            <td class="listheader">
                <a href="javascript:document.forms.showinv.sortcol.value='invsite';document.forms.showinv.submit();" class="listheader">
                    [{ oxmultilang ident="JXINVENTORY_SITE" }]
                </a>
                [{ if $sortcol == 'invsite' }]<span style="font-size:1.5em;">&nbsp;&nbsp;&blacktriangle;</span>[{/if}]
            </td>
            <td class="listheader">
                <a href="javascript:document.forms.showinv.sortcol.value='invstore';document.forms.showinv.submit();" class="listheader">
                    [{ oxmultilang ident="JXINVENTORY_STORE" }]
                </a>
                [{ if $sortcol == 'invstore' }]<span style="font-size:1.5em;">&nbsp;&nbsp;&blacktriangle;</span>[{/if}]
            </td>
            <td class="listheader">[{ oxmultilang ident="JXINVENTORY_RACK" }]</td>
            <td class="listheader">[{ oxmultilang ident="JXINVENTORY_LEVEL" }]</td>
        </tr>
        [{foreach name=outer item=Inventory from=$aInventory}]
            [{ cycle values="listitem,listitem2" assign="listclass" }]
            [{assign var="rownum" value=$rownum+1}]
            [{ if $Inventory.jxinvstock > 0 }]
                [{assign var="totalbuysum" value=$totalbuysum+$Inventory.invbuysum}]
                [{assign var="totalsellsum" value=$totalsellsum+$Inventory.invsellsum}]
                [{assign var="stocksum" value=$stocksum+$Inventory.jxinvstock}]
            [{/if}]
            <tr>
                <td valign="top" class="[{ $listclass}][{ if $Inventory.oxactive == 1}] active[{/if}]" height="15">
                    <div class="listitemfloating">&nbsp</a></div>
				</td>
                <td class="[{ $listclass }]">
                    <input type="hidden" name="oxid_[{$rownum}]" value="[{$Inventory.oxid}]">
                    <input type="hidden" name="invoxid_[{$rownum}]" value="[{$Inventory.jxartid}]">
                    <a href="Javascript:editThis('[{$Inventory.oxid}]');">[{ $Inventory.oxartnum }]</a>
                </td>
                <td class="[{ $listclass }]"><a href="Javascript:editThis('[{$Inventory.oxid}]');">[{ $Inventory.oxtitle }]</a></td>
                <td class="[{ $listclass }]"><a href="Javascript:editThis('[{$Inventory.oxid}]');">[{ $Inventory.oxvarselect }]</a></td>
                <td class="[{ $listclass }]" style="text-align:right; padding-right:10px;">
                    <table cellspacing="0" cellpadding="0" width="100%" border="0"><tr>
                        <td width="35%"><span style="[{if $Inventory.jxinvstock < 0}]color:#ff0000;[{/if}]">[{ $Inventory.jxinvstock }]&nbsp;</span></td>
                        <td align="right">
                            <div style="background-color:#323232; border-radius:3px;height:14px;width:18px;padding-top:-2px">
                                <a href="javascript:javascript:document.forms['showinv'].elements['oxid'].value = '[{$Inventory.oxid}]';document.forms['showinv'].elements['fnc'].value = 'copyInv2Shop';document.forms.showinv.submit();" class="listheader">
                                    <span style="color:#fff;position:relative;top:-2px;font-size:0.9em;">&#10140;&nbsp;</span>
                                </a>
                            </div>
                        </td>
                        <td width="35%">(<span style="[{if $Inventory.oxstock <= 0}]color:#ff0000;[{/if}]">[{ $Inventory.oxstock }]</span>)</td>
                    </tr></table>
                </td>
                <td class="[{ $listclass }]" style="text-align:right; padding-right:10px;"><span style="[{if $Inventory.invbuysum <= 0.0}]color:#ff0000;[{/if}]">[{ $Inventory.invbuysum|string_format:"%.2f" }]</span></td>
                <td class="[{ $listclass }]" style="text-align:right; padding-right:10px;"><span style="[{if $Inventory.invsellsum <= 0.0}]color:#ff0000;[{/if}]">[{ $Inventory.invsellsum|string_format:"%.2f" }]</span></td>
                <td class="[{ $listclass }]">
                    [{ if $Inventory.oxstockflag == 1 }][{ oxmultilang ident="GENERAL_STANDARD" }][{/if}]
                    [{ if $Inventory.oxstockflag == 2 }][{ oxmultilang ident="GENERAL_OFFLINE" }][{/if}]
                    [{ if $Inventory.oxstockflag == 3 }][{ oxmultilang ident="GENERAL_NONORDER" }][{/if}]
                    [{ if $Inventory.oxstockflag == 4 }][{ oxmultilang ident="GENERAL_EXTERNALSTOCK" }][{/if}]
                </td>
                <td class="[{ $listclass }]">[{ $Inventory.jxinvsite }]</td>
                <td class="[{ $listclass }]">[{ $Inventory.jxinvstore }]</td>
                <td class="[{ $listclass }]">[{ $Inventory.jxinvrack }]</td>
                <td class="[{ $listclass }]">[{ $Inventory.jxinvlevel }]</td>
            </tr>
        [{/foreach}]
    </table>
    </form>
    </div>
        
    <h3><br />[{ oxmultilang ident="JXINVENTORY_SUMMARY" }]</h3>
    <table>
        <tr>
            <td>[{ oxmultilang ident="JXINVENTORY_ITEMSUM" }]:&nbsp;</td>
            <td align="right"><b>[{ $stocksum }]</b>&nbsp;</td>
            <td align="right"></td>
            <td align="left"></td>
        </tr>
        <tr>
            <td>[{ oxmultilang ident="JXINVENTORY_BUYSUM" }]:&nbsp;</td>
            <td align="right"><b>[{ $totalbuysum|string_format:"%.2f" }]</b>&nbsp;</td>
            <td align="right">[{math equation="( val * tax )" val=$totalbuysum tax=1.19 format="%.2f"}]</td>
            <td align="left">[{ oxmultilang ident="ORDER_OVERVIEW_PDF_BRUTTO" }]</td>
        </tr>
        <tr>
            <td>[{ oxmultilang ident="JXINVENTORY_SELLSUM" }]:&nbsp;</td>
            <td align="right"><b>[{ $totalsellsum|string_format:"%.2f" }]</b>&nbsp;</td>
            <td align="right">[{math equation="( val / tax )" val=$totalsellsum tax=1.19 format="%.2f"}]</td>
            <td align="left">[{ oxmultilang ident="ORDER_OVERVIEW_PDF_NETTO" }]</td>
        </tr>

    </table>

    


</div>
