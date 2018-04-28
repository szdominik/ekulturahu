migráció utáni TODO
===================

- metavalue szabad* és type: 0 törlések

SQL szkriptek
=============
ekult_olvaso: fájlnév pipa, if not exists, tranzakció, mezőneveket, kiterjesztett

SELECT 
`id` , 
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
`image_id`,
0 AS `image_horizontal`,
`body`
FROM  `post`
ORDER BY `id`

//category rendbetévő
UPDATE articles SET category_id = 1 WHERE category_id = 3;
UPDATE articles SET category_id = 3 WHERE category_id = 2;
UPDATE articles SET category_id = 2 WHERE category_id = 4;

// subcat_lekérdező
SELECT CONCAT( post.id,  ',' ) AS result
FROM  `post` 
LEFT JOIN  `postmetadata` ON postmetadata.post_id = post.id
WHERE metadatavalue_id =13642

//metadata
SELECT 
`id`,
`metadata_id` AS `type`,
`value` AS `name`,
`value_unacc` AS `slug`
FROM `metadatavalue`

//postmetadata
SELECT `id`,
`post_id` AS `article_id`,
`metadatavalue_id` AS `metavalue_id`
FROM `postmetadata`

UPDATE metavalue SET type = 3 WHERE type = 4;
UPDATE metavalue SET type = 4 WHERE type = 36;
UPDATE metavalue SET type = 5 WHERE type = 37;
UPDATE metavalue SET type = 6 WHERE type = 105;
UPDATE metavalue SET type = 7 WHERE type = 315;
UPDATE metavalue SET type = 8 WHERE type = 41;
UPDATE metavalue SET type = 9 WHERE type = 312;
UPDATE metavalue SET type = 10 WHERE type = 104;
UPDATE metavalue SET type = 11 WHERE type = 98;

DELETE FROM `meta_value_article` WHERE article_id = 0 OR metavalue_id = 0;

//cikk-típus
DELETE FROM `meta_value_article` WHERE meta_value_article.metavalue_id IN 
(SELECT metavalue.id FROM metavalue WHERE type = 322)

DELETE FROM `metavalue` WHERE TYPE = 322;
DELETE FROM `metavalue` WHERE TYPE = 0;

UPDATE articles
SET image_path = (SELECT path FROM image WHERE image.id = articles.image_id);

//image_name-es post migráció
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

//users
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