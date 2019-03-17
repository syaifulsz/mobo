<?php

$control = $control ?? new app\models\Column;

?>

<select
name="<?= $control->getName() ?>"
class="form-control form-control--<?= $control->getKey() ?>"
id="form-control--<?= $control->getKey() ?>"
placeholder="<?= $control->getPlaceholder() ?>"
<?= $control->isReadOnly() ? 'readOnly' : '' ?>
<?= $control->isRequired() ? 'required' : '' ?>>
<?php if ( $control->getPlaceholder() ) : ?>
    <option <?= !$control->getValue() ? 'selected' : '' ?>><?= $control->getPlaceholder() ?></option>
<?php endif ?>
<?php foreach ( $control->getOptions() as $option ) : ?>
    <option value="<?= $option[ 'value' ] ?>" <?= $control->getValue() ? 'selected' : '' ?>><?= $option[ 'label' ] ?></option>
<?php endforeach ?>
</select>
