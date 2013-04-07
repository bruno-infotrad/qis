<?php 
	
	$user_guid = elgg_extract("user_guid", $vars);
	$site_url = elgg_get_site_url();
?>
	<select id="passport-number" class="elgg-input" name="passport_guid"/>
	</select>
	<div class="clearfloat"></div>
	
	<script type="text/javascript">
		$(document).ready(function() {
			$("#qisuser-dropdown").change(function() {
        			var $dropdown = $(this);
        			//alert($dropdown.prop('outerHTML'));
        			var user_guid = $dropdown.children("option").filter(":selected").val();
				$.getJSON( "<?php echo $site_url; ?>get_citizenship_docs", {"user_guid": user_guid},function(data){
        				//alert($dropdown.children("option").filter(":selected").val());
        				//alert(data.toSource());
					var $passportNumber = $("#passport-number");
                			$passportNumber.empty();
                			$.each(data, function() {
                        			$passportNumber.append("<option value=" + this.passport_guid +">" + this.passport_country + "</option>");
						//$passportNumber.append($('<option>', { value : key }) .text(value)); 
                			});

				});
			});
		});
	</script>
