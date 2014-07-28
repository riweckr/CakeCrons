<div class="cakeCrons form">
    <?php echo $this->Form->create('CakeCron'); ?>
    <fieldset>
        <legend><?php __('Edit Cake Cron'); ?></legend>
        <?php
        echo $this->Form->input('id');
        echo $this->Html->div('input text', $this->Html->tag('label', __('Task', true)) . $this->data['CakeCron']['task']);
        echo $this->Form->input('interval');
        if ($this->data['CakeCron']['last_start'] == '0000-00-00 00:00:00')
            echo $this->Html->div('input text', $this->Html->tag('label', __('Last Start', true)) . __('never', true));
        else
            echo $this->Html->div('input datetime', $this->Html->tag('label', __('Last Start', true)) . strftime('%c', strtotime($this->data['CakeCron']['last_start'])));
        echo $this->Form->input('priority');
        echo $this->Html->div('input text', $this->Html->tag('label', __('Last Runtime', true)) . $this->data['CakeCron']['last_runtime'] . ' ' . __('secounds', true));
        echo $this->Html->div('input datetime', $this->Html->tag('label', __('Next Start', true)) . $this->Form->datetime('next_start', 'YMD', '24', strtotime($this->data['CakeCron']['next_start']), array('empty' => false, 'monthNames' => false, 'minYear' => date('Y'))));
//        echo $this->Form->input('next_start');
        ?>
    </fieldset>
    <?php echo $this->Form->end(__('Submit', true)); ?>
</div>
<div class="actions">
    <h3><?php __('Actions'); ?></h3>
    <ul>

        <li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('CakeCron.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('CakeCron.id'))); ?></li>
        <li><?php echo $this->Html->link(__('List Cake Crons', true), array('action' => 'index')); ?></li>
    </ul>
</div>