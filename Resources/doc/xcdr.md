INVITE Networks Cisco WSAPI XCDR Documentation
==============================================

### WSAPI XCDR SOAP Request/Response

**RequestXcdrRegister**

````
POST /cisco_xcdr HTTP/1.1
Host: 209.180.87.74:8090
Connection: Keep-Alive
User-Agent: PHP-SOAP/5.4.11
Content-Type: application/soap+xml; charset=utf-8; action="http://www.cisco.com/schema/cisco_xcdr/v1_0#RequestXcdrRegister"
Content-Length: 933

<?xml version="1.0" encoding="UTF-8"?>
<env:Envelope xmlns:env="http://www.w3.org/2003/05/soap-envelope"  xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:enc="http://www.w3.org/2003/05/soap-encoding">
   <env:Body>
     <RequestXcdrRegister xmlns="http://www.cisco.com/schema/cisco_xcdr/v1_0" env:encodingStyle="http://www.w3.org/2003/05/soap-encoding">
        <applicationData>
           <name>invite_xcdr</name>
           <url>http://app_url:80/wsapi/xcdr</url>
        </applicationData>
        <msgHeader>
           <transactionID>xcdr53106379126a3</transactionID>
        </msgHeader>
        <providerData>
           <url>http://router_ip:8090/cisco_xcdr</url>
        </providerData>
     </RequestXcdrRegister>
   </env:Body>
</env:Envelope>

````

**ResponseXcdrRegister**

````
HTTP/1.1 200 OK
Date: Fri, 28 Feb 2014 10:22:49 GMT
Server: cisco-IOS
Connection: close
Content-Length: 417
Content-Type: application/soap+xml
Expires: Fri, 28 Feb 2014 10:22:49 GMT
Last-Modified: Fri, 28 Feb 2014 10:22:49 GMT
Cache-Control: no-store, no-cache, must-revalidate
Accept-Ranges: none

<?xml version="1.0" encoding="UTF-8"?>
<SOAP:Envelope xmlns:SOAP="http://www.w3.org/2003/05/soap-envelope">
  <SOAP:Body>
    <ResponseXcdrRegister xmlns="http://www.cisco.com/schema/cisco_xcdr/v1_0">
      <msgHeader>
         <transactionID>xcdr53106379126a3</transactionID>
         <registrationID>AFADA9F4:XCDR:invite_xcdr:70</registrationID>
      </msgHeader>
      <providerStatus>IN_SERVICE</providerStatus>
    </ResponseXcdrRegister>
  </SOAP:Body>
</SOAP:Envelope>

````