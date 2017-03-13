'use strict';
var petsRarityLabel=['普通','高級','稀有','英雄','傳說'];
var nowTeamSet=[];
var teamSetLabel = ['訓練師','騎寵','前中','前左','前右','待命1','待命2'];

function displayDivMessage(msg) {
  this.msgbox = $('<div>').attr('class','modal').css('display','block');
  this.msgtxt = $('<div>').attr('class','modal-content').append(msg);
  this.close = $('<span>').attr({'class':'close','click':'void(0)'}).append('X');

  this.msgtxt.prepend(this.close);
  this.msgbox.append(this.msgtxt);
  $('body').append(this.msgbox);

  this.close.bind('click', function(){
    $(this).parent().parent().css('display','none');
  });

  var that = this.msgbox;
  $(window).bind('click', function(e){
    if(e.target==that)that.css('display','none');
  });
}

function appendImgIcon(dataAry) {
  this.ImgAry = dataAry;
  this.ParentDom = parent;
  this.keyImg = 'o';
  this.KeyIndex = 'i';
  this.BaseUrl = '';
}

appendImgIcon.prototype.AppendMultiImg = function(parent, style, limit) {
  if(!limit)limit=0;
  for(var i=0;i<Object.keys(this.ImgAry).length;i++){
    var con = $('<div>').attr({'onclick':'void(0)','data-id':this.ImgAry[i][this.KeyIndex],'class':'img-con'});
    var row = $('<img>').attr({'src':this.BaseUrl+this.ImgAry[i][this.keyImg], 'class':style});
    con.append(row);
    $(parent).append(con);
  }
};

function appendOptions(parent, label, maxvalue) {
  if(!label)label='';

  if(Array.isArray(maxvalue)){
    for(var i=0;i<maxvalue.length;i++){
      var row = $('<option>').attr('value',label+i).append(maxvalue[i]);
      $(parent).append(row);
    }
  }

  if(Number.isInteger(maxvalue)){
    for(var i=1;i<=maxvalue;i++){
      var row = $('<option>').attr('value',label+i).append(i);
      $(parent).append(row);
    }
  }

  var plzSelected = $('<option>').attr({'value':'-1','selected':true}).append('請選擇');
  $(parent).prepend(plzSelected);
}

function getObjAryIndex(obj, keyname) {
  var indexAry=[];
  for(var i=0;i<Object.keys(obj).length;i++){
    if(indexAry.indexOf(obj[i][keyname])!=-1)continue;
    indexAry.push(obj[i][keyname]);
  }
  return indexAry;
}

function addToTeamSet(teampos, petid) {
  /*chk if element has been created or not*/
  if(nowTeamSet[teampos]){
    /*if has been created then check the position of insert value*/

    if(nowTeamSet[teampos]==false){
      nowTeamSet[teampos]=true;
      nowTeamSet[teampos]=petid;
      return true;
    }else{
      if(teampos ==3 || teampos==5){
        if(!nowTeamSet[teampos+1] || nowTeamSet[teampos+1]==false){
          nowTeamSet[teampos+1]=true;
          nowTeamSet[teampos+1]=petid;
          return true;
        }
      }
    }

  }else{
    /*if not then creat element and push into its*/
    nowTeamSet[teampos]=true;
    nowTeamSet[teampos]=petid;
    return true;
  }
  return false;
}

function displayTeamSet(teamary, parent) {
  $(parent).empty();
  var petsRaw = $.grep(displayPets, function(i,v){return nowTeamSet.indexOf(i.i)!=-1;});
  /*[0] is the id of trainer*/
  for(var k=0;k<=teamary.length;k++){
    if(!teamary[k])continue;
    if(teamary[k]==false)continue;
    var imgicon = $.grep(petsRaw, function(i,v){
      return i.i==teamary[k];
    });
    var con = $('<div>').attr('class','team-cell');
    var label = $('<label>').append(teamSetLabel[k].toString());
    var imgSrc = k==0 ? saTrainer[parseInt(teamary[k])-1]['o'] : 'http://i.imgbox.com/'+imgicon[0]['o'];
    var img = $('<img>').attr({'src':imgSrc,'class':'img-icon img-rounded'});
    var conImg = $('<div>').append(img);
    var delBtn = $('<img>')
    .attr({'src':'/assets/images/delete.svg','class':'img-delbtn','onclick':'void(0)','data-pos':k})
    .bind('click',function(){
      var del = parseInt($(this).data('pos'));
      delFromTeamSet(del, parent);
      return false;
    });
    var conBtn = $('<div>').append(delBtn);

    con.prepend(label);
    con.append(conImg);
    con.append(conBtn);
    $(parent).append(con);

  }
}

function delFromTeamSet(pos, div) {
  if(pos==-1){
    nowTeamSet.fill(false);
  }else{
    nowTeamSet[pos]=false;
  }
  displayTeamSet(nowTeamSet, div);
}

function appendPetsSeriesOptions(assign) {
  var petPos = $('input[name=team-set-pos]:checked').val();
  var selVal = assign? assign : parseInt($('#select-pets-rarity').val())+1;
  var petsRaw = $.grep(displayPets, function(i,v){return i.r!=0&&i.r==selVal;});
  if(petPos==1)petsRaw = $.grep(petsRaw, function(i,v){return i.d==1;});
  $('#select-pets-by-rarity').empty();
  appendOptions('#select-pets-by-rarity','',getObjAryIndex(petsRaw,'s'));
  rebindImgIcon();
}

function rebindImgIcon() {
  /*bind click function and push into team ary*/
  $('div#pool-of-team-pets>div.img-con').off('click');
  $('div#pool-of-team-pets>div.img-con').bind('click',function(){
    var petPos = $('input[name=team-set-pos]:checked').val();
    var newpet = addToTeamSet(parseInt(petPos), parseInt($(this).data('id')));
    if(newpet) displayTeamSet(nowTeamSet,'#pool-of-now-team');
    return false;
  });
}