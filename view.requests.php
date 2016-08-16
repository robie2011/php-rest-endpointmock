<?php
require_once 'include.php';
$serviceRequests = get_objects_from_file(PATH_SERVICEREQUESTS_DATA);

if (isset($_GET['id'])) {
    $request = find_by_id($serviceRequests, $_GET['id']);
    $bicycles = get_objects_from_file(PATH_BICYCLE_DATA);
    $bike = find_by_id($bicycles, $request->bicycleProfileId);
}

function getRequestStatName($id){
    switch ($id){
        case 1:
            return "Open";
        case 2:
            return "Appointed";
        case 3:
            return "Finished";
        case 4:
            return "Complained";
        case 5:
            return "Payment Failed";
    }
}


require_once 'header.php';
?>
<div class="col-md-4 col-lg-5">
    <h1>Service Requests</h1>
    <table class="table">
        <?php foreach ($serviceRequests as $r): ?>
            <tr>
                <td><a href="view.requests.php?id=<?= $r->id ?>">#<?= $r->id ?></a></td>
                <td>
                    <?= $r->translatedName ?>
                    (<?= time2str($r->insertDate) ?>)
                    <span class="label label-primary badge"><?= getRequestStatName($r->serviceRequestStateId) ?></span>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php if(isset($request)) : ?>
<div class="col-md-8 col-lg-7">
    <h1><?= $request->translatedName ?> <span class="label label-primary badge"><?= getRequestStatName($request->serviceRequestStateId) ?></span></h1>
    <table class="table table-striped">
        <tr>
            <td>Insert Date</td>
            <td><?= $request->insertDate ?></td>
        </tr>
        <tr>
            <td>Desired Date</td>
            <td><?= $request->appointmentDateTime ?  $request->appointmentDateTime : "ASAP" ?></td>
        </tr>
        <tr>
            <td>Appointment Address</td>
            <td>
                <?= $request->appointmentAddress->name ?>
                <br/>
                <?= $request->appointmentAddress->street ?>
                <br/>
                <?= $request->appointmentAddress->zipCode ?> <?= $request->appointmentAddress->city ?>
            </td>
        </tr>
        <?php if($request->deliveryAddressOrNull): ?>
        <tr>
            <td>Delivery Address</td>
            <td>
                <?= $request->deliveryAddressOrNull->name ?>
                <br/>
                <?= $request->deliveryAddressOrNull->street ?>
                <br/>
                <?= $request->deliveryAddressOrNull->zipCode ?> <?= $request->deliveryAddressOrNull->city ?>
            </td>
        </tr>
        <?php endif; ?>
        <tr>
            <td>Bicycle</td>
            <td>
                <strong><?= $bike->name ?></strong>
                <p><?= $bike->model ?></p>
                <p><?= $bike->manufacter ?></p>
                <?php foreach ($bike->media as $m): ?>
                    <p>
                        <img style="max-height: 200px; max-width: 80%;" src="/downloadMedia/<?= $m->fileName ?>" alt="">
                    </p>
                <?php endforeach; ?>
            </td>
        </tr>
        <tr>
            <td>Notes</td>
            <td>
                <p>Text: <?= $request->userNote ?></p>
                <?php foreach ($request->media as $m): ?>
                    <p>
                        <?php if(strpos($m["content-type"], "image") > -1): ?>
                            <img style="max-height: 200px; max-width: 80%;" src="/downloadMedia/<?= $m->fileName ?>" alt="">
                        <?php else: ?>
                            <audio controls>
                                <source src="/downloadMedia/<?= $m->fileName ?>" type="<?= $m["content-type"] ?>" />
                            </audio>
                        <?php endif; ?>
                    </p>

                <?php endforeach; ?>
            </td>
        </tr>
    </table>

    <h2>Comments</h2>
    <form action="/servicerequest/<?= $request->id ?>/comment" method="POST">
        <textarea name="comment" class="form-control" rows="3"></textarea>
        <br>
        <input class="form-control" name="userName" value="Dummy Service Provider AG" type="text">
        <br>
        <input class="btn btn-primary" type="submit">
    </form>

    <br>
    <br>
    <?php foreach ($request->comments as $c): ?>
        <p><strong><?= $c->userName?> (<?= time2str($c->insertDate) ?>):</strong></p>
        <p><?= $c->comment ?></p>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php  require_once 'footer.php'; ?>