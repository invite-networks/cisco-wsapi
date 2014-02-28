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

**SolicitXcdrProbing**

````
POST /wsapi/xcdr HTTP/1.1
Host: cdr1.inviteservices.com:80
Content-Length: 516
Content-Type: application/soap+xml
Connection: Keep-Alive
Accept: text/vxml, text/x-vxml, application/vxml, application/x-vxml, application/voicexml, application/x-voicexml, text/plain, text/html, audio/basic, audio/wav, multipart/form-data, application/octet-stream
User-Agent: Cisco-IOS-C3900/15.2

<?xml version="1.0" encoding="UTF-8"?>
<SOAP:Envelope xmlns:SOAP="http://www.w3.org/2003/05/soap-envelope">
  <SOAP:Body>
    <SolicitXcdrProbing xmlns="http://www.cisco.com/schema/cisco_xcdr/v1_0">
      <msgHeader>
         <transactionID>AFDCD578:144265</transactionID>
         <registrationID>AFDA9FDC:XCDR:invite_xcdr:73</registrationID>
      </msgHeader>
      <sequence>1</sequence>
      <interval>120</interval>
      <failureCount>0</failureCount>
      <registered>true</registered>
      <providerStatus>IN_SERVICE</providerStatus>
    </SolicitXcdrProbing>
  </SOAP:Body>
</SOAP:Envelope>
````

**ResponseXcdrProbing**

````
HTTP/1.1 200 OK
Date: Fri, 28 Feb 2014 11:14:20 GMT
Server: Apache/2.2.23 (Unix) mod_ssl/2.2.23 OpenSSL/1.0.1f DAV/2 PHP/5.4.11
X-Powered-By: PHP/5.4.11
Content-Length: 729
Cache-Control: no-cache
Keep-Alive: timeout=5, max=100
Connection: Keep-Alive
Content-Type: application/soap+xml

<?xml version="1.0" encoding="UTF-8"?>
<soapenv:Envelope xmlns:soapenv="http://www.w3.org/2003/05/soap-envelope">
  <soapenv:Header/>
  <soapenv:Body>
    <ResponseXcdrProbing xmlns="http://www.cisco.com/schema/cisco_xcdr/v1_0">
      <msgHeader>
        <registrationID>AFDA9FDC:XCDR:invite_xcdr:73</registrationID>
        <transactionID>AFDCD578:144265</transactionID>
      </msgHeader>
      <sequence>1</sequence>
    </ResponseXcdrProbing>
  </soapenv:Body>
</soapenv:Envelope>
````