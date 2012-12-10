<?php


// pagination list function
function pagedrows($params){
	/*
	
		params :
			fields
			table
			where
			order
			limit
			page
	
	*/

	$result = array();

	// page limit
	if( !$params['limit'] ) $params['limit'] = 20;

	// fields
	if( !is_array($params['fields']) ) $params['fields'] = explode(' ', $params['fields']);

	// where
	if( $params['where'] ) $where = ' where '.$params['where'];
	else $where = '';

	// order
	if( $params['order'] ) $order = ' order by '.$params['order'];
	else $order = '';

	// getting row count
	$countQuery = mysql_query('select count('. $params['fields'][0] .') as rowcount from '. $params['table'] . $where);
	if( !is_resource($countQuery) ) return false;
	$countQuery = mysql_fetch_assoc($countQuery);
	$result['count'] = $countQuery['rowcount'];
	if( !$result['count'] || $result['count'] == 0 ) return false;

	// calculating page count
	$result['pageCount'] = ceil($result['count'] / $params['limit']);
	$result['limit']     = $params['limit'];

	// is page number valid
	if( $params['page'] < 1 || $params['page'] > $result['pageCount'] ){
		$result['page'] = 1;
	}else{
		$result['page'] = $params['page'];
	}

	// generating limit
	$start = ($result['page']-1)*$result['limit'];
	$limit = $start . ', ' . $result['limit'];

	// getting rows
	$query = mysql_query('select `' . implode('`,`', $params['fields']) . '` from ' . $params['table'] . $where . $order . ' limit ' . $limit);
	while ($row = mysql_fetch_assoc($query)) {
	    $result['results'][] = $row;
	}

	// return results
	return $result;
}



// grid function
function grid($params){
    $gridHTML = '';
    
	// fields 
	if( $params['fields'] ){
		foreach ($params['fields'] as $key => $value){
		    if (isset($value[4])) {
			    if( $value[4] !== false ) 
			        $fields[] = $key;
		    } else {
		        $fields[] = $key;
		    }
		}
	}else{
		// getting tables all fields
		$query = mysql_query('select * from '. $params['table'] .' limit 0, 1');
		$query = mysql_fetch_assoc($query);
		foreach ($query as $key => $value){
			$params['fields'][ $key ] = array( ucfirst(str_replace('_', ' ', $key)), false, true, true );
			$fields[] = $key;
		}
	}
	

	// default where clause if has
	if( $params['where'] ) $where[] = $params['where'];

	// default order
	if (isset($_GET['grid']['order'])) $order = $_GET['grid']['order'];
	else $order = false;
	if( !$order && $params['order'] ) $order = $params['order'];
	if (isset($_GET['grid']['sort'])) $sort = $_GET['grid']['sort'];
	else $sort = false;
	if( !$sort && $params['sort'] ) $sort = $params['sort'];


	// search
	if( isset($_GET['grid']['search']) && isset($_GET['grid']['search_field']) ){
	    if( $_GET['grid']['search'] && $_GET['grid']['search_field'] ){
	        // search field is searchable?
    		if( $params['fields'][ $_GET['grid']['search_field'] ][3] == true ){
    			// adding where array
    			$where[] = mysql_real_escape_string($_GET['grid']['search_field']) . ' like "%'. $_GET['grid']['search'] .'%"';
    		}
	    }
	}
	
	// page
	if (isset($params['page'])) $page = $params['page'];
	elseif (isset($_GET['page'])) $page = $_GET['page'];
	else $page = false;

	// getting paged rows
	$rows = pagedrows(array(
		'table'  => $params['table'],
		'fields' => $fields,
		'limit'  => $params['limit'],
		'order'  => mysql_real_escape_string($order) .' '. mysql_real_escape_string($sort),
		'where'  => ( $where ? (count($where) == 1 ? $where[0]:implode(' and ', $where)):false ),
		'page'   => $page
	));

	// post process
	if( $params['do'] && $rows['results'] ){
		foreach ($rows['results'] as $key => $value){
		    $rows['results'][ $key ] = $params['do']($value);
		}
	}

	// search form
	foreach ($params['fields'] as $key => $field){
	    if (isset($field[3])) {
	        if( $field[3] == true ) $searchables[] = $key;
	    }
	}
	
	if( $params['searchable'] && $searchables ){
	    if (isset($_GET['grid']['search_field'])) $searh_field = $_GET['grid']['search_field'];
	    else $searh_field = false;
	    
	    if (isset($_GET['grid']['search'])) $search_query = $_GET['grid']['search'];
	    else $search_query = '';
	    
	    
		// starting to build search form
		$gridHTML .= '<div class="search"><form method="get"><ul>';
			$gridHTML .= '<li><select name="grid[search_field]">';
				foreach ($searchables as $field){
					$gridHTML .= '<option value="'. $field .'" '. ($searh_field == $field ? 'selected="true"':'') .'>'. $params['fields'][ $field ][0] .'</option>';
				}
			$gridHTML .= '</select></li>';
			$gridHTML .= '<li><input type="input" name="grid[search]" value="'. $search_query .'"></li>';
			$gridHTML .= '<li><input type="submit" value="Search"></li>';
			if( isset($_GET['grid']['search']) ){
			    if( strlen($_GET['grid']['search']) > 0 ){
				    $gridHTML .= '<li><a href="?grid[search]=&grid[search_field]=" class="clearSearch">Clear Search</a></li>';
			    }
			}
		$gridHTML .= '</ul></form><div class="clear"></div></div>';
	}

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

	// printing rows
	if( count($rows['results']) > 0 ){
		$gridHTML .= "\n".'<table class="grid">'."\n";

			// headers (fields)
			$gridHTML .= "\t<tr>\n";
				foreach ($params['fields'] as $key => $field){
					if( isset($field[2]) ){
					    if ($field[2] == true) {
						    $head_content = '<a href="?grid[order]='. $key .'&grid[sort]='. (($order == $key && $sort == 'asc') ? 'desc':'asc') .'">'. $field[0] .'</a>';					        
					    } else {
					        $head_content = $field[0];
					    }
					}else{
						$head_content = $field[0];
					}
					$gridHTML .= "\t\t".'<td class="head col_'. $key .' '. ( $order == $key ? 'sort_'.$sort : '' ) .'">'. $head_content .'</td>'."\n";
				}
			$gridHTML .= "\t</tr>\n";

			// rows
			$i = 0;
			foreach ($rows['results'] as $row){
				$gridHTML .= "\t".'<tr class="row '. ($i%2 == 0 ? 'zebra':'') .'">'."\n";
					foreach ($params['fields'] as $key => $field){
						$gridHTML .= "\t\t".'<td class="cell col_'. $key .'">'. $row[ $key ] .'</td>'."\n";
					}
				$gridHTML .= "\t</tr>\n";
				$i++;
			}

		$gridHTML .= "</table>\n";
		$gridHTML .= '<style type="text/css" media="screen">'."\n";
			foreach ($params['fields'] as $key => $field){
				if( $field[1] ){
					$gridHTML .= "\t.grid .col_$key { width: $field[1]px; }\n";
				}
			}
		$gridHTML .= "</style>\n\n";

		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

		// pagination
		if( !isset($params['pageLink']) ) $params['pageLink'] = '?page=__page__';
		if( isset($_GET['grid']) ){
			foreach ($_GET['grid'] as $key => $val){
				$params['pageLink'] .= '&grid['. $key .']=' . $val;
			}
		}

		// automatic pagination type defination
		if( isset($params['pagination']) ){
			if( $rows['pageCount'] > 20 ) $params['pagination'] = 'select';
			else $params['pagination'] = 'link';
		}

        if($rows['pageCount'] > 1){
		    $gridHTML .= '
    		<div class="pagination">
    			<ul>
    				';

    				// previous page
    				if( $rows['page'] > 1 ){
    					$gridHTML .= '<li class="prev"><a href="'. str_replace('__page__', ($rows['page']-1), $params['pageLink']) .'">'.( isset($params['prevPage']) ? $params['prevPage'] : 'Previous' ).'</a></li>';
    				}else{
    					$gridHTML .= '<li class="prev"><span>'.( isset($params['prevPage']) ? $params['prevPage'] : 'Previous' ).'</span></li>';
    				}

    				// pages
    				if (!isset($params['pagination'])) $params['pagination'] = 'select';
    				if( $params['pagination'] == 'select' ){
    					// combobox pagination
    					$gridHTML .= '<li class="selectLeft"><select class="pageSelect">';
    						for ($i=1; $i <= $rows['pageCount']; $i++){ 
    							$gridHTML .= '<option '.( $i == $rows['page'] ? 'selected':'' ).'>'. $i .'</option>';
    						}
    					$gridHTML .= '</select></li>';
    					$gridHTML .= '<li class="selectMid">/</li>';
    					$gridHTML .= '<li class="selectRight">'. $rows['pageCount'] .'</li>';
    				}else{
    					// link style pagination
    					for ($i=1; $i <= $rows['pageCount']; $i++){ 
    						$gridHTML .= '<li>'.( $i == $rows['page'] ? '<span class="selected">'. $i .'</span>':'<a href="'. str_replace('__page__', $i, $params['pageLink']) .'">'. $i .'</a>' ).'</li>';
    					}
    				}

    				// next page
    				if( $rows['page'] < $rows['pageCount'] ){
    					$gridHTML .= '<li class="next"><a href="'. str_replace('__page__', ($rows['page']+1), $params['pageLink']) .'">'.( isset($params['nextPage']) ? $params['nextPage'] : 'Next' ).'</a></li>';
    				}else{
    					$gridHTML .= '<li class="next"><span>'.( isset($params['nextPage']) ? $params['nextPage'] : 'Next' ).'</span></li>';
    				}

    				$gridHTML .= '
    			</ul>
    		</div>
    		';
        }

	}else{
		$gridHTML .= '<div class="no-rows">No results</div>';
	}


	return '<div class="grid">'. $gridHTML .'</div>';
}

