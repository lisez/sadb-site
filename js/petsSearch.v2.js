/* lisez
 * v2.0.2
 */

'use strict';

var allpets = getAllPets;

/*寵物種類篩選*/
function petsFilter(type) {
  /*寵物種類索引 */
  var typeTxt		= ['首領','普通','高級','稀有','英雄','傳說'];
  var skipEle   = ['地地','水水','火火','風風'];
  var typeTerm	= typeTxt.indexOf(type);
  var urlOnline = '/pets-';

  /*清空顯示頁面 */
  $("#pets-content").empty();

  /*篩選資料*/
  var data = $.grep(allpets, function(k,v){
    if(typeTerm==0)return k.r===typeTerm;
    return k.r===typeTerm && skipEle.indexOf(k.e)==-1;
  });

  /*顯示資料*/
  $.each(data, function(i,v){
    if(v.c!=''){
      var row=$('<div>').attr({'class':'col-xs-3 col-md-2','data-pets-type':v.t,'data-pets-element':v.e,'data-pets-riding':v.d,'onclick':'void(0)'});
      var rowCell=$('<div>').attr({'class':'pets-item','style':'background-image:url(http://i.imgbox.com/'+v.c+');background-size:100px;'});
      var rowInfo=$('<div>').attr({'class':'pets-item-info'});
      var rowSrc=$('<a>').attr({
        "href":urlOnline+v.i+'/',
        "title":v.n});
      var rowTxt=$('<p>').text(v.n);

      row.append(rowSrc);
      rowSrc.append(rowCell);
      rowCell.append(rowInfo);
      rowInfo.append(rowTxt);
      row.appendTo("#pets-content");
    }});
}

/*偵測網址書籤變化*/
function callFilter() {
  var petsURLBookMark=getParameterByBookMark('type');
  if(petsURLBookMark) petsFilter(petsURLBookMark);
}
