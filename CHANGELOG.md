# Changelog

<!-- CHANGELOGGER -->

## [1.1.0] - 2020-03-22

### Feature change (1 change)

- Created groups are counted per month.
- Count started groups per month.

### Other (1 change)

- Update to Laravel 6.8

### New feature (4 changes)

- Delete old groups: `wichtel:delete-groups`
- New command `wichtel:delete-users` to delete user records without any groups.
- Add Command `wichtel:inform-deletion`. Inform group admin about upcoming data deletion after successful wichtel action.

## [1.0.0] - 2018-11-25

🤩

## [0.2.0] - 2016-12-27

Switch completely to Laravel Framework.

### Changes

* No `wichteln/` endpoint anymore. To start update the group status to `started`.
* Nice E-Mails with blade templates
* Using a queue to dispatch raffle job and sending mails
* Add wish list of buddy to email

## [0.1.0] - 2016-12-17

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
