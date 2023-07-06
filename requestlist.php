<?php include ('admin/db_connect.php');?>

<div class="container-fluid">
	
	<div class="col-lg-12">
		<div class="row">
			
			<!-- Table Panel -->
			<div class="col-md-9">
				<div class="card">
					<div class="card-header">
						<b>Request List</b>
					</div>
					<div class="card-body">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
                                    <th class="text-center">Request ID</th>
									<th class="text-center">Requested by</th>
                                    <th class="text-center">Description</th>
                                    <th class="text-center">From Date</th>
                                    <th class="text-center">To Date</th>
                                    <th class="text-center">Status</th>
									
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$request = $conn->query("SELECT * FROM request order by requestID asc");
								while($row=$request->fetch_assoc()):
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
                                    <td class="text-center">
                                        <p><small><b><?php echo $row['requestID'] ?></b></small></p>
                                    </td>
									<td class="">
										<p><b><?php echo $row['iname'] ?></b></p>
									<td class="text-center">
                                        <p><small><b><?php echo $row['descr'] ?></b></small></p>
                                    </td>
                                    <td class="text-center">
                                        <p><b><?php echo $row['fromdate'] ?></b></p>
                                    </td>
                                    <td class="text-center">
                                        <p><b><?php echo $row['todate'] ?></b></p>
                                    <td class="text-center">
                                        <p><b><?php echo $row['status'] ?></b></p>
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
		$('#manage-request').get(0).reset()
		$('#manage-request input,#manage-request textarea').val('')
	}
	$('#manage-request').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:'ajax.php?action=save_request',
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
	$('.edit_request').click(function(){
		start_load()
		var cat = $('#manage-request')
		cat.get(0).reset()
		cat.find("[name='requestID']").val($(this).attr('data-id'))
		cat.find("[name='iname']").val($(this).attr('data-requestedby'))
		cat.find("[name='descr']").val($(this).attr('data-description'))
        cat.find("[name='fromdate']").val($(this).attr('data-fromdate'))
		cat.find("[name='todate']").val($(this).attr('data-todate'))
		cat.find("[name='status']").val($(this).attr('data-status'))
		end_load()
	})
	$('.delete_request').click(function(){
		_conf("Are you sure to delete this request?","delete_request",[$(this).attr('data-id')])
	})
	function delete_request($requestID){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_request',
			method:'POST',
			data:{requestID:$requestID},
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
