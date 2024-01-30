<?php

function report_process_api_fun($patientID, $reportID, $reportPath){
  $url = 'http://18.234.116.52:8000/process_reports?patient_id=' . $patientID . '&report_id=' . $reportID . '&report_path_s3=' . $reportPath;
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
  ));

  $response = curl_exec($curl);

  curl_close($curl);
  return $response;
}
