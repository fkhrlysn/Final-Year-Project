<?php include 'db_connect.php' ?>
<?php
if(isset($_GET['id'])){
$qry = $conn->query("SELECT * FROM schedules where id= ".$_GET['id']);
foreach($qry->fetch_array() as $k => $val){
	$$k=$val;
}
if(!empty($repeating_data)){
$rdata= json_decode($repeating_data);
	foreach($rdata as $k => $v){
		 $$k = $v;
	}
	$dow_arr = isset($dow) ? explode(',',$dow) : '';
	// var_dump($start);
}
}
?>
<style>
	
	
</style>
<div class="container-fluid">
	<form action="" id="manage-schedule">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div class="col-lg-16">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="" class="control-label">Instructor</label>
						<select name="instructor_id" id="" class="custom-select select2">
							<option value="0">All</option>
						<?php 
							$instructor = $conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM instructor order by concat(lastname,', ',firstname,' ',middlename) asc");
							while($row= $instructor->fetch_array()):
						?>
							<option value="<?php echo $row['id'] ?>" <?php echo isset($instructor_id) && $instructor_id == $row['id'] ? 'selected' : '' ?>><?php echo ucwords($row['name']) ?></option>
						<?php endwhile; ?>
						</select>
					</div>
					<div class="form-group">
						<label for="" class="control-label">Title</label>
						<textarea class="form-control" name="title" cols="30" rows="3"><?php echo isset($title) ? $title : '' ?></textarea>
					</div>
					<div class="form-group">
						<label for="" class="control-label">Schedule Type (Priority)</label>
						<select name="schedule_type" id="" class="custom-select">
							<option value="1" <?php echo isset($schedule_type) && $schedule_type == 1 ? 'selected' : ''  ?>>Class (High)</option>
							<option value="2" <?php echo isset($schedule_type) && $schedule_type == 2 ? 'selected' : ''  ?>>Meeting (Medium)</option>
							<option value="3" <?php echo isset($schedule_type) && $schedule_type == 3 ? 'selected' : ''  ?>>Others (Low)</option>
						</select>
					</div>
					<div class="form-group">
						<label for="" class="control-label">Description</label>
						<textarea class="form-control" name="description" cols="30" rows="3"><?php echo isset($description) ? $description : '' ?></textarea>
					</div>
					<div class="form-group">
						<label for="" class="control-label">Location</label>
						<textarea class="form-control" name="location" cols="30" rows="3"><?php echo isset($location) ? $location : '' ?></textarea>
					</div>
					<div class="form-group">
						<div class="form-check">
						  <input class="form-check-input" type="checkbox" value="1" id="is_repeating" name="is_repeating" <?php echo isset($is_repeating) && $is_repeating != 1 ? '' : 'checked' ?>>
						  <label class="form-check-label" for="type">
						   	Weekly Schedule
						  </label>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group for-repeating">
						<label for="dow" class="control-label">Days of Week</label>
						<select name="dow[]" id="dow" class="custom-select select2" multiple="multiple">
							<?php 
							$dow = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
							for($i = 0; $i < 7;$i++):
							?>
							<option value="<?php echo $i ?>"  <?php echo isset($dow_arr) && in_array($i,$dow_arr) ? 'selected' : ''  ?>><?php echo $dow[$i] ?></option>
						<?php endfor; ?>
						</select>
					</div>
					<div class="form-group for-repeating">
						<label for="" class="control-label">Month From</label>
						<input type="month" name="month_from" id="month_from" class="form-control" value="<?php echo isset($start) ? date("Y-m",strtotime($start)) : '' ?>">
					</div>
					<div class="form-group for-repeating">
						<label for="" class="control-label">Month To</label>
						<input type="month" name="month_to" id="month_to" class="form-control" value="<?php echo isset($end) ? date("Y-m",strtotime($end)) : '' ?>">
					</div>
					<div class="form-group for-nonrepeating" style="display: none">
						<label for="" class="control-label">Schedule Date</label>
						<input type="date" name="schedule_date" id="schedule_date" class="form-control" value="<?php echo isset($schedule_date) ? $schedule_date : '' ?>">
					</div>
					<div class="form-group">
						<label for="" class="control-label">Time From</label>
						<input type="time" name="time_from" id="time_from" class="form-control" value="<?php echo isset($time_from) ? $time_from : '' ?>">
					</div>
					<div class="form-group">
						<label for="" class="control-label">Time To</label>
						<input type="time" name="time_to" id="time_to" class="form-control" value="<?php echo isset($time_to) ? $time_to : '' ?>">
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
<div class="imgF" style="display: none " id="img-clone">
			<span class="rem badge badge-primary" onclick="rem_func($(this))"><i class="fa fa-times"></i></span>
	</div>
<script>
	if('<?php echo isset($id) ? 1 : 0 ?>' == 1){
		if($('#is_repeating').prop('checked') == true){
			$('.for-repeating').show()
			$('.for-nonrepeating').hide()
		}else{
			$('.for-repeating').hide()
			$('.for-nonrepeating').show()
		}
	}
	$('#is_repeating').change(function(){
		if($(this).prop('checked') == true){
			$('.for-repeating').show()
			$('.for-nonrepeating').hide()
		}else{
			$('.for-repeating').hide()
			$('.for-nonrepeating').show()
		}
	})
	$('.select2').select2({
		placeholder:'Please Select Here',
		width:'100%'
	})
	$('#manage-schedule').submit(function(e){
		e.preventDefault()
		start_load()
		$('#msg').html('')
		$.ajax({
			url:'ajax.php?action=save_schedule',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully saved",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
				
			}
		})
		
	})
	
</script>