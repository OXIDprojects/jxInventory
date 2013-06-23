[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]


<script type="text/javascript">
<!--
function loadLang(obj)
{
    var langvar = document.getElementById("agblang");
    if (langvar != null )
        langvar.value = obj.value;
    document.myedit.submit();
}
function editThis( sID )
{
    var oTransfer = top.basefrm.edit.document.getElementById( "transfer" );
    oTransfer.oxid.value = sID;
    oTransfer.cl.value = top.basefrm.list.sDefClass;

    //forcing edit frame to reload after submit
    top.forceReloadingEditFrame();

    var oSearch = top.basefrm.list.document.getElementById( "search" );
    oSearch.oxid.value = sID;
    oSearch.actedit.value = 0;
    oSearch.submit();
}
//-->
</script>

[{ if $readonly }]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]


<form name="transfer" id="transfer" action="[{ $shop->selflink }]" method="post">
    [{ $shop->hiddensid }]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="article_inventory">
    <input type="hidden" name="editlanguage" value="[{ $editlanguage }]">
</form>


[{*debug*}]
    <h3>[{ oxmultilang ident="JXINVENTORY_SUMMARY" }]</h3>
    <form name="invedit" id="invedit" action="[{ $shop->selflink }]" method="post">
    [{ $shop->hiddensid }]
    <input type="hidden" name="editlanguage" value="[{ $editlanguage }]">
    <input type="hidden" name="cl" value="article_jxinventory">
    <input type="hidden" name="fnc" value="saveinventory">
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="parentvarname" value="[{$edit->oxarticles__oxvarname->value}]">
    [{ assign var="onChangeStyle" value="this.style.color='blue';this.style.border='1px solid blue';" }]
          
    <table cellspacing="0" cellpadding="0" border="0" style="width:100%;">
        <tr>
            <td class="listheader">[{ oxmultilang ident="GENERAL_ARTNUM" }]</td>
            <td class="listheader">[{ oxmultilang ident="tbclarticle_variant" }]</td>
            <td class="listheader">[{ oxmultilang ident="GENERAL_ARTICLE_OXSTOCK" }]</td>
            <td class="listheader">[{ oxmultilang ident="PAYMENT_MAIN_AMOUNT" }]</td>
            [{*<th>Amount</th>*}]
            <td class="listheader">[{ oxmultilang ident="GENERAL_ARTICLE_OXSTOCKFLAG" }]</td>
            <td class="listheader">[{ oxmultilang ident="JXINVENTORY_SITE" }]</td>
            <td class="listheader">[{ oxmultilang ident="JXINVENTORY_STORE" }]</td>
            <td class="listheader">[{ oxmultilang ident="JXINVENTORY_RACK" }]</td>
            <td class="listheader">[{ oxmultilang ident="JXINVENTORY_LEVEL" }]</td>
        </tr>
        [{foreach name=outer item=InvItem from=$aInventory}]
            [{ cycle values="listitem,listitem2" assign="listclass" }]
            [{ assign var="rownum" value=$rownum+1 }]
            <tr>
                <td class="[{ $listclass }]">
                    <input type="hidden" name="oxid_[{$rownum}]" value="[{$InvItem.oxid}]">
                    <input type="hidden" name="invoxid_[{$rownum}]" value="[{$InvItem.jxartid}]">
                    [{ $InvItem.oxartnum }]
                </td>
                <td class="[{ $listclass }]">[{ $InvItem.oxvarselect }]</td>
                <td class="[{ $listclass }]"><input type="text" size="7" maxlength="10" name="invstock_[{$rownum}]" value="[{ $InvItem.jxinvstock }]" onChange="[{ $onChangeStyle }]"></td>
                <td class="[{ $listclass }]" style="text-align:right; padding-right:20px;">[{ $InvItem.invbuysum|string_format:"%.2f" }]</td>
                [{*<td>[{ $InvItem.invamount }]</td>*}]
                <td class="[{ $listclass }]">
                    [{ if $InvItem.oxstockflag == 1 }][{ oxmultilang ident="GENERAL_STANDARD" }][{/if}]
                    [{ if $InvItem.oxstockflag == 2 }][{ oxmultilang ident="GENERAL_OFFLINE" }][{/if}]
                    [{ if $InvItem.oxstockflag == 3 }][{ oxmultilang ident="GENERAL_NONORDER" }][{/if}]
                    [{ if $InvItem.oxstockflag == 4 }][{ oxmultilang ident="GENERAL_EXTERNALSTOCK" }][{/if}]
                </td>
                <td class="[{ $listclass }]"><input type="text" size="25" maxlength="255" name="invsite_[{$rownum}]" value="[{ $InvItem.jxinvsite }]" onChange="[{ $onChangeStyle }]"></td>
                <td class="[{ $listclass }]"><input type="[{ if $InvItem.oxstockflag != 4 }]text[{else}]hidden[{/if}]" size="25" maxlength="255" name="invstore_[{$rownum}]" value="[{ $InvItem.jxinvstore }]" onChange="[{ $onChangeStyle }]"></td>
                <td class="[{ $listclass }]"><input type="[{ if $InvItem.oxstockflag != 4 }]text[{else}]hidden[{/if}]" size="10" maxlength="255" name="invrack_[{$rownum}]" value="[{ $InvItem.jxinvrack }]" onChange="[{ $onChangeStyle }]"></td>
                <td class="[{ $listclass }]"><input type="[{ if $InvItem.oxstockflag != 4 }]text[{else}]hidden[{/if}]" size="10" maxlength="255" name="invlevel_[{$rownum}]" value="[{ $InvItem.jxinvlevel }]" onChange="[{ $onChangeStyle }]"></td>
            </tr>
        [{/foreach}]
        <input type="hidden" name="rownum" value="[{$rownum}]">

        <tr>
            <td colspan="9" align="right">&nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="9" align="right">
              <input class="edittext" type="submit" 
                     onClick="document.forms['invedit'].elements['parentvarname'].value = document.forms['search'].elements['editval[oxarticles__oxvarname]'].value;" 
                     value=" [{ oxmultilang ident="JXINVENTORY_INVSAVE" }]" [{ $readonly }]>
            </td>
        </tr>
        </form>

    </table>


[{include file="bottomnaviitem.tpl"}]

[{include file="bottomitem.tpl"}]