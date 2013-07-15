<?php
require_once('./Lib/Tools/Torrent.class.php');

class RssAction extends DkAction
{
    function index()
    {
        
      		$id=$_GET['id'];
	        $Rss      = M("Rss");
	        $archives = M("archives");
        	$rssurl = $Rss->where("id=$id")->find();
            $i = 1;
            set_time_limit(900);
            $url     = $rssurl[Rss];
            $lasturl = $rssurl[lasturl];
      
            if (strpos($url, "ktxp")) {
                
                $html = file_get_contents($url);
                
                $xml = simplexml_load_string($html);
                
                $result = $xml->xpath("//channel/item");
                
                
                foreach ($result as $v) {
                    
                    
                    //读取enclosure中属性的值
                    foreach ($v->enclosure->attributes() as $a => $b) {
                        if ($a == "url") {
                            $url         = $b;
                            $url         = (string) $url;
			                 if ($url == $lasturl) {
			                        return;
			                    }
                          $torrenttemp = new Torrent("$url");
                                    echo $url."<br>-----------------------------------";
                            $download    = "magnet:?xt=urn:btih:" . $torrenttemp->hash_info();
                            
                   				echo $download."<br>-----------------------------------";
                            
                        }
                    }
                    

                    
                    $sql= "diskurl="."'".$download."'";
					$check = $archives->where($sql)->find();
                     $checkurl     = $check[diskurl];
                    if ($checkurl!=$download){
                   
                    
                    $archivesData['diskurl']  = "$download";
                    $archivesData['name']     = "$v->title";
                    $archivesData['diskname'] = "Magnet";
                     $archivesData['isautocollect'] = "1";
                    $archivesData['remark']   = "$v->pubDate<br>$v->author<br>$v->description<br>$v->link<br>$v->guid<br>";
      
                    $archives->add($archivesData);
							echo $i."<br>";
                    }
                    
                    if ($i == 1) {
                        $temp['lasturl'] = "$url";
                        $temp['id']      = $id;
                        $Rss->save($temp);
                        
                    }
                    $i++;
                }
                
                
                
                
            }else if(strpos($url, "ehtracker")) {
            



			
   			 $html = file_get_contents($url);
				$html=str_replace('<feed xmlns="http://www.w3.org/2005/Atom">', '<feed>', $html);

                $xml  = simplexml_load_string($html);
                
                $result = $xml->xpath("//feed/entry");
         
                
                    foreach ($result as $v) {
                    
           
  					
                    //读取enclosure中属性的值
                   foreach ($v->link->attributes() as $a => $b) {
                    	
       
            
                        if ($a == "href") {
                            $url         = $b;
                            $url         = (string) $url;
                          
			                 if ($url == $lasturl) {
			                        return;
			                    }
			        		 $urltemp=$url;
			                $ch = curl_init();
							curl_setopt($ch, CURLOPT_URL, "$urltemp");//确定解析对象
							curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)");
							curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-FORWARDED-FOR:$iipp", "CLIENT-IP:$iipp"));  //构造IP
							$iipp="180.235.252.230";
							curl_setopt($ch, CURLOPT_REFERER,$iipp);   //构造来路
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);  //是否抓取跳转后的页面
							$file = curl_exec($ch);//数据流存放文件
							curl_close($ch);
							$urltemp= $file;
                            $torrenttemp = new Torrent("$urltemp");
                             echo $url1."<br>-----------------------------------";
                         echo $url."<br>-----------------------------------";
                            $download    = "magnet:?xt=urn:btih:" . $torrenttemp->hash_info();
                            
                   				echo $download."<br>-----------------------------------";
                           
                        }
            
                        
                    foreach ($v->content->div->img->attributes() as $a) {
                    	
       						
                        } 
                    }
                    	

                    
                    $sql= "diskurl="."'".$download."'";
					$check = $archives->where($sql)->find();
                     $checkurl     = $check[diskurl];
                     
                    
                    if ($checkurl!=$download){
                   
                    
                    $archivesData['diskurl']  = "$download";
                    $archivesData['name']     = "$v->title";
                    $archivesData['diskname'] = "Magnet";
                     $archivesData['isautocollect'] = "1";
                    $archivesData['remark']   = '<img src="'.$a.'"></img>';
           
             $archives->add($archivesData);
							echo $i."<br>";
                    }
                    
                    if ($i == 1) {
                        $temp['lasturl'] = "$url";
                        $temp['id']      = $id;
                        $Rss->save($temp);
                        
                    }
                    $i++;
                }
            
   
            
            
            
            } else if (strpos($url, "nyaa")) {
                

                $html = file_get_contents($url);

                $xml  = simplexml_load_string($html);
                
                $result = $xml->xpath("//channel/item");

                
       
                foreach ($result as $v) {
                    
                    $url = (string) $v->link;
                	if ($url == $lasturl) {
                        return;
                    }
                    $torrenttemp = new Torrent("$url");
             
                         echo $url."<br>-----------------------------------";
                            $download    = "magnet:?xt=urn:btih:" . $torrenttemp->hash_info();
                            
                   				echo $download."<br>-----------------------------------";

                      $sql= "diskurl="."'".$download."'";
					$check = $archives->where($sql)->find();
                     $checkurl     = $check[diskurl];
                    if ($checkurl!=$download){
                   
                    
                    
                    $archivesData['diskurl']  = "$download";
                    $archivesData['name']     = "【"."$v->category" ."】" . "$v->title";
                    $archivesData['diskname'] = "Magnet";
                    $archivesData['isautocollect'] = "1";
                    $archivesData['remark']   = "$v->pubDate<br><br>$v->description<br>$v->link<br>$v->guid<br>";
                    	$archives->add($archivesData);
             			echo $i."<br>";
                    }
                    
                     
                    if ($i == 1) {
                        $temp['lasturl'] = "$url";
                        $temp['id']      = $id;
                        $Rss->save($temp);
                        
                    }
                    $i++;
                }
                
            }
            
            
            
            
            
            
        }
        
        
        
        
        
        
    }

