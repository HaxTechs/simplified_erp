<?php require_once 'includes/header.php'; ?>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
					<i class="glyphicon glyphicon-check"></i>	Importer des marques
			</div>
			<!-- /panel-heading -->
			<div class="panel-body">
				
				<form class="form-horizontal" id="submitImportForm" action="php_action/createBrandImport.php" method="POST" enctype="multipart/form-data">
				<div id="add-product-messages"></div>
				<div class="form-group">
	        	<label for="brandfile" class="col-sm-3 control-label">Fichier d'import des marques : </label>
	        	<label class="col-sm-1 control-label">: </label>
				    <div class="col-sm-8">
					    <!-- the avatar markup -->
							<div id="kv-avatar-errors-1" class="center-block importbrand-error-container"></div>							
					    <div class="kv-avatar center-block">					        
					        <input type="file" class="form-control file-loading importbrand-file-input" id="brandfile" placeholder="Fichier d'import des marques" name="brandfile"/>
							
					    </div>
							<a class="importbrand-sample-link" href="assests/import/brand.xlsx" download>Fichier exemple</a>
				      
				    </div>
	        	</div> <!-- /form-group-->	
				
				  <div class="form-group">
				    <div class="col-sm-offset-2 col-sm-10">
					      <button type="submit" class="btn btn-success" id="importBrandBtn"> <i class="glyphicon glyphicon-ok-sign"></i> Importer</button>
				    </div>
				  </div>
				</form>

			</div>
			<!-- /panel-body -->
		</div>
	</div>
	<!-- /col-dm-12 -->
</div>
<!-- /row -->
<script src="custom/js/import.js"></script>
<?php require_once 'includes/footer.php'; ?>
