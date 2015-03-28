[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign box=" "}]

<script type="text/javascript">
  if(top)
  {
    top.sMenuItem    = "[{ oxmultilang ident="mxmanageprod" }]";
    top.sMenuSubItem = "[{ oxmultilang ident="jxinventory_displaylist" }]";
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
            value=" [{ oxmultilang ident="JXINVENTORY_DOWNLOAD" }] " [{ $readonly }]>
    </h2>
    <div style="position:absolute;top:4px;right:8px;color:gray;font-size:0.9em;border:1px solid gray;border-radius:3px;">
        &nbsp;[{$sModuleId}]&nbsp;[{$sModuleVersion}]&nbsp;
    </div>
          
    [{assign var="totalbuysum" value="0.0"}]
    [{assign var="totalsellsum" value="0.0"}]
    [{assign var="stocksum" value="0"}]
    <div id="liste">
    
    <form name="transfer" id="transfer" action="[{ $shop->selflink }]" method="post">
        [{ $shop->hiddensid }]
        <input type="hidden" name="oxid" value="[{ $oxid }]">
        <input type="hidden" name="cl" value="article">
        <input type="hidden" name="updatelist" value="1">
    </form>
        
    <form name="showinv" id="showinv" action="[{ $shop->selflink }]" method="post">
    [{ $shop->hiddensid }]
    <input type="hidden" name="cl" value="jxinventory_list">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="sortcol" value="[{ $sortcol }]">
    <input type="hidden" name="dispmode" value="[{ $dispmode }]">
    <input type="hidden" name="language" value="[{ $actlang }]">
    <input type="hidden" name="editlanguage" value="[{ $actlang }]">
	
    <div style="position:absolute; top:20px; left:300px;">
        <a href="javascript:document.forms.showinv.fnc.value='';document.forms.showinv.sortcol.value='';document.forms.showinv.dispmode.value='details';document.forms.showinv.submit();" class="listheader">
            <span style="">[{ oxmultilang ident="JXINVENTORY_SHOWDETAILS" }]</span>
        </a>
    </div>

    <table cellspacing="0" cellpadding="0" border="0" [{*width="99%"*}]>
        <tr>
            <td valign="top" class="listfilter first" align="right">
                <div class="r1"><div class="b1">&nbsp;</div></div>
            </td>
            <td class="listfilter"><div class="r1"><div class="b1">
                <input class="listedit" type="text" size="15" maxlength="128" name="jxwhere[oxartnum]" value="[{ $aWhere.oxartnum }]">
                </div></div></td>
            <td class="listfilter"><div class="r1"><div class="b1">
                [{*<input class="listedit" type="text" size="15" maxlength="128" name="jxwhere[oxtitle]" value="[{ $aWhere.oxtitle }]">*}]
                </div></div></td>
            <td class="listfilter"><div class="r1"><div class="b1"></td>
            <td class="listfilter"><div class="r1"><div class="b1"><div class="find">
                <input class="listedit" type="submit" name="submitit" value="[{ oxmultilang ident="GENERAL_SEARCH" }]">
                </div></div></div></td>
        </tr>
        <tr>
            <td class="listheader first" height="15" width="30" align="center">
                [{ oxmultilang ident="GENERAL_ACTIVTITLE" }]
			</td>
            <td class="listheader">
                <a href="javascript:document.forms.showinv.sortcol.value='manutitle';document.forms.showinv.submit();" class="listheader">
                    Hersteller[{* oxmultilang ident="GENERAL_ARTNUM" *}]
                </a>
                &nbsp;&nbsp;[{ if $sortcol == 'manutitle' }]<span style="font-size:1.5em;">&blacktriangle;</span>[{/if}]
            </td>
            <td class="listheader">
                <a href="javascript:document.forms.showinv.sortcol.value='invstock';document.forms.showinv.submit();" class="listheader">
                    Anzahl Artikel[{* oxmultilang ident="GENERAL_TITLE" *}]
                </a>
                &nbsp;&nbsp;[{ if $sortcol == 'invstock' }]<span style="font-size:1.5em;">&blacktriangle;</span>[{/if}]
            </td>
            <td class="listheader">
                <a href="javascript:document.forms.showinv.sortcol.value='invbuysum';document.forms.showinv.submit();" class="listheader">
                    EK (netto)[{* oxmultilang ident="tbclarticle_variant" *}]
                </a>
                &nbsp;&nbsp;[{ if $sortcol == 'invbuysum' }]<span style="font-size:1.5em;">&blacktriangle;</span>[{/if}]
            </td>
            <td class="listheader">
				VK (brutto)&nbsp;&nbsp;[{* oxmultilang ident="GENERAL_ARTICLE_OXSTOCK" *}]
			</td>
        </tr>
        [{foreach name=outer item=Inventory from=$aInventory}]
            [{ cycle values="listitem,listitem2" assign="listclass" }]
            [{assign var="rownum" value=$rownum+1}]
            [{ if $Inventory.invstock > 0 }]
                [{assign var="totalbuysum" value=$totalbuysum+$Inventory.invbuysum}]
                [{assign var="totalsellsum" value=$totalsellsum+$Inventory.invsellsum}]
                [{assign var="stocksum" value=$stocksum+$Inventory.invstock}]
            [{/if}]
            <tr>
                <td valign="top" class="[{ $listclass}][{ if $Inventory.oxactive == 1}] active[{/if}]" height="15">
                    <div class="listitemfloating">&nbsp</a></div>
				</td>
                <td class="[{ $listclass }]">
                    <input type="hidden" name="oxid_[{$rownum}]" value="[{$Inventory.oxid}]">
                    <input type="hidden" name="invoxid_[{$rownum}]" value="[{$Inventory.invoxid}]">
                    <a href="Javascript:editThis('[{$Inventory.oxid}]');">[{ $Inventory.manutitle }]</a>
                </td>
                <td class="[{ $listclass }]" style="text-align:right; padding-right:20px;">[{ $Inventory.invstock }]</td>
                <td class="[{ $listclass }]" style="text-align:right; padding-right:20px;">[{ $Inventory.invbuysum|string_format:"%.2f" }]</td>
                <td class="[{ $listclass }]" style="text-align:right; padding-right:20px;">[{ $Inventory.invsellsum|string_format:"%.2f" }]</td>
                [{*<td>[{ $Inventory.invamount }]</td>*}]
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
