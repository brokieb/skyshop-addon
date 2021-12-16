<pre>
<?php
$array = GetBaselinker("getOrders", array(
    'status_id' => '211793'
));
// print_r($array);
foreach ($array as $z) {
    // print_r($z);
    foreach ($z as $x) {
        // print_r($x);
        if ($x['delivery_package_module'] == 'allegrokurier') {
            echo  "<a target='_blank' href='https://panel.baselinker.com/orders.php#order:" . $x['order_id'] . "'>" . $x['order_id'] . "</a> - - ><a target='_blank' href='https://tracktrace.dpd.com.pl/parcelDetails?typ=1&p1=" . $x['delivery_package_nr'] . "'>" . $x['delivery_package_nr'] . "</a>";
            echo "</br>";
        }
    }
};
?>
</pre>
<form method='POST' action="index.php?site=<?=$_GET['site']?>">
    Pobieranie faktur dla danych przychodów z allegro
<div class='d-flex flex-row'>
    <div class="mb-3 p-2">
        <label for="od" class="form-label">Od</label>
        <input type="date" class="form-control" name='od' id="od">
    </div>
    <div class="mb-3 p-2">
        <label for="do" class="form-label">Do</label>
        <input type="date" class="form-control" name='do' id="do">
    </div>
    </div>
    <button type='submit' class='btn btn-primary'>WCZYTAJ</button>
</form>
<?php
$statusy = GetBaselinker("getOrderStatusList",array());
$ans = array();
foreach($statusy['statuses'] as $z){
    $ans[$z['id']] = $z['name'];
}
if(isset($_POST['od'])){
    echo "Przeglądasz wpływy z zakresu dat od ".$_POST['od']." do ".$_POST['do'];


?>
<table class='table table-striped dataTable'>
    <thead>
        <tr>
            <th>Konto</th>
            <th>Kiedy?</th>
            <th>Kto?</th>
            <th>Kwota</th>
            <th>Faktura</th>
            <th>ID zam</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT * FROM `allegro_account` WHERE `user_id`=" . $_SESSION['user_id'] . " ";
        $query = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
            if(isset($_POST['do'])){
                $det = restGet('/payments/payment-operations?occurredAt.gte='.Date("Y-m-d\TH:i:s.000\Z", strtotime($_POST['od'])).'&occurredAt.lte='.Date("Y-m-d\TH:i:s.000\Z", strtotime($_POST['do'])).'&group=INCOME', $row['allegro_token']);
            }else{
                $det = restGet('/payments/payment-operations?occurredAt.gte='.Date("Y-m-d\TH:i:s.000\Z", strtotime($_POST['od'])).'&group=INCOME', $row['allegro_token']);
            }
            
            foreach ($det->paymentOperations as $x) {
        ?>
                <tr>
                    <td><?php
                        $me = restGet('/me', $row['allegro_token']);
                        echo $me->login;
                        ?></td>
                    <td><?= Date("Y-m-d H:i:s", strtotime($x->occurredAt)) ?></td>
                    <td><?= $x->participant->firstName ?> <?= $x->participant->lastName ?></td>
                    <td><?= $x->value->amount ?> <?= $x->value->currency ?></td>
                    <td>
                        <?php
                        $mail = restGet('/order/checkout-forms?payment.id='.$x->payment->id,$row['allegro_token']);
                        $array = GetBaselinker("getOrders", array(
                            'filter_email' => $mail->checkoutForms[0]->buyer->email
                        ));
                        if(isset($array['orders'][0]['order_status_id'])){

                       
                        if($array['orders'][0]['order_status_id']=='200422'){
                            echo $array['orders'][0]['extra_field_2'];
                        }else{
                            echo "STATUS : <span class='badge bg-secondary'>" . $ans[$array['orders'][0]['order_status_id']]."</span>";
                        }

                    }else{
                        echo "<span class='badge bg-danger'>BŁĄD!!!!</span>";
                    }
                        ?>
</td>
<td>
    <?php
echo "<a href='https://panel.baselinker.com/orders.php#order:".$array['orders'][0]['order_id']."' target='_blank'>".$array['orders'][0]['order_id']."</a>";
    ?>
</td>
                </tr>
        <?php
            }
        }
        // print_r($array);
        ?>

    </tbody>
</table>
<?php
}
?>