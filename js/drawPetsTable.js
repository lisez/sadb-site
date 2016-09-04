var imgBaseURL = 'http://i.imgbox.com/';
var imgLoadAry =[];
var imgTrainer =[
'rRIqki7A.jpg',
'I8TxcTvH.jpg',
'wyU4Bouq.jpg',
'FylhwTJP.jpg',
'8XaFsado.jpg',
'0fOtZA8M.jpg',
'QLDMQomo.jpg',
'6TRVhSHR.jpg'];

var imgPets = [
'VWKYjcSy.jpg',
'ThS7pvWf.jpg',
'9HUkSR77.jpg',
'KaMlgZ6q.jpg',
'WArTs7xz.jpg',
'oecyqihL.jpg',
'O5qa9HFc.jpg'
/*'Iha5oknj.jpg',
'AI9AZLtQ.jpg',
'J3Ov0WmS.jpg',
'CjCjFx3U.jpg',
'yP4NmtV0.jpg',
'XTMOuiey.jpg',
'd8rJ1Zuf.jpg',
'LHWzkjcn.jpg',
'51EMv4Q4.jpg',
'IpV7vwcR.jpg',
'jy5C2i7G.jpg',
'WTdcA1DN.jpg',
'tRlG2s9i.jpg',
'7JcAkPor.jpg',
'XYquhJz7.jpg',
'xBWERiBB.jpg',
'PRDkoQGg.jpg',
'WtDbZfk9.jpg',
'KPz6jZpg.jpg',
'1bOr1WBx.jpg',
'ptEqBI2W.jpg',
'pN9aPwvA.jpg',
'btSZtbLM.jpg',
'm7wbUO7k.jpg',
'GOsQLUNw.jpg',
'jao2CpvW.jpg',
'7pSCc2jh.jpg',
'tcGVHiKe.jpg',
'xZE5xXiB.jpg',
'vDQX7vNH.jpg',
'hoUpmti4.jpg',
'xucjS0LO.jpg'*/];

function wherePets(el) {
  if(typeof el=='undefined')el=0;
  return imgBaseURL+imgPets[el];
}

function whereTrainers(el) {
  return imgBaseURL+imgTrainer[el];
}

function drawPetsTable() {
  /*建立物件*/
  this.renderer = new PIXI.autoDetectRenderer();
  this.container = new PIXI.Container();
  /*設定邊界*/
  this.marginOuter = 10;
  this.marginInner = 10;
  this.totalHeight = 0;
  this.totalWidth = 0;
  this.imgOffset = 85;
  /**設定材質*/
  this.imgSize = 75;
  this.imgNull = '';
}

/*顯示*/
drawPetsTable.prototype.display = function (pos, _w, _h) {
  /*設定大小*/
  var totalWidth = _w;//marginOuter*2+imgOffset+imgSize,
      totalHeight= _h;//marginOuter*2+marginInner+imgSize*2;
  this.renderer.resize(_w,_h);
  this.renderer.view.style.border = '1px dashed black';
  this.renderer.backgroundColor = 0x2D241B;
  /*產生*/
  var labelTxt = new PIXI.Text('石器\n部落',{fontFamily:'sans-serif',fontSize:'15pt',fill:'white'});
  labelTxt.position.set(23.75,155);
  this.container.addChild(labelTxt);
  /**/
  this.renderer.render(this.container);
  document.getElementById(pos).appendChild(this.renderer.view);
  /*釋放*/
  drawPetsTable.release;
};

/*插入文字*/
drawPetsTable.prototype.drawText = function(txt,_x,_y,_size,_color) {
  var genTxt = new PIXI.Text(
    txt,
    {fontFamily:'sans-serif', fontSize:_size, fill: _color}
  );
  genTxt.position.set(_x,_y);
  this.container.addChild(genTxt);
  /*釋放*/
  drawPetsTable.free(genTxt);
}

/*載入圖片*/
drawPetsTable.initImg = function (_url) {
  var loadReady=false;
  if(imgLoadAry.indexOf(_url)==-1){
    PIXI.loader.add(_url);
    imgLoadAry.push(_url);
  }
//  PIXI.loader.on('progress',initImgProgressInfo)
//             .on('complete',initImgCompleteInfo)
  PIXI.loader.load(loadReady=true);
  return loadReady;
};

/*
function initImgProgressInfo(l,r) {
  var txt = '載入中，進度 '+(r|0)+'%<br>';
  console.log(txt);
  $('#generatorInfo').append(txt);
}

function initImgCompleteInfo() {
  var txt = '載入完成<br>';
  console.log(txt);
  $('#generatorInfo').append('載入完成<br>');
}
*/

/*插入圖片*/
drawPetsTable.prototype.drawImg = function(_url,_x,_y) {
  var theCon = this.container;
  var theRen = this.renderer;
  if(drawPetsTable.initImg(_url)){
    var iconTexture = PIXI.loader.resources;
    var icon = new PIXI.Sprite(iconTexture[_url].texture);
    icon.width = icon.height = this.imgSize;
    icon.x=_x;
    icon.y=_y;
    theCon.addChild(icon);
    theRen.render(theCon);
  }
}

/*釋放*/
drawPetsTable.free = function(obj) {
  obj=null; delete obj;
  return true;
}

/*清理釋放*/
drawPetsTable.prototype.release = function() {
  this.renderer.destory;
  PIXI.WebGLRenderer.reset;
}
