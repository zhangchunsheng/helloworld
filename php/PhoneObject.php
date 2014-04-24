
<?PHP

  //********************************************************************************
  //If you should have any comments, suggestions or improvements to these samples,
  //we welcome you to contact us at SampleCode@melissadata.com also please visit our
  //developers bulletin board at forum.melissadata.com.
  //********************************************************************************

  // ********************** LICENSE STRINGS ***********************
  //       To unlock the full functionality of Phone Object,     
  //   please call a sales representative at 1-800-MELISSA ext. 3  
  //           (1-800-635-4772 x3) for a license string.           
  //        Without a valid license string, Phone Object will      
  //                  only verify Nevada numbers.     
  //                 REPLACE "DEMO" with LICENSE STRING                    

  //   SetLicenseString will also check for a valid license in the 
  //   MDPHONE_LICENSE(Environment) variable. This allows you to  
  //   modify the license without recompiling the project
  // **************************************************************
  $dLICENSE = "DEMO";

  // ********************** DATA FILE PATH  ***********************
  // File location path is set to the default Data File location. *
  // Change this value if you installed the data files to a       *
  // different folder.                                            *
  // The Data Files Directory must contain the following files:   *
  // mdAddr.dat, mdPhone.dat, mdPhone.idx, and ZIPNPA.TXT.                                   *
  // **************************************************************
  $dFILELOC= "C:\\Program Files\\Melissa DATA\\DQT\\Data";

  //Create the objects.
  $phoneObj = new COM("PhoneObject.PhoneCheck") or die("Cannot start for you");


?>
<HTML>
  <HEAD>
    <META http-equiv='Content-Language' content='en-us'>
    <META http-equiv='Content-Type' content='text/html charset=UTF-8'>
    <META http-equiv='ImageToolbar' content='no'>

    <META name='Author' content='Melissa Data Corporation'>
    <META name='Description' content='PhoneObject: PHP Sample Code.'>

    <TITLE>PhoneObject: PHP Sample Code</TITLE>



<?PHP
  //Using PHP:
  //Setting page variables and clearing them.
  $Build="----";
  $DatabaseDate="----";
  $InitErrorString="----";

  $AreaCode="";
  $NewAreaCode="";
  $City="";
  $CountryCode="";
  $CountyFips="";
  $CountyName="";
  $Distance="";
  $Extension="";
  $Latitude="";
  $Longitude="";
  $MSA="";
  $PMSA="";
  $Prefix="";
  $Results="";
  $State="";
  $Suffix="";
  $TimeZone="";
  $TimeZoneCode="";
  $ResultsString="";
  $Validation="";
  $ExchangeType="";
  $PhoneType="";
  $Errors="";

  $Latitude1="";
  $Latitude2="";
  $Longitude1="";
  $Longitude2="";

  $PhoneNumber="";
  $ZipCode="";

  $Bearing="";
  $CompDistance="";

  //Initialize object every time page is loaded
  $phoneObj->SetLicenseString($dLICENSE);
  $phoneObj->Initialize($dFILELOC);

  //Loading form with database date, expiration date, Build, and initialization status.
  $Build=$phoneObj->GetBuildNumber();
  $DatabaseDate=$phoneObj->GetDatabaseDate();
  $InitErrorString=$phoneObj->GetInitializeErrorString();

  //variables used to hold data, here the input info passed through pages.
  if(!empty($_POST)){

    $AreaCode=$_POST["AreaCode"];
    $City=$_POST["City"];
    $CountryCode=$_POST["CountryCode"];
    $CountyFips=$_POST["CountyFips"];
    $CountyName=$_POST["CountyName"];
    $Distance=$_POST["Distance"];
    $Extension=$_POST["Extension"];
    $Latitude=$_POST["Latitude"];
    $Longitude=$_POST["Longitude"];
    $MSA=$_POST["MSA"];
    $PMSA=$_POST["PMSA"];
    $Prefix=$_POST["Prefix"];
    $Results=$_POST["Results"];
    $State=$_POST["State"];
    $Suffix=$_POST["Suffix"];
    $TimeZone=$_POST["TimeZone"];
    $TimeZoneCode=$_POST["TimeZoneCode"];
    $Validation=$_POST["Validation"];
    $ExchangeType=$_POST["ExchangeType"];
    $PhoneType=$_POST["PhoneType"];
    $Errors=$_POST["Errors"];

    $PhoneNumber=$_POST["PhoneNumber"];
    $ZipCode=$_POST["ZipCode"];

    $Latitude1=$_POST["Latitude1"];
    $Latitude2=$_POST["Latitude2"];
    $Longitude1=$_POST["Longitude1"];
    $Longitude2=$_POST["Longitude2"];
    $CompDistance=$_POST["CompDistance"];
    $Bearing=$_POST["Bearing"];


  //If button is pressed, run Lookup
  if ($_POST["bsubmit"] =="Lookup"){

    //Clearing data
    $AreaCode="";
    $NewAreaCode="";
    $City="";
    $CountryCode="";
    $CountyFips="";
    $CountyName="";
    $Distance="";
    $Extension="";
    $Latitude="";
    $Longitude="";
    $MSA="";
    $PMSA="";
    $Prefix="";
    $Results="";
    $State="";
    $Suffix="";
    $TimeZone="";
    $TimeZoneCode="";
    $Validation="";
    $ExchangeType="";
    $PhoneType="";
    $Errors="";

    //Call Method
    $phoneObj->Lookup($_POST["PhoneNumber"],$_POST["ZipCode"]);

    //Store Data
    $AreaCode=$phoneObj->AreaCode;
    $City=$phoneObj->City;
    $CountryCode=$phoneObj->CountryCode;
    $CountyFips=$phoneObj->CountyFips;
    $CountyName=$phoneObj->CountyName;
    $Distance=$phoneObj->Distance;
    $Extension=$phoneObj->Extension;
    $Latitude=$phoneObj->Latitude;
    $Longitude=$phoneObj->Longitude;
    $MSA=$phoneObj->MSA;
    $PMSA=$phoneObj->PMSA;
    $Prefix=$phoneObj->Prefix;
    $Results=$phoneObj->Results;
    $ResultsString=$phoneObj->Results;
    
    if (strstr($ResultsString,"PE") != false)
    {
      if (strstr($ResultsString,"PE01") != false)
             $Errors="Area Code Not valid";
      if (strstr($ResultsString,"PE02") != false)
             $Errors="Blank Phone Number";
      if (strstr($ResultsString,"PE03") != false)
             $Errors="Phone Number Not valid";
      if (strstr($ResultsString,"PE04") != false)
             $Errors="Input has Multiple Matches";
      if (strstr($ResultsString,"PE05") != false)
             $Errors="Phone Prefix Not Valid";
      if (strstr($ResultsString,"PE06") != false)
             $Errors="Zip Code Not Valid";
    }
    
    if ((strstr($ResultsString,"PS01") != false) || (strstr($ResultsString,"PS02") != false))
    {
      if (strstr($ResultsString,"PS01") != false)
         $Validation="Validated to 10 Digits";
      elseif (strstr($ResultsString,"PS02") != false)
         $Validation="Validated to 7 Digits";
       
      if (strstr($ResultsString,"PS07") != false)
         $ExchangeType="Cellular";
      elseif (strstr($ResultsString,"PS08") != false)
         $ExchangeType="Land Line";  
      elseif (strstr($ResultsString,"PS09") != false)
         $ExchangeType="VOIP"; 
         
      if (strstr($ResultsString,"PS10") != false)
         $PhoneType="Residential";
      elseif (strstr($ResultsString,"PS11") != false)
         $PhoneType="Business";  
      elseif (strstr($ResultsString,"PS12") != false)
         $PhoneType="Small/Home Office";      
    }
         
    $State=$phoneObj->State;
    $Suffix=$phoneObj->Suffix;
    $TimeZone=$phoneObj->TimeZone;
    $TimeZoneCode=$phoneObj->TimeZoneCode;

  }
  elseif($_POST["bsubmit"]=="Correct Area Code") {
    //Clearing Data
    $NewAreaCode="";
    $City="";
    $CountryCode="";
    $CountyFips="";
    $CountyName="";
    $Distance="";
    $Extension="";
    $Latitude="";
    $Longitude="";
    $MSA="";
    $PMSA="";
    $Prefix="";
    $Results="";
    $State="";
    $Suffix="";
    $TimeZone="";
    $TimeZoneCode="";
    $Errors="";
    $Validation="";
    $ExchangeType="";
    $PhoneType="";

    //Call Method
    $phoneObj->CorrectAreaCode($_POST["PhoneNumber"],$_POST["ZipCode"]);

    //Store Data
    $NewAreaCode=$phoneObj->NewAreaCode;
    $AreaCode=$phoneObj->AreaCode;
    $Results=$phoneObj->Results;

  }
  //Store info for Coordinate 1.
  elseif($_POST["bsubmit"]=="Copy to Coordinate 1") {
    $Latitude1=$_POST["Latitude"];
    $Longitude1=$_POST["Longitude"];
  }

  //Store info for Coordinate 2.
  elseif($_POST["bsubmit"]=="Copy to Coordinate 2") {
    $Latitude2=$_POST["Latitude"];
    $Longitude2=$_POST["Longitude"];
  }
  //Compute Bearing.
  elseif($_POST["bsubmit"]=="Compute Bearing") {
    $Bearing=$phoneObj->ComputeBearing($_POST["Latitude1"],$_POST["Longitude1"],$_POST["Latitude2"],$_POST["Longitude2"]);
  }
  //Compute Distance.
  elseif($_POST["bsubmit"]=="Compute Distance") {
    $CompDistance=$phoneObj->ComputeDistance($_POST["Latitude1"],$_POST["Longitude1"],$_POST["Latitude2"],$_POST["Longitude2"]);
  }
}
else{
  //Set Default
  $ZipCode="89119";
  $PhoneNumber="7028965154";
}

?>

</HEAD>



<body>

    <TABLE border='0' cellpadding='0' cellspacing='0' width='744'>
      <TR>
        <TD background='background.gif' height='59'>
          <a href='http:'www.melissadata.com'><img border='0' src='melissadata.gif' width='218' height='21'>
        </TD>
      </TR>
      <TR style='font-family:arial;font-size:110%;color:white'>
        <TD align='center' background='background.gif' colspan='4' height='30'><B>PhoneObject: PHP Sample Code</B></TD>
      </TR>
    </TABLE>

    <FORM method='post' action="">
      <TABLE border='0' cellpadding='0' cellspacing='0' width='744' style='font-family:arial;font-size:80%'>
        <TR><TD></TD></TR>
        <TR><TD Colspan='2'><B>Database Status:</b></TD></TR>
        <TR>
          <TD Colspan='2'style='font-family:arial;font-size:7.8pt;color:gray'>
            Build: <INPUT type='text' name='Build' size='13' value='<?php echo  $Build ?>' disabled>
            Database Date: <INPUT type='text' name='DatabaseDate' size='7' value='<?php echo  $DatabaseDate?>' disabled>
            Init. Error String: <INPUT type='text' name='InitErrorString' size='50' value='<?php echo  $InitErrorString?>' disabled>
          </td>
        </TR>

        <TR>
          <Td colspan='2' style='font-family:arial;font-size:7.8pt;color:gray'>

          </td>
        </tr>
        <tr height=20><td></td></tr>
        <TR><TD Colspan='2'><B>PhoneCheck Methods:</b></TD></TR>
        <TR style='font-family:arial;font-size:7.8pt'>
          <TD colspan='2'align='center'><B>Phone Number:</B><INPUT type='text' name='PhoneNumber' size='15' value='<?php echo  $PhoneNumber ?>'>
              <B>Zip Code:</B><INPUT type='text' name='ZipCode' size='10' value='<?php echo  $ZipCode ?>'>
              </TD>
        </TR>
        <TR style='font-family:arial;font-size:7.8pt'>
          <TD colspan='2'align='center'><INPUT type='submit' name='bsubmit' value='Correct Area Code'>
              <INPUT type='submit' name='bsubmit' value='Lookup'>
              </TD>
        </TR>

        <tr height=10><td></td></tr>
        <TR>
          <td colspan='2'align='center'>
            <TABLE border='0' cellpadding='0' cellspacing='0' width='400'  style='font-family:arial;font-size:7.8pt'>
              <TR>
                <TD align='right'><b>Errors:</b></TD>
                <TD><INPUT type='text' name='Errors' size='25' value='<?php echo  $Errors ?>' ></TD>
                </TR>
              <TR>
              <TR>
                <TD align='right'><b>Validation:</b></TD>
                <TD><INPUT type='text' name='Validation' size='25' value='<?php echo  $Validation ?>' ></TD>
                </TR>
              <TR>
              <TR>
                <TD align='right'><b>Exchange Type:</b></TD>
                <TD><INPUT type='text' name='ExchangeType' size='25' value='<?php echo  $ExchangeType ?>' ></TD>
                </TR>
              <TR>  
              <TR>
                <TD align='right'><b>Phone Type:</b></TD>
                <TD><INPUT type='text' name='PhoneType' size='25' value='<?php echo  $PhoneType ?>' ></TD>
              </TR>
              <TR>
                <TD align='right'><b>Results:</b></TD>
                <TD><INPUT type='text' name='Results' size='25' value='<?php echo  $Results ?>' ></TD>
              </tr>
              <TR>
                <TD align='right'><b>Latitude:</b></TD>
                <TD><INPUT type='text' name='Latitude' size='15' value='<?php echo  $Latitude ?>' ></TD>
                <TD align='right'><b>Longitude:</b></TD>
                <TD><INPUT type='text' name='Longitude' size='15' value='<?php echo  $Longitude ?>' ></TD>
              </TR>
              <TR>
                <TD align='right'><b>AreaCode:</b></TD>
                <TD><INPUT type='text' name='AreaCode' size='15' value='<?php echo  $AreaCode ?>' ></TD>
                <TD align='right'><b>New AreaCode:</b></TD>
                <TD><INPUT type='text' name='NewAreaCode' size='15' value='<?php echo  $NewAreaCode ?>' ></TD>
              </TR>
              <TR>
                <TD align='right'><b>City:</b></TD>
                <TD><INPUT type='text' name='City' size='15' value='<?php echo  $City ?>' ></TD>
                <TD align='right'><b>State:</b></TD>
                <TD><INPUT type='text' name='State' size='15' value='<?php echo  $State ?>'></TD>
              </TR>
              <TR>
                <TD align='right'><b>Prefix:</b></TD>
                <TD><INPUT type='text' name='Prefix' size='15' value='<?php echo  $Prefix ?>' ></TD>
                <TD align='right'><b>Suffix:</b></TD>
                <TD><INPUT type='text' name='Suffix' size='15' value='<?php echo  $Suffix ?>' ></TD>
              </TR>
              <TR>
                <TD align='right'><b>CountryCode:</b></TD>
                <TD><INPUT type='text' name='CountryCode' size='15' value='<?php echo  $CountryCode ?>' ></TD>
                <TD align='right'></TD>
                <TD></TD>
              </tr>
              <TR>
                <TD align='right'><b>County Fips:</b></TD>
                <TD><INPUT type='text' name='CountyFips' size='15' value='<?php echo  $CountyFips ?>' ></TD>
                <TD align='right'><b>County Name:</b></TD>
                <TD><INPUT type='text' name='CountyName' size='15' value='<?php echo  $CountyName ?>' ></TD>
              </TR>
              <TR>
                <TD align='right'><b>Distance:</b></TD>
                <TD><INPUT type='text' name='Distance' size='15' value='<?php echo  $Distance ?>' ></TD>
                <TD align='right'><b>Extension:</b></TD>
                <TD><INPUT type='text' name='Extension' size='15' value='<?php echo  $Extension ?>' ></TD>
              </TR>
              <TR>
                <TD align='right'><b>MSA:</b></TD>
                <TD><INPUT type='text' name='MSA' size='15' value='<?php echo  $MSA ?>' ></TD>
                <TD align='right'><b>PMSA:</b></TD>
                <TD><INPUT type='text' name='PMSA' size='15' value='<?php echo  $PMSA ?>'></TD>
              </tr>
              <TR>
                <TD align='right'><b>TimeZone:</b></TD>
                <TD><INPUT type='text' name='TimeZone' size='15' value='<?php echo  $TimeZone ?>' ></TD>
                <TD align='right'><b>TimeZoneCode:</b></TD>
                <TD><INPUT type='text' name='TimeZoneCode' size='15' value='<?php echo  $TimeZoneCode ?>'></TD>
              </tr>
              
            </TABLE>
          </td>
        </tr>
        <tr height=10><td></td></tr>
        <TR><TD Colspan='2'><B>Compute Bearing/Distance Methods:</b></TD></TR>
        <TR style='font-family:arial;font-size:7.8pt'>
          <TD align='center'><INPUT type='submit' name='bsubmit' value='Copy to Coordinate 1'></TD>
          <TD align='center'><INPUT type='submit' name='bsubmit' value='Copy to Coordinate 2'></TD>
        </TR>

        <TR style='font-family:arial;font-size:7.8pt' align=center colspan=2>
          <TABLE border='0' cellpadding='0' cellspacing='0' width=744pt  style='font-family:arial;font-size:7.8pt'>
          <TR style='font-family:arial;font-size:7.8pt'>
            <TD align='right' width=25%>Latitude 1:</td>
            <TD align='left' width=25%><INPUT type='text' name='Latitude1' size='10' value='<?php echo  $Latitude1?>' ></TD>
            <TD align='right' width=25%>Latitude 2:</td>
            <TD align='left' width=25%><INPUT type='text' name='Latitude2' size='10' value='<?php echo  $Latitude2?>' ></TD>

          </TR>
          <tr>
            <TD align='right' width=25%>Longitude 1:</td>
            <td align='left' width=25%><INPUT type='text' name='Longitude1' size='10' value='<?php echo  $Longitude1?>' ></TD>
            <TD align='right' width=25%>Longitude 2:</td>
            <td align='left' width=25%><INPUT type='text' name='Longitude2' size='10' value='<?php echo  $Longitude2?>' ></TD>
          </tr>
          <tr height=10><td></td></tr>
          <TR style='font-family:arial;font-size:7.8pt'>
            <TD  Colspan='4'align='Center'><B>Bearing:</B><INPUT type='text' name='Bearing' size='10' value='<?php echo  $Bearing ?>' >
            <INPUT type='submit' name='bsubmit' value='Compute Bearing' ></TD>
          </TR>
          <TR style='font-family:arial;font-size:7.8pt'>
            <TD Colspan='4'align='Center'><B>Distance:</B><INPUT type='text' name='CompDistance' size='10' value='<?php echo  $CompDistance ?>' >
            <INPUT type='submit' name='bsubmit' value='Compute Distance' ></TD>
          </TR>
          </table>
        </TR>


        <tr>
        </tr>
        <tr>
        </tr>
        <tr>
        </tr>
      </TABLE>

    </FORM>

</body>