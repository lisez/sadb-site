/* lisez
 * v1.1.0
 */

/*寵物種類篩選*/
function petsFilter(type) {
  /*寵物種類索引 */
  var typeTxt		= ['首領','普通','高級','稀有','英雄','傳說'];
  var typeTerm	= typeTxt.indexOf(type);
  var dbFile		=	'/requirepets.json';
  var urlOnline = '/pets-';

  /*清空顯示頁面 */
  $("#pets-content").empty();

  /*呼叫JSON */
  $.getJSON(dbFile)
    .done(function(json){
      /*篩選資料*/
      var data = $.grep(json, function(k,v){
        return JSON.parse(k.r)===typeTerm;
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
    })

    /*連線錯誤時*/
    .fail(function(j,s,e) {
      var err = s + ", " + e;
      console.log( "Request Failed: " + err );
    });
}

/*偵測網址書籤變化*/
function callFilter() {
  var petsURLBookMark=getParameterByBookMark('type');
  if(petsURLBookMark) petsFilter(petsURLBookMark);
}
