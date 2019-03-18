<?php

$control = $control ?? new ssz\mobo\models\Column;

?>

<input
name="<?= $control->getName() ?>"
type="<?= $control->getType() ?>"
<?= $control->getValidationRegex() ? 'pattern="' . $control->getValidationRegex() . '"' : '' ?>
class="form-control form-control--<?= $control->getKey() ?>"
id="form-control--<?= $control->getKey() ?>"
placeholder="<?= $control->getPlaceholder() ?>"
value="<?= $control->getValue() ?>"
<?= ( $control->isReadOnly() ? 'readOnly' : '' ) . PHP_EOL ?>
<?= ( $control->isRequired() ? 'required' : '' ) . PHP_EOL ?>
<?= ( $control->isAutofocus() ? 'autofocus' : '' ) . PHP_EOL?>
>
