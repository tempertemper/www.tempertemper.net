if (typeof CMSMap == 'undefined') {
	CMSMap	= {};
}

CMSMap.UI	= function()
{
	var api_key;

	var init	= function() {
		if (CMSMap.maps.length) {			
			plot_maps();
		}
	};
	
	var plot_maps = function() {
		var i, l, data, img, mapdiv, latlng, opts, map, point, marker;
		for (i=0,l=CMSMap.maps.length; i<l; i++) {
			data   = CMSMap.maps[i];
			img    = document.getElementById(data.mapid);
			mapdiv = document.createElement('div');
			mapdiv.setAttribute('id', 'd'+data.mapid);
			mapdiv.setAttribute('class', 'cmsmap');
			if (data.width) mapdiv.style.width   = data.width+'px';
			if (data.height) mapdiv.style.height = data.height+'px';
			img.parentNode.insertBefore(mapdiv, img);
			img.parentNode.removeChild(img);
			
		    latlng = new google.maps.LatLng(data.clat, data.clng);
		    opts = {
				zoom: parseInt(data.zoom,10),
				center: latlng
		    };
			switch(data.type) {
				case 'satellite': opts.mapTypeId = google.maps.MapTypeId.SATELLITE; break;
				case 'hybrid'	: opts.mapTypeId = google.maps.MapTypeId.HYBRID; break;
				case 'terrain'	: opts.mapTypeId = google.maps.MapTypeId.TERRAIN; break;
				default			: opts.mapTypeId = google.maps.MapTypeId.ROADMAP; break;
			}
	
		    map = new google.maps.Map(mapdiv, opts);
			point = new google.maps.LatLng(data.lat, data.lng);
			marker = new google.maps.Marker({
				position: point, 
				map: map, 
				title: data.adr.replace(/\\/g, '')
			});

			CMSMap.maps[i].gmap = map;
			CMSMap.maps[i].latlng = latlng;
		}
	};

	var refresh = function() {
		var i, l;
		for(i=0, l=CMSMap.maps.length; i<l; i++) {
			google.maps.event.trigger(CMSMap.maps[i].gmap, 'resize');
			CMSMap.maps[i].gmap.setCenter(CMSMap.maps[i].latlng);
		}
		return true;
	};
	
	return {
		init: init,
		refresh: refresh,
		key: api_key
	};
	
}();

CMSMap.Loader = function(){
	var func = CMSMap.UI.init;
	var oldonload = window.onload;
	if (typeof window.onload != 'function') {
		window.onload = func;
	} else {
		window.onload = function() {
			if (oldonload) {
				oldonload();
			}
			func();
		};
	}
}();

var load_google_map_api = function(){
	var js = document.createElement("script");
	js.type = "text/javascript";
	js.src = 'https://maps.googleapis.com/maps/api/js?key='+CMSMap.key;
	if (typeof document.head != 'object') {
		document.body.appendChild(js);
	}else{
		document.head.appendChild(js);
	}
}();
