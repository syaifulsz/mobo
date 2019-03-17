<div class="form-group form-group--<?= $control->getKey() ?> form-check form-check--<?= $control->getKey() ?>">
    <input
        name="<?= $control->getName() ?>"
        type="checkbox"
        class="form-check-input form-check-input--<?= $control->getKey() ?>"
        id="form-check-input--<?= $control->getKey() ?>"
        <?= $control->isChecked() ? 'checked' : '' ?>
        value="<?= $control->getValue() ?>"
        <?= $control->isRequired() ? 'required' : '' ?>
        <?= $control->isReadOnly() ? 'readOnly' : '' ?>
        >
    <label class="form-check-label" for="form-check-input--<?= $control->getKey() ?>"><?= $control->getLabel() ?></label>
</div>
