function typeInTextarea(newText, el = document.getElementById("contents")) {
	const start = el.selectionStart;
	const end = el.selectionEnd;
	const text = el.value;
	const before = text.substring(0, start);
	const after = text.substring(end, text.length);
	el.value = before + newText + after;
	el.selectionStart = el.selectionEnd = start + newText.length;
	el.focus();
}

document.getElementById("contents").onkeydown = (e) => {
	if (e.key === "Enter") typeInTextarea("<br>");
};

document.getElementById("btn1").onclick = (e) => {
	typeInTextarea(" <b>TEKST HIER</b> ");
};
document.getElementById("btn2").onclick = (e) => {
	typeInTextarea(" <i>TEKST HIER</i> ");
};
document.getElementById("btn3").onclick = (e) => {
	typeInTextarea(" <blockquote>TEKST HIER</blockquote> ");
};
document.getElementById("btn4").onclick = (e) => {
	typeInTextarea(" <h5>TEKST HIER</h5> ");
};
document.getElementById("btn5").onclick = (e) => {
	typeInTextarea(" <pre><code>TEKST HIER</code></pre> ");
};

function changePath() {
	var x = document.getElementById("image");

	if (x.files.length > 0) {
		document.getElementById("pathToFile").innerHTML = "";

		for (var i = 0; i <= x.files.length - 1; i++) {
			var fname = x.files.item(i).name;
			var fsize = x.files.item(i).size;

			document.getElementById("pathToFile").innerHTML +=
				fname +
				" (<b>Grootte: " +
				(fsize / 1000000).toFixed(2) +
				" MB</b>)<br>";
		}
	}
}
function Reset() {
	document.getElementById("pathToFile").innerHTML = "<i>Geen pad bekend.</i>";
}
function showContents() {
	var outer = document.getElementById("outerContentsOutput");
	var inner = document.getElementById("innerContentsOutput");
	var title = document.getElementById("titleContentsOutput");

	if (outer.style.display == "none") {
		outer.style.display = "inline";
		title.innerHTML = "Verberg voorbeeld";
	} else if (outer.style.display == "inline") {
		outer.style.display = "none";
		title.innerHTML = "Laat voorbeeld zien";
	}
}
function editContents() {
	var output = document.getElementById("innerContentsOutput");
	var input = document.getElementById("contents");

	if (input.value == "") {
		output.innerHTML =
			"<i>Typ iets in het inhoudsvakje om een voorbeeld te genereren! <br>Als je al tekst getypt hebt, maar komt deze niet tevoorschijn, druk dan op een willekeurige toets in het tekstvakje.</i>";
	} else {
		output.innerHTML = input.value;
	}
}
function insertBold() {
	typeInTextarea("<b></b>");
}
