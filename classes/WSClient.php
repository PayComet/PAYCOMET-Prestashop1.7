<?php
/**
 * 2007-2019 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author     PAYCOMET <info@paycomet.com>
 *  @copyright  2019 PAYTPV ON LINE ENTIDAD DE PAGO S.L
 *  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

if (!class_exists('nusoap_client')) {
    include_once(_PS_MODULE_DIR_ . '/' . 'paytpv/lib/nusoap.php');
}

class WSClient
{
    public $client = null;
    public $config = null;

    private function writeLog($log)
    {

        PrestaShopLogger::addLog($log, 1);
    }
    public function __construct(
        array $config = array(),
        $proxyhost = '',
        $proxyport = '',
        $proxyusername = '',
        $proxypassword = ''
    ) {

        $useFsaveOrderAfterSubmit = Tools::getIsset(Tools::getValue('usecurl')) ? Tools::getValue('usecurl') : '0';

        $this->config = $config;

        $this->client = new nusoap_client(
            $this->config['endpoint_paytpv'],
            false,
            $proxyhost,
            $proxyport,
            $proxyusername,
            $proxypassword
        );

        $err = $this->client->getError();

        if ($err) {
            $this->writeLog($err);
            $this->writeLog('Debug: ' . $this->client->getDebug());
            exit();
        }

        $useCURL = $useFsaveOrderAfterSubmit;
        $this->client->setUseCurl($useCURL);
    }

    public function executePurchase(
        $DS_IDUSER,
        $DS_TOKEN_USER,
        $TERMINAL,
        $DS_MERCHANT_CURRENCY,
        $amount,
        $ref,
        $MERCHANT_SCORING,
        $MERCHANT_DATA
    ) {

        $DS_MERCHANT_MERCHANTCODE = $this->config['clientcode'];

        $DS_MERCHANT_TERMINAL = $TERMINAL;

        $DS_MERCHANT_AMOUNT = $amount;

        $DS_MERCHANT_ORDER = $ref;

        $DS_MERCHANT_MERCHANTSIGNATURE = hash('sha512', $DS_MERCHANT_MERCHANTCODE . $DS_IDUSER . $DS_TOKEN_USER .
                                            $DS_MERCHANT_TERMINAL . $DS_MERCHANT_AMOUNT . $DS_MERCHANT_ORDER .
                                            $this->config['pass']);


        $DS_ORIGINAL_IP = Tools::getRemoteAddr();
        if ($DS_ORIGINAL_IP == "::1") {
            $DS_ORIGINAL_IP = "127.0.0.1";
        }

        $p = array(

            'DS_MERCHANT_MERCHANTCODE' => $DS_MERCHANT_MERCHANTCODE,

            'DS_MERCHANT_TERMINAL' => $DS_MERCHANT_TERMINAL,

            'DS_IDUSER' => $DS_IDUSER,

            'DS_TOKEN_USER' => $DS_TOKEN_USER,

            'DS_MERCHANT_AMOUNT' => (string) $DS_MERCHANT_AMOUNT,

            'DS_MERCHANT_ORDER' => (string) $DS_MERCHANT_ORDER,

            'DS_MERCHANT_CURRENCY' => $DS_MERCHANT_CURRENCY,

            'DS_MERCHANT_MERCHANTSIGNATURE' => $DS_MERCHANT_MERCHANTSIGNATURE,

            'DS_ORIGINAL_IP' => $DS_ORIGINAL_IP,

            'DS_MERCHANT_PRODUCTDESCRIPTION' => '',

            'DS_MERCHANT_OWNER' => ''

        );

        if ($MERCHANT_SCORING != null) {
            $p["MERCHANT_SCORING"] = $MERCHANT_SCORING;
        }
        if ($MERCHANT_DATA != null) {
            $p["MERCHANT_DATA"] = $MERCHANT_DATA;
        }

        $this->writeLog("Petición execute_purchase:\n" . print_r($p, true));

        $res = $this->client->call('execute_purchase', $p, '', '', false, true);

        $this->writeLog("Respuesta execute_purchase:\n" . print_r($res, true));

        return $res;
    }

    public function infoUser($idUser, $tokeUser)
    {

        $DS_MERCHANT_MERCHANTCODE = $this->config['clientcode'];

        $DS_MERCHANT_TERMINAL = $this->config['term'];

        $DS_IDUSER = $idUser;

        $DS_TOKEN_USER = $tokeUser;

        $DS_MERCHANT_MERCHANTSIGNATURE = hash('sha512', $DS_MERCHANT_MERCHANTCODE . $DS_IDUSER . $DS_TOKEN_USER .
                                        $DS_MERCHANT_TERMINAL . $this->config['pass']);

        $DS_ORIGINAL_IP = Tools::getRemoteAddr();
        if ($DS_ORIGINAL_IP == "::1") {
            $DS_ORIGINAL_IP = "127.0.0.1";
        }

        $p = array(

            'DS_MERCHANT_MERCHANTCODE' => $DS_MERCHANT_MERCHANTCODE,

            'DS_MERCHANT_TERMINAL' => $DS_MERCHANT_TERMINAL,

            'DS_IDUSER' => $DS_IDUSER,

            'DS_TOKEN_USER' => $DS_TOKEN_USER,

            'DS_MERCHANT_MERCHANTSIGNATURE' => $DS_MERCHANT_MERCHANTSIGNATURE,

            'DS_ORIGINAL_IP' => $DS_ORIGINAL_IP

        );

        $this->writeLog("Petición info_user:\n" . print_r($p, true));

        $res = $this->client->call('info_user', $p, '', '', false, true);

        $this->writeLog("Respuesta info_user:\n" . print_r($res, true));

        return $res;
    }

    public function removeUser($idUser, $tokeUser)
    {

        $DS_MERCHANT_MERCHANTCODE = $this->config['clientcode'];

        $DS_MERCHANT_TERMINAL = $this->config['term'];

        $DS_IDUSER = $idUser;

        $DS_TOKEN_USER = $tokeUser;

        $DS_MERCHANT_MERCHANTSIGNATURE = hash('sha512', $DS_MERCHANT_MERCHANTCODE . $DS_IDUSER . $DS_TOKEN_USER .
                                         $DS_MERCHANT_TERMINAL . $this->config['pass']);

        $DS_ORIGINAL_IP = Tools::getRemoteAddr();
        if ($DS_ORIGINAL_IP == "::1") {
            $DS_ORIGINAL_IP = "127.0.0.1";
        }

        $p = array(

            'DS_MERCHANT_MERCHANTCODE' => $DS_MERCHANT_MERCHANTCODE,

            'DS_MERCHANT_TERMINAL' => $DS_MERCHANT_TERMINAL,

            'DS_IDUSER' => $DS_IDUSER,

            'DS_TOKEN_USER' => $DS_TOKEN_USER,

            'DS_MERCHANT_MERCHANTSIGNATURE' => $DS_MERCHANT_MERCHANTSIGNATURE,

            'DS_ORIGINAL_IP' => $DS_ORIGINAL_IP

        );
        $this->writeLog("Petición removeUser:\n" . print_r($p, true));

        $res = $this->client->call('removeUser', $p, '', '', false, true);

        $this->writeLog("Respuesta removeUser:\n" . print_r($res, true));

        return $res;
    }

    public function createSubscriptionToken(
        $idUser,
        $tokeUser,
        $amount,
        $stardate,
        $enddate,
        $peridicity,
        $MERCHANT_SCORING,
        $MERCHANT_DATA,
        $DS_SUSCRIPTION_CURRENCY = 'EUR',
        $ref = ''
    ) {
        $DS_MERCHANT_MERCHANTCODE = $this->config[ 'clientcode' ];

        $DS_MERCHANT_TERMINAL = $this->config[ 'term' ];

        $DS_IDUSER = $idUser;

        $DS_TOKEN_USER = $tokeUser;

        $DS_SUBSCRIPTION_STARTDATE = $stardate;
        $DS_SUBSCRIPTION_ENDDATE = $enddate;

        if ($ref=='') {
            $DS_SUBSCRIPTION_ORDER = time();
        } else {
            $DS_SUBSCRIPTION_ORDER = str_pad($ref, 8, "0", STR_PAD_LEFT) . round(rand(0, 99));
        }

        $DS_SUBSCRIPTION_PERIODICITY = $peridicity;

        $DS_SUSCRIPTION_AMOUNT = $amount;
        
        $DS_MERCHANT_MERCHANTSIGNATURE = hash(
            'sha512',
            $DS_MERCHANT_MERCHANTCODE . $DS_IDUSER . $DS_TOKEN_USER . $DS_MERCHANT_TERMINAL . $DS_SUSCRIPTION_AMOUNT
            .$DS_SUSCRIPTION_CURRENCY . $this->config[ 'pass' ]
        );

        $DS_ORIGINAL_IP = Tools::getRemoteAddr();
        if ($DS_ORIGINAL_IP=="::1") {
            $DS_ORIGINAL_IP = "127.0.0.1";
        }

        $p = array(

            'DS_MERCHANT_MERCHANTCODE' => $DS_MERCHANT_MERCHANTCODE,

            'DS_MERCHANT_TERMINAL' => $DS_MERCHANT_TERMINAL,

            'DS_IDUSER' => $DS_IDUSER,

            'DS_TOKEN_USER' => $DS_TOKEN_USER,

            'DS_SUBSCRIPTION_STARTDATE' => $DS_SUBSCRIPTION_STARTDATE,
            'DS_SUBSCRIPTION_ENDDATE' => $DS_SUBSCRIPTION_ENDDATE,

            'DS_SUBSCRIPTION_ORDER' => ( string ) $DS_SUBSCRIPTION_ORDER,
            'DS_SUBSCRIPTION_PERIODICITY' => $DS_SUBSCRIPTION_PERIODICITY,

            'DS_SUBSCRIPTION_AMOUNT' => ( string ) $DS_SUSCRIPTION_AMOUNT,

            'DS_SUBSCRIPTION_CURRENCY' => $DS_SUSCRIPTION_CURRENCY,
            

            'DS_MERCHANT_MERCHANTSIGNATURE' => $DS_MERCHANT_MERCHANTSIGNATURE,

            'DS_ORIGINAL_IP' => $DS_ORIGINAL_IP

        );


        if ($MERCHANT_SCORING!=null) {
            $p["MERCHANT_SCORING"] = $MERCHANT_SCORING;
        }
        if ($MERCHANT_DATA!=null) {
            $p["MERCHANT_DATA"] = $MERCHANT_DATA;
        }

        
        $this->writeLog("Petición create_subscription_token:\n".print_r($p, true));

        $res = $this->client->call('create_subscription_token', $p, '', '', false, true);

        $this->writeLog("Respuesta create_subscription_token:\n".print_r($res, true));

        return $res;
    }



    public function removeSubscription($idUser, $tokeUser)
    {

        $DS_MERCHANT_MERCHANTCODE = $this->config['clientcode'];

        $DS_MERCHANT_TERMINAL = $this->config['term'];

        $DS_IDUSER = $idUser;

        $DS_TOKEN_USER = $tokeUser;

        $DS_MERCHANT_MERCHANTSIGNATURE = hash('sha512', $DS_MERCHANT_MERCHANTCODE . $DS_IDUSER . $DS_TOKEN_USER .
                                         $DS_MERCHANT_TERMINAL . $this->config['pass']);

        $DS_ORIGINAL_IP = Tools::getRemoteAddr();
        if ($DS_ORIGINAL_IP == "::1") {
            $DS_ORIGINAL_IP = "127.0.0.1";
        }

        $p = array(

            'DS_MERCHANT_MERCHANTCODE' => $DS_MERCHANT_MERCHANTCODE,

            'DS_MERCHANT_TERMINAL' => $DS_MERCHANT_TERMINAL,

            'DS_IDUSER' => $DS_IDUSER,

            'DS_TOKEN_USER' => $DS_TOKEN_USER,

            'DS_MERCHANT_MERCHANTSIGNATURE' => $DS_MERCHANT_MERCHANTSIGNATURE,

            'DS_ORIGINAL_IP' => $DS_ORIGINAL_IP

        );
        $this->writeLog("Petición remove_subscription:\n" . print_r($p, true));

        $res = $this->client->call('remove_subscription', $p, '', '', false, true);

        $this->writeLog("Respuesta remove_subscription:\n" . print_r($res, true));

        return $res;
    }


    public function executeRefund($idUser, $tokeUser, $order, $currency, $authcode, $amount)
    {

        $DS_MERCHANT_MERCHANTCODE = $this->config['clientcode'];
        $DS_MERCHANT_TERMINAL = $this->config['term'];
        $DS_IDUSER = $idUser;
        $DS_TOKEN_USER = $tokeUser;
        $DS_MERCHANT_ORDER = $order;
        $DS_MERCHANT_AUTHCODE = $authcode;
        $DS_MERCHANT_CURRENCY = $currency;
        $DS_MERCHANT_AMOUNT = $amount;
        $DS_MERCHANT_MERCHANTSIGNATURE = hash('sha512', $DS_MERCHANT_MERCHANTCODE . $DS_IDUSER . $DS_TOKEN_USER .
                                        $DS_MERCHANT_TERMINAL . $DS_MERCHANT_AUTHCODE . $DS_MERCHANT_ORDER .
                                        $this->config['pass']);

        $DS_ORIGINAL_IP = Tools::getRemoteAddr();
        if ($DS_ORIGINAL_IP == "::1") {
            $DS_ORIGINAL_IP = "127.0.0.1";
        }

        $p = array(

            'DS_MERCHANT_MERCHANTCODE' => $DS_MERCHANT_MERCHANTCODE,
            'DS_MERCHANT_TERMINAL' => $DS_MERCHANT_TERMINAL,
            'DS_IDUSER' => $DS_IDUSER,
            'DS_TOKEN_USER' => $DS_TOKEN_USER,
            'DS_MERCHANT_AUTHCODE' => $DS_MERCHANT_AUTHCODE,
            'DS_MERCHANT_ORDER' => $DS_MERCHANT_ORDER,
            'DS_MERCHANT_CURRENCY' => $DS_MERCHANT_CURRENCY,
            'DS_MERCHANT_MERCHANTSIGNATURE' => $DS_MERCHANT_MERCHANTSIGNATURE,
            'DS_ORIGINAL_IP' => $DS_ORIGINAL_IP,
            'DS_MERCHANT_AMOUNT' => $DS_MERCHANT_AMOUNT

        );


        $this->writeLog("Petición execute_refund:\n" . print_r($p, true));

        $res = $this->client->call('execute_refund', $p, '', '', false, true);

        $this->writeLog("Respuesta execute_refund:\n" . print_r($res, true));

        return $res;
    }

    public function addUserToken($token)
    {


        $DS_MERCHANT_MERCHANTCODE = $this->config['clientcode'];
        $DS_MERCHANT_TERMINAL = $this->config['term'];
        $DS_JETID = $this->config['jetid'];
        $DS_MERCHANT_MERCHANTSIGNATURE = hash('sha512', $DS_MERCHANT_MERCHANTCODE . $token . $DS_JETID .
                                        $DS_MERCHANT_TERMINAL . $this->config['pass']);

        $DS_ORIGINAL_IP = Tools::getRemoteAddr();
        if ($DS_ORIGINAL_IP == "::1") {
            $DS_ORIGINAL_IP = "127.0.0.1";
        }


        $p = array(

            'DS_MERCHANT_MERCHANTCODE' => $DS_MERCHANT_MERCHANTCODE,
            'DS_MERCHANT_TERMINAL' => $DS_MERCHANT_TERMINAL,
            'DS_MERCHANT_JETTOKEN' => $token,
            'DS_MERCHANT_JETID' => $DS_JETID,
            'DS_MERCHANT_MERCHANTSIGNATURE' => $DS_MERCHANT_MERCHANTSIGNATURE,
            'DS_ORIGINAL_IP' => $DS_ORIGINAL_IP

        );


        $this->writeLog("Petición add_user_token:\n" . print_r($p, true));

        $res = $this->client->call('add_user_token', $p, '', '', false, true);

        $this->writeLog("Respuesta add_user_token:\n" . print_r($res, true));

        return $res;
    }
}
