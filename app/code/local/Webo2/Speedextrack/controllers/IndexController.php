<!-- 
/**
* @category   Webo2
* @package    Speedextrack
* @author     Mits Xourikis
* @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/
-->
<?php
class Webo2_Speedextrack_IndexController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function validate() {
        
    }

    public function initOrder() {
        if ($data = $this->getRequest()->getPost()) {
            $orderId = $data["order_id"];
            $email = $data["email"];
            $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
            $cEmail = $order->getCustomerEmail();
            if ($cEmail == trim($email)) {
                Mage::register('current_order', $order);
            } else {
                Mage::register('current_order', Mage::getModel("sales/order"));
            }
        }
    }

	public function getSpeedorder() {
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

	public function loadorderstatus() {
			$params = new \SoapVar('<GetVouTrans xmlns="http://tempuri.org/">'.$xml.'</GetVouTrans>', XSD_ANYXML);
			$result = $client->__call("GetVouTrans", array($params));
			$responsevalues = $client->__getLastResponse();
			$doc = new DOMdocument();
			$doc->loadXML( $responsevalues );

				echo "<table class='dtrack-data-table'><tbody><tr><td>#ID</td><td>Ημ/νία</td><td>Περιγραφή</td><td>Σχόλιο 1</td><td>Σχόλιο 2</td><td>Περιοχή</td></tr>";
				$rows = $doc->getElementsByTagName("Table"); 
				$nodeCount = $rows->length; 
					echo ("There are {$nodeCount} records<br />");
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
					echo ("There are {$nodeCount} records<br />");
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
						$stat = "\n" . $vousid . "\n";
						$vousdat = "\n" . $vousdate . "\n";
						$descr = "\n" . $vstatu . "\n";
						$bran = "\n" . $branch . "\n";
						$commentz = "\n" . $com . "\n";
						$colwidth = "15%";
							echo "<tr><td>" . $stat. "</td><td>" . $vousdat. "</td><td>" . $descr. "</td><td>" . $commentz. "</td><td>" . $vdatesz. "</td><td>" . $bran. "</td></tr>";
				
					}
					echo "</tbody></table>";
    }
	
    public function trackAction() {
        $post = $this->getRequest()->getPost();

        if ($post) {
            try {
                if (!Zend_Validate::is(trim($post['trackcode_id']), 'NotEmpty')) {
                    $error = true;
                }
                if ($error) {
                    throw new Exception();
                }
                $this->getSpeedorder($post);
				
            } catch (Exception $e) {
                Mage::getSingleton('core/session')->addError(Mage::helper('speedextrack')->__('Please Enter Order Detail.'));
                $this->getResponse()->setBody($this->getLayout()->getMessagesBlock()->getGroupedHtml());
                return;
            }
        } else {
            $this->_redirect('*/*/');
        }
    }

    public function viewAction() {


        $actionUrl = $_SERVER['REQUEST_URI'];
        $pathinfo = pathinfo($actionUrl);
        $trackid = $pathinfo['basename'];

            $orderdata = Mage::getModel('sales/order')->getCollection()
                    ->addAttributeToFilter('track_link', $trackid)
                    ->getData();

            $order = Mage::getModel('sales/order')->load($orderdata[0]['entity_id']);
            

            
            
            if ($order->getId()) {
                Mage::register('current_order', $order);
                 $this->loadLayout();
                 $this->renderLayout();
                return true;
               
            } else {
                    Mage::getSingleton('core/session')->addError($this->__('Order not found. Please try again later.'));
                    $this->_redirect('*/*/');
            }
    }
    
    protected function _getGridHtml() {
        $layout = $this->getLayout();
        $update = $layout->getUpdate();
        $update->load("speedextrack_index_track");
        $layout->generateXml();
        $layout->generateBlocks();
        $output = $layout->getOutput();
        return $output;
    }

}
