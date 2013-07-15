<?php
require_once('./Lib/Tools/Torrent.class.php');

class xtAction extends DkAction
{
    function index()
    {
			$page=$_GET['page'];
			$i=1;
            set_time_limit(0);
            $url     = "http://sukebei.nyaa.eu/?page=rss&cats=7_28&offset=$page";
           
    		  $archives = M("archives");

                $html = file_get_contents($url);

                $xml  = simplexml_load_string($html);
                
                $result = $xml->xpath("//channel/item");

                
       
                foreach ($result as $v) {
                    
                    $url = (string) $v->link;
                    $torrenttemp = new Torrent("$url");
                    $download    = "magnet:?xt=urn:btih:" . $torrenttemp->hash_info();
                    $sql= "diskurl="."'".$download."'";
					$check = $archives->where($sql)->find();
                     $checkurl     = $check[diskurl];
                    if ($checkurl!=$download){
                    	
                    $archivesData['diskurl']  = "$download";
                    $archivesData['name']     = "【"."$v->category" ."】" . "$v->title";
                    $archivesData['diskname'] = "Magnet";
                    $archivesData['remark']   = "$v->pubDate<br><br>$v->description<br>$v->link<br>$v->guid<br>";
           			
                    $archives->add($archivesData);
    				echo $i."<br>";
    				$i++;
                    }
                    
                    
                    
                }

                
            
                
    }
}           
 
       
            
 
        
        
