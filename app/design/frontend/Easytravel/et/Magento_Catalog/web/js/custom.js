define(
	[
		'jquery'
	],
	function($)
	{
		function main(config)
		{
			$('.departure_dates').change(function()
			{	
				if(config.attributeSetId==config.privetGroupId)
		    	{
		    		var endDate = "";
					
					var startDate = $(this).val();
					var today = new Date();
					var dd = today.getDate();
					var mm = today.getMonth()+1; //January is 0!
					if(mm < 10)
				    {
				    	mm = '0'+mm;
				    }
				    if(dd < 10)
				    {
				    	dd = '0'+dd;
				    }
					var yyyy = today.getFullYear();
					currentDate = yyyy + '-' + mm + '-' + dd;
					
					if(startDate && startDate > currentDate)
					{
					    
					    var newdate = new Date(startDate);
					    var noOfDays = parseInt(config.duration-1);
					    newdate.setDate(newdate.getDate() + noOfDays);
					    
					    var dd = newdate.getDate();
					    var mm = newdate.getMonth() + 1;
					    var y = newdate.getFullYear();
					    if(mm < 10)
					    {
					    	mm = '0'+mm;
					    }
					    if(dd < 10)
					    {
					    	dd = '0'+dd;
					    }
					    var endDate = mm + '/' + dd + '/' + y;
						
					    $('.end_date').val(endDate);
					    updateNoOfTravellers()
					}
					else
					{	
						alert('Date must be grater than current Date');
						$('.no_of_travellers').html("<option value=''>Chose an Option</option>");
						$('.no_of_travellers').attr("disabled",'disabled');
						$('.end_date').val('');
					}
		    	}	
		    	else
		    	{
		    		updateNoOfTravellers()
		    	}
				
			    //checkProductStockAndCart();
			});

			$('.no_of_travellers').change(function()
			{	
				if(config.attributeSetId==config.privetGroupId)
		    	{
		    		var departure_dates 	= $('.departure_dates').val();
					var noOfTravels 		= $(this).val();
					$('.loader').show();
					$.ajax(
					{
						type:"POST",
						url: config.baseUrl+"easytravel_core/ajax/index",
			            data:
						{	
							'product_id'		: config.productId,
							'departure_dates'	: departure_dates, 
							'no_of_travellers'	: noOfTravels, 
							'product_group'		: config.attributeSetId,
							'type'				:'getProductOptions',
						},
						success: function(response)
						{
							//response = $.parseJSON(response);
							if(response.status==1)
							{	
								var options =""; 
								$.each(response.noOfKids, function(index, value){
								  options += "<option value='"+value+"'>"+value+"</option>";
								});
								$('.no_of_kids').html("<option value=''>Chose an Option</option>"+options);
								$('.no_of_kids').removeAttr('disabled');
							}
							$('.loader').hide();
						}
					});
		    	}
		    	else
		    	{
		    		checkProductStockAndCart();
		    	}
			});
			
			if(config.attributeSetId==config.privetGroupId)
	    	{
	    		$('.no_of_kids').change(function()
				{	
					checkProductStockAndCart();
				});
			}

			$('.book_now').click(function()
			{	
				var no_of_travellers	= $('.no_of_travellers').val();
				var departure_dates 	= $('.departure_dates').val();
				var noOfKids 			= "";
				var aErrors = {};
				
				if(!no_of_travellers)
			    {
			       aErrors['no_of_travellers'] = 'No Of Travellers is Required';
			    }
			    if(!departure_dates)
			    {
			       aErrors['departure_dates'] = 'Departure Date is Required';
			    }
			    
			    if(config.attributeSetId==config.privetGroupId)
		    	{
					noOfKids = $('.no_of_kids').val();
					if(!noOfKids)
				    {
				       aErrors['no_of_kids'] = 'No of Kids is Required';
				    }
				}
				var errsLength = Object.keys(aErrors).length;
			    if(errsLength > 0)
			    {
			    	var errorMsg = "";
			      	$.each(aErrors, function( key, value ) 
			      	{
			        	errorMsg += value;
			        	errorMsg += "<br>";
			      	});
			      	$('.error-msg').html(errorMsg);
			        $('.error-msg').show().delay(3000).fadeOut();
			    }
			    else
				{	
					$('.loader').show();
					addProductIntoCart(no_of_travellers,departure_dates,noOfKids);
				}
			});

            function updateNoOfTravellers()
			{	

				var departure_dates 	= $('.departure_dates').val();
				$('.book_now').removeAttr('disabled');
				if(departure_dates)
				{
					$('.loader').show();
					$.ajax(
					{
						type:"POST",
						url:  config.baseUrl + "easytravel_core/ajax/index",
		                data:
						{	
							'product_id'		: config.productId,
							'departure_dates'	: departure_dates, 
							'product_group'		: config.attributeSetId,
							'type'				:'getProductOptions',
						},
						success: function(response)
						{
							//response = $.parseJSON(response);
							if(response.status==1)
							{	
								var options =""; 
								var noOfTravelers = response.noOfTravelers;
								console.log(noOfTravelers);
								if(noOfTravelers.length == 0)
								{	
									options += "<option value=''>NO OPTION FOUND</option>";
								}
								else
								{	
									options += "<option value=''>Chose an Option</option>";
									$.each(response.noOfTravelers, function(index, value)
									{
									  options += "<option value='"+value+"'>"+value+"</option>";
									});
								}
								$('.no_of_travellers').html(options);
								$('.no_of_travellers').removeAttr('disabled');
								$('.no_of_kids').html("<option value=''>Chose an Option</option>");
								$('.no_of_kids').attr("disabled",'disabled');
							}

							$('.loader').hide();
						}
					});
				}
				else
				{
					$('.no_of_travellers').html("<option value=''>Chose an Option</option>");
					$('.no_of_travellers').attr("disabled",'disabled');
				}
				changeBtnStatus(0);
			}

			function checkProductStockAndCart()
			{
				var no_of_travellers	= $('.no_of_travellers').val();
				var departure_dates 	= $('.departure_dates').val();
				var noOfKids 			= "";
				
				if(config.attributeSetId==config.privetGroupId)
		    	{
					noOfKids = $('.no_of_kids').val();
				}

				if(no_of_travellers && departure_dates)
				{	
					$('.loader').show();
					$.ajax(
					{
						type:"POST",
						url: config.baseUrl + "easytravel_core/ajax/index",
		                data:
						{	
							'product_id'		: config.productId,
							'product_group'		: config.attributeSetId,
							'no_of_travellers'	: no_of_travellers,
							'departure_dates'	: departure_dates, 
							'no_of_kids'		: noOfKids, 
							'type'				: 'checkStockAndCart' 
						},
						success: function(result)
						{
							//result = $.parseJSON(result);
							if(result.stock==1)
							{
								$('.stock-availability').html('Stock Available');
								$('.book_now').removeAttr('disabled');
							}
							else
							{
								$('.stock-availability').html('Out of Stock');
								$('.book_now').attr('disabled','disabled');
							}
							changeBtnStatus(result.cart);
							$('.loader').hide();
						}
					});
				}
			}

			function addProductIntoCart(no_of_travellers,departure_dates,noOfKids)
			{
				$.ajax(
					{
						type:"POST",
						url: config.baseUrl + "easytravel_core/ajax/index",
		                data:
						{	
							'product_id'		: config.productId,
							'product_group'		: config.attributeSetId,
							'no_of_travellers'	: no_of_travellers,
							'departure_dates'	: departure_dates, 
							'no_of_kids'		: noOfKids, 
							'item_id'			: $('.book_now').val(), 
							'type'				: 'addToCart',
						},
						success: function(result)
						{
							//result = $.parseJSON(result);
							if(result.status==1)
							{	
								$('.success-msg').html("Cart Update Successfully");
			        			$('.success-msg').show().delay(3000).fadeOut();
						        
						      	//updateMiniCart();
								changeBtnStatus(result.item_id);
							}
							
							$('.loader').hide();
						}
					});
			}

			function changeBtnStatus(itemId)
			{
				if(itemId)
				{	
					$('.book_now').html('Update Booking');
					$('.book_now').val(itemId);
					
				}
				else
				{
					$('.book_now').html('Book Now');
					$('.book_now').val(0);
				}
			}
		};

		return main;
	}

	
);