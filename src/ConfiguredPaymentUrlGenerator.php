<?php

namespace Likemusic\Wordpress\Bambora\Checkout;

use Likemusic\BamboraCheckout\PaymentUrlGenerator;

class ConfiguredPaymentUrlGenerator extends PaymentUrlGenerator
{
    public function __construct(
        // Order info
               $trnAmount = null, $trnOrderNumber = null, $trnType = null, $trnCardOwner = null, $trnLanguage = null,

        // Billing address
               $ordName = null, $ordEmailAddress = null, $ordAddress1 = null, $ordAddress2 = null, $ordCity = null,
               $ordProvince = null, $ordPostalCode = null, $ordCountry = null,

        // Shipping address
               $shipName = null, $shipEmailAddress = null, $shipAddress1 = null, $shipAddress2 = null, $shipCity = null,
               $shipProvince = null, $shipPostalCode = null, $shipCountry = null, $shipPhoneAddress = null,

        // Redirects
               $approvedPage = null, $declinedPage = null,

        // Hash expiry
               $hashExpiry = null,

        // References info
               $ref1 = null, $ref2 = null, $ref3 = null, $ref4 = null, $ref5 = null,

               $baseUrl = 'https://web.na.bambora.com/scripts/payment/payment.asp'
    )
    {
        $config = get_option('bambora');

        $hashKey = $config['hash_key']; //INSERT API ACCESS PASSCODE
        $merchantId = $config['merchant_id']; //INSERT MERCHANT ID (must be a 9 digit string)

        parent::__construct(
            $hashKey,

            // Authorization
            $merchantId,

            // Order info
            $trnAmount, $trnOrderNumber, $trnType, $trnCardOwner, $trnLanguage,

            // Billing address
            $ordName, $ordEmailAddress, $ordAddress1, $ordAddress2, $ordCity, $ordProvince, $ordPostalCode, $ordCountry,

            // Shipping address
            $shipName, $shipEmailAddress, $shipAddress1, $shipAddress2, $shipCity, $shipProvince, $shipPostalCode, $shipCountry, $shipPhoneAddress,

            // Redirects
            $approvedPage, $declinedPage,

            // Hash expiry
            $hashExpiry,

            // References info
            $ref1, $ref2, $ref3, $ref4, $ref5,

            $baseUrl
        );
    }

}
