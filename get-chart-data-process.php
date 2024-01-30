<?php 
    include "databaseConnect.php"; 

    $testid_ = '';
    if(isset($_POST['test_id']) && !empty($_POST['test_id'])){
        $testid_ = $_POST['test_id'];
    }

    $selectSql = "SELECT testvalue, uploadeddatetime FROM tbltestresults WHERE testid = $testid_ ORDER BY uploadeddatetime DESC LIMIT 10";
    $stmt = $conn->query($selectSql);

    $response = array();
    if ($stmt->rowCount() > 0) {
        while ($getTestResults = $stmt->fetch(PDO::FETCH_ASSOC)) {
            foreach ($getTestResults as $key => $value) {
                if ($key === 'testvalue') {
                    $getTestResults[$key] = preg_replace("/[^0-9]/", "", $value);
                }
            }
            $response[] = $getTestResults;
        }
    }

    echo json_encode($response);

?>


