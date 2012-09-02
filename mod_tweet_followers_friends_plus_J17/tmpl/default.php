<?php // no direct access
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
defined('_JEXEC') or die('Restricted access'); 

if($needed == 1){
//EDIT THIS TO YOUR NEEDS 
	echo "<div class='twitter-followers'>";
	//DON"T EDIT BELOW

	$countArr = count($TwitOut);
	
if($countArr<=0 || $countArr == null){
	echo "ERROR ! <br /> Please check your params! <br/> Like twitter login info!</div>";
}else{
	
	if($countArr<$count){$count = $countArr;}

	$data = range(1, $countArr);
	$rand = array_rand($data,$count);

	$followers = $TwitOut;

	if($OpenLink == 1){
		$target = "_blank";
	}else{
		$target = "_self";
	}

	$out = "";
	
	for ( $i=0; $i<$count; $i++){

		if($RanLast== 0){
			$j = $rand[$i];
		}else{
			$j = $i;
		}

		$follower = $TwitOut[$j];
		if (!isset($TwitOut[$j])) break;
			$out .= "\n<a href=\"http://twitter.com/" . $follower["screen_name"] . "\" title=\"" . $follower["screen_name"] . ": " . htmlspecialchars($follower["description"]) . "\" target=\"".$target."\"><img src=\"" . $follower["profile_image_url"] . "\" border=\"0\" style=\"width: {$size}px; height: {$size}px;\" alt=\"".$follower["screen_name"]."\" /></a>";	
	}
	echo $out;
	echo "</div>";
	}}
if($Blink == 0){
	echo "<div style='font-size:10px; text-align: center;margin-top:10px;'>";
	echo "Created by <a href='http://www.iamboredsoiblog.eu' target='_blank'>IamBoredSoIBlog.eu</a>";
	echo "</div>";
}


?>