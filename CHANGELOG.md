# Changelog

## 0.2.0 (27.12.2016)

Switch completely to Laravel Framework.

### Changes

* No `wichteln/` endpoint anymore. To start update the group status to `started`.
* Nice E-Mails with blade templates
* Using a queue to dispatch raffle job and sending mails
* Add wish list of buddy to email

## 0.1.0 (17.12.2016)

The service is only and running at [wichtel.me](https://wichtel.me)

### Features

* Endpoints
  * `wichtelgroup/`: CRUD Endpoint for groups
  * `wichtelgroup/:group_id/wichtelmember`: CRUD Endpoint for members
  * `wichteln/`: Start Secret Santa for a group
* Authentication is set with API tokens per group
* Mails will be sent to members, when
  * added to approve their participation
  * their wichtel buddies are drawn
