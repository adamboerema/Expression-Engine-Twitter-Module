<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ExpressionEngine - by EllisLab
 *
 * @package		ExpressionEngine
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2003 - 2011, EllisLab, Inc.
 * @license		http://expressionengine.com/user_guide/license.html
 * @link		http://expressionengine.com
 * @since		Version 2.0
 * @filesource
 */
 
// ------------------------------------------------------------------------

/**
 * Prpl_Twitter Module Front End File
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Module
 * @author		Adam Boerema
 * @link		http://purplerockscissors.com
 */

class Prpl_twitter {
	
	private static USER_NAME = '';
	private static OAUTH_ACCESS_TOKEN_SECRET = '';
	private static CONSUMER_KEY = '';
	private static CONSUMER_SECRET = ''; 

	public $return_data;
	
    /**
     * Cron function to cache
     * tweets in the database
     *
     */
    function __construct(){
        ee()->load->helper('TwitterAPIExchange');
    }

    /**
     * Cron will query the twitter api for
     * user tweets and cache them in the database
     *
     */
    function cron(){
        //Setup the required tokens
        $user = urlencode(self::USER_NAME);
        $settings = array(
            'oauth_access_token' => self::OAUTH_ACCESS_TOKEN,
            'oauth_access_token_secret' => self::OAUTH_ACCESS_TOKEN_SECRET,
            'consumer_key' => self::CONSUMER_KEY,
            'consumer_secret' => self::CONSUMER_SECRET
        );

        $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
        $getfield = "?include_entities=true&include_rts=true&screen_name={$user}";
        $requestMethod = 'GET';
        $twitter = new TwitterAPIExchange($settings);
        $response = $twitter->setGetfield($getfield)
            ->buildOauth($url, $requestMethod)
            ->performRequest();

        $camacho = json_decode($response);

        //Loop through the feed and store in database
        foreach($camacho as $feed){

            //Convert time to unix timestamp
            $time = strtotime($feed->created_at);

            //Comma deliminate hashtag array and return as string
            $hashArray = array();
            foreach($feed->entities->hashtags as $hashtag){
                $hashArray[] = "#$hashtag->text";
            }
            $tags = implode(",", $hashArray);

            //Check if tweet already exists based on unix timestamp
            $duplicateTweets = ee()->db->select('tweet_time')
                ->from('prpl_twitter')
                ->where("tweet_time = {$time}")
                ->get();

            //If no duplicate time entries proceed
            if($duplicateTweets->num_rows() < 1){
                ee()->db->insert(
                    'prpl_twitter',
                    array(
                        'user_id' => $feed->user->id,
                        'user_name' => $feed->user->screen_name,
                        'tags' => $tags,
                        'tweet' => $feed->text,
                        'tweet_time' => $time
                    )
                );
            }
        }
    }
}
/* End of file mod.prpl_twitter.php */
/* Location: /system/expressionengine/third_party/prpl_twitter/mod.prpl_twitter.php */
