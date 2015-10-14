<?php

/**
 * A Framework API
 *
 * Supporting GET, POST, PUT, and DELETE
 *
 * @author Jarrod Sampson
 * @copyright 2015 Planlodge
 *
 */

include_once('access.php');

class FrameworkAccessClass extends AccessObject
{

	public function __construct()
	{
	      header('Access-Control-Allow-Origin: *');
		  header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
		  header('Content-type:application/json;charset=utf-8');
		  parent::__construct();
	}

	public function databaseConnection()
    {
		// check database connections
        $conn       = mysqli_connect($this->host, $this->username, $this->password, $this->database);
        $connection = true;
        
        if ($conn) {
            $connection = true;
        } else {
            $connection = false;
            echo "There was an error, please contact web administrator.";
            return false;
        }
        
        return $conn;
	}
	
	public function getRequestParam()
	{
		
		if (isset($_GET['query']))
		{
			$query = $_GET['query'];

			// check database connections
			$conn = $this->databaseConnection();
			
			if ($conn) {

				echo json_encode(array(
					'success' => 'Query Set'
				));
					
				$queries = new Queries;
				$sqlQuery   = $queries->fetchFrameworksSearch();
				
				$result = mysqli_query($conn, $sqlQuery);
				
				while ($row = mysqli_fetch_assoc($result)) {
					
					$framework = $row['framework'];
					$language  = $row['language'];
						$link = $row['link'];
						$description = $row['description'];

					$frameworks[] = array(
						'framework' => $framework,
						'language' => $language,
						'link' => $link,
						'description' => $description
					);
				}
				
				$output = json_encode(array(
					'data' => $frameworks
				));
				
				echo $output;

			} else {
				$connection = false;
				echo "There was an error, please contact web administrator.";
				return false;
			}



		}
		else
		{
		
			// check database connections
			$conn = $this->databaseConnection();
			
			if ($conn) {
				
				$queries = new Queries;
				$sqlQuery   = $queries->fetchAllFrameworks();
				
				$result = mysqli_query($conn, $sqlQuery);
				
				while ($row = mysqli_fetch_assoc($result)) {
					
						$framework = $row['framework'];
						$language  = $row['language'];
						$link = $row['link'];
						$description = $row['description'];

					$frameworks[] = array(
						'framework' => $framework,
						'language' => $language,
						'link' => $link,
						'description' => $description,
						
						'id' => $row['id']
					);
				}
				
				$output = json_encode(array(
					'data' => $frameworks
				));
				
				echo $output;
				
				
				
			} else {
				$connection = false;
				echo "There was an error, please contact web administrator.";
				return false;
			}
		}
	
	}
	
	public function postFramework()
    {
		// check database connections
		$conn = $this->databaseConnection();
		
		if ($conn) {
			
			
			$framework   = trim($_POST['framework']);
			$language    = trim($_POST['language']);
			$link        = trim($_POST['link']);
			$description = trim($_POST['description']);
			
			$queries = new Queries;
			$sqlQuery   = $queries->newFrameworkAddition($framework, $language, $link, $description);
				
			$result = mysqli_query($conn, $sqlQuery);
			if ($result) {
				echo json_encode(array(
					'success' => 'Created Framework Entry.'
				));
			} else {
				echo json_encode(array(
					'error' => 'Unable to Add Request.'
				));
			}	
			
		} else {
			$connection = false;
			echo "There was an error, please contact web administrator.";
			return false;
		}
	}
	
	public function deleteFramework()
    {
		// check database connections
		$conn = $this->databaseConnection();
		
		parse_str(file_get_contents("php://input"), $post_vars);
		$framework = $post_vars['framework'];
		
		if ($conn) {

			$queries = new Queries;
			$sqlCheck   = $queries->checkFrameworkIfExists($framework);
			$checkResult = mysqli_query($conn, $sqlCheck);
			if ($checkResult) 
			{

				$sqlQuery   = $queries->deleteFrameworkQuery($framework);
				$result = mysqli_query($conn, $sqlQuery);
				if ($result) {
					echo json_encode(array(
						'success' => 'Deleted ' . $framework
					));
				} else {
					echo json_encode(array(
						'error' => 'Unable to Delete ' . $framework
					));
				}


			}
			else
			{
				echo json_encode(array(
					'error' => $framework . ' does not exist.'
				));
			}
			
		} 
		else 
		{
			$connection = false;
			echo "There was an error, please contact web administrator.";
			return false;
		}
				
			
	}
	
	public function updateFramework()
    {
		// check database connections
		$conn = $this->databaseConnection();
		
		parse_str(file_get_contents("php://input"), $post_vars);
		$id          = $post_vars['id'];
		$framework   = trim($post_vars['framework']);
		$language    = trim($post_vars['language']);
		$link        = trim($post_vars['link']);
		$description = trim($post_vars['description']);
		
		if ($conn) {
        
			$queries = new Queries;
			$sqlQuery   = $queries->updateFrameworkQuery($id, $framework, $language, $link, $description);
			
				$result = mysqli_query($conn, $sqlQuery);
				if ($result) {
					echo json_encode(array(
						'success' => 'Updated ' . $framework
					));
				} else {
					echo json_encode(array(
						'error' => 'Unable to Update ' . $framework
					));
				}
			
			
			
			
			
		} else {
			$connection = false;
			echo "There was an error, please contact web administrator.";
			return false;
		}
				
			
	}
}

class Queries
{
	public function fetchAllFrameworks()
	{
		$query  = "SELECT * FROM Frameworks";
		
		return $query;
	}

	public function fetchFrameworksSearch()
	{
		$query  = "SELECT * FROM Frameworks WHERE framework LIKE '%$query%' OR description LIKE '%$query%' OR language LIKE '%$query%' LIMIT 10";
		
		return $query;
	}
	
	public function newFrameworkAddition($framework, $language, $link, $description)
	{
		$query  = "INSERT INTO Frameworks VALUES('','$framework','$language','$link','$description')";
		
		return $query;
	}
	
	public function deleteFrameworkQuery($framework)
	{
		$query  = "DELETE FROM Frameworks WHERE framework = '$framework'";
		
		return $query;
	}
	
	public function updateFrameworkQuery($id, $framework, $language, $link, $description)
	{
		$query  = "UPDATE Frameworks SET framework = '$framework', language = '$language', link = '$link', description = '$description' WHERE id = '$id'";
		
		return $query;
	}
	
	public function checkFrameworkIfExists($framework)
	{
		$query  = "SELECT * FROM Frameworks WHERE framework = '$framework'";
		
		return $query;
	}
}

$frameobj = new FrameworkAccessClass; 

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

	$frameobj->getRequestParam();
	
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	$frameobj->postFramework();
 
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

	$frameobj->deleteFramework();

}


if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

	$frameobj->updateFramework();
    
} 


?>
