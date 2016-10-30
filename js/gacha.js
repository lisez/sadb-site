function gachaByRarity(rarity, pool) {
	var petList = $.grep(pool, function(i,v){
		return i.r==rarity;
	});
	var petMax = petList.length;
	var petRand = function(minNum, maxNum){
		return Math.round(Math.random()*(maxNum-minNum)+minNum);
	};

	var num = petRand(0,petMax-1);
	return [petList[num].i, petList[num].o];
}

function getTenPets(pos, pool) {
	$(pos).empty();

	var skip = ['地','水','火','風'];
	var petData = $.grep(pool, function(i,v){
		return skip.indexOf(i.e)!=-1;
	});

	var icon = function(num, img){
		var a = $('<a>').attr('href', '/pets-'+num);
		var b = $('<img>').attr({'src':'https://i.imgbox.com/'+img, 'class':'img-rounded img-icon'});
		return a.append(b);
	};

	var randomNum = function(minNum, maxNum){
		return Math.round(Math.random()*(maxNum-minNum)+minNum);
	};

	for(var i=1;i<=10;i++){
		var getPet = randomNum(1,1000);
		switch(true){
			case getPet<=10:
				var pet = gachaByRarity(5, petData);
				break;
			case getPet>=11 && getPet<=200:
				var pet = gachaByRarity(4, petData);
				break;
			default:
				var pet = gachaByRarity(3, petData);
				break;
		}
		$(pos).append(icon(pet[0],pet[1]));
	}
}