SELECT `articles`.*, `subcategory`.`name` AS `subcat_name`, 
    `subcategory`.`slug` AS `subcat_slug`, `users`.`name` AS `user_name` 
FROM `articles`
LEFT JOIN `subcategory` ON `subcategory`.`id` = `articles`.`subcategory_id` 
LEFT JOIN `users` ON `users`.`id` = `articles`.`user_id` 
WHERE `articles`.`published` = 1 
AND `articles`.`pub_time` <= '2017-08-24 18:39:10' 
AND ( MATCH(articles.body) AGAINST('*miéville*' IN BOOLEAN MODE) 
    OR MATCH(users.name) AGAINST('*miéville*' IN BOOLEAN MODE) ) 
GROUP BY `articles`.`id` 
ORDER BY `pub_time` DESC

SELECT `articles`.*, `subcategory`.`name` AS `subcat_name`, 
    `subcategory`.`slug` AS `subcat_slug`, `users`.`name` AS `user_name`
FROM `articles`
LEFT JOIN `subcategory` ON `subcategory`.`id` = `articles`.`subcategory_id`
LEFT JOIN `users` ON `users`.`id` = `articles`.`user_id`
WHERE `articles`.`published` = 1
AND `articles`.`pub_time` <= '2017-08-24 18:55:25'
AND  ( MATCH(articles.body) AGAINST('+*stephen* +*king* ' IN BOOLEAN MODE) 
    OR MATCH(users.name) AGAINST('+*stephen* +*king* ' IN BOOLEAN MODE))
GROUP BY `articles`.`id`
ORDER BY `pub_time` DESC

SELECT `articles`.*, `subcategory`.`name` AS `subcat_name`, 
	`subcategory`.`slug` AS `subcat_slug`, `users`.`name` AS `user_name`
FROM `articles`
LEFT JOIN `subcategory` ON `subcategory`.`id` = `articles`.`subcategory_id`
LEFT JOIN `users` ON `users`.`id` = `articles`.`user_id`
LEFT JOIN `meta_value_article` ON `meta_value_article`.`article_id` = `articles`.`id`
LEFT JOIN `metavalue` ON `metavalue`.`id` = `meta_value_article`.`metavalue_id`
WHERE `articles`.`published` = 1
AND `articles`.`pub_time` <= '2017-09-08 17:06:11'
AND  ( MATCH(articles.body) AGAINST('+*stephen* +*king* ' IN BOOLEAN MODE) 
    OR MATCH(users.name) AGAINST('+*stephen* +*king* ' IN BOOLEAN MODE)
	OR MATCH(metavalue.name) AGAINST('+*stephen* +*king* ' IN BOOLEAN MODE)) 
GROUP BY `articles`.`id`
ORDER BY `pub_time` DESC

SELECT `articles`.*, `subcategory`.`name` AS `subcat_name`, 
    `subcategory`.`slug` AS `subcat_slug`, `users`.`name` AS `user_name`
FROM `articles`
LEFT JOIN `subcategory` ON `subcategory`.`id` = `articles`.`subcategory_id`
LEFT JOIN `users` ON `users`.`id` = `articles`.`user_id`
WHERE `articles`.`published` = 1
AND `articles`.`pub_time` <= '2017-08-24 18:32:18'
AND (`articles`.`body` LIKE '%miéville%') OR (`users`.`name` LIKE '%miéville%')
GROUP BY `articles`.`id`
ORDER BY `pub_time` DESC 

        /*$like_part = '(';
		foreach($tables_for_filter as $tff)
		{
			foreach($filter_array as $f)
				$like_part .= $tff . " LIKE '%" . $f . "%' AND ";
				
			$like_part = substr($like_part, 0, -5);
			$like_part .= ') OR (';
		}
		$like_part = substr($like_part, 0, -4);*/

        /*$like_part = '(';
		foreach($filter_array as $f)
			$like_part .= 'articles.title' . " LIKE '%" . $f . "%' AND ";
		$like_part = substr($like_part, 0, -5);
		$like_part .= ')';*/