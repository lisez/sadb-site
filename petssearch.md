---
layout: page
title: "寵物圖鑑"
permalink: /petssearch/
comments: false
---
<script type="text/javascript">
$(document).ready(function(){
  /*
  $('.petsSearchFilterOptions').click(function(){
    var _filter = $(this).attr('name');
    var _checked = $(this).prop('checked');
    var _target = $('div[data-'+_filter+'^='+$(this).val()+']>a>div');
    if(_checked==true){
      _target.addClass('selecting');
    }else{
      _target.removeClass('selecting');
    }
  });*/
  $('.petsSearchFilterOptions').click(function(){
    /*設定變數*/
    var _indexAry = [];
    var _finalCon = [];
    var _filterCon = [];
    /*搜集條件*/
    $('.petsSearchFilterOptions').each(function(index, el){
      if($(el).prop('checked')==false)return;
      if(_indexAry.indexOf($(el).attr('name'))==-1)_indexAry.push($(el).attr('name'));
      var _theIndex=_indexAry.indexOf($(el).attr('name'));
      if(typeof _filterCon[_theIndex]=='undefined')_filterCon[_theIndex]=[];
      _filterCon[_theIndex].push('[data-'+$(el).attr('name')+'^='+$(el).val()+']');
    });
    /*彙整條件*/
    for (var i = 0; i < _filterCon.length; i++) {
      _finalCon.push(_filterCon[i].join(','));
    }
    console.log(_filterCon);
    if(_filterCon!=''){
      $('#pets-content>div'+_finalCon.join('')).show();
      $('#pets-content>div:not('+_finalCon.join('')+')').hide();
    }else{
      $('#pets-content>div').show();
    }
  });
});
</script>
<div id="petsSearchFilter" class="div-table">
  <div class="div-table-tr">
    <div class="div-table-th" style="width:20%">屬性</div>
    <div class="div-table-td">
      <label><input name="pets-element" type="checkbox" value="地" class="petsSearchFilterOptions" />地</label>
      <label><input name="pets-element" type="checkbox" value="水" class="petsSearchFilterOptions" />水</label>
      <label><input name="pets-element" type="checkbox" value="火" class="petsSearchFilterOptions" />火</label>
      <label><input name="pets-element" type="checkbox" value="風" class="petsSearchFilterOptions" />風</label>
    </div>
  </div>
  <div class="div-table-tr">
    <div class="div-table-th">類型</div>
    <div class="div-table-td">
      <label><input name="pets-type" type="checkbox" value="攻擊型" class="petsSearchFilterOptions" />攻擊型</label>
      <label><input name="pets-type" type="checkbox" value="範圍型" class="petsSearchFilterOptions" />範圍型</label>
      <label><input name="pets-type" type="checkbox" value="控制型" class="petsSearchFilterOptions" />控制型</label>
      <label><input name="pets-type" type="checkbox" value="防禦型" class="petsSearchFilterOptions" />防禦型</label>
      <label><input name="pets-type" type="checkbox" value="支援型" class="petsSearchFilterOptions" />支援型</label>
      <label><input name="pets-type" type="checkbox" value="治癒型" class="petsSearchFilterOptions" />治癒型</label>
    </div>
  </div>
  <div class="div-table-tr">
    <div class="div-table-th">騎乘</div>
    <div class="div-table-td">
      <label><input name="pets-riding" type="checkbox" value="1" class="petsSearchFilterOptions" />可騎乘</label>
      <label><input name="pets-riding" type="checkbox" value="0" class="petsSearchFilterOptions" />不可騎乘</label>
    </div>
  </div>
</div>

{% include petssearch.html %}
