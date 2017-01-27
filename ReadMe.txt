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
    - Locations l�schbar machen in �bersicht
    - Bei Location add die Standardorte raus
    - Inputfelder nur so lang wie Datenfeld in DB!!!
    - salted passwords
    - die Reihenfolge der Reisen wird mittels verschiebend der Einträge in der Liste gemacht.
        - Die Nummerierung der Reihenfolge wird automatisch gemacht
        - ändert sich die Reihenfolge, wird erneut durchnummeriert und danach gespeichert