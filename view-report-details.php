<?php 
    include "databaseConnect.php";
    $reportid = "";
    if(isset($_POST['viewereportid']) && !empty($_POST['viewereportid'])){
        $reportid = $_POST['viewereportid'];
    }else{
        exit;
    }

    $userId = "";
    if(isset($_POST['viewuserId']) && !empty($_POST['viewuserId'])){
        $userId = $_POST['viewuserId'];
    }else{
        exit;
    }

    $permissionType = "";
    if(isset($_POST['permissionType']) && !empty($_POST['permissionType'])){
        echo $permissionType = $_POST['permissionType'];
    }else{
        exit;
    }

    $testDetailsSql = "select * from tbltestresults where patientid = $userId AND reportid = $reportid ORDER BY 
    CASE
      WHEN status = 'low' THEN 1
      WHEN status = 'high' THEN 2
      WHEN status = 'normal' THEN 3
    END;";
    $detailsstmt = $conn->prepare($testDetailsSql);
    $detailsstmt->execute();
?>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>View Detailed Report</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <style>
        @media only screen and (min-width: 768px) {
            .main-header {
                margin-left: 0 !important;
                border-bottom: none;
            }
        }

        .navbar {
            display: block !important;
        }
        .hide{
            display:none;
        }
        .overlay{
            position: absolute;
            top: 50%;
            left: 50%;
            z-index: 99999;
        }
        .overlay i{
            font-size: 50px;
            color: #0090ff;
        }
    </style>
</head>

<body>
    <div class="wrapper">
    <div class="overlay hide">
        <i class="fa fa-spinner fa-spin"></i>
    </div>
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <!-- <i class="fas fa-edit"></i> -->
                        Detailed Report
                    </h3>
                </div>
                <div class="card-body">
                    <h4>Menu</h4>
                    <div class="row">
                        <div class="col-5 col-sm-3">
                            <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist"
                                aria-orientation="vertical">
                                <a class="nav-link active" id="vert-tabs-home-tab" data-toggle="pill"
                                    href="#vert-tabs-home" role="tab" aria-controls="vert-tabs-home"
                                    aria-selected="true">Home</a>
                                <?php if($permissionType == 3){?>
                                    <a class="nav-link" id="vert-tabs-profile-tab" data-toggle="pill"
                                        href="#vert-tabs-profile" role="tab" aria-controls="vert-tabs-profile"
                                        aria-selected="false">Chart View</a>
                                    <!-- <a class="nav-link" id="vert-tabs-messages-tab" data-toggle="pill"
                                        href="#vert-tabs-messages" role="tab" aria-controls="vert-tabs-messages"
                                        aria-selected="false">Summary</a> -->
                                <?php }?>
                            </div>
                        </div>
                        <div class="col-7 col-sm-9">
                            <div class="tab-content" id="vert-tabs-tabContent">
                                <div class="tab-pane text-left fade show active" id="vert-tabs-home" role="tabpanel"
                                    aria-labelledby="vert-tabs-home-tab">
                                    <?php 
                                        if ($detailsstmt->rowCount() > 0) {   
                                    ?>
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Test Wise Details</h3>
                                        </div>
                                        <div class="card-body table-responsive p-0">
                                            <table class="table table-hover text-nowrap">
                                                <thead>
                                                    <tr>
                                                        <th>Test Name</th>
                                                        <th>Test Result</th>
                                                        <!-- <th>Minimum Value</th> -->
                                                        <!-- <th>Maximum Value</th> -->
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                            $testArr = array();
                                                            while($getTestDetails = $detailsstmt->fetch(PDO::FETCH_ASSOC)){
                                                                $getTestNameValSql = "select * from tbltest where testid =".$getTestDetails['testid'];
                                                                $stmt = $conn->query($getTestNameValSql);
                                                                if ($stmt->rowCount() > 0) {
                                                                    $getTestNameVal = $stmt->fetch(PDO::FETCH_ASSOC);

                                                                    $testArr[$getTestDetails['testid']] = $getTestNameVal['testname'];
                                                                }
                                                        ?>
                                                    <tr>
                                                        <td><?=$getTestNameVal['testname']?></td>
                                                        <td><?=$getTestDetails['testvalue']?><?php //echo $getTestNameVal['unit']?>
                                                        </td>
                                                        <!-- <td><?=$getTestNameVal['testminvalue']?><?=$getTestNameVal['unit']?>
                                                        </td> -->
                                                        <!-- <td><?=$getTestNameVal['testmaxvalue']?><?=$getTestNameVal['unit']?>
                                                        </td> -->
                                                        <?php 
                                                        $statusClr = "";
                                                        if($getTestDetails['status'] == 'normal'){
                                                            $statusClr = '<span style="color:#000">Normal</span>';
                                                        }else if($getTestDetails['status'] == 'low'){
                                                            $statusClr = '<span style="color:red">Low</span>';
                                                        }else if($getTestDetails['status'] == 'high'){
                                                            $statusClr = '<span style="color:green">High</span>';
                                                        }
                                                        
                                                        ?>
                                                        <td><?=$statusClr?></td>
                                                    </tr>
                                                    <?php }?>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                    <?php       
                                        }else{
                                            echo "No data Found.";
                                        }
                                    ?>
                                </div>
                                <div class="tab-pane fade" id="vert-tabs-profile" role="tabpanel"
                                    aria-labelledby="vert-tabs-profile-tab">
                                    <div class="row mb-4">
                                        <div class="col-md-6"></div>
                                        <div class="col-md-6">
                                            <select class="form-control select2 select2-primary"
                                                data-dropdown-css-class="select2-primary" style="width: 100%;"
                                                onchange="getTestResults(this.value);">
                                                <option value="">Select Test Type</option>
                                                <?php foreach ($testArr as $testid_ => $testname_) {?>
                                                <option value="<?=$testid_?>"><?=$testname_?></option>
                                                <?php }?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="card card-info chartBox hide">
                                        <div class="card-body">
                                            <div class="chart">
                                                <canvas id="lineChart"
                                                    style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="vert-tabs-messages" role="tabpanel"
                                    aria-labelledby="vert-tabs-messages-tab">
                                    Morbi turpis dolor, vulputate vitae felis non, tincidunt congue mauris. Phasellus
                                    volutpat
                                    augue id mi placerat mollis. Vivamus faucibus eu massa eget condimentum. Fusce nec
                                    hendrerit
                                    sem, ac tristique nulla. Integer vestibulum orci odio. Cras nec augue ipsum.
                                    Suspendisse ut
                                    velit condimentum, mattis urna a, malesuada nunc. Curabitur eleifend facilisis velit
                                    finibus
                                    tristique. Nam vulputate, eros non luctus efficitur, ipsum odio volutpat massa, sit
                                    amet
                                    sollicitudin est libero sed ipsum. Nulla lacinia, ex vitae gravida fermentum, lectus
                                    ipsum
                                    gravida arcu, id fermentum metus arcu vel metus. Curabitur eget sem eu risus
                                    tincidunt
                                    eleifend ac ornare magna.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </nav>
    </div>
    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- Select2 -->
    <script src="plugins/select2/js/select2.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })
        })

        var myLineChart;

    function getTestResults(val) {
        $('.overlay').removeClass('hide');
        if (myLineChart) {
            myLineChart.destroy();
        }

        $.ajax({
            url: "get-chart-data-process.php",
            type: "POST",
            data: {
                test_id: val,
            },
            success: function (data) {
                try {
                    // Parse the JSON data received from the server
                    var testResults = JSON.parse(data);

                    // Extract data for chart
                    var labels = [];
                    var testvalues = [];

                    for (var i = 0; i < testResults.length; i++) {
                        var dateTimeComponents = testResults[i].uploadeddatetime.split(" ");

                        // Use only the date part (index 0)
                        var datePart = dateTimeComponents[0];

                        // Push the date to the labels array
                        labels.push(datePart);
                        testvalues.push(testResults[i].testvalue);
                    }

                    // Check if there are valid data for the chart
                    if (labels.length === 0 || testvalues.length === 0) {
                        console.error('No valid data available for the chart.');
                        return;
                    }

                    if (labels.length === 1) {
    // Use a small offset for the duplicated data
    var offset = 0.000001; // You can adjust this value based on your data range

    labels.push(labels[0]);
    testvalues.push(testvalues[0] + offset);
}

                    $('.overlay').addClass('hide');
                    $('.chartBox').removeClass('hide');

                    // Chart configuration
                    var lineChartData = {
                        labels: labels,
                        datasets: [{
                            label: 'Test Values',
                            borderColor: 'rgba(60,141,188,0.8)',
                            pointRadius: false,
                            pointColor: '#3b8bba',
                            pointStrokeColor: 'rgba(60,141,188,1)',
                            pointHighlightFill: '#fff',
                            pointHighlightStroke: 'rgba(60,141,188,1)',
                            data: testvalues
                        }]
                    };

                    var lineChartOptions = {
                        maintainAspectRatio: false,
                        responsive: true,
                        legend: {
                            display: false
                        },
                        scales: {
                            xAxes: [{
                                type: 'time',
                                time: {
                                    unit: 'day',
                                    displayFormats: {
                                        day: 'YYYY-MM-DD HH:mm:ss' // Adjust the format based on your actual date-time format
                                    }
                                },
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Date'
                                }
                            }],
                            yAxes: [{
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Test Value'
                                }
                            }]
                        }
                    };

                    // Render the chart
                    var lineChartCanvas = $('#lineChart').get(0).getContext('2d');
                    myLineChart = new Chart(lineChartCanvas, {
                        type: 'line',
                        data: lineChartData,
                        options: lineChartOptions
                    });
                } catch (error) {
                    console.error('Error parsing or processing data:', error);
                }
            },
            error: function (xhr, status, error) {
                console.error('Ajax request failed. Status:', status, 'Error:', error);
            }
        });
    }
    </script>
</body>

</html>