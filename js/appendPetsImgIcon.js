function queryPetImgURL(id) {
	var target = $.grep(getAllPets, function(i,v){return i.i==id});
	var none = '/assets/images/none.png';
	if(target.length ===0 ) return none;
	if(target[0].o=='')return none;
	return 'http://i.imgbox.com/'+target[0].o;
}

function appendPetsImg(id) {
	if(id===0 || !id)return;
	var row=$('<a>');
	var img=$('<img>');
	
	row.attr('href','/pets-'+id+'/');
	img.attr({'src': queryPetImgURL(id), 'class':'img-icon img-rounded'});

	return row.append(img);
}

function appendTrainerImg(query) {
	var id = parseInt(query);
	var pImg = $.grep(saTrainer, function(i,v){
		return i.i==id;
	});
	var row = $('<a>').attr('href','/gameinfo/trainers/');
	var img = $('<img>').attr({'src': pImg[0].o, 'class':'img-icon img-rounded'});
	
	return row.append(img);
}

function loopSpan() {

	$('.add-icon-list').each(function(i,v){
		var list = $(this).data('query-id').toString().split(',');
		for(var i=0; i<list.length; i++){
			if(list[i]==0 || list[i]=='undefined')continue;
			var thing = parseInt(list[i]);
			if(i==0){
				$(this).append(appendTrainerImg(thing));
			}else{
				$(this).append(appendPetsImg(thing));
			}
		}
	});

	$('.add-pet-img').each(function(i){
		var thing = appendPetsImg($(this).data('query-id'));
		$(this).append(thing);
	});

	$('.add-trainers-img').each(function(i){
		var thing = appendTrainerImg($(this).data('query-id'));
		$(this).append(thing);
	});
}