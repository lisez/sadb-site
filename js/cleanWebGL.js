/*
 * sadb.lisezdb.com BY lisez
*/

/*清除webgl*/
function cleanWebGL() {
  var canvas = document.getElementsByTagName('canvas')[0];
  if(canvas){
    var gl = canvas.getContext('webgl');
    gl.getExtension('WEBGL_lose_context').loseContext();
    console.log('cleanup');
  }
}
