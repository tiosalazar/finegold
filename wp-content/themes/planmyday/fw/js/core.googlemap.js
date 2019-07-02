function planmyday_googlemap_init(dom_obj, coords) {
	"use strict";
	if (typeof PLANMYDAY_STORAGE['googlemap_init_obj'] == 'undefined') planmyday_googlemap_init_styles();
	PLANMYDAY_STORAGE['googlemap_init_obj'].geocoder = '';
	try {
		var id = dom_obj.id;
		PLANMYDAY_STORAGE['googlemap_init_obj'][id] = {
			dom: dom_obj,
			markers: coords.markers,
			geocoder_request: false,
			opt: {
				zoom: coords.zoom,
				center: null,
				scrollwheel: false,
				scaleControl: false,
				disableDefaultUI: false,
				panControl: true,
				zoomControl: true, //zoom
				mapTypeControl: false,
				streetViewControl: false,
				overviewMapControl: false,
				styles: PLANMYDAY_STORAGE['googlemap_styles'][coords.style ? coords.style : 'default'],
				mapTypeId: google.maps.MapTypeId.ROADMAP
			}
		};
		
		planmyday_googlemap_create(id);

	} catch (e) {
		
		dcl(PLANMYDAY_STORAGE['strings']['googlemap_not_avail']);

	};
}

function planmyday_googlemap_create(id) {
	"use strict";

	// Create map
	PLANMYDAY_STORAGE['googlemap_init_obj'][id].map = new google.maps.Map(PLANMYDAY_STORAGE['googlemap_init_obj'][id].dom, PLANMYDAY_STORAGE['googlemap_init_obj'][id].opt);

	// Add markers
	for (var i in PLANMYDAY_STORAGE['googlemap_init_obj'][id].markers)
		PLANMYDAY_STORAGE['googlemap_init_obj'][id].markers[i].inited = false;
	planmyday_googlemap_add_markers(id);
	
	// Add resize listener
	jQuery(window).resize(function() {
		if (PLANMYDAY_STORAGE['googlemap_init_obj'][id].map)
			PLANMYDAY_STORAGE['googlemap_init_obj'][id].map.setCenter(PLANMYDAY_STORAGE['googlemap_init_obj'][id].opt.center);
	});
}

function planmyday_googlemap_add_markers(id) {
	"use strict";
	for (var i in PLANMYDAY_STORAGE['googlemap_init_obj'][id].markers) {
		
		if (PLANMYDAY_STORAGE['googlemap_init_obj'][id].markers[i].inited) continue;
		
		if (PLANMYDAY_STORAGE['googlemap_init_obj'][id].markers[i].latlng == '') {
			
			if (PLANMYDAY_STORAGE['googlemap_init_obj'][id].geocoder_request!==false) continue;
			
			if (PLANMYDAY_STORAGE['googlemap_init_obj'].geocoder == '') PLANMYDAY_STORAGE['googlemap_init_obj'].geocoder = new google.maps.Geocoder();
			PLANMYDAY_STORAGE['googlemap_init_obj'][id].geocoder_request = i;
			PLANMYDAY_STORAGE['googlemap_init_obj'].geocoder.geocode({address: PLANMYDAY_STORAGE['googlemap_init_obj'][id].markers[i].address}, function(results, status) {
				"use strict";
				if (status == google.maps.GeocoderStatus.OK) {
					var idx = PLANMYDAY_STORAGE['googlemap_init_obj'][id].geocoder_request;
					if (results[0].geometry.location.lat && results[0].geometry.location.lng) {
						PLANMYDAY_STORAGE['googlemap_init_obj'][id].markers[idx].latlng = '' + results[0].geometry.location.lat() + ',' + results[0].geometry.location.lng();
					} else {
						PLANMYDAY_STORAGE['googlemap_init_obj'][id].markers[idx].latlng = results[0].geometry.location.toString().replace(/\(\)/g, '');
					}
					PLANMYDAY_STORAGE['googlemap_init_obj'][id].geocoder_request = false;
					setTimeout(function() { 
						planmyday_googlemap_add_markers(id); 
						}, 200);
				} else
					dcl(PLANMYDAY_STORAGE['strings']['geocode_error'] + ' ' + status);
			});
		
		} else {
			
			// Prepare marker object
			var latlngStr = PLANMYDAY_STORAGE['googlemap_init_obj'][id].markers[i].latlng.split(',');
			var markerInit = {
				map: PLANMYDAY_STORAGE['googlemap_init_obj'][id].map,
				position: new google.maps.LatLng(latlngStr[0], latlngStr[1]),
				clickable: PLANMYDAY_STORAGE['googlemap_init_obj'][id].markers[i].description!=''
			};
			if (PLANMYDAY_STORAGE['googlemap_init_obj'][id].markers[i].point) markerInit.icon = PLANMYDAY_STORAGE['googlemap_init_obj'][id].markers[i].point;
			if (PLANMYDAY_STORAGE['googlemap_init_obj'][id].markers[i].title) markerInit.title = PLANMYDAY_STORAGE['googlemap_init_obj'][id].markers[i].title;
			PLANMYDAY_STORAGE['googlemap_init_obj'][id].markers[i].marker = new google.maps.Marker(markerInit);
			
			// Set Map center
			if (PLANMYDAY_STORAGE['googlemap_init_obj'][id].opt.center == null) {
				PLANMYDAY_STORAGE['googlemap_init_obj'][id].opt.center = markerInit.position;
				PLANMYDAY_STORAGE['googlemap_init_obj'][id].map.setCenter(PLANMYDAY_STORAGE['googlemap_init_obj'][id].opt.center);				
			}
			
			// Add description window
			if (PLANMYDAY_STORAGE['googlemap_init_obj'][id].markers[i].description!='') {
				PLANMYDAY_STORAGE['googlemap_init_obj'][id].markers[i].infowindow = new google.maps.InfoWindow({
					content: PLANMYDAY_STORAGE['googlemap_init_obj'][id].markers[i].description
				});
				google.maps.event.addListener(PLANMYDAY_STORAGE['googlemap_init_obj'][id].markers[i].marker, "click", function(e) {
					var latlng = e.latLng.toString().replace("(", '').replace(")", "").replace(" ", "");
					for (var i in PLANMYDAY_STORAGE['googlemap_init_obj'][id].markers) {
						if (latlng == PLANMYDAY_STORAGE['googlemap_init_obj'][id].markers[i].latlng) {
							PLANMYDAY_STORAGE['googlemap_init_obj'][id].markers[i].infowindow.open(
								PLANMYDAY_STORAGE['googlemap_init_obj'][id].map,
								PLANMYDAY_STORAGE['googlemap_init_obj'][id].markers[i].marker
							);
							break;
						}
					}
				});
			}
			
			PLANMYDAY_STORAGE['googlemap_init_obj'][id].markers[i].inited = true;
		}
	}
}

function planmyday_googlemap_refresh() {
	"use strict";
	for (id in PLANMYDAY_STORAGE['googlemap_init_obj']) {
		planmyday_googlemap_create(id);
	}
}

function planmyday_googlemap_init_styles() {
	// Init Google map
	PLANMYDAY_STORAGE['googlemap_init_obj'] = {};
	PLANMYDAY_STORAGE['googlemap_styles'] = {
		'default': []
	};
	if (window.planmyday_theme_googlemap_styles!==undefined)
		PLANMYDAY_STORAGE['googlemap_styles'] = planmyday_theme_googlemap_styles(PLANMYDAY_STORAGE['googlemap_styles']);
}