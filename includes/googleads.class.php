<?php


namespace WPWR\Plugin;

require_once WPWR_PLUGIN_DIR . 'vendor/autoload.php';

use Google\Ads\GoogleAds\Lib\V20\GoogleAdsClientBuilder;
use Google\Ads\GoogleAds\V20\Services\SearchGoogleAdsRequest;
use Google\Ads\GoogleAds\V20\Enums\CampaignStatusEnum\CampaignStatus;
use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Auth\OAuth2;

class Googleads
{

  /**
   * The single instance of the class.
   *
   * @since 1.0
   */
  protected static $_instance = null;

  /**
   * Hubspot constructor
   *   
   * @access public
   * @since 1.0
   */
  public function __construct()
  {
    add_shortcode('googleads_test', array($this, 'wpdocs_footag_func'));
  }

  /**
   * Main Instance.
   * 
   * @since 1.0
   */
  public static function instance()
  {
    if (is_null(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  public function wpdocs_footag_func($atts)
  {
    $this->googleads_test();
  }

  public function googleads_test()
  {
    $options = get_option('warroom_options');

    $clientId = $options['warroom_field_googleads_client_id'];
    $clientSecret = $options['warroom_field_googleads_client_secret'];
    $redirectUri = 'http://localhost:8003/google-ads/'; //admin_url('admin.php?page=google-ads-manager');
    $developerToken = $options['warroom_field_googleads_api'];

    $code = $_GET['code'] ?? null;

    if (!$code && !get_option('google_ads_refresh_token')) {
      // STEP 1: Show auth link
      $client = new \Google_Client();
      $client->setClientId($clientId);
      $client->setClientSecret($clientSecret);
      $client->setRedirectUri($redirectUri);
      $client->addScope('https://www.googleapis.com/auth/adwords');
      $client->setAccessType('offline');
      $client->setPrompt('consent');

      $authUrl = $client->createAuthUrl();
      echo '<a href="' . esc_url($authUrl) . '" class="button button-primary">Connect to Google Ads</a>';
      return;
    }

    if ($code && !get_option('google_ads_refresh_token')) {
      // STEP 2: Exchange code for token
      $client = new \Google_Client();
      $client->setClientId($clientId);
      $client->setClientSecret($clientSecret);
      $client->setRedirectUri($redirectUri);
      $token = $client->fetchAccessTokenWithAuthCode($code);

      if (!empty($token['refresh_token'])) {
        update_option('google_ads_refresh_token', $token['refresh_token']);
        echo '<div class="notice notice-success">Connected! Refresh the page to fetch campaigns.</div>';
      } else {
        echo '<div class="notice notice-error">Failed to get refresh token. Try again.</div>';
      }
      return;
    }
    // error_log(print_r($token, true));
    // STEP 3: Use refresh token to get campaigns
    $refreshToken = get_option('google_ads_refresh_token');
    $customerId = '2890269600'; // e.g., 1234567890
    ob_start();
    try {
      $oauth2 = (new \Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder())
        ->withClientId($clientId)
        ->withClientSecret($clientSecret)
        ->withRefreshToken($refreshToken)
        ->build();

      $googleAdsClient = (new \Google\Ads\GoogleAds\Lib\V20\GoogleAdsClientBuilder())
        ->withDeveloperToken($developerToken)
        ->withOAuth2Credential($oauth2)
        ->build();

      $request = new SearchGoogleAdsRequest([
        'customer_id' => $customerId,
        'query' => 'SELECT campaign.id, campaign.name, campaign.status FROM campaign'
      ]);

      $response = $googleAdsClient
        ->getGoogleAdsServiceClient()
        ->search($request);
      // error_log(print_r($response, true));

      echo '<h2>Campaigns</h2><ul>';
      foreach ($response->iterateAllElements() as $row) {
        $c = $row->getCampaign();
        printf(
          '<li><strong>%s</strong> (ID: %s) - %s</li>',
          esc_html($c->getName()),
          esc_html($c->getId()),
          esc_html(\Google\Ads\GoogleAds\V20\Enums\CampaignStatusEnum\CampaignStatus::name($c->getStatus()))
        );
      }
      echo '</ul>';
      echo ob_get_clean();
    } catch (\Exception $e) {
      echo '<div class="notice notice-error"><pre>' . esc_html($e->getMessage()) . '</pre></div>';
      echo ob_get_clean();
    }
  }
}
