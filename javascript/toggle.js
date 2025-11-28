function setDisplay(objectID,state) {
	var object = document.getElementById(objectID);
	object.style.display = state;
}

function toggleDisplay(objectID) {
	var object = document.getElementById(objectID);
	state = object.style.display;
	if (state == 'none')
		object.style.display = 'block';
	else if (state != 'none')
		object.style.display = 'none'; 
}

function preLoad() {
	for (var i = 0; i < 20; i++)
	{
		setDisplay('answer' + i,'none');
	}
	for (var j = 0; j < 5; j++)
	{
		 setDisplay('group' + j,'block');
	}
}