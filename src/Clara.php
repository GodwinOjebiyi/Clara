<?php

namespace GodwinOjebiyi;

/**
*  Class Clara
*
*  @param string $address.
*  @author godwinOjebiyi
*/
class Clara{

  /**  @var string $zillow_id Zillow API Key */
  protected $zillow_id = 'X1-ZWz195njxeqvpn_5f91a';
  /**  @var string $google_maps_api_key Google Maps API Key */
  protected $google_maps_api_key = 'AIzaSyDUyiUwtxe-7OCMXwP34-3oXsK315juWuk';
  public $property_address = '';
  public $zillow_ppty_data;

  //Initiate Class
  public function __construct($zillow_id, $google_maps_api_key)
  {
    $this->zillow_id = $zillow_id;
    $this->google_maps_api_key = $google_maps_api_key;
  }

  /**
  * set_address()
  *
  * Method to set address in-case user decides to change address, instead of
  * initiating class again, this method can be called with the address as the only parameter
  *
  * @param string $address The new address.
  *
  * @return NULL
  */
  public function set_address($address){
    $this->property_address = $address;
  }

  /**
  * get_address()
  *
  * Method to get the address clara is currently working on
  *
  * @param NULL.
  *
  * @return string $this->property_address The current address.
  */
  public function get_address(){
    return $this->property_address;
  }

  /**
  * parse()
  *
  * Method to convert xml data to json
  *
  * @param string $url The link to the zillow xml data.
  *
  * @return string $json The json equivalent of the data in the url parsed to the method.
  */
  public function parse($url) {
    $fileContents= file_get_contents($url);
    $fileContents = preg_replace('~\s*(<([^-->]*)>[^<]*<!--\2-->|<[^>]*>)\s*~','$1',$fileContents);
    $simpleXml = simplexml_load_string($fileContents);
    $json = json_encode($simpleXml);
    return $json;
  }

  /**
  * get_home_worth()
  *
  * Method to get the worth of the property located at the address Clara is currently working on. It Uses Zillow API to get
  * data for this property and store it in $this->zillow_ppty_data if found.
  *
  * @param NULL
  *
  * @return string boolean true if Clara was able to get the data from zillow and fallse if the property was not found.
  */
  public function get_home_worth(){
    // Process Address
    $address_data = explode(',', $this->property_address);
    $address = urlencode($address_data[0]);
    $citystatezip = urlencode($address_data[1].' '.$address_data[2]);
    // Zillow Endpoint
    $url = "http://www.zillow.com/webservice/GetSearchResults.htm?zws-id=".$this->zillow_id."&address=".$address."&citystatezip=".$citystatezip;
    $json_data = $this->parse($url);
    $estimate_data = json_decode($json_data);
    // Check if we found the property
    $code = $estimate_data->message->code;
    if($code != 0){
      return false;
    }else{
      $this->zillow_ppty_data = $estimate_data;
      return true;
    }
  }

  /**
    * get_property_image()
    *
    * Method to get the worth of the property located at the address Clara is currently working on.
    *
    * @param string $width - image width, $height - image height.
    *
    * @return string $image_src.
  */
  public function get_property_image($width, $height){
    $image_src = "https://maps.googleapis.com/maps/api/streetview?size=".$width."x".$height."&location=".$this->property_address."&fov=90&heading=&pitch=0&key=".$this->google_maps_api_key;
    return $image_src;
  }

  /**
    * show_estimate_info()
    *
    * Method to show estimate data, estimate data that can be referenced includes: zestimate, home_url, zestimate_low and
    * zestimate_high, more will be added soon.
    *
    * @param string $what_to_show Estimate data to reference.
    *
    * @return string $what_to_show from $this->zillow_ppty_data.
  */
  public function show_estimate_info($what_to_show){
    $ppty_data = $this->zillow_ppty_data;
    switch ($what_to_show) {
      case 'home_url':
        // get the url to the property
        return $ppty_data->response->results->result->links->homedetails;
        break;

      case 'zestimate':
        // get the url to the property
        return $ppty_data->response->results->result->zestimate->amount;
        break;

      case 'zestimate_low':
        // get the url to the property
        return $ppty_data->response->results->result->zestimate->valuationRange->low;
        break;

      case 'zestimate_high':
        // get the url to the property
        return $ppty_data->response->results->result->zestimate->valuationRange->high;
        break;

      case 'zpid':
        // get the url to the property
        return $ppty_data->response->results->result->zpid;
        break;

      default:
        // code...
        break;
    }
  }

}