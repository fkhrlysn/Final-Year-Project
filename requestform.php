<?php include 'admin/db_connect.php';

if (isset($_POST["submit"]))
{
    $instructorID = $_POST["instructorID"];
    $iname = $_POST["iname"];
    $descr = $_POST["descr"];
    $fromdate = $_POST["fromdate"];
    $todate = $_POST["todate"];

    $query = "INSERT INTO request VALUES('', '$instructorID', '$iname', '$descr', '$fromdate', '$todate')";
    mysqli_query($conn, $query);
    echo
    "
    <script> alert('Data Inserted Succesfully'); </script>
    ";

}
?>
<div class="col-md-3">
			<form action="" class="" method="post" autocomplete="off">
				<div class="card">
					<div class="card-header">
						    Request Approval Form
				  	</div>
					<div class="card-body">
                           
                                <label class="control-label">Instructor ID</label>
								<input type="text" name="instructorID">
                            
								<label class="control-label">Requested by</label>
								<input type="text" name="iname">
							
							
								<label class="control-label">Description</label>
								<textarea cols="30" rows='3' name="descr"></textarea>
							
                            
								<label class="control-label">From Date</label>
								<input type="date" name="fromdate" value="<?php echo isset($fromdate) ? $fromdate:'' ?>" required>
							
                            
								<label class="control-label">To Date</label>
								<input type="date" name="todate" value="<?php echo isset($todate) ? $todate:'' ?>" required>
							
                            
							
					</div>
							
					<div class="card-footer">
						<div class="row">
							<div class="col-md-12">
								<button type="submit" name="submit"> Submit</button>
								<button class="btn btn-sm btn-default col-sm-3" type="button" onclick="_reset()"> Cancel</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
<style media="screen">
    label{display: block;}
</style>