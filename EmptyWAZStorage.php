<?php

require_once('Microsoft/Autoloader.php');


define('STORAGE_ACCOUNT', '<endpoint of your storage account>');
define('STORAGE_KEY', '<storage account key>');


// Create connections
$blob = new Microsoft_WindowsAzure_Storage_Blob(
                'blob.core.windows.net',
                STORAGE_ACCOUNT,
                STORAGE_KEY
            );
$queue = new Microsoft_WindowsAzure_Storage_Queue(
                'queue.core.windows.net',
                STORAGE_ACCOUNT,
                STORAGE_KEY
            );
$table = new Microsoft_WindowsAzure_Storage_Table(
                'table.core.windows.net',
                STORAGE_ACCOUNT,
                STORAGE_KEY
            );

while(1) {
    // During 1 AM every night reset the storage
    if(date('H') == '01') {
        // These samples ensure their stuff exists before using it so it is safe to delete
        echo "Removing all tables...";
        $tables = $table->listTables();
        foreach($tables AS $t) {
            $table->deleteTable($t->name);
        }

        echo "\nRemoving all blob containers...";
        $containers = $blob->listContainers();
        foreach($containers AS $c) {
            $blob->deleteContainer($c->name);
        }

        echo "\nRemoving all queues...";
        $queues = $queue->listQueues();
        foreach($queues AS $q) {
            $queue->deleteQueue($q->name);
        }
    }
    sleep(3600); // sleep for 1 hour and check again
}
