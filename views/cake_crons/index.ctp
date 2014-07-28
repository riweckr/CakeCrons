<div class="cakeCrons index">
	<h2><?php __('Cake Crons');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
			<th><?php echo $this->Paginator->sort('task');?></th>
			<th><?php echo $this->Paginator->sort('interval');?></th>
			<th><?php echo $this->Paginator->sort('last start');?></th>
			<th><?php echo $this->Paginator->sort('priority');?></th>
			<th><?php echo $this->Paginator->sort('last runtime');?></th>
			<th><?php echo $this->Paginator->sort('next start');?></th>
                        <th><?php echo $this->Paginator->sort('status');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($cakeCrons as $cakeCron):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $cakeCron['CakeCron']['id']; ?>&nbsp;</td>
		<td><?php echo strftime('%c', strtotime($cakeCron['CakeCron']['created'])); ?>&nbsp;</td>
		<td><?php echo strftime('%c', strtotime($cakeCron['CakeCron']['modified'])); ?>&nbsp;</td>
		<td><?php echo $cakeCron['CakeCron']['task']; ?>&nbsp;</td>
		<td><?php echo $intervals[$cakeCron['CakeCron']['interval']]; ?>&nbsp;</td>
		<td><?php echo strftime('%c', strtotime($cakeCron['CakeCron']['last_start'])); ?>&nbsp;</td>
		<td><?php echo $cakeCron['CakeCron']['priority']; ?>&nbsp;</td>
		<td><?php echo $cakeCron['CakeCron']['last_runtime']; ?>&nbsp;</td>
		<td><?php echo strftime('%c', strtotime($cakeCron['CakeCron']['next_start'])); ?>&nbsp;</td>
                <td><?php echo $cakeCron['CakeCron']['status']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $cakeCron['CakeCron']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $cakeCron['CakeCron']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $cakeCron['CakeCron']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Cake Cron', true), array('action' => 'add')); ?></li>
	</ul>
</div>