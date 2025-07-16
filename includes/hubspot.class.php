<?php

namespace WPWR\Plugin;


class Hubspot
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
  public function __construct() {}

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

  public function get_hubspot_data($args = array())
  {

    if (empty($args)) {
      // 1 Week ago
      $dateStart = date('Y-m-d', strtotime('-1 week'));
      $dateEnd = date('Y-m-d');
      $dateStartTimestamp = strtotime($dateStart) * 1000; // HubSpot uses milliseconds
      $dateEndTimestamp = strtotime($dateEnd) * 1000;
      // $dateCompareStart = 
    } else {
      extract($args);
      $dateStartTimestamp = strtotime($dateStart) * 1000; // HubSpot uses milliseconds
      $dateEndTimestamp = strtotime($dateEnd) * 1000;
    }

    $hubspotData = array();
    $options = get_option('warroom_options');
    if (!isset($options['warroom_field_hubspot_api'])) return;

    $notDone = true;
    $after = "";
    $counter = 1;

    while ($notDone) {
      $data = $this->hubspot_test($after);
      extract($data);


      if ($httpCode === 200) {
        $data = json_decode($response, true);

        if (!isset($data['paging']) && empty($data['paging'])) {
          $notDone = false; // done
        } else {
          $after = $data['paging']['next']['link'];
        }

        foreach ($data['results'] as $submission) {
          $dateCreated = date('Y-m-d', intval($submission['submittedAt'] / 1000));
          $dateTimeStamp = strtotime($dateCreated) * 1000;

          if (isset($dateTimeStamp) && $dateTimeStamp >= $dateStartTimestamp && $dateTimeStamp <= $dateEndTimestamp) {
            $hubspotData[] = array(
              'date' => $dateCreated,
              'url' => $submission['pageUrl']
            );
            $counter++;
          }
          // $hubspotData[] = array(
          //   'date' => date('Y-m-d', intval($submission['submittedAt'] / 1000)),
          //   'url' => $submission['pageUrl']
          // );
          // Y-m-d H:i:s
          // echo $counter . "<br/>";
          // echo "Submitted At: " . date('Y-m-d', intval($submission['submittedAt'] / 1000)) . "<br>";
          // echo "URL: " .  $submission['pageUrl'];
          // echo "<hr>";
          $counter++;
        }
      } else {
        // echo "Error: " . $response;
      }
    }

    return $hubspotData;
  }

  public function hubspot_test($after = '?limit=50')
  {
    $options = get_option('warroom_options');

    $formGuid = '32dd51fa-b13b-450f-ab9c-6a367fb63b88';
    $accessToken = $options['warroom_field_hubspot_api']; // war room api key / token

    $url = "https://api.hubapi.com/form-integrations/v1/submissions/forms/{$formGuid}{$after}";

    $headers = [
      "Authorization: Bearer {$accessToken}",
      "Content-Type: application/json"
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);
    // error_log(print_r($response, true));

    return array(
      'response' => $response,
      'httpCode' => $httpCode
    );
  }
}
