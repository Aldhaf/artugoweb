<pre>

<?php

// Ripcord can be cloned from https://github.com/poef/ripcord
require_once('ripcord/ripcord.php');

// Parameter
$url = 'http://sys.artugo.co.id';
$url_auth = $url . '/xmlrpc/2/common';
$url_exec = $url . '/xmlrpc/2/object';
$db = 'artugo_live_new';
$username = 'admin';
$password = 'joko@123';

// Login
$common = ripcord::client($url_auth);
$uid = $common->authenticate($db, $username, $password, array());
print("<p>Your current user id is '${uid}'</p>");

//***************************************** */

echo "<br/>";
echo "<h2>create Account Invoice</h2>";

$models = ripcord::client($url_exec);

$partner_ids = $models->execute_kw($db, $uid, $password,
    'res.partner', 'search_read',
    array(
        array(
            array('name','ilike','Cash Back')
        )
        ));

foreach ($partner_ids as $key => $value) {
    $partner_id = $value['id'];
}
        
$product_id = $models->execute_kw($db, $uid, $password,
'product.product', 'search',
array(
    array(
        array('default_code','ilike','CB-01')
    )
    ));

$account_ids = $models->execute_kw($db, $uid, $password,
'account.account', 'search_read',
array(
    array(
        array('name','ilike','Biaya Promosi')
    )
    ));

foreach ($account_ids as $key => $value) {
    $account_id = $value['id'];
}

$reference = "Reg No Warranty: 1234567" ;
$bank_account_number = "Rek No 123456789 a/n. Mr. X";
$bank_partner_name = "BCA";
$price_unit = 150000;

$id = $models->execute_kw($db, $uid, $password,
    'account.invoice', 'create',
    array(
        array(
            'company_id'            => 1,
            'partner_id'            => $partner_id,
            'type'                  => "in_invoice",
            'reference'             => $reference,
            'bank_account_number'   => $bank_account_number,
            'bank_partner_name'     => $bank_partner_name,
            'invoice_line_ids'      => array(
                array(0,0,array(
                    'product_id'    => $product_id[0],
                    'account_id'    => $account_id,
                    'name'          => "Promo Cashback",
                    'quantity'      => 1,
                    'price_unit'    => 150000,
                                    ))
            )
        )
    )
);

var_dump($id); 

?>


<!-- 'account_id'    => 1101, -->
