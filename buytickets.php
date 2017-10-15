<html>
<head>
	<title>Ticket Sales - SCU VSA 2016 Cultural Show</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
	<link href="https://scuvsa.org/css/bootstrap.css" rel="stylesheet" />
	<link href="show.css" rel="stylesheet" />
	
	<script type="text/javascript" src="https://js.squareup.com/v2/paymentform"></script>
	<script type="text/javascript">
		var sqPaymentForm = new SqPaymentForm({
		applicationId: 'sq0idp-yB9fLa1qwq4dSPGScFaLcg',
		inputClass: 'sq-input',
		cardNumber: {
			elementId: 'sq-card-number',
			placeholder: "0000 0000 0000 0000"
		},
		cvv: {
			elementId: 'sq-cvv',
			placeholder: 'CVV'
		},
		expirationDate: {
			elementId: 'sq-expiration-date',
			placeholder: 'MM/YY'
		},
		postalCode: {
			elementId: 'sq-postal-code',
			placeholder: 'Postal Code'
		},
		inputStyles: [
			// Because this object provides no value for mediaMaxWidth or mediaMinWidth,
			// these styles apply for screens of all sizes, unless overridden by another
			// input style below.
			{
				fontSize: '14px',
				padding: '3px'
			},
			// These styles are applied to inputs ONLY when the screen width is 400px
			// or smaller. Note that because it doesn't specify a value for padding,
			// the padding value in the previous object is preserved.
			{
				mediaMaxWidth: '400px',
				fontSize: '18px',
			}
		],
		callbacks: {
			cardNonceResponseReceived: function(errors, nonce, cardData) {
			if (errors) {
				var errorDiv = document.getElementById('errors');
				errorDiv.innerHTML = "";
				errors.forEach(function(error) {
				var p = document.createElement('p');
				p.innerHTML = error.message;
				errorDiv.appendChild(p);
				});
			} else {
				// Assign the value of the nonce to a hidden form element
				var nonceField = document.getElementById('card-nonce');
				nonceField.value = nonce;
				
				var errorDiv = document.getElementById('errors');
				errorDiv.innerHTML = "";
				var p = document.createElement('p');
				p.innerHTML = "Processing your tickets now! Please wait just a few moments.";
				errorDiv.appendChild(p);
				
				// Submit the form
				document.getElementById('form').submit();
			}
			},
			unsupportedBrowserDetected: function() {
			// Alert the buyer that their browser is not supported
			}
		}
		});
		function submitButtonClick(event) {
			event.preventDefault();
			sqPaymentForm.requestCardNonce();
		}
		function quantityChanged(quantity) {
			if (quantity >= 5) {
				subtotal = quantity * 8;
				discountLabel = document.getElementById('discount-label');
				discountBox = document.getElementById('discount-box');
				discountLabel.className = 'form-group';
				discountBox.className = 'form-group';
			}
			else {
				subtotal = quantity * 10;
				discountLabel = document.getElementById('discount-label');
				discountBox = document.getElementById('discount-box');
				discountLabel.className = 'form-group hidden';
				discountBox.className = 'form-group hidden';
			}
			total = (subtotal + 0.15) / 0.965;
			fee = total - subtotal;
			
			subtotalLabel = document.getElementById('subtotal');
			feeLabel = document.getElementById('fee');
			totalLabel = document.getElementById('total');
			
			subtotalLabel.innerHTML = '$' + subtotal.toFixed(2);
			feeLabel.innerHTML = '$' + fee.toFixed(2);
			totalLabel.innerHTML = '$' + total.toFixed(2);
		}
	</script>
	<style type="text/css">
		.sq-input {
			border: 1px solid #CCCCCC;
			margin-bottom: 10px;
			padding: 1px;
		}
		.sq-input--focus {
			outline-width: 5px;
			outline-color: #70ACE9;
			outline-offset: -1px;
			outline-style: auto;
		}
		.sq-input--error {
			outline-width: 5px;
			outline-color: #FF9393;
			outline-offset: 0px;
			outline-style: auto;
		}
	</style>
	</script>
</head>
<body>
	<div class="tickets-wrapper">
		<div class="tickets-wrapper-inner">.
			<div class="container">
				<div class="row botspace-xxs">
					<div class="col-md-12"><h1>Check Out</h1></div>
				</div>
				<form class="form-horizontal" id="form" novalidate action="/process-card.php" method="post">
					<div class="form-group">
						<label for="first-name" class="col-sm-3 control-label">first name</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="first-name" name="firstName" placeholder="Jon" />
						</div>
					</div>
					<div class="form-group">
						<label for="last-name" class="col-sm-3 control-label">last name</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="last-name" name="lastName" placeholder="Fortescue" />
						</div>
					</div>
					<div class="form-group">
						<label for="email" class="col-sm-3 control-label">email address</label>
						<div class="col-sm-8">
							<input type="email" class="form-control" id="email" name="email" placeholder="jon@jon.com" />
						</div>
					</div>
					<div class="form-group">
						<label for="phone" class="col-sm-3 control-label">phone number</label>
						<div class="col-sm-8">
							<input type="tel" class="form-control" id="phone" name="phone" placeholder="408-888-8853" />
						</div>
					</div>
					<div class="form-group">
						<label for="sq-card-number" class="col-sm-3 control-label">credit card number</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="sq-card-number" />
						</div>
					</div>
					<div class="form-group">
						<label for="sq-cvv" class="col-sm-3 control-label">cvv</label>
						<div class="col-sm-2">
							<input type="text" class="form-control" id="sq-cvv" />
						</div>
						<label for="sq-expiration-date" class="col-sm-3 control-label">expiration date</label>
						<div class="col-sm-3">
							<input type="text" class="form-control" id="sq-expiration-date"/>
						</div>
					</div>
					<div class="form-group">
						<label for="sq-postal-code" class="col-sm-3 control-label">zip code</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="sq-postal-code" />
						</div>
					</div>
					<div class="form-group">
					</div>
					<div class="form-group hidden" id="discount-label">
						<label>you qualify for the group discount!</label>
					</div>
					<div class="form-group hidden" id="discount-box">
						<label for="group-name" class="col-sm-3 control-label">group name</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="group-name" name="groupName" />
						</div>
					</div>
					<div class="form-group">
						<label for="num-tickets" class="col-sm-3 col-sm-offset-1 control-label">number of tickets</label>
						<div class="col-sm-2">
							<input type="number" onchange="quantityChanged(this.value)" class="form-control" id="num-tickets" name="numTickets"	min="1" max="50" value="1" />
						</div>
						<label id="subtotal" class="col-sm-2 col-sm-offset-1">$8.00</label>
					</div>
					<div class="form-group">
						<label class="col-sm-2 col-sm-offset-5" for="fee">processing fee</label>
						<label class="col-sm-2" id="fee">$0.45</label>
					</div>
					<div class="form-group total">
						<label class="col-sm-2 col-sm-offset-5">total</label>
						<label class="col-sm-2" id="total">$8.45</label>
					</div>
					<input type="hidden" id="card-nonce" name="nonce">
					<input type="submit" onclick="submitButtonClick(event)" id="card-nonce-submit" class="btn btn-tickets-now" value="purchase tickets">
				</form>
				
				<div id="errors"></div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title" id="myModalLabel">Please Wait</h4>
      </div>
      <div class="modal-body center-block">
        <div class="progress">
          <div class="progress-bar bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
            
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://scuvsa.org/js/bootstrap.min.js"></script>

</body>
</html>