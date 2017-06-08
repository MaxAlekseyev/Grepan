function hide_developer_toolbar() {
    if (document.getElementById('bar_content').style.display == 'none') {
        document.getElementById('bar_content').style.display = "inline";
        document.getElementById('devtools').style.width = "100%";
    } else {
        document.getElementById('bar_content').style.display = "none";
        document.getElementById('devtools').style.width = "100px";
    }
}