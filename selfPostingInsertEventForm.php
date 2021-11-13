<?php
    
    $dateInserted = currentDateUSFormat();
    $dateUpdated = currentDateUSFormat();

    function currentDateUSFormat(){
        $date = date_default_timezone_set("America/Chicago");   // sets the date to central US time since server is in EU
        $date = date("m/d/Y");                                  // assign formatted date in $date variable 
        return $date;                                           // return the formatted date
        
    }// end currentDateUSFormat()
    
    function currentDateSqlFormat()
    {
        $date = date_default_timezone_set("America/Chicago");   // sets the date to central US time since server is in EU
        $date = date("Y-m-d");                                  // assign formatted date in $date variable   
        return $date;                                           // return the sql formatted date
    }// end currentDateSqlFormat()

    if(isset($_POST['submit'])){

        // honeypot validation
        $host = $_POST['events_host'];
        if(!empty($host)){
            header("refresh:0");    // refreshes page if text field is not empty
        }else{
            $eventName = $_POST['events_name'];
            $eventDescription = $_POST['events_description'];
            $eventPresenter = $_POST['events_presenter'];
            $eventDate = $_POST['events_date'];
            $eventTime = $_POST['events_time'];
            $eventDateInserted = currentDateSqlFormat();
            $eventDateUpdated = currentDateSqlFormat();
            
            try {       
                require 'dbConnect.php';	//CONNECT to the database
                
                //Create the SQL command string
                $sql = "INSERT INTO wdv341_events (";   // db table columns
                $sql .= "events_name, ";
                $sql .= "events_description, ";
                $sql .= "events_presenter, ";
                $sql .= "events_date, ";
                $sql .= "events_time, ";
                $sql .= "events_date_inserted, ";
                $sql .= "events_updated_date ";
                $sql .= ") VALUES (";                   // values for columns
                $sql .= ":eventName, ";
                $sql .= ":eventDescription, ";
                $sql .= ":eventPresenter, ";
                $sql .= ":eventDate, ";
                $sql .= ":eventTime, ";
                $sql .= ":eventDateInserted, ";
                $sql .= ":eventDateUpdated)";
                
                //PREPARE the SQL statement
                $stmt = $conn->prepare($sql);
                
                //BIND the values to the input parameters of the prepared statement
                $stmt->bindParam(':eventName', $eventName);
                $stmt->bindParam(':eventDescription', $eventDescription);		
                $stmt->bindParam(':eventPresenter', $eventPresenter);		
                $stmt->bindParam(':eventDate', $eventDate);		
                $stmt->bindParam(':eventTime', $eventTime);
                $stmt->bindParam(':eventDateInserted', $eventDateInserted);
                $stmt->bindParam(':eventDateUpdated', $eventDateUpdated);		
                
                //EXECUTE the prepared statement
                $stmt->execute();	   
            }
            
            catch(PDOException $e)
            {
                $message = "There has been a problem. The system administrator has been contacted. Please try again later.";
                error_log($e->getMessage());			//Delivers a developer defined error message to the PHP log file at c:\xampp/php\logs\php_error_log
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatable" content="IE=edge">
        <meta name="viewport" content="width=device-width, intial-scale=1.0">
        <title>Self Posting Insert Event Form</title>
        <!--Jeremy Brannen
            WDV341 Event Insert Self Posting Form-->

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        
        <script>

        </script>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&display=swap');
            body{
                font-family: 'Open Sans', sans-serif;
            }
            a:hover{
                color: purple;
                text-decoration: none;
            }
            div:nth-child(4){
                display: none;
            }
            
        </style>
    </head>

    <body>
        <h1 class="text-center">Self Posting Insert Event Form</h1>
        <h2 class="text-center">WDV341 Intro PHP</h2>
        
        <div class= "jumbotron col-md-4 mx-auto border border-dark rounded-lg m-4 p-4" style="background-color:lightblue">
            <?php   // PHP response message if form was submitted (view)
                if(isset($_POST['submit'])){

                    echo"<p><h3>Your event has been saved!</h3>
                            Event: $eventName<br>
                            Description: $eventDescription<br>
                            Presenter: $eventPresenter<br>
                            Time: $eventTime<br>
                            Date: $dateInserted<br>
                        </p>";
            
                }else{  // Displays form if form has not been submitted (view)
            ?>
            <form name="eventsForm" id="eventsForm" method="post" action="selfPostingInsertEventForm.php">

                <div class="form-group">
                    <label for="events_name">Event Name: </label>
                    <input type="text" class="form-control form-control-sm" name="events_name" id="events_name">
                </div>

                <div class="form-group">
                    <label for="events_description">Event Description: </label>
                    <input type="text" class="form-control form-control-sm" name="events_description" id="events_description">
                </div>
                    
                <div class="form-group">
                    <label for="events_presenter">Event Presenter: </label>
                    <input type="text" class="form-control form-control-sm" name="events_presenter" id="events_presenter"> 
                </div>

                <div class="form-group">
                    <label for="events_host">Event Host: </label>
                    <input type="text" class="form-control form-control-sm" name="events_host" id="events_host">
                </div>
                    
                <div class="form-group">
                    <label for="events_date">Event Date: </label>
                    <input type="date" class="form-control form-control-sm" name="events_date" id="events_date"> 
                </div>
                    
                <div class="form-group">
                    <label for="events_time">Event Time: </label>
                    <input type="time" class="form-control form-control-sm" name="events_time" id="events_time"> 
                </div>
                    
                <div class="form-group">
                    <label for="events_date_inserted">Event Date Inserted: </label>
                    <input type="text" class="form-control form-control-sm" name="events_date_inserted" id="events_date_inserted" value="<?php echo $dateInserted ?>" readonly> 
                </div>

                <div class="form-group">
                    <label for="events_updated_date">Event Date Updated: </label>
                    <input type="text" class="form-control form-control-sm" name="events_updated_date" id="events_updated_date" value="<?php echo $dateUpdated ?>" readonly> 
                </div>
                    
                <div class="text-center">
                    <input type="submit" name="submit" id="submit" value="Add Event">
                    <input type="reset" name="Reset" id="button" value="Clear Form">
                </div>
            </form>
        </div>
        <footer>

            <p class="text-center">
                <a target="_blank"href="https://github.com/jrbrannen/Self-Posting-Insert-Event-Form-PHP.git">GitHub Repo Link</a>    <!--  GitHub Repo Link -->
            </p>

            <p class="text-center">
                <a href="../wdv341.php">PHP Homework Page</a>    <!-- Homework page link -->
            </p>

        </footer>

    </body>

</html>
<?php
    }
?>