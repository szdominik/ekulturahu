migráció utáni TODO
===================

-- metavalue szabad* törlések - elvileg ok, de csekkolni érdemes:
SELECT * FROM `metavalue` WHERE `name` LIKE  'szabad%';

SQL szkriptek
=============
ekult_olvaso: fájlnév pipa, if not exists, tranzakció, mezőneveket, kiterjesztett

-- cikkek kiolvasása
SELECT 
post.id AS `id` , 
`title` ,  
`title_unacc` AS  `slug`,
`published`,
`published_at` AS `pub_time`,
`site_id` AS `category_id`,
`folder_id` AS `subcategory_id`,
`mainpage`,
0 AS `login`,
`commentable` AS `comment`,
`user_id`,
`kedv_vasar`,
`eredeti_cim`,
`ar`,
CONCAT(`hossz`, `oldalszam`) AS `terjedelem`,
`forgatokonyviro`,
`operator`,
`producer`,
image.filename AS `image_path`,
0 AS `image_horizontal`,
`body`
FROM  `post`
LEFT JOIN `image` ON image.id = post.image_id
ORDER BY `id`

-- category rendbetévő
UPDATE articles SET category_id = 1 WHERE category_id = 3;
UPDATE articles SET category_id = 3 WHERE category_id = 2;
UPDATE articles SET category_id = 2 WHERE category_id = 4;

-- subcat lekérdező & rendbetevő
SELECT CONCAT( post.id,  ',' ) AS result
FROM  `post` 
LEFT JOIN  `postmetadata` ON postmetadata.post_id = post.id
WHERE metadatavalue_id = 13642 -- interjú
WHERE metadatavalue_id = 13641 -- életrajz
WHERE metadatavalue_id = 13643 -- beszámoló

UPDATE articles SET subcategory_id = 7 WHERE subcategory_id = 5;
UPDATE articles SET subcategory_id = 6 WHERE id IN (); -- interjú
UPDATE articles SET subcategory_id = 3 WHERE id IN (); -- életrajz
UPDATE articles SET subcategory_id = 5 WHERE id IN (); -- beszámoló
-- hírek maradhat 1
-- ajánlók maradhat 2
-- részlet maradhat 4

-- metavalue tábla tartalma
SELECT 
`id`,
`metadata_id` AS `type`,
`value` AS `name`,
`value_unacc` AS `slug`
FROM `metadatavalue`

-- meta_value_article tábla tartalma
SELECT `id`,
`post_id` AS `article_id`,
`metadatavalue_id` AS `metavalue_id`
FROM `postmetadata`

-- metavalue tábla rendbetétele (metatype / kategória oszlop frissítése)
UPDATE metavalue SET type = 3 WHERE type = 4;
UPDATE metavalue SET type = 4 WHERE type = 36;
UPDATE metavalue SET type = 5 WHERE type = 37;
UPDATE metavalue SET type = 6 WHERE type = 105;
UPDATE metavalue SET type = 7 WHERE type = 315;
UPDATE metavalue SET type = 8 WHERE type = 41;
UPDATE metavalue SET type = 9 WHERE type = 312;
UPDATE metavalue SET type = 10 WHERE type = 104;
UPDATE metavalue SET type = 11 WHERE type = 98;

-- szeméttörlések:
DELETE FROM `meta_value_article` WHERE article_id = 0 OR metavalue_id = 0;

-- felesleges cikk-típus metaadat törlése
DELETE FROM `meta_value_article` WHERE meta_value_article.metavalue_id IN 
(SELECT metavalue.id FROM metavalue WHERE type = 322)

DELETE FROM `metavalue` WHERE TYPE = 322;
DELETE FROM `metavalue` WHERE TYPE = 0;

-- users
SELECT 
ID AS id,
IF(`Username` = 'null', CONCAT(`Username`, `ID`) , 
IF(`Username` = '', CONCAT('null', `ID`), `Username`)) AS username,
`Password` AS password,
`Teljes_nev` AS name,
`Email` AS email,
`role` AS level
FROM `ekult_users`
WHERE (`Email` LIKE '%gmail.%')
OR (`Email` LIKE '%.hu%')
OR (`Email` LIKE '%hotmail.%')
OR (`Email` LIKE '%yahoo%')
OR (`Email` = '')

UPDATE users SET level = 5 WHERE level = 3;
UPDATE users SET level = 3 WHERE level = 1;
UPDATE users SET level = 1 WHERE level = 0;
UPDATE users SET level = 6 WHERE level = 4;
UPDATE users SET level = 4 WHERE level = 2;
UPDATE users SET level = 2 WHERE level = 6;

-- missing: quotes -- talán nem is kell
-- missing: calendar