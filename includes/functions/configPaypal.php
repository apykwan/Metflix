<?php

declare(strict_types=1);

require_once __DIR__ . '/../../config.php';

// Get an OAuth 2.0 Access Token
function getAccessToken($clientId, $clientSecret)
{
  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, "https://api.sandbox.paypal.com/v1/oauth2/token");
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Accept: application/json",
    "Accept-Language: en_US"
  ]);
  curl_setopt($ch, CURLOPT_USERPWD, $clientId . ":" . $clientSecret);
  curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $response = curl_exec($ch);
  if (curl_errno($ch)) {
    throw new Exception('Curl error: ' . curl_error($ch));
  }
  curl_close($ch);

  $data = json_decode($response, true);
  if (isset($data['access_token'])) {
    return $data['access_token'];
  }
  throw new Exception('Failed to get access token: ' . $response);
}

// Create a Product
function createProduct($accessToken, $productName, $productDescription)
{
  $payload = json_encode([
    "name" => $productName,
    "description" => $productDescription,
    "type" => "SERVICE",
    "category" => "SOFTWARE"
  ]);

  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v1/catalogs/products");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $accessToken"
  ]);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
  curl_setopt($ch, CURLOPT_POST, true);

  $response = curl_exec($ch);
  curl_close($ch);

  $data = json_decode($response, true);

  if (isset($data['id'])) {
    return $data['id']; // This is your product ID
  }

  throw new Exception('Product creation failed: ' . $response);
}

// Create a billing plan
function createPlan(string $accessToken, string $productId, float $price, string $currency = 'USD'): string
{
  $ch = curl_init();

  $data = [
    "product_id" => $productId,
    "name" => "Monthly Plan",
    "billing_cycles" => [[
      "frequency" => [
        "interval_unit" => "MONTH",
        "interval_count" => 1
      ],
      "tenure_type" => "REGULAR",
      "sequence" => 1,
      "total_cycles" => 0,  // 0 = infinite (until canceled)
      "pricing_scheme" => [
        "fixed_price" => [
          "value" => number_format($price, 2, '.', ''),
          "currency_code" => $currency
        ]
      ]
    ]],
    "payment_preferences" => [
      "auto_bill_outstanding" => true,
      "setup_fee" => [
        "value" => "0",
        "currency_code" => $currency
      ],
      "setup_fee_failure_action" => "CONTINUE",
      "payment_failure_threshold" => 3
    ],
    "taxes" => [
      "percentage" => "0",
      "inclusive" => false
    ]
  ];

  curl_setopt($ch, CURLOPT_URL, "https://api.sandbox.paypal.com/v1/billing/plans");
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $accessToken"
  ]);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $response = curl_exec($ch);
  curl_close($ch);

  $json = json_decode($response, true);
  return $json['id'] ?? '';
}

// Create a Subscription
function createSubscription($accessToken, $planId)
{
  $url = "https://api.sandbox.paypal.com/v1/billing/subscriptions";

  $body = json_encode([
    "plan_id" => $planId,
    "application_context" => [
      "brand_name" => "Metflix",
      "locale" => "en-US",
      "shipping_preference" => "NO_SHIPPING",
      "user_action" => "SUBSCRIBE_NOW",
      "return_url" => "http://localhost/metflix/profile?success=true",
      "cancel_url" => "http://localhost/metflix/profile?success=false"
    ]
  ]);

  $ch = curl_init($url);

  curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $accessToken"
  ]);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $response = curl_exec($ch);
  if (curl_errno($ch)) {
    throw new Exception('Curl error: ' . curl_error($ch));
  }
  curl_close($ch);

  $data = json_decode($response, true);
  return $data;
}

function getSubscriptionDetails(string $accessToken, string $subscriptionId): array
{
  $url = "https://api-m.sandbox.paypal.com/v1/billing/subscriptions/$subscriptionId";

  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $accessToken"
  ]);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $response = curl_exec($ch);
  curl_close($ch);

  return json_decode($response, true);
}


// use PayPalCheckoutSdk\Core\PayPalHttpClient;
// use PayPalCheckoutSdk\Core\SandboxEnvironment;

// function PayPalClient()
// {
//   $clientId = $_ENV['PAYPAL_CLIENT_ID'];
//   $clientSecret = $_ENV['PAYPAL_SECRET_KEY'];

//   $environment = new SandboxEnvironment($clientId, $clientSecret);
//   return new PayPalHttpClient($environment);
// }

