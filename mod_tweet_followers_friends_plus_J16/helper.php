<?php
/**
 * @Author		I am Bored So I Blog
 * @version		3.2
 * @copyright	Theo van der Sluijs / IAMBOREDSOIBLOG.eu
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * 

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


require_once 'twitteroauth/twitteroauth.php';

class modTweetFollowersFriendsPlus{

	function GetThem(&$params,$kind){
		$CONSUMER_KEY 	= $params->get( 'CONSUMER_KEY' );
		$CONSUMER_SECRET 	= $params->get( 'CONSUMER_SECRET' );
		$REQUEST_TOKEN = $params->get( 'REQUEST_TOKEN' );
		$REQUEST_TOKEN_SECRET = $params->get( 'REQUEST_TOKEN_SECRET' );
			
		$connection = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET,$REQUEST_TOKEN,$REQUEST_TOKEN_SECRET );
		//$connection->format = 'xml';
		$connection->format = 'json';
		$connection->decode_json = TRUE;
		$result = $connection->get('account/verify_credentials', array());

		$TwitInfo = json_encode($result);
		//NODIG 
		$Username = $result->screen_name;		
		$avatar = $result->profile_image_url;	
		$id = $result->id;
		$totalFollowers = $result->followers_count;
		$totalFriends = $result->friends_count;

		if($kind==0){//friends
			$result = $connection->get('statuses/friends', array('cursor' => -1));
		}else{
			$result = $connection->get('statuses/followers', array('cursor' => -1));
		}	
		
		self::DeleteThem($kind);//delete 
		$twitffs = array();
		$newresult = get_object_vars($result);

		foreach ($newresult['users'] as $key => $users){
			$user =  get_object_vars($users);
			$twitffs[$key] = array('screen_name' => $user['screen_name'],'name' => $user['name'],'profile_image_url' => $user['profile_image_url'], 'description' =>$user['description'] );
			self::SaveThem($user['screen_name'],$user['name'],$user['profile_image_url'],$user['description'],$kind);	//save 
		}
		return $twitffs;
	}

	function GetFromDB($kind){
		global $mainframe;
		$db  	 = JFactory::getDBO();
		//	if($kind==0){//friends
				$query   = "SELECT * FROM #__twitter_friends_followers WHERE ff = ".$kind.";";
//			}else{
	//			$query   = "SELECT * FROM #__twitter_followers;";
		//	}	
		$db->setQuery( $query );
		$rows = $db->loadObjectList(); 
		$newrows = self::objecttoarray($rows);
		return $newrows;
	}		
	
	function DeleteThem($kind){
			global $mainframe;
			$db   = JFactory::getDBO();
			//if($kind==0){//friends
				$query   = "DELETE FROM #__twitter_friends_followers WHERE ff = ".$kind.";";
		//	}else{
	//			$query   = "DELETE FROM #__twitter_followers;";
//			}
			$db->setQuery( $query );
			$db->query();
	}
	
	function SaveThem($screen_name,$name,$profile_image_url, $description,$kind){
			global $mainframe;
			$db   = JFactory::getDBO();	
//			if($kind==0){//friends
				$query = "INSERT INTO #__twitter_friends_followers (`screen_name`,`name`,`profile_image_url`, `description`, `ff`) values ('".$screen_name."','".$name."','".$profile_image_url."', '".$description."', ".$kind.");";
//			}else{
				//$query = "INSERT INTO #__twitter_followers (`screen_name`,`name`,`profile_image_url`, `description`) values //('".$screen_name."','".$name."','".$profile_image_url."', '".$description."');";
		//	}
			$db->setQuery( $query );
			$db->query();
	}	

	function GetFF(){
		global $mainframe;
		$db  	 = JFactory::getDBO();
		$query   = "SELECT * FROM #__twitter_ff";	
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		return $rows;
	}	

	function SetFF($UCacheTime, $kind){
		global $mainframe;
		$db  	 = JFactory::getDBO();
		if($kind==0){//friends
			$query   = "UPDATE #__twitter_ff SET `friends_cache` = ".$UCacheTime;	
		}else{
			$query   = "UPDATE #__twitter_ff SET `followers_cache` = ".$UCacheTime;	
		}	
		$db->setQuery( $query );
		if (!$db->query()) {
			//return the error
			$status['error'] = 'Error : ' . $db->stderr();
			echo $status;
		 }	
	}

	function CreateFF(){
		global $mainframe;
		$db   = JFactory::getDBO();

		$table_query = "CREATE TABLE IF NOT EXISTS #__twitter_ff (  `friends_cache` INT(10) NULL,  `followers_cache` INT(10) NULL ) COLLATE='utf8_general_ci';";
		$db->setQuery( $table_query );

		if (!$db->query()) {
			//return the error
			$status['error'] = 'Error : ' . $db->stderr();
			echo $status;
		 }
/*
		$table_query = "CREATE TABLE IF NOT EXISTS #__twitter_friends (  
								`datetime` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
								`screen_name` VARCHAR(250) NULL DEFAULT NULL,
								`name` VARCHAR(250) NULL DEFAULT NULL,
								`profile_image_url` VARCHAR(250) NULL DEFAULT NULL,
								`description` VARCHAR(250) NULL DEFAULT NULL
							)
						COLLATE='utf8_general_ci';";
		$db->setQuery( $table_query );

		if (!$db->query()) {
			//return the error
			$status['error'] = 'Error : ' . $db->stderr();
			echo $status;
		 }		 
*/
		$table_query = "CREATE TABLE IF NOT EXISTS #__twitter_friends_followers (
						`datetime` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
						`screen_name` VARCHAR(250) NULL DEFAULT NULL,
						`name` VARCHAR(250) NULL DEFAULT NULL,
						`profile_image_url` VARCHAR(250) NULL DEFAULT NULL,
						`description` VARCHAR(250) NULL DEFAULT NULL,
						`ff` INT(1) DEFAULT 0
					)
					COLLATE='utf8_general_ci';";
		$db->setQuery( $table_query );
		
		if (!$db->query()) {
			//return the error
			$status['error'] = 'Error : ' . $db->stderr();
		echo $status;
		 } 
	}	
	
	
	function objecttoarray( $object ) 
	{ 
		if( !is_object( $object ) && !is_array( $object ) ) 
		{ 
			return $object; 
		} 
		if( is_object( $object ) ) 
		{ 
			$object = get_object_vars( $object ); 
		} 
		return array_map( 'objecttoarray', $object ); 
	}
}
?>