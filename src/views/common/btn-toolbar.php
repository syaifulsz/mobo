<?php if ( $group ) : ?>
    <div class="btn-toolbar mb-3">
        <?php foreach ( $group as $buttons ) : ?>
            <div class="btn-group mr-2">
                <?php foreach ( $buttons as $button ) : ?>
                    <a href="<?= $button[ 'url' ] ?>" class="btn btn-outline-primary"><?= $button[ 'label' ] ?></a>
                <?php endforeach ?>
            </div>
        <?php endforeach ?>
    </div>
<?php endif ?>
