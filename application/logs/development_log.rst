Változások 2017.07.12.
======================

- a fejlécről lekerült a regisztráció, a felhasználók kezeléséhez felkerült
- a fejlécről lekerült a belépés, a láblécre felkerült
- a fejlécről lekerült a beállítások gomb, a láblécre felkerült
- jelszóemlékeztető gomb eltávolításra került a belépési felületekről
- a kategória-alkategória összekapcsolás gomb eltávolításra került a kategóriák kezeléséből
- a hozzászólások kezelése oldalon a táblázatbeli cikkek linkjének javítása
- hozzászólási lehetőség eltávolításra került a cikkek alól

Változások 2017.07.13.
======================

- cikkek adminfelületi listájához készült publikáltakra szűrési opció
- olvasnivalo/ajanlok/cikk/dátum/title típusú (2-3.0) linkek támogatása
- cikkek listázása során a "Végére" gomb megfelelő időben válik aktívvá/tiltottá
- cikkek listázása során a cikkszerző neve is kattintható link
- cikkek listázása meta alapján: ha a link végén nem $limit-tel osztható szám szerepel, nem vesszük figyelembe (3.0 linkproblémájának áthidalása)
- címkék adminfelületi listájáhou készült névre szűrési opció
- cikkek szerkesztési felületén az előnézet linkjének javítása (készült generate_link függvény)

Változások 2017.07.19.
======================

- cikkek migrálásának előkészítése, SQL szkriptek írása
- a kedvezményes vásárlás a '0'-t tartalmazó mező esetén sem jelenik meg
- cikkek megjelenítő listájában a lead min(<br />, </p>) hosszúságú lett
- meták migrálásának előkészítése, SQL szkriptek írása
- kategóriák kezeléséből kivételre került a cikkek-címkék számlálása

Változások 2017.07.20.
======================

- cikkekből a div-ek törlése szkript megírása
- a mutat.php-s linkek módosítási szkriptjének frissítése
- a publikus felületeken a dátum szebb kijelzése
- ha a cikk adatainak füle üres, nem jelenik meg az 'Adatok' szöveg
- user_link létrehozása a cikkíró cikkeinek listájához az egyes cikklistákban
- bugfix: cikkek szerkesztése listához left joinnal csatlakozzon a users tábla

Változások 2017.07.21.
======================

- image tábla eltávolítása: image_path az articles része, metódusok módosítása, az articles-ből kikerült az image_id
- cikkfeltöltésnél automatikusan a cikkfeltöltő nevéhez kerül

Változások 2017.07.25.
======================

- style.css változása: p-nél mindenféle margin/padding 0 pixel lett
- cikkfeltöltés során hibás űrlapnál sem tölti fel többször a képet
- ha üres (nincs címke) a 'kapcsolódó tartalmak' oldalrész, nem jelenik meg a szöveg

Változások 2017.07.26.
======================

- editimage kivétel a szövegeditorból, így nem lehet már blobként képet menteni szövegbe
- a szövegeditor nem alkalmaz HTML-entity-kódolást
- a szövegközbeni képek mappáját frissítő szkript megírása
- szövegközbeni képfeltöltő az uploads/articles mappába tölt

Változások 2017.08.01.
======================

- users tábla migrációs szkriptje
- "Jelszó legalább öt karakter hosszú legyen" szabály kikapcsolása
- ha a jelszó sha1-ben nem stimmel, megpróbálja md5-tel is
- statikus cikkek kézi migrálása

Változások 2017.08.23.
======================

- mysql frissítése 5.7-re -> fulltext index az articles.body-ra
- autocomplete keresés a fejlécben letiltása

Változások 2017.08.24.
======================

- autocomplete keresés a fejlécben rövidebben keres, újra működik
- fulltext index az articles.title-re és a users.name-re
- keresés újraírása, match against bevezetése

Változások 2017.09.08.
======================

- fulltext index a metavalue.name-re
- keresés refaktorálása és bővítése metavalue.name-re való kereséssel

Változások 2017.09.11.
======================

- események kivétele a menüből
- users: level rendberakása, update szkript megírása
- a cikkek szerkesztése esetén minden menüben ábécérendben legyenek a felhasználónevek
- ha metát szerkesztünk, a link nem fog változni (TODO: userDoksi: szóljon!)
- 404 esetén a default 404-es oldalt veszi elő

TODO
====

- képhelyzet mappák. Várjuk cikkek random urljei.
- keresés találatainak sorrendje?: prioritás a címbéli találatnak
- szép 404
- partnerek menü

low priority TODO
=================

- last_modified mező? (régi post táblában van -> migrációnál figyelni!)
- uglify ajaxjs
- admin: user_list névre szűrés
- "<div> </div>" részeket ne törölje teljesen, legyen helyette "<p> </p>"
