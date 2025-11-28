<?php

//mb_internal_encoding("UTF-8");

include_once("ez_sql.php");

global $db;

$query = "SELECT store_tracks.track_id, name, store_tracks.price, COUNT(*) AS 'num_tracks'
		  FROM stats_add_to_cart
		  INNER JOIN store_tracks
		  ON stats_add_to_cart.track_id = store_tracks.track_id
		  GROUP BY stats_add_to_cart.track_id
		  ORDER BY num_tracks DESC
		  LIMIT 0,5";

$results = $db->get_results($query);

include_once("./html/top5.html");

?>
