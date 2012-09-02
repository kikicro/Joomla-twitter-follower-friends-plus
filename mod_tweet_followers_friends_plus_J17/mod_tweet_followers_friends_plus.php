<?php
/**
 * @Author		I am Bored So I Blog
 * @version		1.0
 * @copyright	Theo van der Sluijs / IAMBOREDSOIBLOG.eu
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
 * PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');

$Blink 	     	= $params->def( 'Blink', 0 );
$count	 		= (int) $params->get( 'TwitCount', 12 );
$cache	 		= (int) $params->get( 'TwitCache', 30 );
$size	 		= (int) $params->get( 'TwitSize', 48 );
$OpenLink	 	= (int) $params->get( 'OpenLink', 1 );
$RanLast	 	= (int) $params->get( 'RanLast', 0 );
$showwhat		= (int) $params->get( 'showwhat', 0 );
//check if server has everything to work with
$host = JURI::root();
JHTML::stylesheet("tweet_follower.css", $host."modules/mod_tweet_followers_friends_plus/tmpl/");

modTweetFollowersFriendsPlus::CreateFF();
$TwitterParams = modTweetFollowersFriendsPlus::GetFF();

$UTime = time();
$now = time();
$UCacheTime = ($now + ($cache*60));
$needed = 1;

foreach ($TwitterParams as $TwitterParam){
	if($showwhat==0){//friends
		$timestamp = (int) $TwitterParam->friends_cache;
	}else{
		$timestamp = (int) $TwitterParam->followers_cache;
	}	
}

if(empty($timestamp)){
	$TwitOut = modTweetFollowersFriendsPlus::GetThem($params,$showwhat);
	modTweetFollowersFriendsPlus::SetFF($UCacheTime,$showwhat);
}else{
	
	if ($cache == 0 || $UTime > $timestamp)
	{
		$TwitOut = modTweetFollowersFriendsPlus::GetThem($params,$showwhat);
		modTweetFollowersFriendsPlus::SetFF($UCacheTime,$showwhat);
	
	}else{
		$TwitOut = modTweetFollowersFriendsPlus::GetFromDB($showwhat);
	}
}		

require(JModuleHelper::getLayoutPath('mod_tweet_followers_friends_plus'));