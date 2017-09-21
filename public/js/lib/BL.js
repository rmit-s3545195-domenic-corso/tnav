let BL = {};

BL.httpGET = (url, vars, callback) => {
	let paramString = "?";
	let xhrObj = new XMLHttpRequest();

	if (vars) {
		for (p in vars) {
			if (vars.hasOwnProperty(p)) {
				paramString += p + "=" + vars[p] + "&";
			}
		}
	}

	url = url + paramString;

	xhrObj.onreadystatechange = () => {
		if (xhrObj.readyState == 4 && xhrObj.status == 200) {
			if (callback) {
				callback(xhrObj.responseText);
			}
		}
	}

	xhrObj.open("GET", url, true);
	xhrObj.send();
};

BL.httpPOST = function (url, params, callback) {
    var xhr = new XMLHttpRequest();

    // Parse object and turn into POST-String
    params = (function (obj) {
        var str = "";
        for (var p in obj) {
            if (obj.hasOwnProperty(p)) {
                str += p + "=" + obj[p] + "&";
            }
        } return str.slice(0, str.length - 1);
    })(params);

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            if (callback) {
                callback(xhr.responseText);
            }
        }
    };

    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send(params);
};

BL.getChildIndex = function (element) {
    for (var i = 0; (element = element.previousElementSibling); i++);
    return i;
};
