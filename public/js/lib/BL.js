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

	xhrObj.timeout = 250;
	xhrObj.open("GET", url, true);
	xhrObj.send();
};
