<?php

class CakeCronsShell extends Shell
{

    var $uses = array('CakeCron');
    var $tasksToExecute = array();
    var $blockFilename = '';

    function startup()
    {
    }

    function help()
    {
        // TODO print some helpful text
        parent::help();
    }

    function loadTasks()
    {
        $filename = TMP . 'CakeCronsIsRunning.tmp';
        if (file_exists($filename))
        {
            $this->out('CakeCrons is already running -> exit');
            $this->out('CakeCrons is not running? You can delete this file:');
            $this->out($filename);
            $this->_stop(0);
        } // if file_exists($filename)
        else
        {
            touch($filename);
        }
        $this->blockFilename = $filename;
        $tasksToExecute = $this->CakeCron->find('all', array(
            'fields' => array('id', 'modified', 'task', 'interval', 'priority', 'next_start'),
            'conditions' => array(
                'next_start <=' => date('YmdHis'),
                'status NOT' => array('running', 'done')
            ),
            'order' => array('priority', 'last_runtime')
                )
        );
        $this->out('CakeCrons started. ' . count($tasksToExecute) . ' task(s) found');
        foreach ($tasksToExecute as $t)
        {
            $this->tasksToExecute[$t['CakeCron']['task']] = $t['CakeCron'];
        } // foreach $tasksToExecute
    }

    function loadTask($taskName)
    {
        $this->taskNames = array();
        $task = Inflector::underscore($taskName);
        $taskClass = Inflector::camelize($taskName . 'Task');

        if (!class_exists($taskClass))
        {
            foreach ($this->Dispatch->shellPaths as $path)
            {
                $taskPath = $path . 'tasks' . DS . $task . '.php';
                if (file_exists($taskPath))
                {
                    require_once $taskPath;
                    break;
                }
            }
        }
        $taskClassCheck = $taskClass;
        if (!PHP5)
        {
            $taskClassCheck = strtolower($taskClass);
        }
        if (ClassRegistry::isKeySet($taskClassCheck))
        {
            $this->taskNames[] = $taskName;
            if (!PHP5)
            {
                $this->{$taskName} = & ClassRegistry::getObject($taskClassCheck);
            }
            else
            {
                $this->{$taskName} = ClassRegistry::getObject($taskClassCheck);
            }
        }
        else
        {
            $this->taskNames[] = $taskName;
            if (!PHP5)
            {
                $this->{$taskName} = & new $taskClass($this->Dispatch);
            }
            else
            {
                $this->{$taskName} = new $taskClass($this->Dispatch);
            }
        }

        if (!isset($this->{$taskName}))
        {
            $this->err("Task '{$taskName}' could not be loaded");
            return false;
        }
        return true;
    }

    function main()
    {
        if (!$this->tasksToExecute)
        {
            unlink($this->blockFilename);
            return;
        }

        foreach ($this->tasksToExecute as $t => $taskDefinition)
        {
            if (!$this->loadTask($t))
                continue;
            $this->CakeCron->id = $taskDefinition['id'];
            $current = $this->CakeCron->read();
            if ($current['CakeCron']['modified'] != $taskDefinition['modified'])
            {
                //task has changed since loadTasks
                $this->out("'$t' has been changed -> skipped");
                unset($this->{$t});
                continue;
            } // if $current['CakeCron']['modified'] != $taskDefinition['modified']
            if ($current['CakeCron']['status'] == 'running')
            {
                $this->out("'$t' is already running -> skipped");
                unset($this->{$t});
                continue;
            } // if $current['CakeCron']['status'] == 'running'
            $this->CakeCron->saveField('status', 'running');
            unset($current);
            $this->out("starting task '$t'");
            $fields = array('CakeCron' => array());
            $startTime = time();
            try
            {
                $this->{$t}->execute();
            }
            catch (Exception $exc)
            {
                $this->err($t . ': ' . $exc->getMessage());
                $fields['CakeCron']['status'] = 'failed';
            }
            unset($this->{$t});
            $fields['CakeCron']['last_runtime'] = time() - $startTime;
            $fields['CakeCron']['last_start'] = date('YmdHis', $startTime);
            if (!array_key_exists('status', $fields['CakeCron']))
            {
                $fields['CakeCron']['status'] = 'ready';
                if (!$taskDefinition['interval'])
                    $fields['CakeCron']['status'] = 'done';
            }
            if ($taskDefinition['interval'])
            {
                $nextStart = strtotime($taskDefinition['interval'], strtotime($taskDefinition['next_start']));
                while ($nextStart <= time())
                {
                    $nextStart = strtotime($taskDefinition['interval'], $nextStart);
                }
                $fields['CakeCron']['next_start'] = date('YmdHis', $nextStart);
            }
            $this->CakeCron->save($fields);
        } //foreach $this->tasksToExecute
        unlink($this->blockFilename);
    }

    function out($message = null, $newlines = 1)
    {
        if ($message)
            $message = date('Y-m-d H:i') . ' ' . $message;
        parent::out($message, $newlines);
    }

    function err($message = null, $newlines = 1)
    {
        if ($message)
            $message = date('Y-m-d H:i') . ' ' . $message;
        parent::err($message, $newlines);
    }

}
