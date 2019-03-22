# UN/EDIFACT Generator

<a href="https://packagist.org/packages/florowebdevelopment/edifact-generator"><img src="https://poser.pugx.org/florowebdevelopment/edifact-generator/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/florowebdevelopment/edifact-generator"><img src="https://poser.pugx.org/florowebdevelopment/edifact-generator/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/florowebdevelopment/edifact-generator"><img src="https://poser.pugx.org/florowebdevelopment/edifact-generator/license.svg" alt="License"></a>

## Install

```
composer require florowebdevelopment/edifact-generator
```

## Usage

```php
use Florowebdevelopment\EdifactGenerator;
```

COHAOR
------
Container special handling order message

```
$oInterchange = (new \Florowebdevelopment\EdifactGenerator\Interchange('ME', 'YOU'));

$sMessageReferenceNumber = 'ROW' . str_pad(1, 11, 0, STR_PAD_LEFT);

$oCohaor = (new \Florowebdevelopment\EdifactGenerator\Cohaor($sMessageReferenceNumber));

// Segment Group 2

$aSegments = [];

// Segment Group 2 : Name And Address

$oNameAndAddress = (new \Florowebdevelopment\EdifactGenerator\Segment\NameAndAddress())
    ->setPartyFunctionCodeQualifier('')
    ->setPartyIdentificationDetails('My Party')
    ->setNameAndAddress([
        'My Company', // line 1 .. 5
        'My Address', // line 2 .. 5
        '1234 AB' // line 3 .. 5
    ])
    ->setCityName('My City')
    ->setPostalIdentificationCode('123456')
    ->setCountryIdentifier('NL')
    ->compose();

$aSegments[] = $oNameAndAddress->getComposed();

// Segment Group 2

$oCohaor->addSegmentGroup(2, $aSegments);

// Segment Group 4

$aSegments = [];

// Segment Group 4 : Equipment Details

$oEquipmentDetails = (new \Florowebdevelopment\EdifactGenerator\Segment\EquipmentDetails())
    ->setEquipmentTypeCodeQualifier('AM') // Refrigerated Container
    ->setEquipmentIdentification('123456')
    ->setEquipmentSizeAndType('1234', '', 5, '')
    ->compose()
;

$aSegments[] = $oEquipmentDetails->getComposed();

// Segment Group 4 : Date Time Period

$oDateTimePeriod = (new \Florowebdevelopment\EdifactGenerator\Segment\DateTimePeriod())
    ->setDateOrTimeOrPeriodFunctionCodeQualifier(7) // Effective from date/time
    ->setDateOrTimeOrPeriodText('201812031015')
    ->setDateOrTimeOrPeriodFormatCode(203)// CCYYMMDDHHMM
    ->compose();

$aSegments[] = $oDateTimePeriod->getComposed();

// Segment Group 4 : Place Location Identification

$oPlaceLocationIdentification = (new \Florowebdevelopment\EdifactGenerator\Segment\PlaceLocationIdentification())
    ->setLocationFunctionCodeQualifier('9') // Place of loading
    ->setLocationIdentification('NLRTM') // Rotterdam
    ->compose();

$aSegments[] = $oPlaceLocationIdentification->getComposed();

// Segment Group 4 : Free Text

$oFreeText1 = (new \Florowebdevelopment\EdifactGenerator\Segment\FreeText())
    ->setTextSubjectCodeQualifier('AAA') // Good Description
    ->setFreeTextFunctionCode('')
    ->setTextReference('')
    ->setTextLiteral(['Bananas']) // Commodity
    ->compose();

$aSegments[] = $oFreeText1->getComposed();

// Segment Group 4 : Measurements

$oMeasurements = (new \Florowebdevelopment\EdifactGenerator\Segment\Measurements())
    ->setMeasurementPurposeCodeQualifier('AAE') // Measurement
    ->setMeasurementDetails('AAO') // Humidity
    ->setValueRange('PER', '95.00')
    ->compose()
;

$aSegments[] = $oMeasurements->getComposed();

// Segment Group 4 : Percentage details

$oPercentageDetails = (new \Florowebdevelopment\EdifactGenerator\Segment\PercentageDetails())
    ->setPercentageDetails(146, '6.7') // O2 or CO2 etc.
    ->compose();

$aSegments[] = $oPercentageDetails->getComposed();
        
// Segment Group 4

$oCohaor->addSegmentGroup(4, $aSegments);

// Segment Group 11

$aSegments = [];

// Segment Group 11 : Temperature

$oTemperature = (new \Florowebdevelopment\EdifactGenerator\Segment\Temperature())
    ->setTemperatureTypeCodeQualifier('SET')
    ->setTemperatureSetting('13.00', 'CEL')
    ->compose();

$aSegments[] = $oTemperature->getComposed();

// Segment Group 11 : Range Details

$oRangeDetails = (new \Florowebdevelopment\EdifactGenerator\Segment\RangeDetails())
    ->setRangeTypeCodeQualifier('5') // Temperature range
    ->setMeasurementUnitCode('CEL')
    ->setRangeMinimumQuantity('10.00')
    ->setRangeMaximumQuantity('15.00')
    ->compose();

$aSegments[] = $oRangeDetails->getComposed();

// Segment Group 11 : Control Total

$oControlTotal = (new \Florowebdevelopment\EdifactGenerator\Segment\ControlTotal())
    ->setControlTotalTypeCodeQualifier('16')
    ->setControlTotalQuantity('1')
    ->compose()
;

$aSegments[] = $oControlTotal->getComposed();

// Segment Group 11

$oCohaor->addSegmentGroup(11, $aSegments);

$sDocumentIdentifier = uniqid(); // Your unique identifier

$oCohaor->compose(9, 293, $sDocumentIdentifier);

$aComposed = $oInterchange->addMessage($oCohaor)->getComposed();

echo (new \EDI\Encoder($aComposed, false))->get(); // requires php-edifact/edifact
```

## Output

```
UNB+UNOA:2+ME+YOU+190130:2015+I5C51F7D31CFF8'
UNH+ROW00000000001+COHAOR:D:17B:UN:ITG12'
BGM+293+5c51f7d31e599+9'
NAD++My Party+My Company:My Address:1234 AB+My City+123456+NL'
EQD+AM+123456+1234::5:'
DTM+7:201812031015:203'
LOC+9+NLRTM'
FTX+AAA+++Bananas'
MEA+AAE+AAO+PER:95.00'
PCD+146:6.7'
TMP+SET+13.00:CEL'
RNG+5+CEL:10.00:15.00'
CNT+16:1'
UNT+12+ROW00000000001'
UNZ+1+I5C51F7D31CFF8'
```

