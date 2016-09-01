/*常用數值*/
var basicCol=['hp','atk','def','spd'];
var theInput=['input','select'];

/*偵測是否支援JS*/
function indisplayNoJSAlert(ele) {
  window.document.getElementsByClassName(ele)[0].style.display='none';
};

/*切數字*/
function getInt(theInt, theDigi=1) {
  return parseInt(theInt.toFixed(theDigi));
};

/*自動帶入字串*/
function insertVal(hp, atk, def, spd) {
  /*參照字串*/
  var val=[hp, atk, def, spd];
  /*填入字串*/
  for (var i = 0; i < basicCol.length; i++) {
    var obj = 'pet_'+basicCol[i]+'_now';
    window.document.getElementsByClassName(obj)[0].value=val[i];
  };
  /*完成後計算*/
  autoCalcTotal();
};

/*全選文字*/
function autoSelectInput() {
  for (var i = 0; i < theInput.length; i++) {
    if(theInput[i]!='input'){continue};
    var el=window.document.getElementsByTagName(theInput[i]);
    for (var k = 0; k < el.length; k++) {
      if(el[k]){
        el[k].addEventListener('click',function(){
          this.focus();
          this.select();
        });
      };
    };
  };
};

/*捕捉input欄位改變*/
function hookInputChange() {
  for (var i = 0; i < theInput.length; i++) {
    var el=window.document.getElementsByTagName(theInput[i]);
    for (var k = 0; k < el.length; k++) {
      if(el[k]){
        el[k].addEventListener('change',function(){
          autoCalcTotal();
        });
      };
    };
  };
};

/*自動加總*/
function autoCalcTotal() {
  var tmpAry=[];
  for (var i = 0; i < basicCol.length; i++) {
    var txt= basicCol[i];
    tmpAry[i]=new getPwrVal(txt);
    window.document.getElementsByClassName('pet_'+txt+'_total')[0].innerHTML = tmpAry[i].total().toLocaleString();
  };
};

/*計算*/
function getPwrVal(type) {
  var Obj = this;
  Obj._type = type;
  Obj._index = basicCol.indexOf(Obj._type);
  Obj.lvzero = getInt(valStart[Obj._index]-(valMin[Obj._index]*valStartLV),1);

  Obj.lvup = function(lv, now){

    Obj._lv = lv || parseInt(window.document.getElementsByClassName('pet_lv_now')[0].value);

    Obj._now = now || parseFloat(window.document.getElementsByClassName('pet_'+Obj._type+'_now')[0].value);

    return getInt(Obj.lvzero+Obj._lv*Obj._now,1);
  };

  Obj.total = function(lv,now){

    Obj._lvup=Obj.lvup();

    Obj._chNow = parseFloat(window.document.getElementsByClassName('pet_'+Obj._type+'_char')[0].value) || 0;
    Obj._chNow=1+(Obj._chNow/100);
    return getInt(Obj._lvup*Obj._chNow,1);
  };

};
