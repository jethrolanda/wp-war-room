<?php

namespace WPWR\Plugin;


class Callrail
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
    // add_shortcode('callrail_test', array($this, 'wpdocs_footag_func'));
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

  // public function wpdocs_footag_func($atts)
  // {
  //   $this->get_callrail_data();
  // }

  public function get_callrail_data($args)
  {
    extract($args);

    if (empty($dateStart) && empty($dateEnd)) {
      $dateStart = date('Y-m-d', strtotime('-1 week'));
      $dateEnd = date('Y-m-d');
    }

    $callrailData = array();
    $options = get_option('warroom_options');
    if (!isset($options['warroom_field_callrail_api'])) return;

    $apiKey    = $options['warroom_field_callrail_api'];
    $accountId = '292905471';
    // $startDate = '2025-06-26';
    // $endDate   = '2025-12-31';
    $page      = 1;
    $totalPages = 0;
    $perPage   = 100;
    $tag = isset($args['tag']) ? $args['tag'] : ""; // 'A-FLKickAss' or 'A-NeedsFuel';
    $trackingNumber = '(PPC) San Antonio TX Ads';

    $allCalls = [];

    do {
      $url = "https://api.callrail.com/v3/a/{$accountId}/calls.json?" . http_build_query([
        'start_date' => $dateStart,
        'end_date' => $dateEnd,
        'page' => $page,
        'per_page' => $perPage,
        'tags[]' => $tag,
        // 'tracking_numbers[]' => $trackingNumber,
      ]);

      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Token token=\"{$apiKey}\"",
        "Content-Type: application/json",
      ]);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      $resp = curl_exec($ch);
      $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);

      if ($code !== 200) {
        die("Error: HTTP {$code} — {$resp}");
      }

      $json = json_decode($resp, true);
      $allCalls = array_merge($allCalls, $json['calls']);

      $totalPages = $json['total_pages'] ?? 1;
      $page++;
    } while ($page <= $totalPages);

    // Display call IDs and start times
    foreach ($allCalls as $call) {
      $callrailData[] = array(
        'state' => $call['customer_state'],
        'tracking_phone_number' => $call['tracking_phone_number'],
        'date' => $call['start_time']
      );

      // echo "{$call['tracking_phone_number']} at {$call['start_time']}<br/>";
    }

    return $callrailData;
  }
}
