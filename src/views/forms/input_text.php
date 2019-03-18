<?php

use ssz\mobo\components\View;

$control = $control ?? new ssz\mobo\models\Column;

?>

<div class="form-group">
    <?php if ( $control->getLabel() ) : ?>
        <label for="form-control--<?= $control->getKey() ?>"><?= $control->getLabel() ?></label>
    <?php endif ?>
    <?= View::staticRender( 'forms/input', [ 'control' => $control ] ) ?>
</div>
