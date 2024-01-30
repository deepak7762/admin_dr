<?php
if(isset($_GET['pg']))
{
	$page = ($_GET['pg']);
	if($page)
	{
		$start = ($page - 1) * $numRecordPerPage; 
	}
	else
	{
		$start = 0;
	}
}	
else
{
	$start = 0;
}
function printPagination($pageName,$numRecordPerPage,$totalRecord,$parameters)
{

$targetpage = $pageName;
$total_pages = $totalRecord;
if(isset($_GET['pg']))
{
	$page = ($_GET['pg']);
	if($page)
	{
		$start = ($page - 1) * $numRecordPerPage; 
	}
	else
	{
		$start = 0;
	}
}	
else
{
	$start = 0;
	$page = 0;
}
$stages = 5;
if ($page == 0){$page = 1;}
	$prev = $page - 1;	
	$next = $page + 1;							
	$lastpage = ceil($total_pages/$numRecordPerPage);		
	$LastPagem1 = $lastpage - 1;



	$pagination = '';
	if($lastpage > 1)
	{	
		
		$pagination .= "";
		// Previous
		if ($page > 1){
			
			if(strlen($parameters) >0 )
				$pagination.= "<li><a href='$targetpage?$parameters&pg=$prev' class=\"prev\"><i class=\"fa fa-long-arrow-left\"></i>  Previous</a></li>";
			else
				$pagination.= "<li><a href='$targetpage?pg=$prev' class=\"prev\"><i class=\"fa fa-long-arrow-left\"></i>  Previous</a></li>";
		}else{
			$pagination.= "";	}
			

		
		// Pages	
		if ($lastpage < 7 + ($stages * 2))	// Not enough pages to breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page){
					$pagination.= "<li class='active'><a >$counter</a></li>";
				}else{
					if(strlen($parameters) > 0)
						$pagination.= "<li><a href='$targetpage?$parameters&pg=$counter'>$counter</a></li>";
					else
						$pagination.= "<li><a href='$targetpage?pg=$counter'>$counter</a></li>";
					}					
			}
		}
		elseif($lastpage > 5 + ($stages * 2))	// Enough pages to hide a few?
		{
			// Beginning only hide later pages
			if($page < 1 + ($stages * 2))		
			{
				for ($counter = 1; $counter < 4 + ($stages * 2); $counter++)
				{
					if ($counter == $page){
						$pagination.= "<li class='active'><a class='current'>$counter</a></li>";
					}else{
						if(strlen($parameters) > 0)
							$pagination.= "<li><a href='$targetpage?$parameters&pg=$counter'>$counter</a></li>";					
						else
							$pagination.= "<li><a href='$targetpage?pg=$counter'>$counter</a></li>";
						}					
				}
				$pagination.= "<li>...</li>";
				if(strlen($parameters) > 0)
				{	
					$pagination.= "<li><a href='$targetpage?$parameters&pg=$LastPagem1'>$LastPagem1</a></li>";
					$pagination.= "<li><a href='$targetpage?$parameters&pg=$lastpage'>$lastpage</a></li>";
				}
				else
				{
					$pagination.= "<li><a href='$targetpage?pg=$LastPagem1'>$LastPagem1</a></li>";
					$pagination.= "<li><a href='$targetpage?pg=$lastpage'>$lastpage</a></li>";
				}
			}
			// Middle hide some front and some back
			elseif($lastpage - ($stages * 2) > $page && $page > ($stages * 2))
			{
				if(strlen($parameters) > 0)
					{
					$pagination.= "<li><a href='$targetpage?$parameters&pg=1'>1</a></li>";
					$pagination.= "<li><a href='$targetpage?$parameters&pg=2'>2</a></li>";
					}
				else
					{
					$pagination.= "<li><a href='$targetpage?pg=1'>1</a></li>";
					$pagination.= "<li><a href='$targetpage?pg=2'>2</a></li>";
					}
				$pagination.= "<li>...</li>";
				for ($counter = $page - $stages; $counter <= $page + $stages; $counter++)
				{
					if ($counter == $page){
						$pagination.= "<li class='active'><a class='current'>$counter</a></li>";
					}else{
						if(strlen($parameters) > 0)
							$pagination.= "<li><a href='$targetpage?$parameters&pg=$counter'>$counter</a></li>";
						else
							$pagination.= "<li><a href='$targetpage?pg=$counter'>$counter</a></li>";
						}					
				}
				$pagination.= "<li>...</li>";
				if(strlen($parameters) > 0)
				{
					$pagination.= "<li><a href='$targetpage?$parameters&pg=$LastPagem1'>$LastPagem1</a></li>";
					$pagination.= "<li><a href='$targetpage?$parameters&pg=$lastpage'>$lastpage</a></li>";		
				}
				else
				{
					$pagination.= "<li><a href='$targetpage?pg=$LastPagem1'>$LastPagem1</a></li>";
					$pagination.= "<li><a href='$targetpage?pg=$lastpage'>$lastpage</a></li>";		
				}

			}

			// End only hide early pages
			else
			{
				if(strlen($parameters) > 0)
				{
					$pagination.= "<li><a href='$targetpage?$parameters&pg=1'>1</a></li>";
					$pagination.= "<li><a href='$targetpage?$parameters&pg=2'>2</a></li>";
				}
				else
				{
					$pagination.= "<li><a href='$targetpage?pg=1'>1</a></li>";
					$pagination.= "<li><a href='$targetpage?pg=2'>2</a></li>";
				}
				$pagination.= "<li>...</li>";
				for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page){
						$pagination.= "<li class='active'><a class='current'>$counter</a></li>";
					}else{
						if(strlen($parameters) > 0)
							$pagination.= "<li><a href='$targetpage?$parameters&pg=$counter'>$counter</a></li>";
						else
							$pagination.= "<li><a href='$targetpage?pg=$counter'>$counter</a></li>";
						}					
				}
			}
		}
					
				// Next
		if ($page < $counter - 1){ 
			if(strlen($parameters) > 0)
				$pagination.= "<li><a href='$targetpage?$parameters&pg=$next' class=\"next\">Next <i class=\"fa fa-long-arrow-right\"></i></a></li>";
			else
				$pagination.= "<li><a href='$targetpage?pg=$next' class=\"next\">Next <i class=\"fa fa-long-arrow-right\"></i></a></li>";
		}else{
			$pagination.= "";
			}
			
		$pagination.= "";
	
	
}
 echo $pagination;
}
?>	
