<div class="form-group">
    <?php if ( $control->getLabel() ) : ?>
        <label for="form-control--<?= $control->getKey() ?>"><?= $control->getLabel() ?></label>
    <?php endif ?>
    <select
    name="<?= $control->getName() ?>"
    class="form-control form-control--<?= $control->getKey() ?>"
    id="form-control--<?= $control->getKey() ?>"
    placeholder="<?= $control->getPlaceholder() ?>"
    <?= $control->isReadOnly() ? 'readOnly' : '' ?>
    <?= $control->isRequired() ? 'required aria-required="true"' : '' ?>>
    <?php if ( $control->getPlaceholder() ) : ?>
        <option value="" <?= !$control->getValue() ? 'selected' : '' ?>><?= $control->getPlaceholder() ?></option>
    <?php endif ?>
    <?php foreach ( $control->getOptions() as $option ) : ?>
        <option value="<?= $option[ 'value' ] ?>" <?= $control->getValue() ? 'selected' : '' ?>><?= $option[ 'label' ] ?></option>
    <?php endforeach ?>
    </select>
</div>
