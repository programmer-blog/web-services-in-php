<?php

 /** 
  @Description: Book Information Server Side Web Service:
  This Sctript creates a web service using NuSOAP php library. 
  fetchBookData function accepts ISBN and sends back book information.
  @Author:  http://programmerblog.net/
  @Website: http://programmerblog.net/
 */
 require_once('dbconn.php');
 require_once('lib/nusoap.php'); 
 $server = new nusoap_server();

/* Fetch 1 book data */
function fetchBookData($isbn){
	global $dbconn;
	$sql = "SELECT id, title, author_name, price, isbn, category FROM books 
	        where isbn = :isbn";
  // prepare sql and bind parameters
    $stmt = $dbconn->prepare($sql);
    $stmt->bindParam(':isbn', $isbn);
    // insert a row
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    return json_encode($data);
    $dbconn = null;
}
$server->configureWSDL('booksServer', 'urn:book');
$server->register('fetchBookData',
			array('isbn' => 'xsd:string'),  //parameter
			array('data' => 'xsd:string'),  //output
			'urn:book',   //namespace
			'urn:book#fetchBookData' //soapaction
			);  
$server->service(file_get_contents("php://input"));

?>