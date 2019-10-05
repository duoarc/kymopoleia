<?php
    ob_start();
    session_start();
    
    require_once "./PHP/database.php";
    if(!isset($_SESSION['email'])){
         header("Location: login.php");
    }
    else{
        if(!isset($_SESSION['Budget_id'])){
            if($_GET['value']){
                $_SESSION['error'] = "";
            $data = $_GET['value'];
            $_SESSION['Budget_id']=$data;
            $sql = "SELECT * FROM BudgetDetails WHERE Budget_id = '$data' ";
            $result = $conn->query($sql);
            $Items= $result->fetch(PDO::FETCH_ASSOC);
            }else{
                $_SESSION['error'] = "select a budget from dashboard";
                header("Location: dashboard.php");
            }
        }else{
         $sql = "SELECT * FROM BudgetDetails WHERE Budget_id = '{$_SESSION['Budget_id']}'";
            $result = $conn->query($sql);
            $Items= $result->fetch(PDO::FETCH_ASSOC);
    }
    $sql2 = "SELECT * FROM budget WHERE Budget_id = '{$_SESSION['Budget_id']}' ";
    $result2 = $conn->query($sql2);
    $Budget = $result2->fetch(PDO::FETCH_ASSOC);
    $_SESSION['Amount'] = $Budget['Amount'];


    // $addExpenseSQL = "UPDATE BudgetDetails SET expense = $expense WHERE Budget_id = '{$_SESSION['Budget_id']}'";
    // $addExpenseResult = $conn->query($addExpenseSQL);
    // $addExpense = $addExpenseResult->fetch(PDO::FETCH_ASSOC);    
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Roboto|Rosarivo&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./css/sidebar.css">
    <link rel="stylesheet" href="./css/dashboard.css">
    <link rel="stylesheet" href="./css/popup.css">
    <script src="https://kit.fontawesome.com/833e0cadb7.js" crossorigin="anonymous"></script>
    <link href="https://unpkg.com/bootstrap-table@1.15.4/dist/bootstrap-table.min.css" rel="stylesheet">
    

    <title>Kymo Budget | Dashboard </title>
</head>
<body class="">
    <header>
    <nav>
            <div class="brandname">
                <h2 class="header-brandname"><a href="index.php"><img src="images/kymo.png" alt=""> </a></h2>
            </div>
            <p class="welcome_user"><span class="blueText"><?php if(isset($_SESSION['firstname'])){echo $_SESSION['firstname'];};  ?><?php if(isset($_SESSION['userData'])){echo $_SESSION['userData']['usernames'];}  ?></span></p>
            <img class='user-avatar' src="images/user.png" alt="">
            <div class="dropdown">
                    <div class="dropdown-toggler" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <img src="./images/drop.png" alt="">
                    </div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="dashboard.php"><?php  echo($_SESSION['usernames']); ?></a>
						<a class="dropdown-item" href="change_password.php">Change Password</a>
                        <a class="dropdown-item" href="logout.php">Sign out</a>
                    </div>
                  </div>

        </nav>
        
       
       
</div>
</div>        

    </header>

    <main>
        <?php include "./PHP/sidebar.php"; ?>
        <div id="main">
        <button class="openbtn" onclick="openNav()">☰</button>
        <section class="buget__dashboard">
            <div class="container">
                <div class="welcome__text">
                    <p class="welcome__user"><span class="dashboard__username"><?php  if(isset($SESSION['lastname'])){echo $_SESSION['lastname']; }   ; echo "&nbsp;"; ?></span> Here are your budget items feel free to add and remove.</p>
                    <div class="budget__info">
                        <div>
                            <i style='color: #FD4720;' class="fas fa-wallet fa-2x"></i>
                        </div>
                        <div>
                            <div class="pushLeft">
                                <p><span class="cBlue">Budget Title:</span> <?php echo $_SESSION['Budget_id']  ; ?></p>
                                <p style="display: inline; color: #000000;">Total budget Amount</p>
                                <p ><?php echo($Budget['Amount'])?></p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                    <!-- <table
                    id="table"
                    data-toggle="table"
                    data-search="true"
                    data-show-columns="true"
                    data-pagination="true"
                    data-filter-control="true"
                    data-show-search-clear-button="true">
                <thead>
                  <tr>
                    <th data-field="id" data-sortable="true">Budgets</th>
                    <th data-field="hotel_name" data-sortable="true" data-filter-control="input">Date added</th>
                    <th data-field="state" data-sortable="true" data-filter-control="select">Time due</th>
                    <th data-field="state" data-sortable="true" data-filter-control="select">Number of items</th>
                  </tr>
                </thead>
                <tbody class="budgets">
                  {% for key in data %}
                  <tr class="single_budget" data-name="{{key.budget}}">
                      <td>{{data.index(key) + 1}}</td>
                      <td> {{ key.budgets }} </td>
                      <td> {{ key.date_added }} </td>
                      <td> {{ key.time_due }} </td>
                      <td> {{ key.number_of_items }} </td>
                  </tr>
                  {% endfor %}
              </tbody>
              </table> --> -->


              <table
              id="table"
            data-toggle="table"
            data-search="true"
            data-show-columns="true"
            data-pagination="true"
            data-pagination-pre-text="Previous"
            data-pagination-next-text="Next"
            data-mobile-responsive="true"
            data-check-on-init="true"
            >
    
            <thead>
                <tr>
                <th>Items</th>
                <th>Description</th>
                <th>Priority</th>
                <th>Amount Budgeted</th>
                <th>Amount Expended</th>
                <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php do{?>
                <tr id="tr-id-1" class="tr-class-1" data-title="bootstrap table" data-object='{"key": "value"}'>
                    <td>
                        <a href="#"><?php  echo($Items['Item']);?></a>
                    </td>
                    <td width="100px" data-value="526"><?php  echo($Items['description']);?></td>
                    <td data-value=""><?php  echo($Items['Priority']);?></td>
                    <td class="amount__budgeted" data-value="<?php  echo($Items['Amount']);?>">₦ <?php  echo($Items['Amount']);?></td>
                    <td class="amount__expended" >₦ <?php  echo($Items['expense']);?></td>
                    <!-- <td><button type="button" onclick="return enterExpense('<?php echo($Budgets['Budget_id']); ?>')" class="btn btn-primary">Enter Amount Expended</button></td> -->
                    <td><button type="button" onclick="openForm()" class="btn btn-primary">Update Expense</button></td>
                </tr>
                <?php }while($Items =$result->fetch(PDO::FETCH_ASSOC))?>
            </tbody>
            </table>
            <input type="hidden" name="hidden" id="hidden" class="form-control" >
            <a type="button" href="addBudgetItems.php" class="btn btn-success" id="add-row"><i class="fa fa-plus"></i> Update/Add Budget Item</a>
        </div>
        </section>
        <div class="form-popup" id="updateExpense">
          <form action="/action_page.php" class="form-container">
            <h1>Expense</h1>

            <label for="expense"><b>Amount Spent</b></label>
            <input type="text" placeholder="Enter Amount in Naira" name="expense" required>

            <!-- <label for="psw"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="psw" required> -->

            <a href="" ><button type="submit" class="btn">Enter</button></a>
            <button type="submit" class="btn cancel" onclick="closeForm()">Close</button>
          </form>
        </div>
    </div>
    </main>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="./js/dashboardNew.js"></script>
<script src="./js/sidebar.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://unpkg.com/bootstrap-table@1.15.4/dist/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.15.4/dist/extensions/mobile/bootstrap-table-mobile.min.js"></script>
 <script>
  function deleteRow(r) {
        console.log(r);
        var v = r;
            document.getElementById('hidden').val = v;
           console.log(document.getElementById('hidden').val);
        window.location="../budget/view-budget.html?value=" +document.getElementById('hidden').val;
    }
 </script>  
   <!-- END SIDEBAR MENU -->
   <script>

    function openForm() {
  document.getElementById("updateExpense").style.display = "block";
}

function closeForm() {
  document.getElementById("updateExpense").style.display = "none";
}

  function deleteRow(r) {
        console.log(r);
        var v = r;
            document.getElementById('hidden').val = v;
           console.log(document.getElementById('hidden').val);
       window.location="view-budget.php?value=" +document.getElementById('hidden').val;
    }
    function openNav() {
  document.getElementById("mySidebar").style.width = "300px";
  document.getElementById("mySidebar").style.display = "block";
  document.getElementById("main").style.marginLeft = "300px";
    }

    /* Set the width of the sidebar to 0 and the left margin of the page content to 0 */
    function closeNav() {
    document.getElementById("mySidebar").style.width = "0";
    document.getElementById("mySidebar").style.display = "none";
    document.getElementById("main").style.marginLeft = "0";
    }
    /* Set amount expended */
    function enterExpense() {
        document.getElementById("amountExpended")

    }
 </script> 
 
</body>
</html>