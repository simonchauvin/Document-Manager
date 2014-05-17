function noopHandler(evt) {
  evt.stopPropagation();
  evt.preventDefault();
}

var redirect = false;

function drop(evt) {
	evt.stopPropagation();
	evt.preventDefault();
		 
	var files = evt.dataTransfer.files;
	var count = files.length;
	 
	// Only call the handler if 1 or more files was dropped.
	if (count == 1)
	{
		var file = files[0];
		
		var form = new FormData();
		form.append("addFile", "1");
		form.append("upldFile", file);
		
		calque=document.createElement('div');
		calque.id="loading";
		calque.innerHTML = '<img src="loading.gif"/><br><p id="p_progress">0%</p>';
		document.body.appendChild(calque);
		
		var xhr = new XMLHttpRequest();
		
		console.log('Adding on progress');
		xhr.upload.onprogress = function(evt) {
		  console.log('loading');
		  console.log(evt.lengthComputable);
		  if (evt.lengthComputable) 
		  {
		    var par = document.getElementById('p_progress');
		    var percentComplete = Math.round((evt.loaded / evt.total)*100);  		    
		    console.log(percentComplete);
		    par.innerHTML = percentComplete+'%'
		  }
		
		}
		
		xhr.onload = function() {
			if(redirect){
				window.location = "admin.php?lien="+encodeURI(xhr.responseText);
			}else{
			  setChampAdminForm("c_lien",xhr.responseText);
			  makeChampAdminFormEditable("c_lien");
			}
			console.log("Upload complete.");
			calque.style.display="none";
		};
		xhr.open("post", "upload.php", true);
		xhr.send(form);
 	}
}

function initialisePassiveDrop()
{
	var dropbox = document.body;
 
	// init event handlers
	dropbox.addEventListener("dragenter", noopHandler, false);
	dropbox.addEventListener("dragexit", noopHandler, false);
	dropbox.addEventListener("dragover", noopHandler, false);
	dropbox.addEventListener("drop", drop, false);
	
	redirect = false;
	
	//dropbox.innerHTML = "Drop article pdf here...";
}

function initialiseActiveDrop()
{
	var dropbox = document.body;
 
	// init event handlers
	dropbox.addEventListener("dragenter", noopHandler, false);
	dropbox.addEventListener("dragexit", noopHandler, false);
	dropbox.addEventListener("dragover", noopHandler, false);
	dropbox.addEventListener("drop", drop, false);
	
	redirect = true;
		
	//dropbox.innerHTML = "Drop article pdf here...";
}

