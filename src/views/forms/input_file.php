<div class="form-group">
    <label for="form-control--<?= $control->getKey() ?>"><?= $control->getLabel() ?></label>
    <input
    name="<?= $control->getName() ?>"
    type="<?= $control->getType() ?>"
    <?= $control->getValidationRegex() ? 'pattern="' . $control->getValidationRegex() . '"' : '' ?>
    class="form-control form-control--<?= $control->getKey() ?>"
    id="form-control--<?= $control->getKey() ?>"
    value="<?= $control->getValue() ?>"
    <?= $control->isReadOnly() ? 'readOnly' : '' ?>
    <?= $control->isRequired() ? 'required' : '' ?>>
</div>
