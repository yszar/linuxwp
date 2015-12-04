function $( element ) {
	return document.getElementById( element );
}
function setDivStyle( div, percent ) {
	if( $( div ) ) {
		oDiv = $( div );
		oDiv.style.width = percent * 600 + "px";
	}
}