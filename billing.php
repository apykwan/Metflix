<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

try {
  $accessToken = getAccessToken($_ENV['PAYPAL_CLIENT_ID'], $_ENV['PAYPAL_SECRET_KEY']);
  $productId = createProduct($accessToken, 'Premium Membership', 'Monthly subscription for premium content');
  $planId = createPlan($accessToken, $productId, 9.99);
  $subscriptionResponse = createSubscription($accessToken, $planId);

  // Extract approval link and redirect user
  if (isset($subscriptionResponse['links'])) {
    foreach ($subscriptionResponse['links'] as $link) {
      if ($link['rel'] === 'approve') {
        header("Location: " . $link['href']);
        exit;
      }
    }
  }
  echo "No approval link found.";
} catch (Exception $e) {
  echo "Error: " . $e->getMessage();
}


// use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

// $client = PayPalClient();

// $request = new OrdersCreateRequest();
// $request->prefer('return=representation');
// $request->body = [
//   "intent" => "CAPTURE",
//   "purchase_units" => [[
//     "reference_id" => "test_ref_id1",
//     "amount" => [
//       "value" => "9.99",
//       "currency_code" => "USD"
//     ]
//   ]],
//   "application_context" => [
//     "cancel_url" => "http://localhost/metflix/profile?success=false",
//     "return_url" => "http://localhost/metflix/profile?success=true"
//   ]
// ];

// try {
//   // Call API with your client and get a response for your call
//   $response = $client->execute($request);

//   foreach ($response->result->links as $link) {
//     if ($link->rel === 'approve') {
//       header("Location: " . $link->href);
//       exit;
//     }
//   }

//   // If call returns body in response, you can get the deserialized version from the result attribute of the response
//   print_r($response);
// } catch (HttpException $ex) {
//   echo $ex->statusCode;
//   print_r($ex->getMessage());
// }