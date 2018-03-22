<!-- Modal Edit ACL -->
<div id="editACL" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
	<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Edit ACL <accesslist class="text-green"></accesslist></h4>
		</div>
		<div class="modal-body">
		<form id="editACLForm">
			<div class="row">
			<div class="col-lg-6 col-md-6">
				<div class="form-group name">
					<label for="Name">ACL Name</label>
					<input type="text" class="form-control" name="name" placeholder="Enter ACL Name" autocomplete="off">
					<input type="hidden" name="name_old">
					<input type="hidden" name="id">
					<p class="help-block">it should be unique, but you can change it later</p>
                </div>
            </div>
            </div>
			<div class="row">
				<div class="col-lg-12">
					<div class="form-group ACEs">
						<label for="Name">Access Control Entries</label>
						<div class="table-responsive">
							<table id="aclDataTable_edit" class="table-striped display table table-bordered" style="overflow: auto;"></table>	
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12">
					<div class="btn-group pull-right">
						<button type="button" class="btn btn-success" onclick="addACE('down','editForm')">Add ACE <i class="fa fa-level-up"></i></button>
						<button type="button" class="btn btn-success" onclick="addACE('top','editForm')">Add ACE <i class="fa fa-level-down"></i></button>
                    </div>
				</div>
			</div>
		</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-flat pull-left" data-dismiss="modal">Close</button>
			<button type="button" class="btn btn-flat btn-success" onclick="submitACLChanges()">Edit ACL</button>
		</div>			
	</div>
	</div>
</div>
      <!-- Modal Edit ACL -->