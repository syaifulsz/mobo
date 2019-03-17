<div class="form-group">
    <label for="form-control--<?= $control->getKey() ?>"><?= $control->getLabel() ?></label>
    <textarea
    name="<?= $control->getName() ?>"
    <?= $control->getValidationRegex() ? 'pattern="' . $control->getValidationRegex() . '"' : '' ?>
    class="form-control form-control--<?= $control->getKey() ?>"
    id="form-control--<?= $control->getKey() ?>"
    maxlength="<?= $control->getMaxLength() ?>"
    rows="<?= $control->getRows() ?>"
    cols="<?= $control->getCols() ?>"
    <?= $control->isReadOnly() ? 'readOnly' : '' ?>
    <?= $control->isRequired() ? 'required' : '' ?>><?= $control->getValue() ?></textarea>
</div>
