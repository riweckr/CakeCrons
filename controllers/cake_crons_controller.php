<?php

class CakeCronsController extends CakeCronAppController
{

    var $name = 'CakeCrons';
    var $paginate = array(
        'limit' => 50,
        'order' => array(
            'CakeCron.next_start' => 'asc',
            'CakeCron.priority' => 'asc'
        )
    );
    var $intervals = array();
    var $priorities = array();

    function beforeFilter()
    {
        $intervals = null;
        $priorities = null;

        App::import('Vendor', 'cake_crons/project');

        if (!$intervals || !is_array($intervals))
            $intervals = array(
                '' => __('just once', true),
                '+5 minutes' => __('every 5 min', true),
                '+15 minutes' => __('every 15 min', true),
                '+30 minutes' => __('every 30 min', true),
                '+1 hour' => __('hourly', true),
                '+2 hours' => __('every 2 h', true),
                '+6 hours' => __('every 6 h', true),
                '+12 hours' => __('every 12 h', true),
                '+1 day' => __('daily', true),
                '+1 week' => __('weekly', true),
                '+1 month' => __('monthly', true)
            );
        $this->intervals = $intervals;

        if (!$priorities || !is_array($priorities))
            $priorities = array(5 => 5, 4 => 4, 3 => 3, 2 => 2, 1 => 1);
        $this->priorities = $priorities;
    }

    function index()
    {
        $this->set('cakeCrons', $this->paginate('CakeCron'));
        $this->set('intervals', $this->intervals);
    }

    function add()
    {
        if ($this->data)
        {
            $this->CakeCron->create();
            $this->CakeCron->save($this->data);
            $this->Session->setFlash(__('Your job has been saved.', true));
            $this->redirect(array('action' => 'index'));
        } // if $this->data
        $this->set('priorities', $this->priorities);
        $this->set('intervals', $this->intervals);

        $tasks = array();
        $shellPaths = App::path('shells');
        $plugins = App::objects('plugin', null, false);
        foreach ((array) $plugins as $plugin)
        {
            if ($plugin == 'CakeCrons')
                continue;
            $pluginPath = App::pluginPath($plugin);
            $shellPaths[] = $pluginPath . 'vendors' . DS . 'shells' . DS;
        } //foreach (array)$plugins
        $cakeCoreLib = ROOT . DS . CONSOLE_LIBS;
        foreach ($shellPaths as $path)
        {
            if ($path == $cakeCoreLib)
                continue;
            if (!is_dir($path . 'tasks'))
                continue;
            $taskFiles = glob($path . 'tasks' . DS . '*.php');
            if (!$taskFiles)
                continue;
            foreach ($taskFiles as $taskFile)
            {
                $taskName = Inflector::camelize(basename($taskFile, '.php'));
                $tasks[$taskName] = $taskName;
            } //foreach $taskFiles
        } //foreach $shellPaths
        if ($tasks)
        {
            $scheduledTasks = $this->CakeCron->find('list', array(
                'fields' => array('task'),
                'conditions' => array('next_start >' => date('YmdHis'))
                    )
            );
            foreach ($scheduledTasks as $st)
            {
                if (array_key_exists($st, $tasks))
                    unset($tasks[$st]);
            } //foreach $sceduledTasks
        }
        if (!$tasks)
            $tasks = array('' => __('no tasks found', true));

        $this->set('tasks', $tasks);
    }

    function edit($id)
    {
        $this->CakeCron->id = $id;
        if (empty($this->data))
        {
            $this->data = $this->CakeCron->read();
            $this->set('priorities', $this->priorities);
            $this->set('intervals', $this->intervals);
        }
        else
        {
            if ($this->CakeCron->save($this->data))
            {
                $this->Session->setFlash(__('Your job has been updated.', true));
            }
            $this->redirect(array('action' => 'index'));
        }
    }
    
    function delete($id)
    {
        $this->CakeCron->delete($id);
        $this->redirect(array('action' => 'index'));
    }

}
