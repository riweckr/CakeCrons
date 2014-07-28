<div class="cakeCrons form">
    <?php echo $this->Form->create('CakeCron'); ?>
    <fieldset>
        <legend><?php __('Add Cake Cron'); ?></legend>
        <?php
        echo $this->Form->input('task');
        echo $this->Form->input('interval');
        echo $this->Html->div('input datetime', $this->Html->tag('label', __('Next Start', true)) . $this->Form->datetime('next_start', 'YMD', '24', time(), array('empty' => false, 'monthNames' => false, 'minYear' => date('Y'))));
        echo $this->Form->input('priority');
        ?>
    </fieldset>
    <?php echo $this->Form->end(__('Submit', true)); ?>
</div>
<div class="actions">
    <h3><?php __('Actions'); ?></h3>
    <ul>

        <li><?php echo $this->Html->link(__('List Cake Crons', true), array('action' => 'index')); ?></li>
    </ul>
</div>