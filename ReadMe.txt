Autoloadfunktin: spl_autoload
    http://php.net/manual/en/function.spl-autoload.php
OSM Suche - Api
    http://wiki.openstreetmap.org/wiki/Nominatim
    
    Grenzkoordinaten bekommen von einem Land:
    http://nominatim.openstreetmap.org/search?country=Deutschland&polygon_geojson=1&format=json
    
Infos:
    - https://de.wikipedia.org/wiki/GeoJSON
    - http://leafletjs.com/examples/geojson/
    - http://leafletjs.com/examples/choropleth/
    - GeoJSON selbst bauen (auch Polygone)
        http://geojson.io/#map=6/50.513/11.448
        --> Grenzkoordinaten des einen Landes als Grenzkoordinaten des anderen nehmen, damit sich das nicht �berlappt
    - http://www.laenderdaten.de/laender.aspx
ToDo:
	- Eingabe Datum: Das Enddatum muss nach/gleich Startdatum sein bzw. umgekehrt
	- addLocation
		- Bei Location add die Standardorte raus
	- System
	    - salted passwords
	- editLocation
	    - Nach Upload des Bildes, den Bereich neu laden. Bisher werden die hochgeladenen Bilder nur gezeigt, wenn die Seite neu geladen wird
		- Locations l�schbar machen in �bersicht
	- addLocation/editLocation
		- Inputfelder nur so lang wie Datenfeld in DB!!!
		- Konsolidierung von CSS, JavaScript

