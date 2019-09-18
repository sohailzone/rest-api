<?php
  $request = $_SERVER['REQUEST_METHOD'];
  $response = array();
  switch ($request) {
    case 'GET':
      getResponse(doGet());
      break;
    case 'POST':
      getResponse(doPost());
      break;
    case 'PUT':
      getResponse(doPut());
      break;
    case 'DELETE':
      getResponse(doDelete());
      break;
    default:
      // code...
      break;
  }
  function doGet(){
    if(@$_GET['id']){
      @$id = $_GET['id'];
      $where = "WHERE `s_id` = ".$id;
    }else{
      $id = 0;
      $where = "";
    }
    $db_connect = mysqli_connect('localhost','root', '', 'test');
    $query = mysqli_query($db_connect, "SELECT * FROM student ".$where);
    while($data = mysqli_fetch_assoc($query)){
        $response[] = array('Name' => $data['s_name'],'College Name' => $data['s_college_name'], 'Email ID' => $data['email'] );
    }
    return $response;
  }

  function doPost(){
    if($_POST){
      $db_connect = mysqli_connect('localhost','root', '', 'test');
      $query = mysqli_query($db_connect, "INSERT INTO `student` (`s_name`, `s_college_name`, `email`) VALUES('".$_POST['s_name']."', '".$_POST['s_college_name']."', '".$_POST['email']."')");
      if($query == true)
        $response = array("message" => "Post success");
      else
        $response = array("message" => "Post failed");
      }
    return $response;
  }

  function doPut(){
    parse_str(file_get_contents('php://input'), $_PUT);
    //print_r($_PUT);
    if($_PUT){
      $db_connect = mysqli_connect('localhost','root', '', 'test');
      $query = mysqli_query($db_connect, "UPDATE student SET
        `s_name` = '".$_PUT['s_name']."',
        `s_college_name` = '". $_PUT['s_college_name'] ."',
        `email` = '".$_PUT['email'] ."'  WHERE `s_id` = '".$_GET['id']."' ");
      if($query){
        $response = array('message' => " Put success");
      }else {
        $response = array('message' => "Put` failed");
      }
    }
    return $response;
  }

  function doDelete(){
    if($_GET['id']){
      $db_connect = mysqli_connect('localhost','root', '', 'test');
      $query = mysqli_query($db_connect, "DELETE FROM student WHERE `s_id` = '".$_GET['id']."' ");
      if($query){
        $response = array('message' => "Delete success");
      }else {
        $response = array('message' => "Delete failed");
      }
    }
    return $response;
  }

  function getResponse($response){
      echo json_encode(array("data" => $response));
  }
?>
