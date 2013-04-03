# Azure TaskDemo Bundle

This bundle demonstrates functionality of Windows Azure in combination with Symfony and Doctrine.

This works in combination with [Azure Distribution Bundle](https://github.com/beberlei/AzureDistributionBundle).

    Notice: This bundle is still in development. Functionality to demonstrate Windows Azure features will be added incrementally.

## Features

### Sharding with SQL Azure

This demo uses SQL Azure Federations to show the sharding functionality. The data model looks like follows:

1. User Table (Root Database)
2. Task Table (Federated Table)
3. TaskType Table (Federation Table, but not federated)

The federation is federated on the `user_id` as distribution key. A request listener will decide which federation to use by looking at the session user object. As long as the user is not logged in, no federation will be picked.

The federations require one slight change to a perfectly normalized database schema. Instead of having a task-id on the tasks table there is a composite primary key on "taskid"+"userid".

