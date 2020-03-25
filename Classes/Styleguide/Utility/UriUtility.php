<?php 

namespace Thopra\Styleguide\Utility;

use Endroid\QrCode\QrCode;

Class UriUtility {

	public static function section( $ref, $sourceKey )
	{
		return $_SERVER['SCRIPT_NAME'].'?ref='.$ref.'&src='.$sourceKey;
	}
	
	public static function preview( $ref, $sourceKey )
	{
		return $_SERVER['SCRIPT_NAME'].'?preview&ref='.$ref.'&src='.$sourceKey;
	}

	public static function previewQr( $ref, $sourceKey )
	{
		return self::getQr( (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] ? "https://" : "http://") . $_SERVER['HTTP_HOST'].self::preview($ref, $sourceKey) );
	}


    public static function getQr( $url )
    {
        $qrCode = new QrCode();
        $qrCode->setText($url);
        $qrCode->setSize(150);
        $qrCode->setMargin(10);
        $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH());
        $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
        $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);

        return $qrCode->writeDataUri();
    }

}
