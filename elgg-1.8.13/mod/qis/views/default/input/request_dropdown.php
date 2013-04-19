<?php 
$group_guid = elgg_extract("group_guid", $vars);
$request_guid = elgg_extract("request_guid", $vars);
$site_url = elgg_get_site_url();
?>
<select id="request-number" class="elgg-input" name="request_guid"/>
</select>
<div class="clearfloat"></div>
<script type="text/javascript">
	$(document).ready(function() {
       		var $dropdown = $("#qis-client-dropdown");
       		var group_guid = $dropdown.children("option").filter(":selected").val();
		$.getJSON( "<?php echo $site_url; ?>get_requests", {"group_guid": group_guid},function(data){
       			//alert($dropdown.children("option").filter(":selected").val());
       			//alert(data.toSource());
			var $requestNumber = $("#request-number");
              		$requestNumber.empty();
			var passedRequestGuid = <?php echo $request_guid; ?>;
              		$.each(data, function() {
				//alert(passedRequestGuid+' '+this.request_guid);
				if ( this.request_guid == passedRequestGuid) {
                      			$requestNumber.append("<option value=" + this.request_guid +" selected>" + this.request_user + "</option>");
				} else {
                      			$requestNumber.append("<option value=" + this.request_guid +">" + this.request_user + "</option>");
				}
       			});
		});
		$("#qis-client-dropdown").change(function() {
       			var $dropdown = $(this);
       			//alert($dropdown.prop('outerHTML'));
       			var group_guid = $dropdown.children("option").filter(":selected").val();
			//alert(group_guid);
			$.getJSON( "<?php echo $site_url; ?>get_requests", {"group_guid": group_guid},function(data){
       				//alert($dropdown.children("option").filter(":selected").val());
       				//alert(data.toSource());
				var $requestNumber = $("#request-number");
               			$requestNumber.empty();
               			$.each(data, function() {
					if (this.request_guid == '<?php echo $request_guid; ?>') {
                      				$requestNumber.append("<option value=" + this.request_guid +"selected>" + this.request_user + "</option>");
					} else {
                      				$requestNumber.append("<option value=" + this.request_guid +">" + this.request_user + "</option>");
					}
               			});
			});
		});
	});
</script>
