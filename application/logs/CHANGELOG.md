# CHANGELOG

## Változások 2019.08.18.
## RELEASE v4.0.1

- bugfix: google+ logó törlése a fejlécből
- címkelista admin oldal linkesítve sorolja fel a címkéket
- bugfix: kódolási probléma az ő/ű karakterekkel a keresőmezőben
- a keresési listában enter lenyomásával is kiválaszthatunk egy elemet
- bugfix: cikk szerkesztési nézetben a gyorsbillentyűk ne csak OSX-en működjenek - átállás alt-ról shift-re
- a fekvő képek kevésbé torzítottan jelenjenek meg a cikklisták "dobozaiban"
- minden cikkmentésnél formázzuk a tartalmat: sorkizártak, betűtípus-változtatások törlése
- bugfix: cikktörlésnél ne keresse a comments táblát
- bugfix: a www-vel kezdődő urlekben cors policy miatt nem működnek az ajax hívások: inkább redirecteljünk www-ről

## Változások 2019.01.13.

- bugfix: a "szerzok" legyen alias metatype

## Változások 2019.01.07.

- bugfix: ne csak 100-ig inkrementálja a fájlnevet képfeltöltésnél

## Változások 2019.01.06.

- önálló controller/model/route az alkalmanként lefuttatandó feladatoknak
- önálló adminfelület az alkalmanként lefuttatandó feladatoknak
- sitemap.xml generálás task
- új cikk gomb a cikkek listájához
- új meta hozzáadásánál, a típus váltása után ugorjon a lista elejére, ne maradjon az előző görgetési pozícióban
- legyen a tinymce szövegszerkesztőnek saját css stílusa
- képfeltöltés újragondolása: a max. méret már 4MB, viszont 1200 feletti szélesség vagy magasság esetében újraméretezzük a képet
- keresési várakoztatás csökkentése

## Változások 2019.01.04.

- a "mutat.php?id=" kezdetű linkek frissítése
- google analytics ne számolja a fejlesztés alatti kattintások és az adminoldal kattintásait
- bugfix: az error oldal ne szálljon el
- bugfix: keresés javítása, fogadjon el írásjeleket és többszörös szóközt is
- bugfix: felesleges "height: auto" törlése a cikközbeni képekről
- bugfix: felesleges outline törlése a hamburger buttonről
- a meták törlésekor jelenjen meg, hogy hány darab van belőle használatban
- canonical (gyűjtő) oldal megadása a googlenek
- robots.txt frissítése

## Változások 2018.12.09.

- bugfix: feleslegesen ne próbáljon short_body-t generálni
- bugfix: cikk fekvő főképére a megfelelő css szabályok érvényesüljenek
- bugfix: rendes og tagek a listanézetekhez is
- bugfix: a kapcsolódó tartalmak cím ne jelenjen meg, ha van meta, de nincs szerző vagy sorozat
- bugfix: hiányzó favicon pótlása
- bugfix: hiányzó lapozás a cikkek szerkesztése lista alján
- "Vissza a cikkek listájához" gomb a cikkek szerkesztéséhez
- bugfix: legyen button a menügombból

## RELEASE v4.0.0 - 2018.11.24.

- new htaccess
- production configs

## Változások 2018.11.03.

- képek (listanézetek és cikkhez tartozó képek) újraméretezése
- ALT+C/ALT+V gyorsbillentyűk, illetve dupla kattintás egy címke hozzáadásának megkönnyítésére egy cikknél
- bugfix: a meta_type-ban a "Nincs kiválasztva" az első legyen, ne az utolsó
- magyarázó tooltip a címkéknek szóló hotkey-ekhez
- bugfix: footer pozícionálása nyitott menünél
- a cikkek szerkesztésénél legyen link a cikk címe is

## Változások 2018.10.14.

- próba migráció (adatbázis + képek)
- további migrációs szkriptek
- további kompatibilitási 301-es / 410-es átirányítások

## Változások 2018.10.06.

- legyenek a videók reszponzívak
- újabb 'kapcsolódó tartalmak' térköz fixálás, nyilak átrendezése
- meta és meta:og tagek újragondolása, bővítése
- 301-es redirectek bevezetése az elrontott, de megtalálható URL-ekhez
- átírányítások a 3.0 URL-jei alapján - 410 Gone, ha már nem támogatjuk
- error page-ek összevonása egy fájl alá
- migrációs szkriptek előkészítése

## Változások 2018.09.29.

- a menü mobilon teljes képernyő szélességű
- eltűnt a óra-perc a cikkadatok közül
- a listanézetekben eltűnt az "Írta:" megjelölés
- a listanézetek képei scale effektet kaptak
- a kapcsolódó tartalmaknál a kép is legyen link
- menü ikon svg-k helyett animált html-elemek
- Google Analytics hozzáadása a rendszerhez
- alapszintű robots.txt hozzáadása

## Változások 2018.08.05.

- logó csere .svg-re
- a cikklistákban a címek is linkek
- cikk törlése gomb eltüntetése 4-es hozzáférési szint alatt
- főoldali lapozás elkészítése (egyben a cikklista mostanatól tartalmazza a home.php-t)
- footer bootstrapmentesítése (padding-bug fix)
- cikklisták / lapozók / footer közötti hely pontosítása
- sorkizárt szövegek eltörlése az adatbázisból szkript

## Változások 2018.07.29.

- 'Hozzászólások kezelése' menüpont kivétele a menüből
- bugfix: RSS-ből a `&` jelek escapelése

## Változások 2018.07.22.

- 'kapcsolódó tartalmak' térköz fixálás & stílus hozzáadása a címsorhoz alapból
- a cikkekbeli képek legyenek reszponzívak
- a menü rövidítése
- a 'kiadás éve' típusú címke legyen fordítva (a legnagyobbtól a legkisebbig) rendezve
- a cikkszöveg fogadjon el kézileg beillesztett iframe tageket
- a keresési találatok teljes listája legyen kattintható, ne csak a szöveg
- bugfix: dátum mező adatbázisba töltésének javítása
- bugfix: cikk módosítás esetén, űrlap hiba után nem tűnik el a kép
- jelszó generálás átállás default php password_hash-re (bcrypt)

## Változások 2018.07.14.

- stílusbeli hibajavítások (kategória címkék a listák dobozaiban)
- session library automatikus betöltésének megszüntetése, ehhez kapcsolódó refaktorálás
- admin felület rendbeszedése (placeholderek, comment & login article property-k nem szerkeszthetővé tétele)
- egyedi 404-es oldal készítése
- meta tagek átnézése, robots & ie=edge tag hozzáadása
- 'Főoldal' szó kivétele főoldal esetében a title tagből
- tinyMCE upgrade & nagyobb height

## Változások 2018.07.09.

- költözés tesztszerverre, migráció, kép áttöltések és képrefaktorálások
- stílusbeli hibajavítások (mobil header és menü, cikkekben lévő képek margója)

## Változások 2018.06.03.

- div_delete helyettesítése div_replace-szel
- fejlécben lévő keresés 10 találatot ad vissza
- keresés előrébb rendezi azokat a találatokat, ahol a címben megtalálhatóak a keresett szavak

## Változások 2018.05.13.

- RSS feed generálás és megjelenítés

## Változások 2018.05.12.

- új design: a 'kapcsolódó cikkek' lapozása
- default kép megjelenítése, ha az "igazi" nem elérhető
- a user_list adminoldalon szűrési opció a felhasználók nevére

## Változások 2018.05.05-06.

- új design: article page & menu refaktorálás

## Változások 2018.04.29-30.

- új design: header, footer, főoldal és listaoldal sitebuild & refactor
- CodeIgniter upgrade 3.1.8-ra

## Változások 2018.04.28.

- költözés GitHubra és Macre

## Változások 2017.09.11.

- események kivétele a menüből
- users: level rendberakása, update szkript megírása
- a cikkek szerkesztése esetén minden menüben ábécérendben legyenek a felhasználónevek
- ha metát szerkesztünk, a link nem fog változni (TODO: userDoksi: szóljon!)
- 404 esetén a default 404-es oldalt veszi elő

## Változások 2017.09.08.

- fulltext index a metavalue.name-re
- keresés refaktorálása és bővítése metavalue.name-re való kereséssel

## Változások 2017.08.24.

- autocomplete keresés a fejlécben rövidebben keres, újra működik
- fulltext index az articles.title-re és a users.name-re
- keresés újraírása, match against bevezetése

## Változások 2017.08.23.

- mysql frissítése 5.7-re -> fulltext index az articles.body-ra
- autocomplete keresés a fejlécben letiltása

## Változások 2017.08.01.

- users tábla migrációs szkriptje
- "Jelszó legalább öt karakter hosszú legyen" szabály kikapcsolása
- ha a jelszó sha1-ben nem stimmel, megpróbálja md5-tel is
- statikus cikkek kézi migrálása

## Változások 2017.07.26.

- editimage kivétel a szövegeditorból, így nem lehet már blobként képet menteni szövegbe
- a szövegeditor nem alkalmaz HTML-entity-kódolást
- a szövegközbeni képek mappáját frissítő szkript megírása
- szövegközbeni képfeltöltő az uploads/articles mappába tölt

## Változások 2017.07.25.

- style.css változása: p-nél mindenféle margin/padding 0 pixel lett
- cikkfeltöltés során hibás űrlapnál sem tölti fel többször a képet
- ha üres (nincs címke) a 'kapcsolódó tartalmak' oldalrész, nem jelenik meg a szöveg

## Változások 2017.07.21.

- image tábla eltávolítása: image_path az articles része, metódusok módosítása, az articles-ből kikerült az image_id
- cikkfeltöltésnél automatikusan a cikkfeltöltő nevéhez kerül

## Változások 2017.07.20.

- cikkekből a div-ek törlése szkript megírása
- a mutat.php-s linkek módosítási szkriptjének frissítése
- a publikus felületeken a dátum szebb kijelzése
- ha a cikk adatainak füle üres, nem jelenik meg az 'Adatok' szöveg
- user_link létrehozása a cikkíró cikkeinek listájához az egyes cikklistákban
- bugfix: cikkek szerkesztése listához left joinnal csatlakozzon a users tábla

## Változások 2017.07.19.

- cikkek migrálásának előkészítése, SQL szkriptek írása
- a kedvezményes vásárlás a '0'-t tartalmazó mező esetén sem jelenik meg
- cikkek megjelenítő listájában a lead min(`<br />`, `</p>`) hosszúságú lett
- meták migrálásának előkészítése, SQL szkriptek írása
- kategóriák kezeléséből kivételre került a cikkek-címkék számlálása

## Változások 2017.07.13.

- cikkek adminfelületi listájához készült publikáltakra szűrési opció
- olvasnivalo/ajanlok/cikk/dátum/title típusú (2-3.0) linkek támogatása
- cikkek listázása során a "Végére" gomb megfelelő időben válik aktívvá/tiltottá
- cikkek listázása során a cikkszerző neve is kattintható link
- cikkek listázása meta alapján: ha a link végén nem $limit-tel osztható szám szerepel, nem vesszük figyelembe (3.0 linkproblémájának áthidalása)
- címkék adminfelületi listájáhou készült névre szűrési opció
- cikkek szerkesztési felületén az előnézet linkjének javítása (készült generate_link függvény)

## Változások 2017.07.12.

- a fejlécről lekerült a regisztráció, a felhasználók kezeléséhez felkerült
- a fejlécről lekerült a belépés, a láblécre felkerült
- a fejlécről lekerült a beállítások gomb, a láblécre felkerült
- jelszóemlékeztető gomb eltávolításra került a belépési felületekről
- a kategória-alkategória összekapcsolás gomb eltávolításra került a kategóriák kezeléséből
- a hozzászólások kezelése oldalon a táblázatbeli cikkek linkjének javítása
- hozzászólási lehetőség eltávolításra került a cikkek alól

## Szakdolgozat leadás után

- teljes -> megjelenítendő név
- naptár helyes ékezet
- publikálás szerkesztők <
- fekvő kép
- cikk hozzászólás: név megjelenik
- cikkmegjelenítés meta alapján: különböző típusok működnek
- menüben alkategória szerinti keresés (update: a link miatt változott)
- keresés bővítés / legördülő lista
- keresésnél először a title egyezés
- kapcsolódó tartalmak
- CodeIgniter 2.x.x -> 3.0.6
- PRG pattern: login, logout, comment, search
- hozzászólásnál link törlésre/szerkesztésre
- login: redirect
- pontosabb, oldalanként működő pagination
- új link a cikkeknek (/2015/04/18/china-mieville-patkanykiraly)
- routing egyszerűsítés, felesleges linkrészek eltüntetése (meta, search)
- category eltüntetése (linkek, kiírások)
- statisztika - v0.9 (adatbázis mentés, slugok alapján)
- WYSIWYG editor (TinyMCE) testreszabása, szövegközbeni képfeltöltéssel
- feltöltött képek átnevezése (img_N), átméretezése
- head egyszerűsítése (és scripts template bevezetése)
- cikkszerzőre való keresés

### adatbázis módosítások

- articles: +images_horizontal
- statistics tábla
- minden utf8_hungarian_ci