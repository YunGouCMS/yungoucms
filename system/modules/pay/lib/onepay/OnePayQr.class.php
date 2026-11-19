<?php

class OnePayQr
{

    // Bank provide these data 
    /*
    MCID: mch65c1cab49c500
    MC_ENG: Onepay Test Merchant 04
    MCC: 7299
    SHOPCODE: OVISUZKnKHK8
    */
    protected $mcid = "mch65c1cab49c500";
    protected $mcc = "7299";
    protected $ccy = 418;
    protected $country = "LA";
    protected $province = "VTE";

    //$amount = 540000
public function getQr($amount = 0, $invoiceid = "", $transactionid = "", $terminalid = null, $description = 'Orange Juice')
    {
        $rawqr = $this->buildqr([
            00 => "01",
            01 => "11",
            33 => $this->buildqr([
                00 => "BCEL",
                01 => "ONEPAY",
                02 => $this->mcid
            ]),
            52 => $this->mcc,
            53 => $this->ccy,
            54 => $amount,
            58 => $this->country,
            60 => $this->province,
            62 => $this->buildqr([
                01 => $invoiceid,
                05 => $transactionid,
                07 => $terminalid,
                08 => $description
            ])
        ]);

        $fullqr = $rawqr . $this->buildqr([63 => $this->crc16($rawqr . "6304")]); // Adding itself into checksum 
        return $fullqr;
    }

    private function buildqr($arr)
    {
        $res = "";
        foreach ($arr as $key => $val) {
            if (!$val) continue;
            $res .= str_pad($key, 2, "0", STR_PAD_LEFT) .
                str_pad(strlen($val), 2, "0", STR_PAD_LEFT) .
                $val;
        }
        return $res;
    }

    private function crc16($sStr, $aParams = array())
    {
        $aDefaults = array(
            "polynome" => 0x1021,
            "init" => 0xFFFF,
            "xor_out" => 0,
        );
        foreach ($aDefaults as $key => $val) {
            if (!isset($aParams[$key])) {
                $aParams[$key] = $val;
            }
        }
        $sStr .= "";
        $crc = $aParams['init'];
        $len = strlen($sStr);
        $i = 0;
        while ($len--) {
            $crc ^= ord($sStr[$i++]) << 8;
            $crc &= 0xffff;
            for ($j = 0; $j < 8; $j++) {
                $crc = ($crc & 0x8000) ? ($crc << 1) ^ $aParams['polynome'] :
                    $crc << 1;
                $crc &= 0xffff;
            }
        }
        $crc ^= $aParams['xor_out'];
        return str_pad(strtoupper(dechex($crc)), 4, "0", STR_PAD_LEFT);
    }
}
