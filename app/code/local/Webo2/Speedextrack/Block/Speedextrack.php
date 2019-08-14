<!-- 
/**
* @category   Webo2
* @package    Speedextrack
* @author     Mits Xourikis
* @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/
-->
<?php
class Webo2_Speedextrack_Block_Speedextrack extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getSpeedextrack()     
     { 
        $WebServiceURL = 'http://tempuri.org/';
        $wsdl = "http://www.speedex.gr/getvoutrans/GetVouTrans.asmx?WSDL";

        if (file_get_contents($wsdl)) {
            $client = new SoapClient($wsdl,
                array(
                    'trace' => true
                )
            );
        } else {
			echo $faulty;
            return false;
        }
		$faulty = 'makis';
		$faulty2 = '!$client';
		$faulty3 = 'exception';
        if ($data = $this->getRequest()->getPost()) {
            $vouch_id = $data["trackcode_id"];
			$Cust_id= Mage::getStoreConfig('speedextrack/speedextrack_general/account_id');
			$custagreement_id = '82873';
			$kind = '0';
			$speedextrackingHelper = Mage::helper('speedextrack');		
			$xml = '<VoucherRequest>
				<xs:schema id="NewDataSet" xmlns="" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:msdata="urn:schemas-microsoft-com:xml-msdata">
				  <xs:element name="NewDataSet" msdata:IsDataSet="true" msdata:UseCurrentLocale="true">
					<xs:complexType>
					  <xs:choice minOccurs="0" maxOccurs="unbounded">
						<xs:element name="kritiria">
						  <xs:complexType>
							<xs:sequence>
							  <xs:element name="type" type="xs:short" minOccurs="0" />
							  <xs:element name="voucher" type="xs:string" minOccurs="0" />
							  <xs:element name="customer" type="xs:string" minOccurs="0" />
							</xs:sequence>
						  </xs:complexType>
						</xs:element>
					  </xs:choice>
					</xs:complexType>
				  </xs:element>
				</xs:schema>
				<diffgr:diffgram xmlns:msdata="urn:schemas-microsoft-com:xml-msdata" xmlns:diffgr="urn:schemas-microsoft-com:xml-diffgram-v1">
				  <NewDataSet xmlns="">
					<kritiria diffgr:id="kritiria1" msdata:rowOrder="0">
					  <type>'.$kind.'</type>
					  <voucher>'.$vouch_id.'</voucher>
					  <customer>'.$Cust_id.'</customer>
					</kritiria>
				  </NewDataSet>
				</diffgr:diffgram>
			  </VoucherRequest>';
			  
			$params = new \SoapVar('<GetVouTrans xmlns="http://tempuri.org/">'.$xml.'</GetVouTrans>', XSD_ANYXML);
			
			try {
				$result = $client->__call("GetVouTrans", array($params));
				$responsevalues = $client->__getLastResponse();
				
				$doc = new DOMdocument();
				$doc->loadXML( $responsevalues );

					echo "<table class='dtrack-data-table'><tbody><tr class='tg'><th>#ID</th><th>Ημ/νία</th><th>Περιγραφή</th><th>Σχόλιο 1</th><th>Σχόλιο 2</th><th>Περιοχή</th></tr>";
					$rows = $doc->getElementsByTagName("Table"); 
					$nodeCount = $rows->length; 
					foreach( $rows as $rows ) {
						$vousids = $rows->getElementsByTagName( "Voucher_ID" );
						$vousid = $vousids->item(0)->nodeValue;
						$vousdates = $rows->getElementsByTagName( "TranDate" );
						$vousdate = $vousdates->item(0)->nodeValue;
						$vstatus = $rows->getElementsByTagName( "VStatus_Descr" );
						$vstatu = $vstatus->item(0)->nodeValue;
						$branches = $rows->getElementsByTagName( "branch_description" );
						$branch = $branches->item(0)->nodeValue;
						$vparals = $rows->getElementsByTagName( "com1" );
						$vpar = $vparals->item(0)->nodeValue;
						$vdates = $rows->getElementsByTagName( "com2" );
						$vdate = $vdates->item(0)->nodeValue;
							$stat = "\n" . $vousid . "\n";
							$vousdat = "\n" . $vousdate . "\n";
							$descr = "\n" . $vstatu . "\n";
							$vparal = "\n" . $vpar . "\n";
							$bran = "\n" . $branch . "\n";
							$vdatesz = "\n" . $vdate . "\n";
							$colwidth = "15%";
								echo "<tr><td>" . $stat. "</td><td>" . $vousdat. "</td><td>" . $descr. "</td><td>" . $vparal. "</td><td>" . $vdatesz. "</td><td>" . $bran. "</td></tr>";
					
						}
					$rows = $doc->getElementsByTagName("Table1"); 
					$nodeCount = $rows->length; 
					foreach( $rows as $rows ) {
						$vousids = $rows->getElementsByTagName( "Voucher_ID" );
						$vousid = $vousids->item(0)->nodeValue;
						$vousdates = $rows->getElementsByTagName( "TranDate" );
						$vousdate = $vousdates->item(0)->nodeValue;
						$vstatus = $rows->getElementsByTagName( "VStatus_Descr" );
						$vstatu = $vstatus->item(0)->nodeValue;
						$branches = $rows->getElementsByTagName( "branch_description" );
						$branch = $branches->item(0)->nodeValue;
						$coms = $rows->getElementsByTagName( "Comments" );
						$com = $coms->item(0)->nodeValue;
						$nothing = '-';
							$stat = "\n" . $vousid . "\n";
							$vousdat = "\n" . $vousdate . "\n";
							$descr = "\n" . $vstatu . "\n";
							$bran = "\n" . $branch . "\n";
							$commentz = "\n" . $com . "\n";
							$null = "\n" .$nothing. "\n";
							$colwidth = "15%";
								echo "<tr><td>" . $stat. "</td><td>" . $vousdat. "</td><td>" . $descr. "</td><td>" . $commentz. "</td><td>" . $null. "</td><td>" . $bran. "</td></tr>";
					
						}
						echo "</tbody></table>";
				
				
				if (isset($response->WSException) && !is_null($response->WSException)) {
					if (isset($response->WSException->Description)) {
						$speedextrackingHelper->log('GetLastVouTrans', $response->WSException->Description);
					}
					return false;
					echo $faulty3;
				}
			} catch (SoapFault $e) {
				echo $faulty;
				return false;
			}

			return $responsevalues;
        }
    }
    public function getSpeedextrackInfo($order)
    {
        $shipTrack = array();
        if ($order) {
            $shipments = $order->getShipmentsCollection();
            foreach ($shipments as $shipment){
                $increment_id = $shipment->getIncrementId();
                $tracks = $shipment->getTracksCollection();

                $trackingInfos=array();
                foreach ($tracks as $track){
                    $trackingInfos[] = $track->getNumberDetail();
                }
                $shipTrack[$increment_id] = $trackingInfos;
            }
        }
        return $shipTrack;
    }
       public function formatDeliveryDateTime($date, $time)
    {
        return $this->formatDeliveryDate($date) . ' ' . $this->formatDeliveryTime($time);
    }

    /**
     * Format given date in current locale without changing timezone
     *
     * @param string $date
     * @return string
     */
    public function formatDeliveryDate($date)
    {
        /* @var $locale Mage_Core_Model_Locale */
        $locale = Mage::app()->getLocale();
        $format = $locale->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM);
        return $locale->date(strtotime($date), Zend_Date::TIMESTAMP, null, false)
            ->toString($format);
    }

    /**
     * Format given time [+ date] in current locale without changing timezone
     *
     * @param string $time
     * @param string $date
     * @return string
     */
    public function formatDeliveryTime($time, $date = null)
    {
        if (!empty($date)) {
            $time = $date . ' ' . $time;
        }
        
        /* @var $locale Mage_Core_Model_Locale */
        $locale = Mage::app()->getLocale();
        
        $format = $locale->getTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
        return $locale->date(strtotime($time), Zend_Date::TIMESTAMP, null, false)
            ->toString($format);
    }
}