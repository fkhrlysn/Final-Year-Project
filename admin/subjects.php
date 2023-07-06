<?php include('db_connect.php');?>

<div class="container-fluid">
	
	<div class="col-lg-12">
		<div class="row">
			<!-- FORM Panel -->
			<div class="col-md-4">
			<form action="" id="manage-subject">
				<div class="card">
					<div class="card-header">
						    Subject Form
				  	</div>
					<div class="card-body">
							<input type="hidden" name="id">
							<div class="form-group">
								<label class="control-label">Subject</label>
								<input type="text" class="form-control" name="subject">
							</div>
							<div class="form-group">
								<label class="control-label">Description</label>
								<textarea class="form-control" cols="30" rows='3' name="description"></textarea>
							</div>
							
					</div>
							
					<div class="card-footer">
						<div class="row">
							<div class="col-md-12">
								<button class="btn btn-sm btn-primary col-sm-3 offset-md-3"> Save</button>
								<button class="btn btn-sm btn-default col-sm-3" type="button" onclick="_reset()"> Cancel</button>
							</div>
						</div>
					</div>
				</div>
			</form>
			</div>
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">
						<b>Subject List</b>
					</div>
					<div class="card-body">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="text-center">Subject</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$subject = $conn->query("SELECT * FROM subjects order by id asc");
								while($row=$subject->fetch_assoc()):
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<td class="">
										<p>Subject: <b><?php echo $row['subject'] ?></b></p>
										<p>Description: <small><b><?php echo $row['description'] ?></b></small></p>
										
									</td>
									<td class="text-center">
										<button class="btn btn-sm btn-primary edit_subject" type="button" data-id="<?php echo $row['id'] ?>" data-subject="<?php echo $row['subject'] ?>" data-description="<?php echo $row['description'] ?>" >Edit</button>
										<button class="btn btn-sm btn-danger delete_subject" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
									</td>
								</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>	

</div>
<style>
	
	td{
		vertical-align: middle !important;
	}
</style>
<script>
	function _reset(){
		$('#manage-subject').get(0).reset()
		$('#manage-subject input,#manage-subject textarea').val('')
	}
	$('#manage-subject').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:'ajax.php?action=save_subject',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully added",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
				else if(resp==2){
					alert_toast("Data successfully updated",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	})
	$('.edit_subject').click(function(){
		start_load()
		var cat = $('#manage-subject')
		cat.get(0).reset()
		cat.find("[name='id']").val($(this).attr('data-id'))
		cat.find("[name='subject']").val($(this).attr('data-subject'))
		cat.find("[name='description']").val($(this).attr('data-description'))
		end_load()
	})
	$('.delete_subject').click(function(){
		_conf("Are you sure to delete this subject?","delete_subject",[$(this).attr('data-id')])
	})
	function delete_subject($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_subject',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
	$('table').dataTable()
</script>