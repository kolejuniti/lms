<?php






#header('Location: ' + $output);
#exit();

#curl_close($ch);

$order_number = rand(1111111111,9999999999);

$json = file_get_contents('https://sandbox.securepay.my/api/public/v1/banks/b2c');
$obj = json_decode($json, true);
//echo $obj->access_token;
//$obj->fpx_bankList

//print_r($obj['fpx_bankList']);

$options = "";

foreach ($obj['fpx_bankList'] as $value) {
	if($value['status_format2'])
	{
	    $options .= "<option value=". $value['code'] . ">" . $value['name'] . "</option>";
	}
	else
	{
		$options .= "<option value=". $value['code'] . " disabled>" . $value['name'] . " (offline)</option>";
	}
	//echo $value['code'];
}
 
//print_r($obj->fpx_bankList);

?>
<h1>SecurePay sample code for PHP</h1>
<hr> 
<h3>Form without bank list</h3>
<form action="/securepay-checkout" method="post">
  @csrf
  <label for="fname">Full name:</label><br>
  <input type="text" id="fname" name="buyer_name" value="John Doe"><br>
  <label for="lname">Email:</label><br>
  <input type="text" id="lname" name="buyer_email" value="john@gmail.com"><br>
  <label for="lname">Phone No:</label><br>
  <input type="text" id="lname" name="buyer_phone" value="+60129997979"><br>
  <label for="lname">Order number:</label><br>
  <input type="text" id="lname" name="order_number" value="<?=$order_number;?>"><br>
  <label for="lname">Descriptions:</label><br>
  <input type="text" id="lname" name="product_description" value="Payment for order no. <?=$order_number;?>"><br>
  
  <label for="lname">Callback URL:</label><br>
  <input type="text" id="lname" name="callback_url" value="" placeholder="Optional"><br>
  
  <label for="lname">Redirect URL:</label><br>
  <input type="text" id="lname" name="redirect_url" value="" placeholder="Optional"><br>
  
  <label for="lname">Amount:</label><br>
  <input type="text" id="lname" name="transaction_amount" value="199"><br>
  <br>
  <input type="submit" value="Submit">
</form>

<hr> 

<h3>Form with bank list</h3>


<form action="/securepay-checkout" method="post">
  @csrf
  <label for="fname">Full name:</label><br>
  <input type="text" id="fname" name="buyer_name" value="John Doe"><br>
  <label for="lname">Email:</label><br>
  <input type="text" id="lname" name="buyer_email" value="john@gmail.com"><br>
  <label for="lname">Phone No:</label><br>
  <input type="text" id="lname" name="buyer_phone" value="+60129997979"><br>
  <label for="lname">Order number:</label><br>
  <input type="text" id="lname" name="order_number" value="<?=$order_number;?>"><br>
  <label for="lname">Descriptions:</label><br>
  <input type="text" id="lname" name="product_description" value="Payment for order no. <?=$order_number;?>"><br>
  <label for="lname">Amount:</label><br>
  <input type="text" id="lname" name="transaction_amount" value="199"><br>
  <label for="lname">Callback URL:</label><br>
  <input type="text" id="lname" name="callback_url" value="" placeholder="Optional"><br>
  
  <label for="lname">Redirect URL:</label><br>
  <input type="text" id="lname" name="redirect_url" value="" placeholder="Optional"><br>
  
  <label for="lname">Select bank:</label><br>
  <select id="buyer_bank_code" name="buyer_bank_code"><?=$options?></select><br>
  
  <br>
  <input type="submit" value="Submit">
</form>