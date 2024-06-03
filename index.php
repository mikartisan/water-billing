<?php 
    session_start();
    require_once("db_connection.php");

    //CHECK IF ALREADY LOGIN
    if(!isset($_SESSION['login'])){
        // not logged in
        header('Location: auth/login.php');
        exit();
    }

    //SESSION
    $addMessage = $_SESSION['add-message'] ?? "";
    $username = $_SESSION['user'] ?? "";

    //GET CUBIC PRICE
    $cubic_price = mysqli_query($conn,"SELECT * FROM cu_price");
    if(mysqli_num_rows($cubic_price) > 0){
        while($row_price = mysqli_fetch_assoc($cubic_price)){
            extract($row_price);
        }
    } 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link href="css/style.css" rel="stylesheet" />
    <script src="js/tata.js"></script>
    <link rel="shortcut icon" href="images/favicon.png"/>	
    
    <title>Water Billing</title>
</head>
<body>
    <div class="sidebar">
        <div class="logo_content">
            <div class="logo">
                <i style="color: #80cae5;" class="bx bx-droplet"></i>
                <div class="logo_name">Water Data</div>
            </div>
            <i class="bx bx-menu" id="btn"></i>
        </div>

        <!-- <ul class="nav_list">
            <li>
                <div style="background-color: red;" class="search-box">
                    <i class='bx bx-search'></i>
                    <input type="text" autocomplete="off" placeholder="Search...">
                    <div class="result"></div>
                </div>
            </li>
        </ul> -->

        <ul class="nav_list">
            <li>
                <a href="index.php" class="active" title="Dashboard">
                <i class="bx bx-grid-alt"></i>
                <span class="links_name">Dashboard</span>
                </a>
            </li>
        
            <li>
                <a href="clients.php" title="Clients">
                <i class='bx bx-user'></i>
                <span class="links_name">Clients</span>
                </a>
            </li>
        
            <li>
                <a href="history.php" title="History">
                <i class='bx bx-history'></i>
                <span class="links_name">History</span>
                </a>
            </li>

            <li>
                <a href="setting.php" title="Setting">
                <i class='bx bx-cog'></i>
                <span class="links_name">Setting</span>
                </a>
        
            </li>
        </ul>

        <div class="profile_content">
            <div class="profile">
                <div class="profile_details">
                    <!-- <img src="yourimage.jpg" alt="no image"> -->
                    <div class="name_job">
                        <div class="name"><?php echo $username; ?></div>
                        <div class="job">Administrator</div>
                    </div>
                </div>
                <form action="auth/logout.php">
                    <button type="submit"><i class='bx bx-log-out' id="log_out" title="Logout"></i></button>
                </form>
            </div>
        </div>
    </div>

    <div class="home_content">
        <!-- Live Search Bar-->
        <div class="search-box">
            <div style="display: flex;">
                <i class='bx bx-search search-icon'></i>
                <input type="text" autocomplete="off" placeholder="Search...">
                <button type="button" class="btn btn-info btn-client" data-toggle="modal" data-target="#addClient"><i class='bx bx-user-plus' title="Add Client"></i></button>
                <div class="result"></div>
            </div>
        </div>

        <!-- Modal For Adding New Client -->
        <div class="modal fade" id="addClient" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form method="POST">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" id="exampleModalLabel">Add Client</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <label for="name">Name</label>
                            <input type="text" class="add-input" name="name" placeholder="Client name..">

                            <label for="address">Address</label>
                            <input type="text" class="add-input" name="address" placeholder="Client address..">

                            <label for="contact">Contact No.</label>
                            <input type="text" class="add-input" name="contact" placeholder="Contact Number..">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button formaction="./function/add-client.php" type="submit" name="submit-client" value="true" class="btn btn-info">Add Client</button>
                        </div>
                    </div>
                </form>

                <?php
                    if($addMessage=='false'){   
                        ?>
                            <script type="text/javascript">tata.error('ERROR', 'Invalid Input!', {position: 'tr', duration: 5000})</script>
                        <?php
                    } else if($addMessage=='true'){
                        ?>
                            <script type="text/javascript">tata.success('SUCCESS', 'Client Added!', {position: 'tr', duration: 5000})</script>
                        <?php
                    } 
                ?>
            </div>
        </div>

        <!-- Dashboard-->
        <div class="dashboard">
            <div class="dash-content">
                <div class="dash-icon">
                    <i class='bx bx-user-pin'></i>
                </div>
                <div class="dash-text">
                    <p>Clients</p>
                    <p>
                        <b> <!--Count Total Clients -->
                            <?php 
                                $sqlClient = "SELECT count('name') FROM client";
                                $totalClient = mysqli_query($conn,$sqlClient);
                                $row_client = mysqli_fetch_array($totalClient);
                                echo "$row_client[0]";
                            ?>
                        </b>
                    </p>
                </div>
            </div>

            <div class="dash-content">
                <div class="dash-icon">
                    <i class='bx bx-user-check'></i>
                </div>
                <div class="dash-text">
                    <p>No Balance</p>
                    <p>
                        <b> <!--Count Paid Clients -->
                            <?php 
                                $sqlPaid = "SELECT count(*) FROM payment_status WHERE status='paid'";
                                $totalPaid = mysqli_query($conn,$sqlPaid);
                                $row_Paid = mysqli_fetch_array($totalPaid);
                                echo "$row_Paid[0]";
                            ?>
                        </b>
                        [<a href="nobalance.php">Show</a>]
                    </p>
                </div>
            </div>

            <div class="dash-content">
                <div class="dash-icon">
                    <i class='bx bx-user-x'></i>
                </div>
                <div class="dash-text">
                    <p>Unpaid</p>
                    <p>
                        <b> <!--Count Unpaid Clients -->
                            <?php 
                                $sqlUnpaid = "SELECT count(*) FROM payment_status WHERE status='unpaid'";
                                $totalUnpaid = mysqli_query($conn,$sqlUnpaid);
                                $row_Unpaid = mysqli_fetch_array($totalUnpaid);
                                echo "$row_Unpaid[0]";
                            ?>
                        </b>
                        [<a href="unpaid.php">Show</a>]
                    </p>
                </div>
            </div>

            <div class="dash-content">
                <div class="dash-icon">
                    <i class='bx bx-cube-alt'></i>
                </div>
                <div class="dash-text">
                    <p>Cubic Price</p>
                    <p>
                        <b> <!--Show Cubic Price -->
                            <?php 
                                $cubic_price = mysqli_query($conn,"SELECT * FROM cu_price");
                                if(mysqli_num_rows($cubic_price) > 0){
                                    while($row = mysqli_fetch_assoc($cubic_price)){
                                        extract($row);
                                        echo "₱ ". $cu_price;
                                    }
                                }
                            ?>
                        </b>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="analystic-container">
            <div class="analystic">
                <div style="display: flex;">
                    <div class="analys-icon">
                        <i class='bx bx-line-chart'></i>
                    </div>
                    <div class="analys-text">
                        Line Graph
                    </div>
                </div>
                <div class="line-graph">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
            <div class="analystic">
                <div style="display: flex;">
                    <div class="analys-icon">
                        <i class='bx bx-money'></i>
                    </div>
                    <div class="analys-text">
                        Statement Summary
                    </div>
                </div>
                <div class="dash-amount">
                    <table class="table">
                        <thead class="thead-light">
                            <tr>
                            <th scope="col">Month</th>
                            <th scope="col">Cubic Consume</th>
                            <th scope="col">Amount</th>
                            </tr>
                        </thead>
                        <tbody style="overflow-y: auto;">
                            <!-- Get the total Collectibles Per Month-->
                            <?php

                                $listMonth = array(
                                    'january',
                                    'february',
                                    'march',
                                    'april',
                                    'may',
                                    'june',
                                    'july',
                                    'august',
                                    'september',
                                    'october',
                                    'november',
                                    'december'
                                );

                                foreach($listMonth as $index => $value) {
                                    $cuTotal = mysqli_query($conn, 'SELECT SUM('.$listMonth[$index].') AS cubic_sum FROM cubic_consume'); 
                                    $row_cubic = mysqli_fetch_assoc($cuTotal); 
                                    $cubic_sum = $row_cubic['cubic_sum'];
                            ?>
                                    <tr>
                                        <th scope="row"><?php echo ucwords($listMonth[$index]); ?></th>
                                        <td>
                                            <?php
                                                if($cubic_sum!=0) {
                                                    echo $cubic_sum;
                                                } else {
                                                    echo "--";
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                $collectMonthTotal = $cubic_sum * $cu_price; 
                                                if($collectMonthTotal!=0) {
                                                    echo "₱ ". $collectMonthTotal;
                                                } else {
                                                    echo "--";
                                                }
                                            ?>
                                        </td>
                                    </tr>
                            <?php 
                                }
                            ?>

                            <!-- Total Year Consume -->
                            <tr style="border-top: 2px solid gray;">
                                <?php 
                                    $cuTotalYear = mysqli_query($conn, 'SELECT SUM(cubic_sum) AS year_sum FROM cubic_consume'); 
                                    $rowYearCubic = mysqli_fetch_assoc($cuTotalYear); 
                                    $yearCubicSum = $rowYearCubic['year_sum'];
                                ?>
                                <th scope="row">TOTAL </th>
                                <td><?php echo $yearCubicSum; ?></td>
                                <td>₱ <?php echo $yearCubicSum * $cu_price; ?></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- custom script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="js/script.js"></script>
    <!-- <script src="js/line-graph.js"></script> -->

    <script>
        var xValues = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sept','Oct','Nov','Dec'];

        new Chart("myChart", {
        type: "line",
        data: {
            labels: xValues,
            datasets: [{ 
            data: [100,1140,1060,1060,1070,1110,1330,2210,7830,2478],
            borderColor: "#b3b3ff",
            fill: false
            }]
        },
        options: {
            legend: {display: false}
        }
});
    </script>
</body>
</html>
<?php
    unset($_SESSION["add-message"]);
?>