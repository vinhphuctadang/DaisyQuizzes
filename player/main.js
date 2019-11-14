// viết tất cả các hàm này để thể hiện câu hỏi theo thời gian


var socket = io.connect('http://localhost:8080');

function render (question) {
	var question_pane = document.getElementById ("question");
	question_pane.innerHTML = question;
	// TODO: Invoke onInterval after a desired time 
}

function requestNext () {
	request = new XMLHttpRequest ();
	request.open ("GET", "/api.php?method=get_question_body", true)
	request.onreadystatechange = function () {
		if (this.readyState == 4 && this.status == 200) {			
			render (this.responseText);					
		}
	};
	request.send ();
}
