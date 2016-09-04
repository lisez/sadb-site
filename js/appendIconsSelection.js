/*
 * sadb.lisezdb.com BY lisez
*/

var teamArray=[
  [0,[0,0,0,0]],
  [0,[0,0,0,0]],
  [0,[0,0,0,0]],
  [0,[0,0,0,0]],
  [0,[0,0,0,0]],
  [0,[0,0,0,0]],
  [0,[0,0,0,0]]
];

function appendAryImg(url/*isArray*/,fromwhere,pos/*isElementId*/,sel){
  $('#'+pos).empty();
  for (var i = 0; i < url.length; i++) {
    var row=$('<div>').attr({
      'class':'gentable-cells '+sel,
      'data-icon-id':i,
    });
    var rowCell=$('<img>').attr({
      'name':i,
      'src':imgBaseURL+url[i],
      'class':'img-rounded img-icon'});
    row.append(rowCell);
    row.appendTo('#'+pos);
  }

  doAction(fromwhere);
}

function selectAble(object,selclass,hasornot,limit) {
  if($('.'+object+'.'+selclass).length>=limit && hasornot==false)return;
  return true;
}

function doAction(whereThis){
  $('.selIcon').click(function(){
    var hasOrNot = $(this).hasClass('selectOne');
    if(hasOrNot){
      $(this).removeClass('selectOne');
      $('#skill-list').remove();
      $('.selIcon').show();
      return;
    }else{
      $('.selIcon').not($(this)).hide();
      $('.selIcon').not(this).removeClass('selectOne');
      $(this).addClass('selectOne');
      $(this).after(addOptions($(this).data('icon-id'),whereThis));
    }
  });
}

function addOptions(id, towhere) {
  /*插入主列*/
  var row = $('<div>').attr({
    'id':'skill-list',
    'data-icon-id':id
  });
  /*插入技能選項*/
  for (var i = 1; i <=4; i++) {
    var txt = $('<label>').text('技能'+i);
    var item = $('<input>').attr({
      'id':'skill-list-'+i,
      'name':'skill',
      'type':'checkbox',
      'value':i,
      'checked':'true'
    });
    if(i==1)item.attr({'disabled':'true'});
    txt.append(item);
    txt.appendTo(row);
  }
  /*插入確認按鈕*/
  var button = $('<input>').attr({
    'type':'button',
    'id':'submitOptions',
    'value':'確認'
  });

  row.append(button);

  /*送出資料*/
  $(button).click(function(){submitSkills(towhere);});

  /*回傳*/
  return row;
}

function submitSkills(loc) {
  /*設定變數*/
  var toWhere = $('.general-block[data-team-pos='+loc+']');
  var toWhereTxt = $('#icon-skill-list'+loc);
  var toWhereImg = $('.general-block[data-team-pos='+loc+']>img');
  var skillLabel=['①','②','③','④'];
  var iconID = $('.selectOne').data('icon-id');
  /*清除目前資料*/
  toWhereTxt.remove();
  toWhereImg.remove();
  /*取得技能勾選值*/
  var skillAry = [];
  var skillTxt = $('<div>').attr({'id':'icon-skill-list'+loc});
  for (var i = 0; i < $('input[name=skill]').length; i++) {
    var item=$('#skill-list-'+(i+1));
    if(item.prop('checked')){
      skillAry[i]=1;
      skillTxt.append('<span class="skill-enable">'+skillLabel[i]+'</span>');
    }else{
      skillAry[i]=0;
      skillTxt.append(skillLabel[i]);
    }
  }
  skillTxt.appendTo(toWhere);
  /*插入圖片*/
  var img = function (o) {
    if(o==6)return imgTrainer[$('.selectOne').data('icon-id')];
    return imgPets[$('.selectOne').data('icon-id')];
  };
  toWhere
    .append(
      $('<img>').attr({'src':imgBaseURL+img(loc),'class':'img-icon img-rounded'})
    );
  /*更新陣列值*/
  if(typeof teamArray[loc]=='undefined')teamArray[loc]=[];
  teamArray[loc]=[$('.selectOne').data('icon-id'),skillAry];
  /*console.log(teamArray);*/
  /*清除選擇欄*/
  $('#selectItemsPool').empty();
}
