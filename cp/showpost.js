var xmlhttp;


function showpost(str) {
	
    xmlhttp = CreateRequest();
    if (xmlhttp==null) {
        alert("The request could not be proccesed!");
        return;
    }
    var url = "showpost.php";
    url = url + "?p=" + str;
    xmlhttp.open("GET", url, true);
    xmlhttp.send();
    xmlhttp.onreadystatechange = DisplayRes;
}

function DisplayRes() {
    if (xmlhttp.readyState == 4) {
        if(xmlhttp.status == 200) {
            document.getElementById("show-post").innerHTML = xmlhttp.responseText;
        }
    }
}

function CreateRequest() {
    try {
        xmlhttp = new XMLHttpRequest();
    } catch(ex) {
        try {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        } catch(ex) {
            xmlhttp = null;
        }
    }
    return xmlhttp;
}