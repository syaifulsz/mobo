<?php

use app\components\View;

$control = $control ?? new app\models\Column;

?>

<div class="form-group">
    <?php if ( $control->getLabel() ) : ?>
        <label for="form-control--<?= $control->getKey() ?>"><?= $control->getLabel() ?></label>
    <?php endif ?>
    <?= View::staticRender( 'forms/input', [ 'control' => $control ] ) ?>
</div>
