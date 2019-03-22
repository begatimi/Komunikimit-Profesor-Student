var xmlhttp;

function searchPost(str, cid) {
	
    xmlhttp = CreateRequest();
    if (xmlhttp==null) {
        alert("The request could not be proccesed!");
        return;
    }
    var url = "searchpost.php";
    url = url + "?k=" + str + "&c=" + cid;
    xmlhttp.open("GET", url, true);
    xmlhttp.send();
    xmlhttp.onreadystatechange = DisplaySearchRes;
}

function DisplaySearchRes() {
    if (xmlhttp.readyState == 4) {
        if(xmlhttp.status == 200) {
            document.getElementById("post-feed").innerHTML = xmlhttp.responseText;
        }
    }
}
