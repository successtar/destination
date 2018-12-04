<?php

function stripusersymbol($x){
	$x=strtolower(trim(str_replace("!","",str_replace("#","",str_replace("$","",str_replace("%","",str_replace("&","",str_replace("'","",str_replace("*","",str_replace("+","",str_replace("/","",str_replace("=","",str_replace("?","",str_replace("^","",str_replace("`","'",str_replace("{","",str_replace("|","",str_replace("}","",str_replace("~","",str_replace("@","",str_replace(".","",str_replace("[","",str_replace("]","",trim(filter_var(filter_var(str_replace("-","_",str_replace(" ","_",$x)),FILTER_SANITIZE_EMAIL)),FILTER_SANITIZE_STRING))))))))))))))))))))))));	

		return $x;
		}
$target=strtoupper(stripusersymbol($_REQUEST['code']));

$xml = new DOMDocument( "1.0", "UTF-8" );

$xml_env = $xml->createElement( "soap:Envelope" );
$xml_env->setAttribute( "xmlns:soap", "http://www.w3.org/2003/05/soap-envelope" );
$xml_env->setAttribute( "xmlns:hot", "http://TekTravel/HotelBookingApi" );

/*create header*/

$xml_hed = $xml->createElement( "soap:Header" );
$xml_hed->setAttribute( "xmlns:wsa", "http://www.w3.org/2005/08/addressing" );

$xml_cred = $xml->createElement( "hot:Credentials" );
$xml_cred->setAttribute( "UserName", "behtarin" );
$xml_cred->setAttribute( "Password", "beht@123" );

$xml_wsaa = $xml->createElement( "wsa:Action","http://TekTravel/HotelBookingApi/DestinationCityList" );
$xml_wsat = $xml->createElement( "wsa:To","http://api.tbotechnology.in/hotelapi_v7/hotelservice.svc" );

$xml_hed->appendChild($xml_cred);
$xml_hed->appendChild($xml_wsaa);
$xml_hed->appendChild($xml_wsat);

$xml_env->appendChild($xml_hed);

/*create body*/

$xml_bdy = $xml->createElement( "soap:Body" );
$xml_bdyreq = $xml->createElement( "hot:DestinationCityListRequest" );
$xml_bdyreqele = $xml->createElement( "hot:CountryCode",$target );

$xml_bdyreq->appendChild($xml_bdyreqele);
$xml_bdy->appendChild($xml_bdyreq);
$xml_env->appendChild($xml_bdy);

$xml->appendChild($xml_env);
$request = $xml->saveXML();

$location = "http://api.tbotechnology.in/hotelapi_v7/hotelservice.svc";
$action = "http://TekTravel/HotelBookingApi/DestinationCityList";
$client = new SoapClient("http://api.tbotechnology.in/hotelapi_v7/hotelservice.svc?wsdl");
$xmlfile = $client->__doRequest($request , $location , $action , 2 );


if (!strstr($xmlfile, "ProcessingErr:")){
$xmlstart='<s:Envelope xmlns:s="http://www.w3.org/2003/05/soap-envelope" xmlns:a="http://www.w3.org/2005/08/addressing"><s:Header><a:Action s:mustUnderstand="1">http://TekTravel/HotelBookingApi/IHotelService/DestinationCityListResponse</a:Action></s:Header><s:Body xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><DestinationCityListResponse xmlns="http://TekTravel/HotelBookingApi"><Status><StatusCode>01</StatusCode><Description>Successful: DestinationCityList Successful</Description></Status><CountryName>';

$xmlstart1='<s:Envelope xmlns:s="http://www.w3.org/2003/05/soap-envelope" xmlns:a="http://www.w3.org/2005/08/addressing"><s:Header><a:Action s:mustUnderstand="1">http://TekTravel/HotelBookingApi/IHotelService/DestinationCityListResponse</a:Action></s:Header><s:Body xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><DestinationCityListResponse xmlns="http://TekTravel/HotelBookingApi"><Status><StatusCode>04-13</StatusCode><Description>SystemErr: Technical Failure</Description></Status><CityList>';


$xmlend='</CityList></DestinationCityListResponse></s:Body></s:Envelope>';

$xmlfile=str_replace($xmlend, "", str_replace($xmlstart1, "",  str_replace($xmlstart, "", $xmlfile)));

$country = strtok($xmlfile, '<');

$xmlfile=str_replace($country."</CountryName><CityList>", "", $xmlfile);

$xmlfile=str_replace('"/>', '</div></div>',str_replace('<City CityCode="', '<div class="col-md-6" style="margin-bottom:10px"><div>City Code: ',str_replace('"/><City CityCode="', '</div></div> <div class="col-md-6" style="margin-bottom:10px"><div>City Code: ',str_replace('" CityName="', '</div><div>Destination: ', $xmlfile))));
if ($target=="UK"){
	$country='United Kingdom';
}
}

else{
$country="";
$xmlfile="<div ><center>No Destination found<center></div>";

}



?>



<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $country; ?> Destination Places with City Code</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">

         <meta name="keywords" content="destination, city, code, city code, world, <?php echo $country; ?>">
        <meta name="description" content="Destination Places Around the World with City Code. List of destination places in <?php echo $country; ?>">
    </head>
    <body>
        <div class="header col-md-8" style="margin-top:2px">
            <h2 align="left"> Destination Places with City Code</h2>   
                
        </div>

        
        <div class="content col-md-8">
            
                    <p ><center>
        	<h2>Destinations in <?php echo $country;	?></h2>
        	</center>
        	<br/>
        </p>

                       <!-- <p>Welcome <strong>Successtar</strong></p>  -->
<div class="row" style="margin-left: 50px">

 <?php

 echo $xmlfile;

 ?>

</div>


<div align="center"><a href="index.php" class="btn">Back</a></div>
            

<footer>
            <div class="col-md-12" align="center"><br/><br/>
               <strong> 
                Developed by <a href="https://twitter.com/realsuccesstar" target="_blank" style="color:#00FF00; text-decoration:none">successtar</a> (<a href="tel:+2347061855688" style=" text-decoration:none">07061855688</a>).
                </strong>
            </div>

        </footer> 
        </div>  

            
    </body>
</html>