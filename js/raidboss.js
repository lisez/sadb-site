/*
lisez
v102
*/
function getPetInfo(id) {
	var pet = $.grep(getAllPets, function(i,v){
		return i.i==id;
	});
	var baseImgURL = 'http://i.imgbox.com/';
	if(!pet[0])return 0;
	var petName = pet[0].n;
	var petIcon = pet[0].o?baseImgURL+pet[0].o:0;
	return [petName, petIcon];
}

function addBossTimeList(argument) {
	bossData.forEach(function(v){
		/*boss basic info*/
		var bossName = v.name;
		var bossID   = parseInt(v.id);
		var bossMobs = v.mobs;
		/*list item*/
		var bossListItem = $('<li>');
		var bossListHref = $('<a>').attr({
			'href':'#'+bossID,
			'class':'bossSearchClass',
			'data-bossid':bossID,
			'onclick':'void(0)',
		}).text(bossName);
		/*add to html*/
		$('#boss-list').append(bossListItem.append(bossListHref));
	});
}

function displayBossInfo(id) {
	/*filtering boss data*/
	var bossRaw = $.grep(bossData, function(i,v){
		return i.id==id;
	});
	var boss=bossRaw[0];
	/*img*/
	var htmlBossImg = $('<img>').attr({
		'src':boss.banner,
		'class':'img-rounded'
	});
	/*mobs*/
	/*mobs table*/
	var lvlMobs=$('<div>').attr({'class':'div-table'});
	var lvlMobsHeader=$('<div>').attr({'class':'div-table-tr'});
	lvlMobsHeader.append($('<div>').attr({'class':'div-table-th','style':'width:10%'}).append('關卡等級'));
	lvlMobsHeader.append($('<div>').attr({'class':'div-table-th'}).append('關卡資訊'));
	/*mobs data*/
	for(var i=1;i<=Object.keys(boss.mobs).length;i++){
		if(!boss.mobs[i]){
			$('#boss-team-setting').append('資料搜集中');
			continue;
		};
		/*mobs lvl label*/
		var lvlMobsLine =$('<div>').attr({'class':'div-table-tr'});
		var lvlMobsLbl=$('<div>').attr({'class':'div-table-th'}).append('Lv.'+i);
		var lvlMobsSet=$('<div>').attr({'class':'div-table-td'});
		for(var j=0;j<boss.mobs[i].length;j++){
			var petInfo = getPetInfo(boss.mobs[i][j]);
			if(petInfo==0 || petInfo[1]==''){
				var petSrc  = '/assets/images/none.png';
			}else{
				var petSrc  = petInfo[1];
			};
			var petImg  = $('<img>').attr({'src':petSrc,'class':'img-rounded pets-series-thumbnails'});
			var petName = petInfo[0];
			var petCode = $('<a>').attr({'href':'/pets-'+boss.mobs[i][j]+'/'}).append(petImg); 
			/*add to line*/
			petCode.append(petImg);
			lvlMobsSet.append(petCode);
		}
		/*add to row*/
		lvlMobsLine.append(lvlMobsLbl);
		lvlMobsLine.append(lvlMobsSet);
		/*add to table*/
		lvlMobs.prepend(lvlMobsHeader);
		lvlMobs.append(lvlMobsLine);
	}
	/*insert HTML*/
	$('#boss-label').append(htmlBossImg);
	$('#boss-team-setting').append(lvlMobs);
}