<script>

	function signOut() {
		
		 swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, let me signout!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false
            }).then(function (result) {
				if(result.value == true){
				$.ajax({
							data: "",
							url: BaseUrl+"/api/logout",
							method: 'GET',
							complete: function(response){ 
							 
								if(response.status == 200){
								  swal({
										title: 'Success Signout!',
										text: 'Thank you for using this program',
										type:'success',
										onClose: function () {
											
											window.location.replace(BaseUrl+'/logout');
											
										}
									});
								} else if (response.status == 401) {
									
								}
							},
							dataType:'json'
				})
				}
            });
	
	}
	
	function goBack() {
		window.history.back();
	}
	$(".loader").hide();
	var image = "{{url('assets/images/web/loading.gif')}}";
	var $loading = $(".loader").html( '<img class="loading-image" src="'+image+'" alt="loading..">');
		 jQuery(document).ajaxStart(function () {
				   
					$loading.show();
			});
			
		 jQuery(document).ajaxStop(function () {
				$(".card").fadeIn("slow", function() {
				$loading.hide();
				
		});
		 });
		 
	$(window).on('shown.bs.modal', function() { 
    $("body").removeAttr("style");
	});
	
</script>
