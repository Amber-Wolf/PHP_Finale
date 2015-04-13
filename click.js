var on = new Array(0, 0, 0, 0, 0);
document.getElementById("send").disabled = true;

function onClick(checkbox,id){ // partially from http://stackoverflow.com/questions/5539139/change-get-check-state-of-checkbox
    if (checkbox.checked){
        on[id] = 1;
    }else{
	on[id] = 0;
    }
    setButton();
}

function setButton(){
	var r;
	var counter = 0;
	for	(r = 0; r < on.length; r++) {
		counter += on[r];
	}
	if(counter == 3){
		document.getElementById("send").disabled = false;
	}else{
		document.getElementById("send").disabled = true;
	}
}
